
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Stock Tecnológico')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="public-layout">

    <header class="public-navbar">
        <div class="container navbar-inner">

            <a href="{{ route('home') }}" class="brand">
                <span class="brand-icon">
                    <svg viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">
                        <rect x="3" y="4"
                              width="18" height="13"
                              rx="2"/>
                        <path d="M2 20h20"/>
                    </svg>
                </span>

                Stock Tecnológico
            </a>

            <nav class="navbar-links"
                 aria-label="Navegación principal">
                <a href="{{ route('home') }}#inicio">
                    Inicio
                </a>

                <a href="{{ route('home') }}#servicios">
                    Servicios
                </a>

                <a href="{{ route('home') }}#reglas">
                    Reglas
                </a>

                <a href="{{ route('home') }}#contacto">
                    Contacto
                </a>
            </nav>

            <div class="navbar-actions">

                <button
                    type="button"
                    class="theme-toggle"
                    data-theme-toggle
                    aria-label="Cambiar tema">

                    <svg data-icon-sun
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2"
                         hidden>
                        <circle cx="12" cy="12" r="4"/>
                        <path d="M12 2v2m0 16v2M4.93 4.93l1.42 1.42m11.3 11.3 1.42 1.42M2 12h2m16 0h2"/>
                    </svg>

                    <svg data-icon-moon
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">
                        <path d="M20.9 13A9 9 0 0 1 11 3.1 9 9 0 1 0 20.9 13Z"/>
                    </svg>
                </button>

                @auth
                    <a href="{{ route('dashboard') }}"
                       class="btn btn-primary">
                        Mi panel
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="btn btn-primary">
                        Iniciar sesión
                    </a>
                @endauth

            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="public-footer" id="contacto">
        <div class="container">

            <div class="footer-grid">

                <div>
                    <h3>Stock Tecnológico</h3>
                    <p>
                        Plataforma institucional para la
                        administración de equipos tecnológicos,
                        préstamos e incidencias.
                    </p>
                </div>

                <div>
                    <h3>Enlaces</h3>
                    <a href="{{ route('home') }}">Inicio</a>
                    <a href="{{ route('home') }}#servicios">
                        Servicios
                    </a>
                    <a href="{{ route('home') }}#reglas">
                        Reglamento
                    </a>
                </div>

                <div>
                    <h3>Contacto</h3>
                    <p>Departamento de Recursos Tecnológicos</p>
                    <p>Consulta los datos de contacto de tu institución.</p>
                </div>

            </div>

            <div class="footer-bottom">
                &copy; {{ date('Y') }}
                Stock Tecnológico. Todos los derechos reservados.
            </div>

        </div>
    </footer>

</body>
</html>
