<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('equipment')
            ->orderBy('name')
            ->paginate(10);

        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                'unique:categories,name'
            ],
            'description' => [
                'nullable',
                'string',
                'max:2000'
            ],
        ]);

        Category::create($data);

        return back()->with(
            'success',
            'Categoría registrada correctamente.'
        );
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('categories', 'name')
                    ->ignore($category->id)
            ],
            'description' => [
                'nullable',
                'string',
                'max:2000'
            ],
        ]);

        $category->update($data);

        return back()->with(
            'success',
            'Categoría actualizada correctamente.'
        );
    }

    public function destroy(Category $category)
    {
        if ($category->equipment()->exists()) {
            return back()->with(
                'error',
                'No puedes eliminar una categoría que contiene equipos.'
            );
        }

        $category->delete();

        return back()->with(
            'success',
            'Categoría eliminada correctamente.'
        );
    }
}
