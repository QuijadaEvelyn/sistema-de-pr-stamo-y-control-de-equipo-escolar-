<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Incident;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class IncidentController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = Incident::with([
            'user',
            'equipment',
            'loan'
        ]);

        if ($user->isSolicitante()) {
            $query->where('user_id', $user->id);
        }

        $incidents = $query->latest()->paginate(10);

        $equipment = Equipment::orderBy('name')->get();

        $loans = $user->isSolicitante()
            ? Loan::where('user_id', $user->id)
                ->whereIn('status', [
                    'aprobado',
                    'entregado',
                    'devuelto'
                ])
                ->with('items')
                ->latest()
                ->get()
            : collect();

        return view('incidents.index', compact(
            'incidents',
            'equipment',
            'loans'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'equipment_id' => [
                'required',
                'exists:equipment,id'
            ],
            'loan_id' => [
                'nullable',
                'exists:loans,id'
            ],
            'title' => [
                'required',
                'string',
                'max:150'
            ],
            'description' => [
                'required',
                'string',
                'max:5000'
            ],
        ]);

        if (!empty($data['loan_id'])) {
            $loan = Loan::with('items')
                ->findOrFail($data['loan_id']);

            if (
                $loan->user_id !== Auth::id() ||
                !in_array($loan->status, [
                    'aprobado',
                    'entregado',
                    'devuelto'
                ], true) ||
                !$loan->items->contains(
                    'equipment_id',
                    (int) $data['equipment_id']
                )
            ) {
                throw ValidationException::withMessages([
                    'loan_id' =>
                        'El préstamo no corresponde a tu cuenta o no contiene ese equipo.'
                ]);
            }
        } else {
            $hasLoan = Loan::where('user_id', Auth::id())
                ->whereIn('status', [
                    'aprobado',
                    'entregado',
                    'devuelto'
                ])
                ->whereHas('items', function ($query) use ($data) {
                    $query->where(
                        'equipment_id',
                        $data['equipment_id']
                    );
                })
                ->exists();

            if (!$hasLoan) {
                throw ValidationException::withMessages([
                    'equipment_id' =>
                        'Solo puedes reportar equipos relacionados con tus préstamos.'
                ]);
            }
        }

        Incident::create([
            'user_id' => Auth::id(),
            'equipment_id' => $data['equipment_id'],
            'loan_id' => $data['loan_id'] ?? null,
            'title' => $data['title'],
            'description' => $data['description'],
            'status' => 'abierta',
        ]);

        return redirect()
            ->route('incidents.index')
            ->with(
                'success',
                'Incidencia registrada correctamente.'
            );
    }

    public function update(Request $request, Incident $incident)
    {
        $data = $request->validate([
            'status' => [
                'required',
                Rule::in([
                    'abierta',
                    'en_proceso',
                    'resuelta'
                ])
            ],
            'resolution' => [
                'nullable',
                'string',
                'max:5000',
                Rule::requiredIf(
                    $request->input('status') === 'resuelta'
                )
            ],
        ]);

        $incident->update($data);

        return back()->with(
            'success',
            'Seguimiento de incidencia actualizado.'
        );
    }
}
