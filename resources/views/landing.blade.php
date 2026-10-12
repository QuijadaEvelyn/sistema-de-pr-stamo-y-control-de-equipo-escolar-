
@extends('layouts.public')

@section('title', 'Inicio | Stock Tecnológico')

@section('content')

<section class="hero" id="inicio">
    <div class="container">
        <div class="hero-content">

            <span class="hero-label">
                Plataforma de gestión institucional
            </span>

            <h1>
                Tecnología disponible para
                impulsar el aprendizaje
            </h1>

            <p>
                Consulta los equipos tecnológicos de tu
                institución, solicita préstamos y reporta
                incidencias desde una plataforma sencilla,
                segura y organizada.
            </p>

            <div class="hero-actions">
                @auth
                    <a href="{{ route('dashboard') }}"
                       class="btn btn-primary btn-lg">
                        Ir a mi panel
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="btn btn-primary btn-lg">
                        Solicitar un equipo
                    </a>
                @endauth

                <a href="#servicios"
                   class="btn btn-outline btn-lg">
                    Conocer servicios
                </a>
            </div>

        </div>
    </div>
</section>

<section class="section" id="servicios">
    <div class="container">

        <div class="section-heading">
            <h2>Nuestros servicios</h2>
            <p>
                Una solución para organizar y administrar
                los recursos tecnológicos de la institución.
            </p>
        </div>

        <div class="feature-grid">

            <article class="feature-card">
                <div class="feature-icon">
                    <svg viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">
                        <rect x="3" y="4"
                              width="18" height="14" rx="2"/>
                        <path d="M8 21h8M12 18v3"/>
                    </svg>
                </div>

                <h3>Inventario tecnológico</h3>

                <p>
                    Consulta laptops, proyectores,
                    accesorios y otros equipos, así como
                    su disponibilidad y estado.
                </p>
            </article>

            <article class="feature-card">
                <div class="feature-icon">
                    <svg viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">
                        <rect x="4" y="3"
                              width="16" height="18" rx="2"/>
                        <path d="M8 8h8M8 12h8M8 16h5"/>
                    </svg>
                </div>

                <h3>Préstamos de equipos</h3>

                <p>
                    Solicita equipos tecnológicos y
                    consulta el estado de tus solicitudes,
                    entregas y devoluciones.
                </p>
            </article>

            <article class="feature-card">
                <div class="feature-icon">
                    <svg viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">
                        <path d="M12 3 2 21h20L12 3Z"/>
                        <path d="M12 9v5M12 18h.01"/>
                    </svg>
                </div>

                <h3>Reporte de incidencias</h3>

                <p>
                    Reporta fallas o daños en los equipos
                    y consulta el seguimiento de cada
                    incidencia registrada.
                </p>
            </article>

        </div>
    </div>
</section>

<section class="section section-alt" id="reglas">
    <div class="container">

        <div class="section-heading">
            <h2>Reglas de préstamo</h2>

            <p>
                Para cuidar los recursos institucionales,
                es importante cumplir las siguientes reglas.
            </p>
        </div>

        <div class="rules-grid">

            @php
                $rules = [
                    [
                        'title' => 'Solicita con anticipación',
                        'description' => 'Registra tu solicitud indicando los equipos y las fechas requeridas.'
                    ],
                    [
                        'title' => 'Espera la autorización',
                        'description' => 'El encargado revisará la disponibilidad antes de aprobar la solicitud.'
                    ],
                    [
                        'title' => 'Cuida los equipos',
                        'description' => 'Utiliza los recursos exclusivamente para actividades autorizadas.'
                    ],
                    [
                        'title' => 'Devuelve a tiempo',
                        'description' => 'Respeta la fecha de devolución establecida en el préstamo.'
                    ],
                    [
                        'title' => 'Reporta las fallas',
                        'description' => 'Comunica cualquier daño o funcionamiento incorrecto.'
                    ],
                    [
                        'title' => 'Respeta el procedimiento',
                        'description' => 'La entrega y devolución deben ser registradas por el encargado.'
                    ]
                ];
            @endphp

            @foreach($rules as $index => $rule)
                <article class="rule-item">

                    <span class="rule-number">
                        {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                    </span>

                    <div>
                        <h3>{{ $rule['title'] }}</h3>
                        <p>{{ $rule['description'] }}</p>
                    </div>

                </article>
            @endforeach

        </div>
    </div>
</section>

<section class="section">
    <div class="container text-center">

        <div class="section-heading">
            <h2>Administra tus solicitudes</h2>

            <p>
                Ingresa a tu cuenta institucional para
                consultar el inventario y gestionar tus
                préstamos.
            </p>
        </div>

        <a href="{{ auth()->check() ? route('dashboard') : route('login') }}"
           class="btn btn-accent btn-lg">
            Acceder al sistema
        </a>

    </div>
</section>

@endsection
