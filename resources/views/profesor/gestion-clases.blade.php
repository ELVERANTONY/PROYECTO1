<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Clases - AventuraTec</title>
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
        .sidebar-footer { padding: 1.5rem 1.5rem 0 1.5rem; border-top: 1px solid rgba(15, 255, 192, 0.2); }
        .main-content { flex-grow: 1; padding: 2rem 3rem; margin-left: 260px; }
        .main-header { color: var(--secondary); font-family: 'Orbitron', sans-serif; margin-bottom: 1rem; text-shadow: 0 0 12px var(--secondary); }
        .main-card { background: var(--dark); padding: 2rem; border-radius: 1rem; border: 1px solid var(--primary); }
        .table { background-color: transparent; color: var(--light); }
        .table th { color: var(--primary); border-color: rgba(15, 255, 192, 0.4); }
        .table td { border-color: rgba(15, 255, 192, 0.2); }
        .btn-neon-primary { background: var(--primary); color: var(--dark); font-weight: bold; border: none; transition: all 0.3s ease; box-shadow: 0 0 8px var(--primary); }
        .btn-neon-primary:hover { box-shadow: 0 0 16px var(--primary), 0 0 24px var(--primary); }
        .btn-neon-warning { background: var(--secondary); color: var(--dark); border: none; }
        .btn-neon-info { background: var(--accent); color: var(--dark); border: none; }
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
                <li class="nav-item"><a href="{{ route('profesor.estudiantes') }}"><i class="fas fa-users nav-icon"></i><span class="nav-text">Estudiantes</span></a></li>
                <li class="nav-item active"><a href="{{ route('profesor.gestion-clases') }}"><i class="fas fa-chalkboard-teacher nav-icon"></i><span class="nav-text">Gestión de Clases</span></a></li>
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
        <h1 class="main-header">Gestión de Clases</h1>
        <p class="lead mb-4">Desde aquí puedes administrar todas tus clases. Prepara tu material educativo, crea nuevas clases y gestiona sus preguntas.</p>
        
        <div class="main-card">
             <div class="d-flex justify-content-between align-items-center mb-3">
                 <h3 style="font-family: 'Orbitron', sans-serif; color: var(--light)">Mis Plantillas de Clase</h3>
                 <a href="{{ route('profesor.clases.crear') }}" class="btn btn-neon-primary"><i class="fas fa-plus-circle me-2"></i>Crear Nueva Clase</a>
            </div>
             <div class="table-responsive">
                <table class="table table-hover align-middle">
                     <thead>
                         <tr>
                             <th>Nombre de la Clase</th>
                             <th>Tema</th>
                             <th class="text-center">N° de Preguntas</th>
                             <th class="text-center">Acciones</th>
                         </tr>
                     </thead>
                     <tbody>
                         @forelse ($clases as $clase)
                         <tr>
                             <td>{{ $clase->nombre }}</td>
                             <td>{{ $clase->tema }}</td>
                             <td class="text-center"><span class="badge bg-info">{{ $clase->preguntas_count }}</span></td>
                             <td class="text-center">
                                 <a href="{{ route('profesor.clases.ver', $clase) }}" class="btn btn-sm btn-outline-info mx-1" title="Ver Preguntas"><i class="fas fa-eye"></i></a>
                                 <a href="{{ route('profesor.clases.editar', $clase) }}" class="btn btn-sm btn-outline-warning mx-1" title="Editar Clase"><i class="fas fa-edit"></i></a>
                                 <form action="{{ route('profesor.clases.destroy', $clase) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta clase? Esta acción no se puede deshacer.');">
                                     @csrf
                                     @method('DELETE')
                                     <button type="submit" class="btn btn-sm btn-outline-danger mx-1" title="Eliminar Clase"><i class="fas fa-trash"></i></button>
                                 </form>
                             </td>
                         </tr>
                         @empty
                         <tr>
                             <td colspan="4" class="text-center py-4">No has creado ninguna clase todavía. ¡Haz clic en "Crear Nueva Clase" para empezar!</td>
                         </tr>
                         @endforelse
                     </tbody>
                </table>
             </div>
         </div>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 