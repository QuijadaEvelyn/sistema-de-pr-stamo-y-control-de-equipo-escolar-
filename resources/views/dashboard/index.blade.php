
@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('page-title', 'Panel de control')

@section('content')

<div class="page-header">

    <div>
        <h1>
            Bienvenido, {{ $user->name }}
        </h1>

        <p>
            @if($user->isEncargado())
                Consulta el estado general del inventario,
                préstamos e incidencias.
            @else
                Consulta tus solicitudes y los equipos
                disponibles.
            @endif
        </p>
    </div>

    <div class="page-actions">
        @if($user->isSolicitante())
            <a href="{{ route('loans.index') }}"
               class="btn btn-primary">
                Solicitar equipo
            </a>
        @else
            <a href="{{ route('equipment.index') }}"
               class="btn btn-primary">
                Administrar inventario
            </a>
        @endif
    </div>

</div>

<div class="stats-grid">

    <div class="stat-card">
        <div class="stat-info">
            <p class="stat-label">Equipos disponibles</p>
            <p class="stat-value">{{ $stats['available'] }}</p>
        </div>

        <div class="stat-icon">
            <svg viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2">
                <rect x="3" y="4"
                      width="18" height="14" rx="2"/>
                <path d="M8 21h8M12 18v3"/>
            </svg>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <p class="stat-label">Préstamos pendientes</p>
            <p class="stat-value">{{ $stats['pending_loans'] }}</p>
        </div>

        <div class="stat-icon">
            <svg viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2">
                <circle cx="12" cy="12" r="9"/>
                <path d="M12 7v5l3 2"/>
            </svg>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <p class="stat-label">Préstamos activos</p>
            <p class="stat-value">{{ $stats['active_loans'] }}</p>
        </div>

        <div class="stat-icon">
            <svg viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2">
                <rect x="4" y="3"
                      width="16" height="18" rx="2"/>
                <path d="M8 12l3 3 5-6"/>
            </svg>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <p class="stat-label">Incidencias abiertas</p>
            <p class="stat-value">{{ $stats['open_incidents'] }}</p>
        </div>

        <div class="stat-icon">
            <svg viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2">
                <path d="M12 3 2 21h20L12 3Z"/>
                <path d="M12 9v5M12 18h.01"/>
            </svg>
        </div>
    </div>

</div>

<div class="content-grid">

    <section class="card">

        <div class="card-header">
            <h2>Préstamos recientes</h2>

            <a href="{{ route('loans.index') }}"
               class="btn btn-outline btn-sm">
                Ver todos
            </a>
        </div>

        <div class="table-responsive">
            <table class="data-table">

                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($recentLoans as $loan)
                        <tr>
                            <td>
                                <a href="{{ route('loans.show', $loan) }}">
                                    #{{ $loan->id }}
                                </a>
                            </td>

                            <td>
                                {{ $loan->created_at->format('d/m/Y') }}
                            </td>

                            <td>
                                @php
                                    $loanBadge = match($loan->status) {
                                        'pendiente' => 'badge-warning',
                                        'aprobado' => 'badge-info',
                                        'rechazado' => 'badge-danger',
                                        'entregado' => 'badge-success',
                                        'devuelto' => 'badge-neutral',
                                        default => 'badge-neutral'
                                    };
                                @endphp

                                <span class="badge {{ $loanBadge }}">
                                    {{ ucfirst($loan->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="table-empty">
                                No hay préstamos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </section>

    <section class="card">

        <div class="card-header">
            <h2>Incidencias recientes</h2>

            <a href="{{ route('incidents.index') }}"
               class="btn btn-outline btn-sm">
                Ver todas
            </a>
        </div>

        <div class="table-responsive">
            <table class="data-table">

                <thead>
                    <tr>
                        <th>Incidencia</th>
                        <th>Equipo</th>
                        <th>Estado</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($recentIncidents as $incident)
                        <tr>
                            <td>{{ $incident->title }}</td>

                            <td>
                                {{ $incident->equipment->name ?? 'Sin equipo' }}
                            </td>

                            <td>
                                @php
                                    $incidentBadge = match($incident->status) {
                                        'abierta' => 'badge-danger',
                                        'en_proceso' => 'badge-warning',
                                        'resuelta' => 'badge-success',
                                        default => 'badge-neutral'
                                    };
                                @endphp

                                <span class="badge {{ $incidentBadge }}">
                                    {{ ucfirst(str_replace('_', ' ', $incident->status)) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="table-empty">
                                No hay incidencias registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </section>

</div>

@endsection
