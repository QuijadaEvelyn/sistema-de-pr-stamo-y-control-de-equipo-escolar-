
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Iniciar sesión | Stock Tecnológico</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

<div class="auth-page">

    <section class="auth-info">

        <a href="{{ route('home') }}"
           class="brand mb-3"
           style="color:var(--lemon-chiffon);">

            <span class="brand-icon"
                  style="background:var(--soft-fawn);color:var(--rich-mahogany);">

                <svg viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2">
                    <rect x="3" y="4"
                          width="18" height="14" rx="2"/>
                    <path d="M8 21h8M12 18v3"/>
                </svg>
            </span>

            Stock Tecnológico
        </a>

        <h1>
            Gestiona los recursos
            tecnológicos de tu institución
        </h1>

        <p>
            Accede a tu cuenta para consultar equipos,
            solicitar préstamos, revisar su estado
            y reportar incidencias.
        </p>

    </section>

    <section class="auth-form-side">

        <div class="auth-card">

            <button type="button"
                    class="theme-toggle mb-3"
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

            <h2>Bienvenido</h2>

            <p>
                Ingresa tus credenciales para continuar.
            </p>

            @if($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul class="error-list">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login.submit') }}"
                  method="POST"
                  data-prevent-double-submit>

                @csrf

                <div class="form-group">

                    <label for="email" class="form-label">
                        Correo electrónico
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control"
                        placeholder="usuario@institucion.edu"
                        value="{{ old('email') }}"
                        autocomplete="username"
                        required
                        autofocus>

                </div>

                <div class="form-group">

                    <label for="password" class="form-label">
                        Contraseña
                    </label>

                    <div class="flex gap-1">

                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control"
                            placeholder="Ingresa tu contraseña"
                            autocomplete="current-password"
                            required>

                        <button type="button"
                                class="btn btn-outline"
                                data-password-toggle="password"
                                aria-label="Mostrar contraseña">
                            Ver
                        </button>

                    </div>
                </div>

                <button type="submit"
                        class="btn btn-primary btn-block">
                    Iniciar sesión
                </button>

            </form>

            <div class="auth-links">
                <a href="{{ route('home') }}">
                    Volver al inicio
                </a>
            </div>

        </div>

    </section>

</div>

</body>
</html>
