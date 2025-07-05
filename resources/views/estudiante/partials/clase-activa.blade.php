@if($claseActiva)
    <div class="card bg-dark text-white mb-4 border border-{{ $claseActiva->estado === 'iniciada' ? 'success' : 'warning' }}">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div>
                <h5 class="mb-0">Clase: <span class="text-info">{{ $claseActiva->nombre }}</span></h5>
                <small class="text-secondary">Profesor: <span class="fw-bold text-warning">{{ $claseActiva->profesor->name }}</span></small>
            </div>
            <span class="badge bg-{{ $claseActiva->estado === 'iniciada' ? 'success' : 'warning' }} mt-2 mt-md-0">
                Estado: {{ ucfirst($claseActiva->estado) }}
            </span>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="badge bg-primary">
                    <i class="fas fa-users me-1"></i>
                    <span id="estudiantesConectados">{{ $claseActiva->users_count }} estudiantes conectados</span>
                </span>
            </div>
            @if($claseActiva->estado === 'iniciada')
                <div class="alert alert-success text-center mb-0">
                    <i class="fas fa-chalkboard-teacher me-2"></i>
                    La clase está en curso. ¡Espera las indicaciones del profesor!
                </div>
            @else
                <div class="alert alert-warning mb-0">
                    <i class="fas fa-clock me-2"></i>Esperando a que el profesor inicie la clase...
                </div>
            @endif
        </div>
    </div>
@else
    <div class="card bg-dark text-center p-4 border-secondary">
        <div class="card-body">
            <i class="fas fa-chalkboard-teacher fa-3x mb-3" style="color: var(--primary);"></i>
            <h5 class="text-white">No estás en ninguna clase activa</h5>
            <p class="text-white-50">Únete a una clase con el código proporcionado por tu profesor.</p>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#unirseClaseModal">
                <i class="fas fa-plus-circle me-2"></i>Unirse a una clase
            </button>
        </div>
    </div>
@endif
