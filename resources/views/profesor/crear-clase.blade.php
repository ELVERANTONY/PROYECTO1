<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nueva Clase - AventuraTec</title>
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
        .nav-item a:hover, .nav-item.active a {
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
        .main-card {
             background: var(--dark);
             padding: 2rem;
             border-radius: 1rem;
             border: 1px solid var(--primary);
        }
        .form-control, .form-select {
            background-color: var(--dark-deep);
            color: var(--light);
            border-color: var(--accent);
        }
        .form-control:focus, .form-select:focus {
            background-color: var(--dark-deep);
            color: var(--light);
            border-color: var(--primary);
            box-shadow: 0 0 8px var(--primary);
        }
        .btn-neon-primary {
            background: var(--primary);
            color: var(--dark);
            font-weight: bold;
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 0 8px var(--primary);
        }
        .btn-neon-primary:hover { box-shadow: 0 0 16px var(--primary), 0 0 24px var(--primary); }
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
        <h1 class="main-header">Crear Nueva Clase</h1>
        <p class="lead mb-4">Configura los detalles de tu nueva clase y prepara el campo de batalla del conocimiento.</p>
        
        <form action="{{ route('profesor.clases.store') }}" method="POST">
            @csrf
            <div class="main-card mb-4">
                <h3 class="mb-3 text-light">Información General</h3>
                <div class="mb-3">
                    <label for="nombre" class="form-label">Ingrese el curso</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <div class="mb-3">
                    <label for="tema" class="form-label">Ingrese el tema</label>
                    <input type="text" class="form-control" id="tema" name="tema" required>
                </div>
            </div>

            <div class="main-card">
                 <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="text-light">Banco de Preguntas</h3>
                    <button type="button" class="btn btn-outline-info" id="add-pregunta">
                        <i class="fas fa-plus-circle me-2"></i>Añadir Pregunta
                    </button>
                </div>

                <div id="preguntas-container">
                    <!-- Las preguntas se añadirán aquí dinámicamente -->
                </div>
            </div>

            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-neon-primary btn-lg">Crear Clase</button>
            </div>
        </form>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let preguntaIndex = 0;

    document.getElementById('add-pregunta').addEventListener('click', function () {
        const container = document.getElementById('preguntas-container');
        const preguntaCard = document.createElement('div');
        preguntaCard.className = 'pregunta-card card bg-dark border-secondary mb-3';
        preguntaCard.innerHTML = `
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h5 class="card-title text-primary">Pregunta ${preguntaIndex + 1}</h5>
                    <button type="button" class="btn-close btn-close-white remove-pregunta"></button>
                </div>
                <div class="mb-3">
                    <label for="pregunta_texto_${preguntaIndex}" class="form-label">Texto de la pregunta</label>
                    <input type="text" class="form-control" id="pregunta_texto_${preguntaIndex}" name="preguntas[${preguntaIndex}][texto]" required>
                </div>
                <h6>Alternativas</h6>
                <div class="alternativas-container">
                    <!-- Alternativas dinámicas aquí -->
                </div>
                <button type="button" class="btn btn-sm btn-outline-success add-alternativa mt-2" data-pregunta-index="${preguntaIndex}">Añadir Alternativa</button>
            </div>
        `;
        container.appendChild(preguntaCard);

        // Añadir 2 alternativas por defecto
        addAlternativa(preguntaCard.querySelector('.alternativas-container'), preguntaIndex, 0);
        addAlternativa(preguntaCard.querySelector('.alternativas-container'), preguntaIndex, 1);
        
        preguntaIndex++;
    });

    document.getElementById('preguntas-container').addEventListener('click', function(e) {
        if (e.target.classList.contains('add-alternativa')) {
            const pIndex = e.target.dataset.preguntaIndex;
            const altContainer = e.target.previousElementSibling;
            const altIndex = altContainer.children.length;
            addAlternativa(altContainer, pIndex, altIndex);
        }
        if (e.target.classList.contains('remove-pregunta')) {
            e.target.closest('.pregunta-card').remove();
        }
         if (e.target.classList.contains('remove-alternativa')) {
            e.target.closest('.input-group').remove();
        }
    });

    function addAlternativa(container, pIndex, altIndex) {
        const alternativaDiv = document.createElement('div');
        alternativaDiv.className = 'input-group mb-2';
        alternativaDiv.innerHTML = `
            <div class="input-group-text bg-dark border-secondary">
                <input class="form-check-input mt-0" type="radio" value="${altIndex}" name="preguntas[${pIndex}][correcta]" required>
            </div>
            <input type="text" class="form-control" name="preguntas[${pIndex}][alternativas][${altIndex}][texto]" required>
            <button class="btn btn-outline-danger remove-alternativa" type="button"><i class="fas fa-trash"></i></button>
        `;
        container.appendChild(alternativaDiv);
    }
});
</script>
</body>
</html> 