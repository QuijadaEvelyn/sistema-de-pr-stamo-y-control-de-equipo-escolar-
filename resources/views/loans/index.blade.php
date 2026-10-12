
@extends('layouts.dashboard')

@section('title', 'Préstamos')
@section('page-title', 'Gestión de préstamos')

@section('content')

<div class="page-header">
    <div>
        <h1>Préstamos</h1>
        <p>
            @if(auth()->user()->isEncargado())
                Administra las solicitudes y entregas de equipos.
            @else
                Consulta tus solicitudes y solicita equipos.
            @endif
        </p>
    </div>

    @if(auth()->user()->isSolicitante())
        <button type="button" class="btn btn-primary"
                data-modal-open="createLoanModal">
            Nueva solicitud
        </button>
    @endif
</div>

<div class="card">
    <div class="card-header">
        <h2>Listado de préstamos</h2>
        <span class="text-muted">{{ $loans->total() }} registros</span>
    </div>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Folio</th>
                    @if(auth()->user()->isEncargado())
                        <th>Solicitante</th>
                    @endif
                    <th>Fecha inicial</th>
                    <th>Devolución prevista</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse($loans as $loan)
                    <tr>
                        <td class="table-code">#{{ $loan->id }}</td>

                        @if(auth()->user()->isEncargado())
                            <td>{{ $loan->user->name }}</td>
                        @endif

                        <td>{{ $loan->start_date->format('d/m/Y') }}</td>
                        <td>{{ $loan->due_date->format('d/m/Y') }}</td>

                        <td>
                            <span class="badge {{ match($loan->status) {
                                'pendiente' => 'badge-warning',
                                'aprobado' => 'badge-info',
                                'rechazado' => 'badge-danger',
                                'entregado' => 'badge-success',
                                default => 'badge-neutral'
                            } }}">
                                {{ ucfirst($loan->status) }}
                            </span>
                        </td>

                        <td>
                            <div class="table-actions">
                                <a href="{{ route('loans.show', $loan) }}"
                                   class="btn btn-outline btn-sm">
                                    Detalles
                                </a>

                                @if(auth()->user()->isEncargado())

                                    @if($loan->status === 'pendiente')
                                        <form method="POST"
                                              action="{{ route('loans.approve', $loan) }}"
                                              data-confirm="¿Aprobar este préstamo?">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-primary btn-sm">
                                                Aprobar
                                            </button>
                                        </form>

                                        <form method="POST"
                                              action="{{ route('loans.reject', $loan) }}"
                                              data-confirm="¿Rechazar esta solicitud?">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-danger btn-sm">
                                                Rechazar
                                            </button>
                                        </form>
                                    @elseif($loan->status === 'aprobado')
                                        <form method="POST"
                                              action="{{ route('loans.deliver', $loan) }}"
                                              data-confirm="¿Registrar la entrega?">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-primary btn-sm">
                                                Entregar
                                            </button>
                                        </form>
                                    @elseif($loan->status === 'entregado')
                                        <form method="POST"
                                              action="{{ route('loans.return', $loan) }}"
                                              data-confirm="¿Registrar la devolución completa?">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-primary btn-sm">
                                                Devolver
                                            </button>
                                        </form>
                                    @endif

                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ auth()->user()->isEncargado() ? 6 : 5 }}"
                            class="table-empty">
                            No hay préstamos registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrapper">
        {{ $loans->links() }}
    </div>
</div>

@if(auth()->user()->isSolicitante())

<div class="modal" id="createLoanModal"
     role="dialog" aria-modal="true"
     aria-labelledby="createLoanTitle"
     aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-header">
            <h2 id="createLoanTitle">Nueva solicitud de préstamo</h2>
            <button type="button" class="modal-close"
                    data-modal-close aria-label="Cerrar">×</button>
        </div>

        <form id="loan-request-form"
              action="{{ route('loans.store') }}"
              method="POST">
            @csrf

            <div class="modal-body">

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label" for="start_date">
                            Fecha inicial
                        </label>
                        <input type="date" id="start_date"
                               name="start_date"
                               class="form-control"
                               min="{{ now()->toDateString() }}"
                               required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="due_date">
                            Fecha de devolución
                        </label>
                        <input type="date" id="due_date"
                               name="due_date"
                               class="form-control"
                               min="{{ now()->toDateString() }}"
                               required>
                    </div>
                </div>

                <h3 class="mb-2">Equipos solicitados</h3>

                <div id="loan-items-container" class="loan-items">

                    <div class="loan-item-row">
                        <div>
                            <label class="form-label">Equipo</label>
                            <select class="form-select"
                                    name="items[0][equipment_id]"
                                    data-loan-equipment required>
                                <option value="">Seleccionar equipo</option>
                                @foreach($equipment as $item)
                                    <option value="{{ $item->id }}"
                                            data-available="{{ $item->available_quantity }}">
                                        {{ $item->name }}
                                        ({{ $item->available_quantity }} disponibles)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="form-label">Cantidad</label>
                            <input type="number"
                                   name="items[0][quantity]"
                                   min="1" value="1"
                                   class="form-control"
                                   data-loan-quantity required>
                        </div>

                        <button type="button"
                                class="btn btn-outline btn-icon"
                                data-remove-loan-item
                                aria-label="Quitar equipo">
                            <svg viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2">
                                <path d="M5 12h14"/>
                            </svg>
                        </button>
                    </div>

                </div>

                <button type="button"
                        id="add-loan-item"
                        class="btn btn-secondary mt-2">
                    Agregar otro equipo
                </button>

                <div class="form-group mt-3">
                    <label class="form-label" for="loan_notes">
                        Observaciones
                    </label>
                    <textarea id="loan_notes" name="notes"
                              class="form-textarea"
                              placeholder="Describe el motivo del préstamo"></textarea>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                        data-modal-close>Cancelar</button>
                <button type="submit" class="btn btn-primary">
                    Enviar solicitud
                </button>
            </div>
        </form>
    </div>
</div>

<template id="loan-item-template">
    <div class="loan-item-row">
        <div>
            <label class="form-label">Equipo</label>
            <select class="form-select"
                    data-loan-equipment required>
                <option value="">Seleccionar equipo</option>
                @foreach($equipment as $item)
                    <option value="{{ $item->id }}"
                            data-available="{{ $item->available_quantity }}">
                        {{ $item->name }}
                        ({{ $item->available_quantity }} disponibles)
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="form-label">Cantidad</label>
            <input type="number" min="1" value="1"
                   class="form-control"
                   data-loan-quantity required>
        </div>

        <button type="button"
                class="btn btn-outline btn-icon"
                data-remove-loan-item
                aria-label="Quitar equipo">
            <svg viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2">
                <path d="M5 12h14"/>
            </svg>
        </button>
    </div>
</template>

@endif

@endsection
