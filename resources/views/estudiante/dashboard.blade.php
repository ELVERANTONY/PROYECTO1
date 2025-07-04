<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lobby del Estudiante - AventuraTec</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Orbitron:wght@700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
        .lobby-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 2rem;
        }
        .character-display {
            background: var(--dark);
            border-radius: 1rem;
            padding: 2rem;
            border: 2px solid var(--accent);
            box-shadow: 0 0 24px rgba(0, 195, 255, 0.3);
            text-align: center;
        }
        .character-display video {
            max-width: 100%;
            border-radius: 1rem;
            box-shadow: 0 0 32px rgba(255, 224, 102, 0.6);
        }
        .character-name {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.8rem;
            color: var(--secondary);
            text-shadow: 0 0 8px var(--secondary);
            margin-top: 1rem;
        }
        .character-info {
            color: var(--primary);
            font-weight: 500;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-shadow: 0 0 4px var(--primary);
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: var(--dark);
            border: 1px solid var(--primary);
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 0 12px rgba(15, 255, 192, 0.2);
            transition: all 0.3s ease-in-out;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0 20px var(--primary);
        }
        .stat-value {
            font-family: 'Orbitron', sans-serif;
            font-size: 2rem;
            margin-bottom: 0.25rem;
            color: var(--light);
        }
        .stat-label {
            font-size: 0.9rem;
            text-transform: uppercase;
            color: var(--primary);
        }
        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        .xp-icon { color: var(--primary); }
        .gold-icon { color: var(--secondary); }
        .level-icon { color: var(--accent); }
        .classes-table-container {
             background: var(--dark);
             padding: 2rem;
             border-radius: 1rem;
             border: 1px solid var(--primary);
        }
        .table {
            background-color: transparent;
            color: var(--light);
        }
        .table th { color: var(--primary); border-color: rgba(15, 255, 192, 0.4); }
        .table td { border-color: rgba(15, 255, 192, 0.2); }
        .btn-neon {
            background: var(--primary);
            color: var(--dark);
            font-weight: bold;
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 0 8px var(--primary);
        }
        .btn-neon:hover { box-shadow: 0 0 16px var(--primary), 0 0 24px var(--primary); }

        @media (max-width: 992px) {
            .lobby-grid { grid-template-columns: 1fr; }
            .sidebar {
                width: 100%;
                height: auto;
                position: static;
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
                border-right: none;
                border-bottom: 2px solid var(--primary);
            }
             .main-content { margin-left: 0; }
             .sidebar-nav { display: flex; flex-grow: 0; padding: 0; }
             .nav-item a { padding: 0.5rem 1rem; border: none; }
             .nav-item a .nav-text { display: none; }
             .sidebar-footer, .sidebar-header { border: none; padding: 0 1rem; }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cerrar alertas automáticamente
            setTimeout(function() {
                var alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    var bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);

            // Actualización en tiempo real del estado de la clase
            const estadoClase = document.getElementById('estadoClase');
            
            // Solo ejecutar si hay una clase activa
            if (estadoClase) {
                const url = estadoClase.dataset.url;
                let estadoActual = estadoClase.dataset.estadoActual;
                const claseId = estadoClase.dataset.claseId;
                let intervaloActualizacion;
                let estaActualizando = false;
                
                // Función para manejar la respuesta del servidor
                function manejarRespuesta(data) {
                    if (data.error) {
                        console.error('Error:', data.error);
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        }
                        return;
                    }
                    
                    // Si el estado cambió, recargar la página
                    if (data.estado !== estadoActual) {
                        console.log('Estado de la clase cambiado a:', data.estado);
                        window.location.reload();
                        return;
                    }
                    
                    // Actualizar contador de estudiantes
                    const contador = document.getElementById('estudiantesConectados');
                    if (contador) {
                        contador.textContent = `${data.estudiantes_count} estudiantes conectados`;
                    }
                    
                    // Actualizar timestamp
                    const timestamp = document.querySelector('.actualizado-hace');
                    if (timestamp) {
                        timestamp.textContent = `Actualizado: ${data.hora_actualizacion || 'hace unos segundos'}`;
                    }
                    
                    // Si la clase está iniciada, verificar si es el turno del estudiante
                    if (data.estado === 'iniciada' && data.es_tu_turno) {
                        // Redirigir a la sesión de la clase
                        window.location.href = `/estudiante/clases/${claseId}/sesion`;
                    }
                }
                
                // Función para actualizar el estado
                async function actualizarEstado() {
                    if (estaActualizando) return;
                    
                    try {
                        estaActualizando = true;
                        const response = await fetch(url);
                        
                        if (!response.ok) {
                            throw new Error('Error en la respuesta del servidor');
                        }
                        
                        const data = await response.json();
                        manejarRespuesta(data);
                    } catch (error) {
                        console.error('Error al verificar el estado:', error);
                    } finally {
                        estaActualizando = false;
                    }
                }
                
                // Iniciar la actualización periódica
                actualizarEstado(); // Primera verificación inmediata
                intervaloActualizacion = setInterval(actualizarEstado, 2000);
                
                // Limpiar intervalo cuando la página se cierre o navegue
                window.addEventListener('beforeunload', function() {
                    if (intervaloActualizacion) {
                        clearInterval(intervaloActualizacion);
                    }
                });
            }
            
            // Manejar el modal de unirse a clase
            const unirseModal = document.getElementById('unirseClaseModal');
            if (unirseModal) {
                unirseModal.addEventListener('shown.bs.modal', function () {
                    const codigoInput = document.getElementById('codigo');
                    if (codigoInput) {
                        codigoInput.focus();
                    }
                });
            }
            
            // Manejar el envío del formulario de unirse a clase
            const formUnirseClase = document.getElementById('formUnirseClase');
            if (formUnirseClase) {
                formUnirseClase.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    const boton = this.querySelector('button[type="submit"]');
                    const codigoInput = this.querySelector('#codigo');
                    const errorContainer = document.getElementById('error-container');
                    const successContainer = document.getElementById('success-container');
                    
                    // Resetear mensajes
                    if (errorContainer) errorContainer.style.display = 'none';
                    if (successContainer) successContainer.style.display = 'none';
                    
                    const codigo = codigoInput ? codigoInput.value.trim() : '';
                    if (!codigo) {
                        if (errorContainer) {
                            errorContainer.textContent = 'Por favor ingresa un código de clase';
                            errorContainer.style.display = 'block';
                        }
                        return;
                    }
                    
                    // Deshabilitar el botón y mostrar spinner
                    if (boton) {
                        boton.disabled = true;
                        boton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Uniéndose...';
                    }

                    try {
                        const formData = new FormData(this);
                        
                        const response = await fetch(this.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        
                        const data = await response.json();
                        
                        if (response.ok && data.status === 'success') {
                            if (successContainer) {
                                successContainer.textContent = data.message;
                                successContainer.style.display = 'block';
                            }
                            
                            if (data.view) {
                                const claseContainer = document.getElementById('clase-activa-container');
                                if (claseContainer) {
                                    claseContainer.innerHTML = data.view;
                                }
                            }
                            
                            const unirseModal = document.getElementById('unirseClaseModal');
                            const modalInstance = bootstrap.Modal.getInstance(unirseModal);
                            if (modalInstance) {
                                setTimeout(() => {
                                    modalInstance.hide();
                                    formUnirseClase.reset();
                                    if (successContainer) {
                                        successContainer.style.display = 'none';
                                    }
                                }, 2000);
                            }
                        } else {
                            if (errorContainer) {
                                errorContainer.textContent = data.message || 'Ocurrió un error desconocido.';
                                errorContainer.style.display = 'block';
                            }
                        }
                    } catch (error) {
                        console.error('Error en la solicitud fetch:', error);
                        if (errorContainer) {
                            errorContainer.textContent = 'Error de conexión. Por favor, inténtalo de nuevo.';
                            errorContainer.style.display = 'block';
                        }
                    } finally {
                        if (boton) {
                            boton.disabled = false;
                            boton.innerHTML = 'Unirse';
                        }
                    }
                });
            }
        });
    </script>
