
@extends('layouts.dashboard')

@section('title', 'Inventario')
@section('page-title', 'Inventario tecnológico')

@section('content')

<div class="page-header">
    <div>
        <h1>Inventario tecnológico</h1>
        <p>Consulta los equipos, categorías y existencias.</p>
    </div>

    @if(auth()->user()->isEncargado())
        <button type="button" class="btn btn-primary"
                data-modal-open="createEquipmentModal">
            Nuevo equipo
        </button>
    @else
        <a href="{{ route('loans.index') }}" class="btn btn-primary">
            Solicitar préstamo
        </a>
    @endif
</div>

<form action="{{ route('equipment.index') }}"
      method="GET" class="filter-bar">

    <div class="filter-group">
        <label for="search" class="form-label">Buscar equipo</label>
        <input id="search" type="search" name="search"
               value="{{ request('search') }}"
               class="form-control"
               placeholder="Nombre o código">
    </div>

    <div class="filter-group">
        <label for="category_filter" class="form-label">
            Categoría
        </label>
        <select id="category_filter" name="category_id"
                class="form-select">
            <option value="">Todas</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}"
                    @selected(request('category_id') == $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="filter-group">
        <label for="status_filter" class="form-label">Estado</label>
        <select id="status_filter" name="status" class="form-select">
            <option value="">Todos</option>
            @foreach(['activo', 'mantenimiento', 'baja'] as $status)
                <option value="{{ $status }}"
                    @selected(request('status') === $status)>
                    {{ ucfirst($status) }}
                </option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Buscar</button>
    <a href="{{ route('equipment.index') }}"
       class="btn btn-outline">Limpiar</a>
</form>

<div class="card">
    <div class="card-header">
        <h2>Equipos registrados</h2>
        <span class="text-muted">{{ $equipment->total() }} registros</span>
    </div>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Equipo</th>
                    <th>Categoría</th>
                    <th>Total</th>
                    <th>Disponibles</th>
                    <th>Estado</th>
                    @if(auth()->user()->isEncargado())
                        <th>Acciones</th>
                    @endif
                </tr>
            </thead>

            <tbody>
                @forelse($equipment as $item)
                    <tr>
                        <td class="table-code">{{ $item->code }}</td>
                        <td>
                            <strong>{{ $item->name }}</strong>
                            <div class="text-muted">
                                {{ $item->description }}
                            </div>
                        </td>
                        <td>{{ $item->category->name }}</td>
                        <td>{{ $item->total_quantity }}</td>
                        <td>
                            {{ $item->status === 'activo'
                                ? $item->available_quantity : 0 }}
                        </td>
                        <td>
                            <span class="badge {{ match($item->status) {
                                'activo' => 'badge-success',
                                'mantenimiento' => 'badge-warning',
                                default => 'badge-danger'
                            } }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>

                        @if(auth()->user()->isEncargado())
                            <td>
                                <div class="table-actions">
                                    <button type="button"
                                            class="btn btn-outline btn-sm"
                                            data-equipment-edit
                                            data-id="{{ $item->id }}"
                                            data-url="{{ route('equipment.update', $item) }}"
                                            data-name="{{ $item->name }}"
                                            data-code="{{ $item->code }}"
                                            data-category="{{ $item->category_id }}"
                                            data-description="{{ $item->description }}"
                                            data-total="{{ $item->total_quantity }}"
                                            data-status="{{ $item->status }}">
                                        Editar
                                    </button>

                                    <form action="{{ route('equipment.destroy', $item) }}"
                                          method="POST"
                                          data-confirm="¿Eliminar este equipo?">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm"
                                                type="submit">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ auth()->user()->isEncargado() ? 7 : 6 }}"
                            class="table-empty">
                            No se encontraron equipos.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrapper">
        {{ $equipment->links() }}
    </div>
</div>

@if(auth()->user()->isEncargado())

    @foreach(['create' => 'Nuevo equipo', 'edit' => 'Editar equipo'] as $mode => $title)
        <div class="modal" id="{{ $mode }}EquipmentModal"
             role="dialog" aria-modal="true"
             aria-label="{{ $title }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-header">
                    <h2>{{ $title }}</h2>
                    <button type="button" class="modal-close"
                            data-modal-close aria-label="Cerrar">×</button>
                </div>

                <form method="POST"
                      @if($mode === 'create')
                          action="{{ route('equipment.store') }}"
                      @else
                          id="edit-equipment-form"
                      @endif>
                    @csrf
                    @if($mode === 'edit')
                        @method('PUT')
                    @endif

                    <div class="modal-body">
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Nombre</label>
                                <input name="name" class="form-control"
                                       required maxlength="150">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Código</label>
                                <input name="code" class="form-control"
                                       required maxlength="50">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Categoría</label>
                                <select name="category_id"
                                        class="form-select" required>
                                    <option value="">Seleccionar</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Cantidad total</label>
                                <input type="number" name="total_quantity"
                                       min="1" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Estado</label>
                                <select name="status"
                                        class="form-select" required>
                                    <option value="activo">Activo</option>
                                    <option value="mantenimiento">Mantenimiento</option>
                                    <option value="baja">Baja</option>
                                </select>
                            </div>

                            <div class="form-group form-full">
                                <label class="form-label">Descripción</label>
                                <textarea name="description"
                                          class="form-textarea"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                                data-modal-close>Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    <script>
        document.querySelectorAll('[data-equipment-edit]').forEach(button => {
            button.addEventListener('click', () => {
                const modal = document.getElementById('editEquipmentModal');
                const form = document.getElementById('edit-equipment-form');

                form.action = button.dataset.url;
                form.elements['name'].value = button.dataset.name;
                form.elements['code'].value = button.dataset.code;
                form.elements['category_id'].value = button.dataset.category;
                form.elements['description'].value = button.dataset.description;
                form.elements['total_quantity'].value = button.dataset.total;
                form.elements['status'].value = button.dataset.status;

                modal.classList.add('show');
                modal.setAttribute('aria-hidden', 'false');
            });
        });
    </script>
@endif

@endsection
