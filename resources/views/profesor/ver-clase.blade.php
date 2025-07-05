<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Clase: {{ $clase->nombre }} - AventuraTec</title>
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
            --success: #28a745;
        }
        body {
            background: radial-gradient(circle at 50% 30%, var(--primary) 0%, var(--dark-deep) 100%);
            min-height: 100vh;
            color: var(--light);
            font-family: 'Montserrat', sans-serif;
            overflow-x: hidden;
        }
        .dashboard-layout { display: flex; min-height: 100vh; }
        .sidebar { width: 260px; background: var(--dark); border-right: 2px solid var(--primary); box-shadow: 4px 0 24px rgba(15, 255, 192, 0.3); display: flex; flex-direction: column; padding: 1.5rem 0; position: fixed; height: 100%; z-index: 1000; }
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
        .question-card { background: var(--dark-deep); border: 1px solid var(--accent); border-radius: 0.5rem; margin-bottom: 1.5rem; }
        .list-group-item.correct { background-color: rgba(40, 167, 69, 0.2); border-left: 4px solid var(--success); }
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
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="main-header">Detalle de la Clase</h1>
            <a href="{{ route('profesor.gestion-clases') }}" class="btn btn-outline-secondary mb-3"><i class="fas fa-arrow-left me-2"></i>Volver a Gestión</a>
        </div>
        
        <div class="main-card mb-4">
            <h2 style="font-family: 'Orbitron', sans-serif; color: var(--primary)">{{ $clase->nombre }}</h2>
            <p class="lead" style="color: var(--secondary)">Tema: {{ $clase->tema }}</p>
        </div>

        <h3 class="mb-3" style="color: var(--light)">Preguntas de la Clase</h3>

        @forelse ($clase->preguntas as $index => $pregunta)
            <div class="question-card p-3">
                <h5 class="font-weight-bold" style="color: var(--accent);">Pregunta {{ $index + 1 }}: {{ $pregunta->pregunta }}</h5>
                <ul class="list-group mt-3">
                    @foreach ($pregunta->alternativas as $alternativa)
                        <li class="list-group-item @if($alternativa->es_correcta) correct @endif" style="background: var(--dark); color: var(--light); border-color: rgba(0, 195, 255, 0.3);">
                            {{ $alternativa->texto }}
                            @if($alternativa->es_correcta)
                                <i class="fas fa-check-circle ms-2" style="color: var(--success);"></i>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @empty
            <div class="main-card text-center">
                <p>Esta clase aún no tiene preguntas.</p>
                <a href="{{ route('profesor.clases.editar', $clase) }}" class="btn btn-neon-primary">Añadir Preguntas</a>
            </div>
        @endforelse
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 