</head>
<body>
<div class="dashboard-layout">
    <!-- Barra Lateral -->
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

<!-- Contenido Principal -->
<main class="main-content">
    <h1 class="main-header">Lobby del {{ ucfirst($user->character_class ?? 'Aventurero') }}</h1>
    <p class="lead mb-4">¡Bienvenido de nuevo, <strong>{{ $user->name }}</strong>! Sigue luchando por el conocimiento.</p>

    <div class="lobby-grid">
        <!-- Columna del Personaje -->
        <div class="character-display">
            @if ($user->skinEquipada && $user->skinEquipada->video_url)
                <video autoplay loop muted playsinline class="w-100 rounded-3" key="{{ asset($user->skinEquipada->video_url) }}">
                    <source src="{{ asset($user->skinEquipada->video_url) }}" type="video/mp4">
                    Tu navegador no soporta el tag de video.
                </video>
                <h2 class="character-name mt-3">{{ $user->name }}</h2>
                @php
                    $className = ucfirst($user->character_class);
                    $skinName = $user->skinEquipada->nombre;
                    $tier = trim(str_ireplace($className, '', $skinName));
                @endphp
                <p class="character-info text-center">{{ $className }} - {{ $tier }}</p>
            @else
                <div class="text-center p-5">
                    <i class="fas fa-question-circle fa-4x mb-3"></i>
                    <p>No tienes una skin equipada.</p>
                </div>
            @endif
        </div>

        <!-- Columna de Stats y Clases -->
        <div>
            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <i class="fas fa-star stat-icon xp-icon"></i>
                    <div id="user-xp" class="stat-value">{{ $user->xp }}</div>
                    <div class="stat-label">Experiencia</div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-coins stat-icon gold-icon"></i>
                    <div id="user-gold" class="stat-value">{{ $user->gold }}</div>
                    <div class="stat-label">Oro Acumulado</div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-shield-alt stat-icon level-icon"></i>
                    <div id="user-level" class="stat-value">{{ $user->nivel }}</div>
                    <div class="stat-label">Tu Nivel</div>
                </div>
            </div>

            <!-- Notificación de unión exitosa -->
            @if(session('mensaje_unido'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    {{ session('mensaje_unido') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Clase Activa -->
            <div class="classes-table-container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 style="color: var(--secondary); margin: 0;">
                        @if($claseActiva)
                            {{ $claseActiva->nombre }}
                        @else
                            Tu Clase Actual
                        @endif
                    </h4>
                </div>
                
                <!-- Vista parcial de la clase activa -->
                <div id="clase-activa-container">
                    @include('estudiante.partials.clase-activa', ['claseActiva' => $claseActiva])
                </div>
            </div>
            </div>

            <!-- Modal para unirse a clase -->
            <div class="modal fade" id="unirseClaseModal" tabindex="-1" aria-labelledby="unirseClaseModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content bg-dark text-light">
                        <div class="modal-header border-secondary">
                            <h5 class="modal-title" id="unirseClaseModalLabel">Unirse a una Clase</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div id="success-container" class="alert alert-success" style="display: none;"></div>
                        <form id="formUnirseClase" action="{{ route('estudiante.clases.unirse') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <div id="error-container" class="alert alert-danger" style="display: none;"></div>
                                <div class="mb-3">
                                    <label for="codigo" class="form-label">Código de la Clase</label>
                                    <input type="text" class="form-control bg-dark text-light border-secondary" id="codigo" name="codigo" required 
                                           placeholder="Ingresa el código proporcionado por el profesor">
                                </div>
                            </div>
                            <div class="modal-footer border-secondary">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Unirse</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
</div>

<script src="{{ asset('js/app.js') }}" defer></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if(Auth::check())
        window.Echo.private('user.{{ Auth::id() }}')
            .listen('StatsUpdated', (e) => {
                console.log('StatsUpdated event received:', e);
                
                const xpEl = document.getElementById('user-xp');
                const goldEl = document.getElementById('user-gold');
                const levelEl = document.getElementById('user-level');
                const characterLevelEl = document.querySelector('.character-class');

                if (xpEl) xpEl.textContent = e.xp;
                if (goldEl) goldEl.textContent = e.gold;
                if (levelEl) levelEl.textContent = e.nivel;

                if (characterLevelEl) {
                    const characterType = characterLevelEl.textContent.split(' Nivel ')[0];
                    characterLevelEl.textContent = `${characterType} Nivel ${e.nivel}`;
                }

                // Efecto visual para destacar el cambio
                [xpEl, goldEl, levelEl].forEach(el => {
                    if(el) {
                        el.closest('.stat-card').style.transform = 'scale(1.05)';
                        el.closest('.stat-card').style.borderColor = 'var(--secondary)';
                        setTimeout(() => {
                            el.closest('.stat-card').style.transform = 'scale(1)';
                            el.closest('.stat-card').style.borderColor = 'var(--primary)';
                        }, 500);
                    }
                });
            });
        @endif
    });
</script>
</body>
</html>