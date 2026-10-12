<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LoanController extends Controller
{
    // Mostrar los préstamos
    public function index()
    {
        $user = Auth::user();

        $query = Loan::with([
            'user',
            'handler',
            'items.equipment'
        ]);

        if ($user->isSolicitante()) {
            $query->where('user_id', $user->id);
        }

        $loans = $query->latest()->paginate(10);

        $equipment = Equipment::where('status', 'activo')
            ->where('available_quantity', '>', 0)
            ->orderBy('name')
            ->get();

        return view('loans.index', compact(
            'loans',
            'equipment'
        ));
    }

    // Mostrar los detalles de un préstamo
    public function show(Loan $loan)
    {
        $user = Auth::user();

        if (
            $user->isSolicitante() &&
            $loan->user_id !== $user->id
        ) {
            abort(403, 'No tienes permiso para ver este préstamo.');
        }

        $loan->load([
            'user',
            'handler',
            'items.equipment.category',
            'incidents'
        ]);

        return view('loans.show', compact('loan'));
    }

    // Registrar una nueva solicitud
    public function store(Request $request)
    {
        $data = $request->validate([
            'start_date' => [
                'required',
                'date',
                'after_or_equal:today'
            ],
            'due_date' => [
                'required',
                'date',
                'after_or_equal:start_date'
            ],
            'notes' => [
                'nullable',
                'string',
                'max:2000'
            ],
            'items' => [
                'required',
                'array',
                'min:1'
            ],
            'items.*.equipment_id' => [
                'required',
                'integer',
                'distinct',
                'exists:equipment,id'
            ],
            'items.*.quantity' => [
                'required',
                'integer',
                'min:1'
            ],
        ]);

        DB::transaction(function () use ($data) {
            $loan = Loan::create([
                'user_id' => Auth::id(),
                'start_date' => $data['start_date'],
                'due_date' => $data['due_date'],
                'notes' => $data['notes'] ?? null,
                'status' => 'pendiente',
            ]);

            foreach ($data['items'] as $item) {
                $equipment = Equipment::findOrFail(
                    $item['equipment_id']
                );

                if (
                    $equipment->status !== 'activo' ||
                    $equipment->available_quantity < $item['quantity']
                ) {
                    throw ValidationException::withMessages([
                        'items' => 'Uno de los equipos no tiene disponibilidad suficiente.'
                    ]);
                }

                $loan->items()->create([
                    'equipment_id' => $equipment->id,
                    'quantity' => $item['quantity'],
                ]);
            }
        });

        return redirect()
            ->route('loans.index')
            ->with(
                'success',
                'Solicitud registrada correctamente.'
            );
    }

    // Aprobar una solicitud
    public function approve(Loan $loan)
    {
        DB::transaction(function () use ($loan) {
            $loan = Loan::whereKey($loan->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($loan->status !== 'pendiente') {
                throw ValidationException::withMessages([
                    'loan' => 'Esta solicitud ya fue procesada.'
                ]);
            }

            // Validar la fecha de devolución con Carbon
            $dueDate = Carbon::parse($loan->due_date)
                ->startOfDay();

            if ($dueDate->lessThan(Carbon::today())) {
                throw ValidationException::withMessages([
                    'loan' => 'No se puede aprobar una solicitud cuya fecha de devolución ya pasó.'
                ]);
            }

            $items = $loan->items()
                ->orderBy('equipment_id')
                ->get();

            if ($items->isEmpty()) {
                throw ValidationException::withMessages([
                    'loan' => 'El préstamo no contiene equipos.'
                ]);
            }

            foreach ($items as $item) {
                $equipment = Equipment::whereKey(
                    $item->equipment_id
                )
                    ->lockForUpdate()
                    ->firstOrFail();

                if (
                    $equipment->status !== 'activo' ||
                    $equipment->available_quantity < $item->quantity
                ) {
                    throw ValidationException::withMessages([
                        'loan' => 'No existe disponibilidad suficiente para aprobar el préstamo.'
                    ]);
                }

                $equipment->decrement(
                    'available_quantity',
                    $item->quantity
                );
            }

            $loan->update([
                'status' => 'aprobado',
                'handled_by' => Auth::id(),
            ]);
        });

        return back()->with(
            'success',
            'Préstamo aprobado y equipos reservados.'
        );
    }

    // Rechazar una solicitud
    public function reject(Loan $loan)
    {
        DB::transaction(function () use ($loan) {
            $loan = Loan::whereKey($loan->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($loan->status !== 'pendiente') {
                throw ValidationException::withMessages([
                    'loan' => 'Esta solicitud ya fue procesada.'
                ]);
            }

            $loan->update([
                'status' => 'rechazado',
                'handled_by' => Auth::id(),
            ]);
        });

        return back()->with(
            'success',
            'Solicitud rechazada correctamente.'
        );
    }

    // Registrar la entrega
    public function deliver(Loan $loan)
    {
        DB::transaction(function () use ($loan) {
            $loan = Loan::whereKey($loan->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($loan->status !== 'aprobado') {
                throw ValidationException::withMessages([
                    'loan' => 'Solo se pueden entregar préstamos aprobados.'
                ]);
            }

            $loan->update([
                'status' => 'entregado',
                'delivered_at' => now(),
                'handled_by' => Auth::id(),
            ]);
        });

        return back()->with(
            'success',
            'Entrega registrada correctamente.'
        );
    }

    // Registrar la devolución
    public function returnLoan(Loan $loan)
    {
        DB::transaction(function () use ($loan) {
            $loan = Loan::whereKey($loan->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($loan->status !== 'entregado') {
                throw ValidationException::withMessages([
                    'loan' => 'Solo se pueden devolver préstamos entregados.'
                ]);
            }

            $items = $loan->items()
                ->orderBy('equipment_id')
                ->get();

            foreach ($items as $item) {
                $equipment = Equipment::whereKey(
                    $item->equipment_id
                )
                    ->lockForUpdate()
                    ->firstOrFail();

                $equipment->increment(
                    'available_quantity',
                    $item->quantity
                );
            }

            $loan->update([
                'status' => 'devuelto',
                'returned_at' => now(),
                'handled_by' => Auth::id(),
            ]);
        });

        return back()->with(
            'success',
            'Devolución registrada correctamente.'
        );
    }
}
