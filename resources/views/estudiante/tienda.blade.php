<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda - AventuraTec</title>
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
        .section-title {
            color: var(--secondary);
            font-family: 'Orbitron', sans-serif;
            margin-bottom: 1.5rem;
            text-shadow: 0 0 8px rgba(255, 224, 102, 0.7);
        }
        .filter-bar {
            background: rgba(15, 255, 192, 0.05);
            border: 1px solid rgba(15, 255, 192, 0.2);
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 2rem;
        }
        .filter-bar .btn-filter {
            background: transparent;
            border: 1px solid var(--primary);
            color: var(--primary);
            margin: 0 0.5rem;
        }
        .filter-bar .btn-filter.active {
            background: var(--primary);
            color: var(--dark);
            box-shadow: 0 0 12px var(--primary);
        }
        .skins-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 2rem;
        }
        .skin-card {
            background: var(--dark);
            border: 2px solid var(--accent);
            border-radius: 1rem;
            overflow: hidden;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 0 16px rgba(0, 195, 255, 0.2);
        }
        .skin-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(0, 195, 255, 0.4);
        }
        .skin-video-wrapper {
            width: 100%;
            height: 300px;
            background-color: #000;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .skin-video {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        .skin-info {
            padding: 1.5rem;
        }
        .skin-name {
            font-family: 'Orbitron', sans-serif;
            color: var(--secondary);
            font-size: 1.25rem;
        }
        .skin-price {
            color: var(--secondary);
            font-weight: bold;
            margin-bottom: 1rem;
        }
        .btn-buy {
            background: var(--primary);
            color: var(--dark-deep) !important;
            font-weight: bold;
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 0 8px var(--primary);
            width: 100%;
            padding: 0.75rem;
        }
        .btn-buy:hover:not(:disabled) {
             box-shadow: 0 0 16px var(--primary), 0 0 24px var(--primary);
        }
        .btn-buy:disabled {
            background: rgba(13, 17, 23, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: rgba(255, 255, 255, 0.4) !important;
            cursor: not-allowed;
            box-shadow: none;
        }
        .skin-description {
            font-size: 0.9rem;
            color: var(--light);
            min-height: 40px;
            margin-bottom: 1rem;
        }
        .btn-neon-secondary {
            background: var(--secondary);
            color: var(--dark);
            font-weight: bold;
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 0 8px var(--secondary);
        }
        .btn-neon-secondary:hover { 
            box-shadow: 0 0 16px var(--secondary), 0 0 24px var(--secondary);
        }
        .btn-equipped {
            background: var(--accent);
            color: var(--dark);
            cursor: not-allowed;
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
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="main-header">Tienda de Skins</h1>
            <div class="gold-balance">
                <i class="fas fa-coins gold-icon" style="color: var(--secondary);"></i>
                <strong>{{ $user->gold ?? 0 }} GOLD</strong>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <section class="shop-section">
            <h2 class="section-title">Skins Disponibles</h2>
            <div class="skins-grid">
                @forelse ($skinsDisponibles as $skin)
                    <div class="skin-card">
                        <div class="skin-video-wrapper">
                            <video class="skin-video" autoplay loop muted playsinline src="{{ asset($skin->video_url) }}"></video>
                        </div>
                        <div class="skin-info">
                            <h3 class="skin-name">{{ $skin->nombre }}</h3>
                            <p class="skin-price">
                                <i class="fas fa-coins"></i> {{ $skin->precio }}
                            </p>
                            <form action="{{ route('estudiante.tienda.comprar') }}" method="POST">
                                @csrf
                                <input type="hidden" name="skin_id" value="{{ $skin->id }}">
                                @php
                                    $requiredLevel = 0;
                                    if (str_contains(strtolower($skin->nombre), 'intermedio')) $requiredLevel = 2;
                                    if (str_contains(strtolower($skin->nombre), 'avanzado')) $requiredLevel = 4;
                                @endphp

                                @if($user->nivel < $requiredLevel)
                                    <button type="button" class="btn btn-buy" disabled>Requiere Nivel {{ $requiredLevel }}</button>
                                @else
                                    <button type="submit" class="btn btn-buy" {{ $user->gold < $skin->precio ? 'disabled' : '' }}>
                                        {{ $user->gold < $skin->precio ? 'Oro insuficiente' : 'Comprar' }}
                                    </button>
                                @endif
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-center w-100">No hay nuevas skins para tu clase en este momento.</p>
                @endforelse
            </div>
        </section>

        <hr class="section-divider">

        <section class="shop-section">
            <h2 class="section-title">Comodines</h2>
            <div class="skins-grid">
                @forelse ($comodines as $comodin)
                    <div class="skin-card">
                        <div class="skin-video-wrapper">
                            @if(!empty($comodin['image_url']))
                                <img src="{{ asset($comodin['image_url']) }}" alt="{{ $comodin['nombre'] }}" class="skin-video">
                            @else
                                <div class="d-flex align-items-center justify-content-center h-100">
                                    <i class="fas fa-star fa-3x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="skin-info">
                            <h3 class="skin-name">{{ $comodin['nombre'] }}</h3>
                            <p class="skin-description">{{ $comodin['descripcion'] }}</p>
                            <p class="skin-price">
                                <i class="fas fa-coins"></i> {{ $comodin['precio'] }}
                            </p>
                            <form action="{{ route('estudiante.tienda.comprar-comodin') }}" method="POST">
                                @csrf
                                <input type="hidden" name="comodin_id" value="{{ $comodin['id'] }}">
                                
                                @if ($user->nivel >= $comodin['nivel_requerido'])
                                    <button type="submit" class="btn btn-buy" {{ $user->gold < $comodin['precio'] ? 'disabled' : '' }}>
                                        {{ $user->gold < $comodin['precio'] ? 'Oro insuficiente' : 'Comprar' }}
                                    </button>
                                @else
                                    <button type="button" class="btn btn-buy" disabled>
                                        Requiere Nivel {{ $comodin['nivel_requerido'] }}
                                    </button>
                                @endif
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-center w-100">No hay comodines disponibles en este momento.</p>
                @endforelse
            </div>
        </section>
        
        <hr class="section-divider">

        <section class="inventory-section">
            <h2 class="section-title">Inventario</h2>
            <div class="skins-grid">
                @forelse ($skinsCompradas as $skin)
                    <div class="skin-card">
                        <div class="skin-video-wrapper">
                            <video class="skin-video" autoplay loop muted playsinline src="{{ asset($skin->video_url) }}"></video>
                        </div>
                        <div class="skin-info">
                            <h3 class="skin-name">{{ $skin->nombre }}</h3>
                            
                            @if ($user->skin_equipada_id == $skin->id)
                                <button class="btn btn-buy" disabled>Equipado</button>
                            @else
                                <form action="{{ route('estudiante.tienda.equipar') }}" method="POST" class="w-100">
                                    @csrf
                                    <input type="hidden" name="skin_id" value="{{ $skin->id }}">
                                    <button type="submit" class="btn btn-buy">Equipar</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-center w-100">No tienes skins. ¡Compra uno en la tienda!</p>
                @endforelse
            </div>
        </section>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // La lógica de filtros ya no es necesaria y se puede eliminar o dejar vacía.
});
</script>
</body>
</html> 