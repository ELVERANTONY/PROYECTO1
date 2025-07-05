<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ranking - AventuraTec</title>
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
        .ranking-card {
            background: var(--dark);
            border: 1px solid var(--primary);
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 0 16px rgba(15, 255, 192, 0.2);
        }
        .table {
            background-color: transparent;
            color: var(--light);
        }
        .table th { color: var(--primary); border-color: rgba(15, 255, 192, 0.4); font-family: 'Orbitron', sans-serif;}
        .table td { vertical-align: middle; border-color: rgba(15, 255, 192, 0.2); }
        .table .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 2px solid var(--accent);
            margin-right: 1rem;
            object-fit: cover;
        }
        .table tr.current-user-rank {
            background: rgba(0, 195, 255, 0.15);
            border-left: 4px solid var(--accent);
            border-right: 4px solid var(--accent);
        }
        .rank-position {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.5rem;
            color: var(--secondary);
            text-shadow: 0 0 8px var(--secondary);
        }
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
        <h1 class="main-header">Ranking de Estudiantes</h1>
        <div class="ranking-card">
            <div class="table-responsive">
                <table class="table" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Puesto</th>
                            <th>Estudiante</th>
                            <th>Nivel</th>
                            <th>Puntos de Experiencia (XP)</th>
                        </tr>
                    </thead>
                    <tbody id="ranking-body">
                        @forelse ($ranking as $index => $rankedUser)
                            <tr id="user-rank-{{ $rankedUser->id }}" class="{{ $rankedUser->id == $user->id ? 'current-user-rank' : '' }}">
                                <td class="rank-position">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <video autoplay loop muted playsinline class="user-avatar">
                                            <source src="{{ $rankedUser->skinEquipada?->video_url ? asset($rankedUser->skinEquipada->video_url) : asset('videos/guerrero-basico.mp4') }}" type="video/mp4">
                                        </video>
                                        <span class="user-name">{{ $rankedUser->name }}</span>
                                    </div>
                                </td>
                                <td class="user-level">{{ $rankedUser->nivel }}</td>
                                <td class="user-xp">{{ $rankedUser->xp ?? 0 }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">El ranking aún no está disponible. ¡Completa misiones para aparecer aquí!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const rankedUserIds = @json($ranking->pluck('id'));

    if (typeof window.Echo !== 'undefined') {
        rankedUserIds.forEach(userId => {
            window.Echo.private(`user.${userId}`)
                .listen('StatsUpdated', (e) => {
                    console.log(`StatsUpdated event received for user ${userId}:`, e);
                    updateRanking(userId, e.nivel, e.xp);
                });
        });
    } else {
        console.warn('Laravel Echo no está disponible. Las actualizaciones en tiempo real no funcionarán.');
    }

    function updateRanking(userId, newLevel, newXp) {
        const rankingBody = document.getElementById('ranking-body');
        let userRow = document.getElementById(`user-rank-${userId}`);

        if (userRow) {
            userRow.querySelector('.user-level').textContent = newLevel;
            userRow.querySelector('.user-xp').textContent = newXp;
        } else {
            // Si el usuario no está en la tabla, podría ser necesario añadirlo si entra en el top 20.
            // Por simplicidad, esta implementación solo actualiza usuarios existentes.
        }

        const rows = Array.from(rankingBody.querySelectorAll('tr'));
        
        // Filtrar la fila de "ranking no disponible" si existe
        const dataRows = rows.filter(row => row.id.startsWith('user-rank-'));

        dataRows.sort((a, b) => {
            const levelA = parseInt(a.querySelector('.user-level').textContent);
            const xpA = parseInt(a.querySelector('.user-xp').textContent);
            const levelB = parseInt(b.querySelector('.user-level').textContent);
            const xpB = parseInt(b.querySelector('.user-xp').textContent);

            if (levelB !== levelA) {
                return levelB - levelA;
            }
            return xpB - xpA;
        });

        // Volver a añadir las filas ordenadas
        dataRows.forEach(row => rankingBody.appendChild(row));

        // Actualizar las posiciones
        const positionCells = rankingBody.querySelectorAll('.rank-position');
        positionCells.forEach((cell, index) => {
            cell.textContent = index + 1;
        });
    }
});
</script>
@endsection
</body>
</html> 