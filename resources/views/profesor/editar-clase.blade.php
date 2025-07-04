<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Clase - AventuraTec</title>
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
        body { background: radial-gradient(circle at 50% 30%, var(--primary) 0%, var(--dark-deep) 100%); min-height: 100vh; color: var(--light); font-family: 'Montserrat', sans-serif; }
        .dashboard-layout { display: flex; min-height: 100vh; }
        .sidebar { width: 260px; background: var(--dark); border-right: 2px solid var(--primary); display: flex; flex-direction: column; padding: 1.5rem 0; position: fixed; height: 100%; }
        .sidebar-brand { font-family: 'Orbitron', sans-serif; font-weight: 700; color: var(--primary) !important; font-size: 1.5rem; text-shadow: 0 0 8px var(--primary); }
        .main-content { flex-grow: 1; padding: 2rem 3rem; margin-left: 260px; }
        .main-header { color: var(--secondary); font-family: 'Orbitron', sans-serif; margin-bottom: 2rem; text-shadow: 0 0 12px var(--secondary); }
        .main-card { background: var(--dark); padding: 2rem; border-radius: 1rem; border: 1px solid var(--primary); }
        .form-control, .form-select { background-color: var(--dark-deep); color: var(--light); border-color: var(--accent); }
        .form-control:focus, .form-select:focus { background-color: var(--dark-deep); color: var(--light); border-color: var(--primary); box-shadow: 0 0 0 0.25rem rgba(15, 255, 192, 0.25); }
        .btn-neon-primary { background: var(--primary); color: var(--dark); font-weight: bold; border: none; transition: all 0.3s ease; box-shadow: 0 0 8px var(--primary); }
        .btn-neon-primary:hover { box-shadow: 0 0 16px var(--primary); }
    </style>
</head>
<body>
<div class="dashboard-layout">
    <!-- Sidebar simplificada para el formulario -->
    <aside class="sidebar justify-content-center">
        <div class="text-center">
            <a class="sidebar-brand" href="{{ route('profesor.dashboard') }}">AventuraTec</a>
        </div>
    </aside>

    <!-- Contenido Principal -->
    <main class="main-content">
        <h1 class="main-header">Editar Clase</h1>

        <div class="main-card">
            <form action="{{ route('profesor.clases.actualizar', $clase) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre de la Clase</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $clase->nombre) }}" required>
                </div>

                <div class="mb-4">
                    <label for="tema" class="form-label">Tema</label>
                    <input type="text" class="form-control" id="tema" name="tema" value="{{ old('tema', $clase->tema) }}" required>
                </div>

                <hr style="border-color: var(--primary);">

                <h4 class="mb-3" style="color: var(--secondary);">Preguntas</h4>
                <div id="preguntas-container">
                    @foreach ($clase->preguntas as $index => $pregunta)
                        <div class="pregunta-group mb-4 p-3 border rounded" style="border-color: var(--accent) !important;">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5>Pregunta {{ $index + 1 }}</h5>
                                <button type="button" class="btn btn-sm btn-outline-danger remove-pregunta">Eliminar Pregunta</button>
                            </div>
                            <input type="hidden" name="preguntas[{{ $index }}][id]" value="{{ $pregunta->id }}">
                            <div class="mb-3">
                                <label class="form-label">Texto de la pregunta</label>
                                <input type="text" class="form-control" name="preguntas[{{ $index }}][texto]" value="{{ old('preguntas.'.$index.'.texto', $pregunta->pregunta) }}" required>
                            </div>
                            <h6>Alternativas</h6>
                            <div class="alternativas-container">
                                @foreach ($pregunta->alternativas as $altIndex => $alternativa)
                                    <div class="input-group mb-2">
                                        <div class="input-group-text">
                                            <input class="form-check-input mt-0" type="radio" name="preguntas[{{ $index }}][correcta]" value="{{ $altIndex }}" @if($alternativa->es_correcta) checked @endif required>
                                        </div>
                                        <input type="hidden" name="preguntas[{{ $index }}][alternativas][{{ $altIndex }}][id]" value="{{ $alternativa->id }}">
                                        <input type="text" class="form-control" name="preguntas[{{ $index }}][alternativas][{{ $altIndex }}][texto]" value="{{ old('preguntas.'.$index.'.alternativas.'.$altIndex.'.texto', $alternativa->texto) }}" required>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('profesor.gestion-clases') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-neon-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </main>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // La lógica para añadir/eliminar preguntas y alternativas se implementará aquí.
    // Por ahora, el formulario permite editar el contenido existente.
});
</script>
</body>
</html> 