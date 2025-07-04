<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sesión de Clase - AventuraTec</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Orbitron:wght@700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
        :root {
            --primary: #0fffc0; --secondary: #ffe066; --dark: #181c23;
            --light: #e0e7ef; --dark-deep: #101216; --accent: #00c3ff;
            --danger: #ff3d71; --success: #28a745;
        }
        body {
            background-color: var(--dark-deep);
            background-image: radial-gradient(circle at 50% 20%, rgba(15, 255, 192, 0.3) 0%, transparent 40%);
            min-height: 100vh; color: var(--light); font-family: 'Montserrat', sans-serif;
        }
        .session-header {
            background-color: var(--dark); padding: 1rem 2rem;
            border-bottom: 2px solid var(--primary); box-shadow: 0 4px 12px rgba(15, 255, 192, 0.2);
        }
        .header-info h1 { font-family: 'Orbitron', sans-serif; color: var(--primary); font-size: 1.5rem; }
        .codigo-container { display: flex; align-items: center; gap: 1rem; background-color: var(--dark-deep); padding: 0.5rem 1rem; border-radius: 0.5rem; border: 1px dashed var(--secondary); }
        .codigo-container strong { font-family: 'Orbitron', sans-serif; color: var(--secondary); font-size: 1.2rem; }
        .main-card { background: var(--dark); padding: 2rem; border-radius: 1rem; border: 1px solid rgba(15, 255, 192, 0.1); box-shadow: 0 0 20px rgba(15, 255, 192, 0.1); }
        .card-title { font-family: 'Orbitron', sans-serif; color: var(--light); margin-bottom: 1.5rem; }
        #wheel-wrapper { position: relative; width: 100%; max-width: 600px; margin: 0 auto; }
        #wheel-canvas { width: 100%; height: auto; }
        #spin-button {
            position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
            width: 100px; height: 100px; border-radius: 50%; background-color: var(--secondary);
            color: var(--dark); font-family: 'Orbitron', sans-serif; font-weight: 700;
            border: 4px solid var(--dark); box-shadow: 0 0 20px var(--secondary);
            cursor: pointer; z-index: 10; display: flex; align-items: center; justify-content: center;
            transition: all 0.2s ease;
        }
        #spin-button:hover:not(:disabled) { transform: translate(-50%, -50%) scale(1.1); }
        #spin-button:disabled { background-color: #555; cursor: not-allowed; box-shadow: none; }
        .student-list-group .list-group-item {
            background-color: var(--dark-deep); color: var(--light);
            border-color: rgba(15, 255, 192, 0.2); transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }
        .student-avatar { width: 40px; height: 40px; border-radius: 50%; margin-right: 1rem; border: 2px solid var(--accent); object-fit: cover; }
        .modal-content { background-color: var(--dark); border: 2px solid var(--primary); }
        .modal-header, .modal-footer { border-color: rgba(15, 255, 192, 0.3); }
        .stat-control { display: flex; justify-content: space-between; align-items: center; background-color: var(--dark-deep); padding: 0.5rem 1rem; border-radius: 0.5rem; margin-bottom: 0.5rem; }
        .stat-control span { font-size: 1.1rem; font-weight: bold; }
        .stat-control .btn { font-weight: bold; }
    </style>
</head>
<body>
<header class="session-header d-flex justify-content-between align-items-center">
    <div>
        <h1>{{ $clase->nombre }}</h1>
        <div class="codigo-container">
            <span>Código: <strong id="class-code">{{ $clase->codigo }}</strong></span>
            <button id="copy-code-btn" class="btn btn-sm btn-outline-primary"><i class="fas fa-copy"></i></button>
        </div>
    </div>
    <form action="{{ route('profesor.clases.finalizar', $clase) }}" method="POST" id="form-finalizar-clase">
        @csrf
        <button type="submit" class="btn btn-danger"><i class="fas fa-flag-checkered"></i> Finalizar Clase</button>
    </form>
