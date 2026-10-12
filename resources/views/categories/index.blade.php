
@extends('layouts.dashboard')

@section('title', 'Categorías')
@section('page-title', 'Categorías')

@section('content')

<div class="page-header">
    <div>
        <h1>Categorías</h1>
        <p>Administra las clasificaciones de los equipos.</p>
    </div>

    <button type="button"
            class="btn btn-primary"
            data-modal-open="createCategoryModal">
        Nueva categoría
    </button>
</div>

<div class="card">
    <div class="card-header">
        <h2>Listado de categorías</h2>
        <span class="text-muted">
            {{ $categories->total() }} registros
        </span>
    </div>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Equipos</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td><strong>{{ $category->name }}</strong></td>
                        <td>{{ $category->description ?: 'Sin descripción' }}</td>
                        <td>{{ $category->equipment_count }}</td>
                        <td>
                            <div class="table-actions">
                                <button type="button"
                                        class="btn btn-outline btn-sm"
                                        data-edit-modal="editCategoryModal"
                                        data-edit-url="{{ route('categories.update', $category) }}"
                                        data-edit-name="{{ $category->name }}"
                                        data-edit-description="{{ $category->description }}">
                                    Editar
                                </button>

                                <form action="{{ route('categories.destroy', $category) }}"
                                      method="POST"
                                      data-confirm="¿Eliminar esta categoría?">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-danger btn-sm">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="table-empty">
                            No hay categorías registradas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrapper">
        {{ $categories->links() }}
    </div>
</div>

<div class="modal" id="createCategoryModal"
     role="dialog" aria-modal="true"
     aria-labelledby="createCategoryTitle"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-header">
            <h2 id="createCategoryTitle">Nueva categoría</h2>
            <button type="button" class="modal-close"
                    data-modal-close aria-label="Cerrar">×</button>
        </div>

        <form action="{{ route('categories.store') }}" method="POST">
            @csrf

            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label" for="category_name">
                        Nombre
                    </label>
                    <input id="category_name" name="name"
                           class="form-control" maxlength="100" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="category_description">
                        Descripción
                    </label>
                    <textarea id="category_description"
                              name="description"
                              class="form-textarea"></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                        data-modal-close>Cancelar</button>
                <button type="submit" class="btn btn-primary">
                    Guardar categoría
                </button>
            </div>
        </form>
    </div>
</div>

<div class="modal" id="editCategoryModal"
     role="dialog" aria-modal="true"
     aria-labelledby="editCategoryTitle"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-header">
            <h2 id="editCategoryTitle">Editar categoría</h2>
            <button type="button" class="modal-close"
                    data-modal-close aria-label="Cerrar">×</button>
        </div>

        <form method="POST">
            @csrf
            @method('PUT')

            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label" for="edit_category_name">
                        Nombre
                    </label>
                    <input id="edit_category_name" name="name"
                           class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="edit_category_description">
                        Descripción
                    </label>
                    <textarea id="edit_category_description"
                              name="description"
                              class="form-textarea"></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                        data-modal-close>Cancelar</button>
                <button type="submit" class="btn btn-primary">
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
