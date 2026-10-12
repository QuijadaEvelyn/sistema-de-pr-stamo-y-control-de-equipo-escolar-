
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token"
          content="{{ csrf_token() }}">

    <title>@yield('title', 'Panel') | Stock Tecnológico</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

<div class="dashboard-layout">

    <aside class="sidebar" id="sidebar">

        <div class="sidebar-header">
            <a href="{{ route('dashboard') }}"
               class="brand">

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
        </div>

        <div class="sidebar-menu">

            <p class="sidebar-label">Menú principal</p>

            <nav class="sidebar-nav"
                 aria-label="Menú del panel">

                <a href="{{ route('dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">

                    <svg viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">
                        <rect x="3" y="3" width="7" height="7"/>
                        <rect x="14" y="3" width="7" height="7"/>
                        <rect x="3" y="14" width="7" height="7"/>
                        <rect x="14" y="14" width="7" height="7"/>
                    </svg>

                    Dashboard
                </a>

                <a href="{{ route('equipment.index') }}"
                   class="sidebar-link {{ request()->routeIs('equipment.*') ? 'active' : '' }}">

                    <svg viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">
                        <rect x="3" y="4"
                              width="18" height="14"
                              rx="2"/>
                        <path d="M8 21h8M12 18v3"/>
                    </svg>

                    Inventario
                </a>

                @if(auth()->user()->isEncargado())

                    <a href="{{ route('categories.index') }}"
                       class="sidebar-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">

                        <svg viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2">
                            <rect x="3" y="3" width="7" height="7"/>
                            <rect x="14" y="3" width="7" height="7"/>
                            <rect x="3" y="14" width="7" height="7"/>
                            <rect x="14" y="14" width="7" height="7"/>
                        </svg>

                        Categorías
                    </a>

                @endif

                <a href="{{ route('loans.index') }}"
                   class="sidebar-link {{ request()->routeIs('loans.*') ? 'active' : '' }}">

                    <svg viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">
                        <rect x="4" y="3"
                              width="16" height="18"
                              rx="2"/>
                        <path d="M8 8h8M8 12h8M8 16h5"/>
                    </svg>

                    Préstamos
                </a>

                <a href="{{ route('incidents.index') }}"
                   class="sidebar-link {{ request()->routeIs('incidents.*') ? 'active' : '' }}">

                    <svg viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">
                        <path d="M12 3 2 21h20L12 3Z"/>
                        <path d="M12 9v5M12 18h.01"/>
                    </svg>

                    Incidencias
                </a>

            </nav>

        </div>

        <div class="sidebar-footer">

            <div class="sidebar-user">

                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>

                <div class="sidebar-user-info">
                    <strong>{{ auth()->user()->name }}</strong>
                    <span>{{ auth()->user()->role }}</span>
                </div>

            </div>

            <form action="{{ route('logout') }}"
                  method="POST">
                @csrf

                <button type="submit"
                        class="sidebar-link"
                        style="width:100%;border:0;background:transparent;text-align:left;">

                    <svg viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <path d="m16 17 5-5-5-5"/>
                        <path d="M21 12H9"/>
                    </svg>

                    Cerrar sesión
                </button>
            </form>

        </div>
    </aside>

    <div class="sidebar-overlay"></div>

    <div class="main-wrapper">

        <header class="topbar">

            <div class="topbar-left">

                <button type="button"
                        class="menu-toggle"
                        data-sidebar-toggle
                        aria-label="Abrir menú"
                        aria-expanded="false">

                    <svg viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">
                        <path d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <span class="topbar-title">
                    @yield('page-title', 'Panel de control')
                </span>

            </div>

            <div class="topbar-actions">

                <button type="button"
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

                <div class="topbar-user">
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>

                    <span>{{ auth()->user()->name }}</span>
                </div>

            </div>
        </header>

        <main class="main-content">

            @if(session('success'))
                <div class="alert alert-success" role="status">
                    <span>{{ session('success') }}</span>

                    <button type="button"
                            class="alert-close"
                            data-alert-close
                            aria-label="Cerrar alerta">
                        <svg viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2">
                            <path d="M18 6 6 18M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger" role="alert">
                    <span>{{ session('error') }}</span>

                    <button type="button"
                            class="alert-close"
                            data-alert-close
                            aria-label="Cerrar alerta">
                        Cerrar
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger" role="alert">
                    <div>
                        <strong>Revisa los siguientes errores:</strong>

                        <ul class="error-list">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @yield('content')

        </main>
    </div>

</div>

</body>
</html>
