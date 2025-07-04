<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajustes - AventuraTec</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Orbitron:wght@700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
        :root {
            --primary: #0fffc0;
            --secondary: #ffe066;
            --dark: #181c23;
            --light: #e0e7ef;
            --dark-deep: #101216;
            --accent: #00c3ff;
        }
        body {
            background: radial-gradient(circle at 50% 30%, var(--primary) 0%, var(--dark-deep) 100%);
            min-height: 100vh;
            color: var(--light);
            font-family: 'Montserrat', sans-serif;
            overflow-x: hidden;
        }
        .dashboard-layout {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 260px;
            background: var(--dark);
            border-right: 2px solid var(--primary);
            box-shadow: 4px 0 24px rgba(15, 255, 192, 0.3);
            display: flex;
            flex-direction: column;
            padding: 1.5rem 0;
            position: fixed;
            height: 100%;
            z-index: 1000;
        }
        .sidebar-header {
            padding: 0 1.5rem 1.5rem 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(15, 255, 192, 0.2);
        }
        .sidebar-brand {
            font-family: 'Orbitron', sans-serif;
            font-weight: 700;
            color: var(--primary) !important;
            letter-spacing: 1px;
            font-size: 1.5rem;
            text-shadow: 0 0 8px var(--primary);
        }
        .sidebar-nav {
            flex-grow: 1;
            list-style: none;
            padding: 1.5rem 0;
            margin: 0;
        }
        .nav-item a {
            display: flex;
            align-items: center;
            padding: 0.8rem 1.5rem;
            color: var(--light);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }
        .nav-item.active a, .nav-item a:hover {
            background: rgba(15, 255, 192, 0.1);
            color: var(--primary);
            border-left-color: var(--primary);
        }
        .nav-item a .nav-icon {
            font-size: 1.2rem;
            margin-right: 1rem;
            width: 20px;
            text-align: center;
            text-shadow: 0 0 6px var(--primary);
        }
        .sidebar-footer {
            padding: 1.5rem 1.5rem 0 1.5rem;
            border-top: 1px solid rgba(15, 255, 192, 0.2);
        }
        .main-content {
            flex-grow: 1;
            padding: 2rem 3rem;
            margin-left: 260px;
        }
        .main-header {
            color: var(--secondary);
            font-family: 'Orbitron', sans-serif;
            margin-bottom: 2rem;
            text-shadow: 0 0 12px var(--secondary);
        }
        .form-card {
            background: var(--dark);
            border: 1px solid var(--primary);
            border-radius: 1rem;
            padding: 2.5rem;
            box-shadow: 0 0 16px rgba(15, 255, 192, 0.2);
        }
        .form-label {
            color: var(--primary);
            font-weight: 500;
        }
        .form-control-neon {
            background: #222b3a;
            color: #fff;
            border-radius: 8px;
            border: 2px solid var(--primary);
        }
        .form-control-neon:focus {
            border-color: var(--secondary);
            box-shadow: 0 0 16px var(--secondary);
            background: #222b3a;
        }
        .btn-neon {
            background: var(--primary);
            color: var(--dark);
            font-weight: bold;
            border: none;
        }
        .skins-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 1rem;
        }
        .skin-radio-card {
            cursor: pointer;
            border: 2px solid var(--accent);
            border-radius: 0.5rem;
            overflow: hidden;
            position: relative;
            transition: all 0.3s ease;
        }
        .skin-radio-card:hover {
            transform: scale(1.05);
            box-shadow: 0 0 12px var(--accent);
        }
        .skin-radio-card input:checked + .skin-card-content {
            box-shadow: 0 0 18px var(--secondary);
            border: 2px solid var(--secondary);
        }
        .skin-card-content {
            border: 2px solid transparent;
            border-radius: 0.5rem;
        }
        .skin-card-content img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            display: block;
        }
        .skin-name-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0,0,0,0.7);
            color: var(--light);
            padding: 0.3rem;
            text-align: center;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
<div class="dashboard-layout">
    <aside class="sidebar">
        <div>
            <div class="sidebar-header">
                <a class="sidebar-brand" href="{{ route('estudiante.dashboard') }}">AventuraTec</a>
            </div>
            <ul class="sidebar-nav">
                <li class="nav-item {{ request()->routeIs('estudiante.dashboard') ? 'active' : '' }}"><a href="{{ route('estudiante.dashboard') }}"><i class="fas fa-user-astronaut nav-icon"></i><span class="nav-text">Mi Lobby</span></a></li>
                <li class="nav-item {{ request()->routeIs('estudiante.clases') ? 'active' : '' }}"><a href="{{ route('estudiante.clases') }}"><i class="fas fa-chalkboard-user nav-icon"></i><span class="nav-text">Mis Clases</span></a></li>
                <li class="nav-item {{ request()->routeIs('estudiante.tienda') ? 'active' : '' }}"><a href="{{ route('estudiante.tienda') }}"><i class="fas fa-store nav-icon"></i><span class="nav-text">Tienda</span></a></li>
                <li class="nav-item {{ request()->routeIs('estudiante.ranking') ? 'active' : '' }}"><a href="{{ route('estudiante.ranking') }}"><i class="fas fa-trophy nav-icon"></i><span class="nav-text">Ranking</span></a></li>
                <li class="nav-item {{ request()->routeIs('estudiante.ajustes') ? 'active' : '' }}"><a href="{{ route('estudiante.ajustes') }}"><i class="fas fa-cog nav-icon"></i><span class="nav-text">Ajustes</span></a></li>
            </ul>
        </div>
        <div class="sidebar-footer">
             <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-danger w-100">Cerrar Sesión</button>
            </form>
        </div>
    </aside>

    <main class="main-content">
        <h1 class="main-header">Ajustes de la Cuenta</h1>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="form-card">
                    <form action="{{-- {{ route('estudiante.ajustes.update') }} --}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <!-- Nombre -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre</label>
                            <input type="text" id="name" name="name" class="form-control form-control-neon" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input type="email" id="email" name="email" class="form-control form-control-neon" value="{{ old('email', $user->email) }}" required>
                        </div>

                        <hr style="border-color: rgba(15, 255, 192, 0.4);">

                        <h5 class="mt-4 mb-3" style="color: var(--secondary);">Cambiar Contraseña</h5>

                        <!-- Contraseña Actual -->
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Contraseña Actual</label>
                            <input type="password" id="current_password" name="current_password" class="form-control form-control-neon">
                        </div>

                        <!-- Nueva Contraseña -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Nueva Contraseña</label>
                            <input type="password" id="password" name="password" class="form-control form-control-neon">
                        </div>

                        <!-- Confirmar Nueva Contraseña -->
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control form-control-neon">
                        </div>

                        <button type="submit" class="btn btn-neon mt-3">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html> 