</header>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="main-card text-center">
                        <h2 class="card-title">RUEDA DEL SABER</h2>
                <div id="wheel-wrapper">
                    <canvas id="wheel-canvas" width="600" height="600"></canvas>
                            <div id="spin-button">GIRAR</div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="main-card">
                <h3 class="card-title text-center">Estudiantes en la Sesión</h3>
                <ul id="student-list" class="list-group student-list-group">
                    @forelse ($clase->users()->where('role', 'estudiante')->with('skinEquipada')->get() as $user)
                    <li class="list-group-item" id="student-list-{{ $user->id }}">
                        <video src="{{ $user->skinEquipada?->video_url ? asset($user->skinEquipada->video_url) : asset('videos/guerrero-basico.mp4') }}" autoplay loop muted playsinline class="student-avatar"></video>
                        {{ $user->name }}
                    </li>
                    @empty
                    <li class="list-group-item text-muted">Aún no hay estudiantes conectados.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="winnerModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body text-center p-4" data-student-id="">
        <h2 id="winner-name" class="font-weight-bold" style="color: var(--secondary);"></h2>
        <div style="width: 150px; height: 150px; border-radius: 50%; border: 4px solid var(--primary); overflow: hidden; margin: 1rem auto;">
            <video id="winner-video" autoplay loop muted playsinline style="width: 100%; height: 100%; object-fit: cover;"></video>
        </div>
        
        <div id="stats-controls" class="my-4">
            <div class="stat-control">
                <label>Nivel:</label>
                <span id="stat-level" class="mx-2">1</span>
            </div>
            <div class="stat-control">
                <label>XP:</label>
                <div>
                    <button class="btn btn-sm btn-outline-danger stat-btn" data-stat="xp" data-amount="-10">-</button>
                    <span id="stat-xp" class="mx-2">0</span>
                    <button class="btn btn-sm btn-outline-success stat-btn" data-stat="xp" data-amount="10">+</button>
        </div>
      </div>
            <div class="stat-control">
                <label>Oro:</label>
                <div>
                    <button class="btn btn-sm btn-outline-danger stat-btn" data-stat="gold" data-amount="-10">-</button>
                    <span id="stat-gold" class="mx-2">0</span>
                    <button class="btn btn-sm btn-outline-success stat-btn" data-stat="gold" data-amount="10">+</button>
    </div>
  </div>
</div>

        <div id="winner-question"></div>
        <button type="button" class="btn btn-primary mt-3" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
