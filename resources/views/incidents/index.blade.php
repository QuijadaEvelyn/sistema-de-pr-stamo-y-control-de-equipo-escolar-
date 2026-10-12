
@extends('layouts.dashboard')

@section('title', 'Incidencias')
@section('page-title', 'Gestión de incidencias')

@section('content')

<div class="page-header">
    <div>
        <h1>Incidencias</h1>
        <p>
            Consulta y administra los reportes de daños
            o fallas en equipos tecnológicos.
        </p>
    </div>

    @if(auth()->user()->isSolicitante())
        <button type="button" class="btn btn-primary"
                data-modal-open="createIncidentModal">
            Reportar incidencia
        </button>
    @endif
</div>

<div class="card">
    <div class="card-header">
        <h2>Reportes registrados</h2>
        <span class="text-muted">
            {{ $incidents->total() }} registros
        </span>
    </div>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Folio</th>
                    <th>Incidencia</th>
                    <th>Equipo</th>
                    @if(auth()->user()->isEncargado())
                        <th>Reportado por</th>
                    @endif
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Seguimiento</th>
                </tr>
            </thead>

            <tbody>
                @forelse($incidents as $incident)
                    <tr>
                        <td class="table-code">
                            #{{ $incident->id }}
                        </td>

                        <td>
                            <strong>{{ $incident->title }}</strong>
                            <div class="text-muted">
                                {{ $incident->description }}
                            </div>
                        </td>

                        <td>{{ $incident->equipment->name }}</td>

                        @if(auth()->user()->isEncargado())
                            <td>{{ $incident->user->name }}</td>
                        @endif

                        <td>
                            {{ $incident->created_at->format('d/m/Y') }}
                        </td>

                        <td>
                            <span class="badge {{ match($incident->status) {
                                'abierta' => 'badge-danger',
                                'en_proceso' => 'badge-warning',
                                'resuelta' => 'badge-success',
                                default => 'badge-neutral'
                            } }}">
                                {{ ucfirst(str_replace('_', ' ', $incident->status)) }}
                            </span>
                        </td>

                        <td>
                            @if(auth()->user()->isEncargado())
                                <button type="button"
                                        class="btn btn-outline btn-sm"
                                        data-incident-edit
                                        data-url="{{ route('incidents.update', $incident) }}"
                                        data-status="{{ $incident->status }}"
                                        data-resolution="{{ $incident->resolution }}">
                                    Atender
                                </button>
                            @else
                                <span class="text-muted">
                                    {{ $incident->resolution ?: 'Pendiente de resolución' }}
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ auth()->user()->isEncargado() ? 7 : 6 }}"
                            class="table-empty">
                            No hay incidencias registradas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrapper">
        {{ $incidents->links() }}
    </div>
</div>

@if(auth()->user()->isSolicitante())

<div class="modal" id="createIncidentModal"
     role="dialog" aria-modal="true"
     aria-labelledby="createIncidentTitle"
     aria-hidden="true">

    <div class="modal-dialog">
        <div class="modal-header">
            <h2 id="createIncidentTitle">Reportar incidencia</h2>
            <button type="button" class="modal-close"
                    data-modal-close aria-label="Cerrar">×</button>
        </div>

        <form action="{{ route('incidents.store') }}"
              method="POST">
            @csrf

            <div class="modal-body">

                <div class="form-group">
                    <label class="form-label" for="incident_loan">
                        Préstamo relacionado
                    </label>

                    <select id="incident_loan"
                            name="loan_id"
                            class="form-select">
                        <option value="">Sin especificar</option>

                        @foreach($loans as $loan)
                            <option value="{{ $loan->id }}">
                                Préstamo #{{ $loan->id }}
                                — {{ $loan->created_at->format('d/m/Y') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="incident_equipment">
                        Equipo
                    </label>

                    <select id="incident_equipment"
                            name="equipment_id"
                            class="form-select" required>
                        <option value="">Seleccionar equipo</option>

                        @foreach($equipment as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->name }} — {{ $item->code }}
                            </option>
                        @endforeach
                    </select>

                    <small class="form-help">
                        Solo puedes reportar equipos relacionados
                        con tus préstamos.
                    </small>
                </div>

                <div class="form-group">
                    <label class="form-label" for="incident_title">
                        Título
                    </label>
                    <input id="incident_title"
                           name="title"
                           class="form-control"
                           maxlength="150" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="incident_description">
                        Descripción del problema
                    </label>
                    <textarea id="incident_description"
                              name="description"
                              class="form-textarea"
                              required></textarea>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                        data-modal-close>Cancelar</button>

                <button type="submit" class="btn btn-primary">
                    Enviar reporte
                </button>
            </div>
        </form>
    </div>
</div>

@endif

@if(auth()->user()->isEncargado())

<div class="modal" id="editIncidentModal"
     role="dialog" aria-modal="true"
     aria-labelledby="editIncidentTitle"
     aria-hidden="true">

    <div class="modal-dialog">
        <div class="modal-header">
            <h2 id="editIncidentTitle">Seguimiento de incidencia</h2>
            <button type="button" class="modal-close"
                    data-modal-close aria-label="Cerrar">×</button>
        </div>

        <form id="edit-incident-form" method="POST">
            @csrf
            @method('PUT')

            <div class="modal-body">

                <div class="form-group">
                    <label class="form-label" for="incident_status">
                        Estado
                    </label>

                    <select id="incident_status"
                            name="status"
                            class="form-select" required>
                        <option value="abierta">Abierta</option>
                        <option value="en_proceso">En proceso</option>
                        <option value="resuelta">Resuelta</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="incident_resolution">
                        Seguimiento o resolución
                    </label>

                    <textarea id="incident_resolution"
                              name="resolution"
                              class="form-textarea"></textarea>

                    <small class="form-help">
                        Es obligatorio registrar una resolución
                        cuando el estado sea Resuelta.
                    </small>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                        data-modal-close>Cancelar</button>

                <button type="submit" class="btn btn-primary">
                    Guardar seguimiento
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.querySelectorAll('[data-incident-edit]').forEach(button => {
        button.addEventListener('click', () => {
            const modal = document.getElementById('editIncidentModal');
            const form = document.getElementById('edit-incident-form');

            form.action = button.dataset.url;
            form.elements['status'].value = button.dataset.status;
            form.elements['resolution'].value = button.dataset.resolution;

            modal.classList.add('show');
            modal.setAttribute('aria-hidden', 'false');
        });
    });
</script>

@endif

@endsection
