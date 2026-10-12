<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class EquipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Equipment::with('category');

        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where(
                'category_id',
                $request->input('category_id')
            );
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $equipment = $query->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('equipment.index', compact(
            'equipment',
            'categories'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => [
                'required',
                'exists:categories,id'
            ],
            'name' => ['required', 'string', 'max:150'],
            'code' => [
                'required',
                'string',
                'max:50',
                'unique:equipment,code'
            ],
            'description' => [
                'nullable',
                'string',
                'max:2000'
            ],
            'total_quantity' => [
                'required',
                'integer',
                'min:1'
            ],
            'status' => [
                'required',
                Rule::in([
                    'activo',
                    'mantenimiento',
                    'baja'
                ])
            ],
        ]);

        $data['available_quantity'] = $data['total_quantity'];

        Equipment::create($data);

        return back()->with(
            'success',
            'Equipo registrado correctamente.'
        );
    }

    public function update(Request $request, Equipment $equipment)
    {
        $data = $request->validate([
            'category_id' => [
                'required',
                'exists:categories,id'
            ],
            'name' => ['required', 'string', 'max:150'],
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('equipment', 'code')
                    ->ignore($equipment->id)
            ],
            'description' => [
                'nullable',
                'string',
                'max:2000'
            ],
            'total_quantity' => [
                'required',
                'integer',
                'min:1'
            ],
            'status' => [
                'required',
                Rule::in([
                    'activo',
                    'mantenimiento',
                    'baja'
                ])
            ],
        ]);

        DB::transaction(function () use ($equipment, $data) {
            $equipment = Equipment::whereKey($equipment->id)
                ->lockForUpdate()
                ->firstOrFail();

            $committed = $equipment->total_quantity
                - $equipment->available_quantity;

            if ($data['total_quantity'] < $committed) {
                throw ValidationException::withMessages([
                    'total_quantity' =>
                        'La cantidad total no puede ser menor que las unidades comprometidas.'
                ]);
            }

            $data['available_quantity'] =
                $data['total_quantity'] - $committed;

            $equipment->update($data);
        });

        return back()->with(
            'success',
            'Equipo actualizado correctamente.'
        );
    }

    public function destroy(Equipment $equipment)
    {
        if (
            $equipment->loanItems()->exists() ||
            $equipment->incidents()->exists()
        ) {
            return back()->with(
                'error',
                'Este equipo tiene historial. Cámbialo a estado de baja en lugar de eliminarlo.'
            );
        }

        $equipment->delete();

        return back()->with(
            'success',
            'Equipo eliminado correctamente.'
        );
    }
}