<script src="{{ asset('js/roulette.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    // --- Elements ---
    const canvas = document.getElementById('wheel-canvas');
    const spinButton = document.getElementById('spin-button');
    const winnerModalElement = document.getElementById('winnerModal');
    const winnerModal = new bootstrap.Modal(winnerModalElement);
    const studentListEl = document.getElementById('student-list');
    const copyBtn = document.getElementById('copy-code-btn');
    const classCodeEl = document.getElementById('class-code');
    const finalizarForm = document.getElementById('form-finalizar-clase');

    // --- State ---
    let students = {!! json_encode($clase->users()->where('role', 'estudiante')->with('skinEquipada')->get()->map(fn($user) => [
        'id' => $user->id,
        'name' => $user->name,
        'avatar_url' => $user->skinEquipada?->video_url ? asset($user->skinEquipada->video_url) : asset('videos/guerrero-basico.mp4'),
        'nivel' => $user->nivel,
        'xp' => $user->puntos_experiencia,
        'gold' => $user->oro
    ])) !!};
    const roulette = new Roulette(canvas, students.map(s => s.name));
    
    // --- Functions ---
    function updateStudentList() {
        if (students.length === 0) {
            studentListEl.innerHTML = '<li class="list-group-item text-muted">Aún no hay estudiantes conectados.</li>';
        } else {
            studentListEl.innerHTML = students.map(student => `
                <li class="list-group-item" id="student-list-${student.id}">
                    <video src="${student.avatar_url}" autoplay loop muted playsinline class="student-avatar"></video>
                    ${student.name}
                </li>
            `).join('');
        }
        roulette.setOptions(students.map(s => s.name));
        roulette.draw();
        spinButton.disabled = students.length < 2;
    }

    const pollStudents = async () => {
        try {
            const response = await fetch('{{ route("clase.participantes", $clase) }}');
            if (!response.ok) throw new Error('Failed to fetch participants');
            const newStudents = await response.json();
            
            const currentIds = students.map(s => s.id).sort().join(',');
            const newIds = newStudents.map(s => s.id).sort().join(',');

            if (currentIds !== newIds) {
                students = newStudents.map(user => ({
                    id: user.id,
                    name: user.name,
                    avatar_url: user.avatar_url,
                    nivel: user.nivel,
                    xp: user.puntos_experiencia,
                    gold: user.oro
                }));
                updateStudentList();
            }
        } catch (error) {
            console.error("Error polling for students:", error);
        }
    };

    // --- Event Listeners ---
    copyBtn.addEventListener('click', () => {
        navigator.clipboard.writeText(classCodeEl.innerText).then(() => {
                const originalContent = copyBtn.innerHTML;
            copyBtn.innerHTML = '<i class="fas fa-check text-success"></i>';
                copyBtn.disabled = true;
                setTimeout(() => {
                    copyBtn.innerHTML = originalContent;
                    copyBtn.disabled = false;
                }, 2000);
            }).catch(err => {
                console.error('Error al copiar el código: ', err);
            });
        });

    spinButton.addEventListener('click', async () => {
        if (students.length < 1) return;
        spinButton.disabled = true;

        try {
            const response = await fetch("{{ route('profesor.clase.seleccionar-estudiante', $clase) }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });
            const data = await response.json();

            if (data.estudiante) {
                roulette.spin(data.estudiante.name, () => {
                    const winner = students.find(s => s.id === data.estudiante.id);
                    if (!winner) return;

                    document.getElementById('winner-name').textContent = winner.name;
                    const videoEl = document.getElementById('winner-video');
                    videoEl.src = winner.avatar_url;
                    videoEl.load();
                    videoEl.play().catch(e => console.error("Error al reproducir el video:", e));
                    winnerModalElement.querySelector('.modal-body').dataset.studentId = winner.id;

                    // Update stats in modal
                    document.getElementById('stat-level').textContent = winner.nivel;
                    document.getElementById('stat-xp').textContent = winner.xp;
                    document.getElementById('stat-gold').textContent = winner.gold;
                    
                    let questionHtml = '<p>No se asignó una pregunta.</p>';
                    if (data.pregunta) {
                        const alternatives = data.pregunta.alternativas.map(alt => `
                            <button class="list-group-item list-group-item-action alternativa-btn" data-alternativa-id="${alt.id}">
                                ${alt.texto}
                            </button>
                        `).join('');
                        questionHtml = `
                            <h6>Pregunta Asignada:</h6>
                            <p>${data.pregunta.pregunta}</p>
                            <div class="list-group text-start" data-pregunta-id="${data.pregunta.id}">${alternatives}</div>
                            <div id="evaluacion-feedback" class="mt-3"></div>`;
                    }
                    document.getElementById('winner-question').innerHTML = questionHtml;
                    winnerModal.show();
                    spinButton.disabled = students.length < 2;
                });
            } else {
                 alert(data.message || "No se pudo seleccionar un estudiante.");
                 spinButton.disabled = students.length < 2;
            }
        } catch (error) {
            console.error("Error spinning the wheel:", error);
            alert("Ocurrió un error al intentar girar la ruleta.");
            spinButton.disabled = students.length < 2;
        }
    });

    winnerModalElement.addEventListener('click', async function(e) {
        if (!e.target.matches('.alternativa-btn')) return;

        const alternativaBtn = e.target;
        const studentId = this.querySelector('.modal-body').dataset.studentId;
        const preguntaId = alternativaBtn.parentElement.dataset.preguntaId;
        const alternativaId = alternativaBtn.dataset.alternativaId;
        const feedbackEl = document.getElementById('evaluacion-feedback');

        // Deshabilitar todos los botones de alternativa
        this.querySelectorAll('.alternativa-btn').forEach(btn => btn.disabled = true);

        try {
            const response = await fetch(`/profesor/clases/{{$clase->id}}/estudiantes/${studentId}/pregunta/${preguntaId}/evaluar`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ alternativa_id: alternativaId })
            });

            const result = await response.json();
            if (result.success) {
                feedbackEl.innerHTML = `<div class="alert ${result.es_correcta ? 'alert-success' : 'alert-danger'}">${result.message}</div>`;
                if(result.es_correcta) {
                    alternativaBtn.classList.add('bg-success', 'text-white');
                } else {
                    alternativaBtn.classList.add('bg-danger', 'text-white');
                }
            } else {
                feedbackEl.innerHTML = `<div class="alert alert-warning">${result.error || 'No se pudo evaluar.'}</div>`;
            }
        } catch (error) {
            console.error('Error al evaluar:', error);
            feedbackEl.innerHTML = `<div class="alert alert-danger">Error de conexión al evaluar.</div>`;
        }
    });

    document.getElementById('stats-controls').addEventListener('click', async (e) => {
        if (!e.target.matches('.stat-btn')) return;

        const button = e.target;
        const studentId = winnerModalElement.querySelector('.modal-body').dataset.studentId;
        const stat = button.dataset.stat;
        const amount = parseInt(button.dataset.amount, 10);

        button.disabled = true;

        try {
            const response = await fetch(`/profesor/clases/{{$clase->id}}/estudiantes/${studentId}/modificar-stats`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ stat, amount })
            });

            const result = await response.json();
            if (result.success) {
                document.getElementById(`stat-${stat}`).textContent = result.new_value;
                if (stat === 'xp') {
                    document.getElementById('stat-level').textContent = result.new_level;
                }
                // Actualizar el valor en el array de `students` tambien
                const student_idx = students.findIndex(s => s.id == studentId);
                if (student_idx > -1) {
                    students[student_idx][stat] = result.new_value;
                    if (stat === 'xp') {
                        students[student_idx]['nivel'] = result.new_level;
                    }
                }
            } else {
                alert(result.error || 'No se pudo actualizar la estadística.');
            }
        } catch (error) {
            console.error('Error al modificar stats:', error);
            alert('Error de conexión al modificar estadísticas.');
        } finally {
            button.disabled = false;
        }
    });

    finalizarForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        if (!confirm('¿Estás seguro de que quieres finalizar esta clase?')) return;
        
        const formData = new FormData(this);
        try {
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': formData.get('_token') }
            });
            const data = await response.json();
            if (data.status === 'success' && data.redirect) {
                window.location.href = data.redirect;
            } else {
                alert(data.message || 'Ocurrió un error inesperado.');
            }
        } catch (error) {
            console.error('Error al finalizar la clase:', error);
            alert('No se pudo contactar al servidor.');
        }
    });

    // --- Initial Load ---
    updateStudentList();
    setInterval(pollStudents, 5000);
});
</script>
</body>
</html>
