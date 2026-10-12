
@extends('layouts.dashboard')

@section('title', 'Detalle del préstamo')
@section('page-title', 'Detalle del préstamo')

@section('content')

<div class="page-header">
    <div>
        <h1>Préstamo #{{ $loan->id }}</h1>
        <p>Información y seguimiento del préstamo.</p>
    </div>

    <a href="{{ route('loans.index') }}"
       class="btn btn-outline">
        Volver a préstamos
    </a>
</div>

<div class="card mb-3">
    <div class="card-header">
        <h2>Información general</h2>

        <span class="badge {{ match($loan->status) {
            'pendiente' => 'badge-warning',
            'aprobado' => 'badge-info',
            'rechazado' => 'badge-danger',
            'entregado' => 'badge-success',
            default => 'badge-neutral'
        } }}">
            {{ ucfirst($loan->status) }}
        </span>
    </div>

    <div class="card-body">
        <div class="loan-detail-grid">

            <div class="detail-item">
                <span class="detail-label">Solicitante</span>
                <span class="detail-value">
                    {{ $loan->user->name }}
                </span>
            </div>

            <div class="detail-item">
                <span class="detail-label">Fecha inicial</span>
                <span class="detail-value">
                    {{ $loan->start_date->format('d/m/Y') }}
                </span>
            </div>

            <div class="detail-item">
                <span class="detail-label">Devolución prevista</span>
                <span class="detail-value">
                    {{ $loan->due_date->format('d/m/Y') }}
                </span>
            </div>

            <div class="detail-item">
                <span class="detail-label">Atendido por</span>
                <span class="detail-value">
                    {{ $loan->handler?->name ?? 'Pendiente' }}
                </span>
            </div>

            <div class="detail-item">
                <span class="detail-label">Fecha de entrega</span>
                <span class="detail-value">
                    {{ $loan->delivered_at?->format('d/m/Y H:i') ?? 'Sin entregar' }}
                </span>
            </div>

            <div class="detail-item">
                <span class="detail-label">Fecha de devolución real</span>
                <span class="detail-value">
                    {{ $loan->returned_at?->format('d/m/Y H:i') ?? 'Sin devolver' }}
                </span>
            </div>

        </div>

        @if($loan->notes)
            <div class="mt-3">
                <h3 class="mb-1">Observaciones</h3>
                <p>{{ $loan->notes }}</p>
            </div>
        @endif
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">
        <h2>Equipos incluidos</h2>
    </div>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Equipo</th>
                    <th>Categoría</th>
                    <th>Cantidad</th>
                </tr>
            </thead>

            <tbody>
                @foreach($loan->items as $item)
                    <tr>
                        <td>{{ $item->equipment->code }}</td>
                        <td>{{ $item->equipment->name }}</td>
                        <td>{{ $item->equipment->category->name }}</td>
                        <td>{{ $item->quantity }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@if($loan->incidents->isNotEmpty())
    <div class="card mb-3">
        <div class="card-header">
            <h2>Incidencias relacionadas</h2>
        </div>

        <div class="card-body">
            @foreach($loan->incidents as $incident)
                <article class="incident-card mb-2">
                    <div class="incident-header">
                        <h3 class="incident-title">
                            {{ $incident->title }}
                        </h3>
                        <span class="badge badge-info">
                            {{ ucfirst(str_replace('_', ' ', $incident->status)) }}
                        </span>
                    </div>
                    <p class="incident-description">
                        {{ $incident->description }}
                    </p>
                </article>
            @endforeach
        </div>
    </div>
@endif

@if(auth()->user()->isEncargado())
    <div class="card">
        <div class="card-header">
            <h2>Administrar préstamo</h2>
        </div>

        <div class="card-body">
            <div class="quick-actions">

                @if($loan->status === 'pendiente')
                    <form action="{{ route('loans.approve', $loan) }}"
                          method="POST"
                          data-confirm="¿Aprobar este préstamo?">
                        @csrf
                        <button class="btn btn-primary" type="submit">
                            Aprobar préstamo
                        </button>
                    </form>

                    <form action="{{ route('loans.reject', $loan) }}"
                          method="POST"
                          data-confirm="¿Rechazar este préstamo?">
                        @csrf
                        <button class="btn btn-danger" type="submit">
                            Rechazar préstamo
                        </button>
                    </form>
                @elseif($loan->status === 'aprobado')
                    <form action="{{ route('loans.deliver', $loan) }}"
                          method="POST"
                          data-confirm="¿Registrar entrega?">
                        @csrf
                        <button class="btn btn-primary" type="submit">
                            Registrar entrega
                        </button>
                    </form>
                @elseif($loan->status === 'entregado')
                    <form action="{{ route('loans.return', $loan) }}"
                          method="POST"
                          data-confirm="¿Confirmar devolución completa?">
                        @csrf
                        <button class="btn btn-primary" type="submit">
                            Registrar devolución
                        </button>
                    </form>
                @else
                    <p class="text-muted">
                        Este préstamo no tiene acciones pendientes.
                    </p>
                @endif

            </div>
        </div>
    </div>
@endif

@endsection
