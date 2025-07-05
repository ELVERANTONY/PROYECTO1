<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Panel del Profesor - AventuraTec</title>
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
        .stat-card {
            background: var(--dark);
            border: 1px solid var(--primary);
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 0 12px rgba(15, 255, 192, 0.2);
        }
        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            color: var(--accent);
        }
        .main-card {
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
        .btn-neon-secondary {
            background: transparent;
            color: var(--secondary);
            border: 1px solid var(--secondary);
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .btn-neon-secondary:hover { 
            background: var(--secondary);
            color: var(--dark);
            box-shadow: 0 0 12px var(--secondary);
        }

        /* Corrección para el color del texto en los encabezados de la tabla */
        .main-card .table thead th {
            color: var(--dark-deep) !important;
        }
    </style>
</head>
<body>
<div class="dashboard-layout">
    <aside class="sidebar">
        <div>
            <div class="sidebar-header">
                <a class="sidebar-brand" href="{{ route('profesor.dashboard') }}">AventuraTec</a>
            </div>
            <ul class="sidebar-nav">
                <li class="nav-item active"><a href="{{ route('profesor.dashboard') }}"><i class="fas fa-tachometer-alt nav-icon"></i><span class="nav-text">Dashboard</span></a></li>
                <li class="nav-item"><a href="{{ route('profesor.estudiantes') }}"><i class="fas fa-users nav-icon"></i><span class="nav-text">Estudiantes</span></a></li>
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

    <main class="main-content">
        <h1 class="main-header">Panel del Maestro</h1>
        <p class="lead mb-4">Bienvenido, <strong>{{ auth()->user()->name }}</strong>. Desde aquí puedes dirigir la orquesta del conocimiento.</p>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="background-color: var(--primary); color: var(--dark); border: none;">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <i class="fas fa-chalkboard stat-icon"></i>
                    <h3 id="clases-activas-count">{{ $clasesActivas->count() }}</h3>
                    <p class="text-white-50 mb-0">Clases Activas</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <i class="fas fa-user-graduate stat-icon"></i>
                    <h4>{{ $clasesActivas->sum('users_count') }}</h4>
                    <p class="text-white-50 mb-0">Estudiantes en Clases Activas</p>
                </div>
            </div>
            <div class="col-md-4">
                 <div class="stat-card">
                    <i class="fas fa-tasks stat-icon"></i>
                    <h4>{{ $clasesFinalizadas->count() }}</h4>
                    <p class="text-white-50 mb-0">Clases Finalizadas</p>
                </div>
            </div>
        </div>

        <div class="main-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                 <h3 style="font-family: 'Orbitron', sans-serif; color: var(--light)">Mis Clases Creadas (Plantillas)</h3>
                 <a href="{{ route('profesor.gestion-clases') }}" class="btn btn-neon-secondary"><i class="fas fa-cogs me-2"></i>Ir a Gestionar Clases</a>
            </div>
             <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Nombre de la Plantilla</th>
                            <th class="text-center">Estado Actual</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody id="clases-creadas-tbody">
                        @forelse ($clasesCreadas as $clase)
                        <tr id="clase-row-{{ $clase->id }}">
                            <td>{{ $clase->nombre }}</td>
                            <td class="text-center">
                                @if ($clase->estado == 'iniciada')
                                    <span class="badge bg-success">En Curso</span>
                                @else
                                    <span class="badge bg-secondary">Disponible</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $tieneClaseActiva = $clasesCreadas->where('id', '!=', $clase->id)->contains('estado', 'iniciada');
                                    $esClaseActiva = $clase->estado == 'iniciada';
                                @endphp
                                
                                @if ($esClaseActiva)
                                    <a href="{{ route('clase.ruleta', $clase) }}" class="btn btn-info btn-sm">
                                        Ir a la Ruleta
                                    </a>
                                @else
                                    <a href="{{ route('clase.ruleta', $clase) }}" 
                                       class="btn btn-primary btn-sm {{ $tieneClaseActiva ? 'disabled' : '' }}">
                                        Iniciar Ruleta
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">No has creado ninguna clase todavía.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
             </div>
         </div>

         <div class="main-card mt-4">
            <h3 style="font-family: 'Orbitron', sans-serif; color: var(--light)">Historial de Clases Finalizadas</h3>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Nombre de la Clase</th>
                            <th class="text-center">N° Estudiantes</th>
                            <th>Fecha de Finalización</th>
                            <th class="text-center">Código</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($clasesFinalizadas as $clase)
                        <tr>
                            <td>{{ $clase->nombre }}</td>
                            <td class="text-center">{{ $clase->users_count }}</td>
                            <td>{{ $clase->updated_at->format('d/m/Y H:i') }}</td>
                            <td class="text-center">
                                <span class="badge bg-info">{{ $clase->codigo ?? 'N/A' }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">No tienes clases en el historial.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- Modal para mostrar código y estudiantes -->
<div class="modal fade" id="modalSalaClase" tabindex="-1" aria-labelledby="modalSalaClaseLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="background: var(--dark); color: var(--light); border: 2px solid var(--primary);">
      <div class="modal-header">
        <h5 class="modal-title" id="modalSalaClaseLabel">Sala de Clase Activa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <strong>Código de la Clase:</strong>
          <span id="codigo-clase-modal" class="badge bg-info" style="font-size:1.2em;"></span>
        </div>
        <div>
          <strong>Estudiantes Conectados:</strong>
          <ul id="lista-estudiantes-modal" class="list-group list-group-flush" style="background:transparent;"></ul>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Cerrar alertas después de 5 segundos
    const successAlert = document.querySelector('.alert-success');
    if (successAlert) {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(successAlert);
            bsAlert.close();
        }, 5000);
    }

    // Función para actualizar el dashboard
    async function actualizarDashboard() {
        try {
            const response = await fetch('{{ route("profesor.dashboard.datos") }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            
            if (!response.ok) throw new Error('Error al cargar los datos');
            
            const data = await response.json();
            
            // Actualizar contadores
            document.getElementById('clases-activas-count').textContent = data.clases_activas_count;
            document.getElementById('estudiantes-activos-count').textContent = data.estudiantes_activos_count;
            
            // Actualizar tabla de clases
            const tbody = document.getElementById('clases-creadas-tbody');
            if (tbody) {
                tbody.innerHTML = ''; // Limpiar tabla actual
                
                if (data.clases.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="3" class="text-center">No has creado ninguna clase todavía.</td></tr>';
                    return;
                }
                
                // Verificar si hay alguna clase activa
                const tieneClaseActiva = data.clases.some(clase => clase.estado === 'iniciada');
                
                data.clases.forEach(clase => {
                    const estaActiva = clase.estado === 'iniciada';
                    const estadoBadge = estaActiva 
                        ? '<span class="badge bg-success">En Curso</span>' 
                        : '<span class="badge bg-secondary">Disponible</span>';
                    
                    let accionBoton = '';
                    
                    if (estaActiva) {
                        accionBoton = `<a href="${clase.url_sesion}" class="btn btn-info btn-sm">Ir a la Ruleta</a>`;
                    } else {
                        const tieneClaseActiva = data.clases.some(c => c.id !== clase.id && c.estado === 'iniciada');
                        const estaDeshabilitado = tieneClaseActiva ? 'disabled' : '';
                        const textoBoton = tieneClaseActiva ? 'Clase en Curso' : 'Iniciar Ruleta';
                        
                        accionBoton = `
                            <a href="${clase.url_ruleta}" class="btn btn-primary btn-sm ${estaDeshabilitado}">
                                ${textoBoton}
                            </a>
                        `;
                    }
                    
                    const fila = `
                        <tr id="clase-row-${clase.id}">
                            <td>${clase.nombre}</td>
                            <td class="text-center">${estadoBadge}</td>
                            <td>${accionBoton}</td>
                        </tr>
                    `;
                    
                    tbody.innerHTML += fila;
                });
            }
            
        } catch (error) {
            console.error('Error al actualizar el dashboard:', error);
        }
    }
    
    // Actualizar cada 5 segundos
    setInterval(actualizarDashboard, 5000);
    
    // Función para manejar el botón de la ruleta
    async function manejarBotonRuleta(boton, tieneClaseActiva) {
        if (tieneClaseActiva) {
            alert('Ya tienes una clase activa. Finaliza la clase actual antes de iniciar una nueva.');
            return;
        }

        const url = boton.getAttribute('data-url');
        const token = document.querySelector('meta[name="csrf-token"]').content;
        
        try {
            // Crear la ruleta
            const ruletaContainer = document.createElement('div');
            ruletaContainer.id = 'ruleta-container';
            ruletaContainer.style.position = 'fixed';
            ruletaContainer.style.top = '0';
            ruletaContainer.style.left = '0';
            ruletaContainer.style.width = '100%';
            ruletaContainer.style.height = '100%';
            ruletaContainer.style.backgroundColor = 'rgba(0,0,0,0.7)';
            ruletaContainer.style.zIndex = '1000';
            ruletaContainer.style.display = 'flex';
            ruletaContainer.style.justifyContent = 'center';
            ruletaContainer.style.alignItems = 'center';
            
            const ruleta = document.createElement('div');
            ruleta.id = 'ruleta';
            ruleta.style.width = '400px';
            ruleta.style.height = '400px';
            ruleta.style.borderRadius = '50%';
            ruleta.style.background = 'conic-gradient(';
            ruleta.style.position = 'relative';
            ruleta.style.overflow = 'hidden';
            ruleta.style.boxShadow = '0 0 20px rgba(255, 255, 255, 0.8)';
            
            // Obtener estudiantes de la clase
            const response = await fetch(`/clases/${boton.getAttribute('data-clase')}/estudiantes`);
            const estudiantes = await response.json();
            
            if (estudiantes.length === 0) {
                alert('No hay estudiantes en esta clase');
                return;
            }
            
            // Crear secciones para cada estudiante
            const anguloPorEstudiante = 360 / estudiantes.length;
            estudiantes.forEach((estudiante, index) => {
                const seccion = document.createElement('div');
                seccion.style.position = 'absolute';
                seccion.style.width = '100%';
                seccion.style.height = '100%';
                seccion.style.clipPath = `polygon(50% 50%, ${50 + 50 * Math.cos((index * anguloPorEstudiante * Math.PI) / 180)}% ${50 + 50 * Math.sin((index * anguloPorEstudiante * Math.PI) / 180)}%, ${50 + 50 * Math.cos(((index + 1) * anguloPorEstudiante * Math.PI) / 180)}% ${50 + 50 * Math.sin(((index + 1) * anguloPorEstudiante * Math.PI) / 180)}%)`;
                seccion.style.backgroundColor = `hsl(${index * (360 / estudiantes.length)}, 70%, 50%)`;
                
                const nombre = document.createElement('div');
                nombre.textContent = estudiante.nombre;
                nombre.style.position = 'absolute';
                nombre.style.top = '50%';
                nombre.style.left = '70%';
                nombre.style.transform = 'translateY(-50%)';
                nombre.style.color = 'white';
                nombre.style.fontWeight = 'bold';
                
                seccion.appendChild(nombre);
                ruleta.appendChild(seccion);
            });
            
            // Agregar puntero
            const puntero = document.createElement('div');
            puntero.style.position = 'absolute';
            puntero.style.top = '0';
            puntero.style.left = '50%';
            puntero.style.transform = 'translateX(-50%)';
            puntero.style.width = '10px';
            puntero.style.height = '30px';
            puntero.style.backgroundColor = 'red';
            puntero.style.zIndex = '10';
            ruleta.appendChild(puntero);
            
            ruletaContainer.appendChild(ruleta);
            document.body.appendChild(ruletaContainer);
            
            // Función para girar la ruleta
            function girarRuleta() {
                const duracion = 5000; // 5 segundos
                const vueltas = 5; // Número de vueltas completas
                const estudianteSeleccionado = Math.floor(Math.random() * estudiantes.length);
                const anguloFinal = 360 * vueltas + estudianteSeleccionado * anguloPorEstudiante;
                
                ruleta.style.transition = `transform ${duracion}ms cubic-bezier(0.34, 1.56, 0.64, 1)`;
                ruleta.style.transform = `rotate(${anguloFinal}deg)`;
                
                // Mostrar resultado después de girar
                setTimeout(() => {
                    alert(`¡Estudiante seleccionado: ${estudiantes[estudianteSeleccionado].nombre}!`);
                    document.body.removeChild(ruletaContainer);
                }, duracion + 500);
            }
            
            girarRuleta();
            
        } catch (error) {
            console.error('Error al manejar la ruleta:', error);
        }
    }
    
    // Mostrar modal de sala y estudiantes en tiempo real
    let pollingInterval = null;
    let currentClaseId = null;
    const modalSala = new bootstrap.Modal(document.getElementById('modalSalaClase'));
    document.body.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-ver-sala')) {
            e.preventDefault();
            const claseId = e.target.dataset.claseId;
            const codigo = e.target.dataset.codigo;
            currentClaseId = claseId;
            document.getElementById('codigo-clase-modal').textContent = codigo || 'N/A';
            actualizarListaEstudiantes(claseId);
            modalSala.show();
            if (pollingInterval) clearInterval(pollingInterval);
            pollingInterval = setInterval(() => actualizarListaEstudiantes(claseId), 3000);
        }
    });
    document.getElementById('modalSalaClase').addEventListener('hidden.bs.modal', function () {
        if (pollingInterval) clearInterval(pollingInterval);
        pollingInterval = null;
        currentClaseId = null;
    });
    async function actualizarListaEstudiantes(claseId) {
        try {
            const response = await fetch(`/profesor/clases/${claseId}/estudiantes`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!response.ok) throw new Error('No se pudo obtener la lista');
            const estudiantes = await response.json();
            const lista = document.getElementById('lista-estudiantes-modal');
            lista.innerHTML = '';
            if (estudiantes.length === 0) {
                lista.innerHTML = '<li class="list-group-item bg-transparent text-warning">Ningún estudiante se ha unido aún.</li>';
            } else {
                estudiantes.forEach(est => {
                    const li = document.createElement('li');
                    li.className = 'list-group-item bg-transparent';
                    li.textContent = est.nombre || est.name || 'Estudiante';
                    lista.appendChild(li);
                });
            }
        } catch (err) {
            document.getElementById('lista-estudiantes-modal').innerHTML = '<li class="list-group-item bg-transparent text-danger">Error al cargar estudiantes</li>';
        }
    }
});
</script>
</body>
</html>
