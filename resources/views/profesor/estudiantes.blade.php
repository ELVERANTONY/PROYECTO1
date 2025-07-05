<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estudiantes - AventuraTec</title>
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
        .dashboard-layout { display: flex; min-height: 100vh; }
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
        .sidebar-header { padding: 0 1.5rem 1.5rem 1.5rem; text-align: center; border-bottom: 1px solid rgba(15, 255, 192, 0.2); }
        .sidebar-brand { font-family: 'Orbitron', sans-serif; font-weight: 700; color: var(--primary) !important; letter-spacing: 1px; font-size: 1.5rem; text-shadow: 0 0 8px var(--primary); }
        .sidebar-nav { flex-grow: 1; list-style: none; padding: 1.5rem 0; margin: 0; }
        .nav-item a { display: flex; align-items: center; padding: 0.8rem 1.5rem; color: var(--light); text-decoration: none; font-weight: 500; transition: all 0.3s ease; border-left: 4px solid transparent; }
        .nav-item.active a, .nav-item a:hover { background: rgba(15, 255, 192, 0.1); color: var(--primary); border-left-color: var(--primary); }
        .nav-item a .nav-icon { font-size: 1.2rem; margin-right: 1rem; width: 20px; text-align: center; text-shadow: 0 0 6px var(--primary); }
        .sidebar-footer { padding: 1.5rem 1.5rem 0 1.5rem; border-top: 1px solid rgba(15, 255, 192, 0.2); }
        .main-content { flex-grow: 1; padding: 2rem 3rem; margin-left: 260px; }
        .main-header { color: var(--secondary); font-family: 'Orbitron', sans-serif; margin-bottom: 1rem; text-shadow: 0 0 12px var(--secondary); }
        .main-card { background: var(--dark); padding: 2rem; border-radius: 1rem; border: 1px solid var(--primary); }
        .table { background-color: transparent; color: var(--light); }
        .table th { color: var(--primary); border-color: rgba(15, 255, 192, 0.4); }
        .table td { border-color: rgba(15, 255, 192, 0.2); }
    </style>
</head>
<body>
<div class="dashboard-layout">
    <!-- Barra Lateral -->
    <aside class="sidebar">
        <div>
            <div class="sidebar-header">
                <a class="sidebar-brand" href="{{ route('profesor.dashboard') }}">AventuraTec</a>
            </div>
            <ul class="sidebar-nav">
                <li class="nav-item"><a href="{{ route('profesor.dashboard') }}"><i class="fas fa-tachometer-alt nav-icon"></i><span class="nav-text">Dashboard</span></a></li>
                <li class="nav-item active"><a href="{{ route('profesor.estudiantes') }}"><i class="fas fa-users nav-icon"></i><span class="nav-text">Estudiantes</span></a></li>
                <li class="nav-item"><a href="{{ route('profesor.gestion-clases') }}"><i class="fas fa-chalkboard-teacher nav-icon"></i><span class="nav-text">Gestión de Clases</span></a></li>
                <li class="nav-item"><a href="{{ route('profesor.ajustes') }}"><i class="fas fa-cog nav-icon"></i><span class="nav-text">Ajustes</span></a></li>
            </ul>
        </div>
        <div class="sidebar-footer">
             <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-danger w-100">Cerrar Sesión</button>
            </form>
        </div>
    </aside>

    <!-- Contenido Principal -->
    <main class="main-content">
        <h1 class="main-header">Gestión de Estudiantes</h1>
        <p class="lead mb-4">Administra a tus guerreros del conocimiento. Observa su progreso y el ranking general.</p>

        <div class="row">
            <!-- Tabla de todos los estudiantes -->
            <div class="col-lg-8">
                <div class="main-card">
                    <h3 class="mb-3" style="font-family: 'Orbitron', sans-serif;">Todos los Estudiantes</h3>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Clases Inscritas</th>
                                    <th class="text-center">Puntos EXP</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($estudiantes as $estudiante)
                                    <tr>
                                        <td>{{ $estudiante->name }}</td>
                                        <td>{{ $estudiante->email }}</td>
                                        <td>
                                            @foreach($estudiante->clasesComoEstudiante as $clase)
                                                <span class="badge bg-primary me-1">{{ $clase->nombre }}</span>
                                            @endforeach
                                        </td>
                                        <td class="text-center">{{ $estudiante->xp ?? 0 }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">Aún no hay estudiantes inscritos en tus clases.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Ranking -->
            <div class="col-lg-4">
                <div class="main-card">
                    <h3 class="mb-3" style="font-family: 'Orbitron', sans-serif;"><i class="fas fa-trophy me-2" style="color: var(--secondary);"></i>Ranking</h3>
                    <ul class="list-group list-group-flush">
                        @forelse ($ranking as $index => $estudiante)
                            <li class="list-group-item d-flex justify-content-between align-items-center" style="background: transparent; color: var(--light); border-color: rgba(15, 255, 192, 0.2);">
                                <div>
                                    <span class="fw-bold me-2">{{ $index + 1 }}.</span> {{ $estudiante->name }}
                                </div>
                                <span class="badge bg-warning rounded-pill">{{ $estudiante->xp ?? 0 }} EXP</span>
                            </li>
                        @empty
                            <li class="list-group-item" style="background: transparent; color: var(--light);">El ranking está vacío.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 