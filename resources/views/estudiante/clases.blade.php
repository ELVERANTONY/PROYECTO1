<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Clases - AventuraTec</title>
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
        .card-neon {
            background: var(--dark);
            border: 1px solid var(--primary);
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 0 16px rgba(15, 255, 192, 0.2);
        }
        .card-neon-header {
            color: var(--secondary);
            font-family: 'Orbitron', sans-serif;
            border-bottom: 1px solid rgba(15, 255, 192, 0.2);
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
        }
        .form-control-neon {
            background: #222b3a;
            color: #fff;
            border-radius: 8px;
            border: 2px solid var(--primary);
            box-shadow: 0 0 8px rgba(15, 255, 192, 0.33);
        }
        .form-control-neon::placeholder { color: rgba(255, 255, 255, 0.7); }
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
            transition: all 0.3s ease;
            box-shadow: 0 0 8px var(--primary);
        }
        .btn-neon:hover { 
            box-shadow: 0 0 16px var(--primary), 0 0 24px var(--primary);
            color: var(--dark);
        }
        .table {
            background-color: transparent;
            color: var(--light);
        }
        .table th { color: var(--primary); border-color: rgba(15, 255, 192, 0.4); }
        .table td { vertical-align: middle; border-color: rgba(15, 255, 192, 0.2); }
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
        <h1 class="main-header">Clases Finalizadas</h1>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="background-color: var(--primary); color: var(--dark); border: none;">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card card-neon">
            <div class="card-body">
                @if($clasesFinalizadas->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-dark table-hover">
                            <thead>
                                <tr>
                                    <th>Clase</th>
                                    <th>Profesor</th>
                                    <th>Fecha de Finalización</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($clasesFinalizadas as $clase)
                                    <tr>
                                        <td>{{ $clase->nombre }}</td>
                                        <td>{{ $clase->profesor->name ?? 'Prof. Desconocido' }}</td>
                                        <td>{{ $clase->updated_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center p-5">
                        <i class="fas fa-history fa-3x mb-3" style="color: var(--accent);"></i>
                        <h4 class="text-white">No tienes clases finalizadas aún.</h4>
                        <p class="text-white-50">Cuando completes una clase, aparecerá aquí en tu historial.</p>
                    </div>
                @endif
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Desvanecer la alerta de éxito después de 5 segundos
    const alert = document.querySelector('.alert-dismissible');
    if (alert) {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    }
</script>

</body>
</html> 