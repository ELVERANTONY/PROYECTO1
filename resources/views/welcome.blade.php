<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AventuraTec - Plataforma Educativa Gamificada</title>
    <!-- Fuentes -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Orbitron:wght@700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #0fffc0;
            --secondary: #ffe066;
            --accent: #00c3ff;
            --dark: #181c23;
            --light: #e0e7ef;
        }
        
        body {
            background: linear-gradient(135deg, #001e14 0%, #003c2a 100%);
            color: var(--light);
            min-height: 100vh;
            overflow-x: hidden;
            font-family: 'Montserrat', sans-serif;
            line-height: 1.6;
            cursor: default;
            position: relative;
        }

        /* Efecto de brillo de Dragon Ball */
        .dbz-glow {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(
                circle at 50% 50%,
                rgba(0, 255, 192, 0.1) 0%,
                rgba(0, 255, 192, 0) 70%
            );
            z-index: -1;
            pointer-events: none;
        }

        /* Video de fondo */
        .video-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            overflow: hidden;
            opacity: 0.25;
        }

        .video-bg {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: absolute;
            top: 0;
            left: 0;
        }

        .gradient-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0, 255, 192, 0.08) 0%, rgba(0, 255, 192, 0.03) 100%);
            z-index: -1;
        }


        
        /* Barra de navegación */
        .navbar {
            background: var(--dark) !important;
            box-shadow: 0 2px 16px 0 rgba(15, 255, 192, 0.5);
            border-bottom: 2px solid var(--primary);
            padding: 0.8rem 0;
            position: relative;
            z-index: 1000;
        }
        
        .navbar-brand {
            font-family: 'Orbitron', sans-serif;
            font-weight: 700;
            color: var(--primary) !important;
            letter-spacing: 2px;
            font-size: 1.8rem;
            text-shadow: 0 0 8px var(--primary), 0 0 2px var(--accent);
            transition: all 0.3s ease;
        }
        
        .navbar-brand:hover {
            text-shadow: 0 0 12px var(--primary), 0 0 4px var(--accent);
        }
        
        .nav-link {
            color: #fff !important;
            font-weight: 500;
            letter-spacing: 1px;
            margin: 0 0.5rem;
            padding: 0.5rem 1rem !important;
            border-radius: 2rem;
            transition: all 0.3s ease;
        }

        /* Estilo para los botones en la navbar */
        .navbar .btn {
            margin: 0 0.5rem;
        }

        .navbar .btn-glow {
            padding: 0.5rem 1.5rem !important;
        }

        .navbar .btn-outline-neon {
            padding: 0.5rem 1.5rem !important;
        }
        
        .nav-link:hover {
            color: var(--primary) !important;
            text-shadow: 0 0 6px var(--primary);
            background: rgba(15, 255, 192, 0.1);
        }
        
        /* Video de fondo */
        .video-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 0;
            overflow: hidden;
        }
        
        .video-bg {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(0.4) blur(1px) saturate(1.2);
        }
        
        /* Sección hero */
        .hero-section {
            position: relative;
            z-index: 10;
            margin-top: 0;
            padding: 3rem 0;
            background: linear-gradient(135deg, rgba(24, 28, 35, 0.95) 0%, rgba(15, 255, 192, 0.1) 100%);
            border-radius: 0 0 2rem 2rem;
            box-shadow: 0 0 32px 0 rgba(15, 255, 192, 0.4), 0 0 8px rgba(255, 224, 102, 0.3);
            border: 2px solid var(--primary);
            backdrop-filter: blur(10px);
            transform: translateY(0);
            transition: transform 0.3s ease-in-out;
        }

        /* Efecto de transición cuando se hace scroll */
        .hero-section.scrolled {
            transform: translateY(-20px);
        }

        /* Decoración de Dragon Ball */
        .dbz-decoration {
            position: absolute;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: linear-gradient(45deg, rgba(15, 255, 192, 0.3), rgba(255, 224, 102, 0.3));
            border: 2px solid var(--primary);
            box-shadow: 0 0 20px rgba(15, 255, 192, 0.5);
            animation: spin 10s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .hero-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 3.2rem;
            font-weight: 700;
            color: var(--primary);
            text-shadow: 0 0 16px var(--primary), 0 0 2px var(--accent);
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }
        
        .hero-title .accent {
            color: var(--secondary);
            text-shadow: 0 0 12px var(--secondary);
        }
        
        .hero-subtitle {
            font-size: 1.4rem;
            color: #fff;
            text-shadow: 0 0 8px rgba(15, 255, 192, 0.3);
            margin-bottom: 2.5rem;
            max-width: 600px;
        }
        
        .hero-media-wrapper {
            position: relative;
            min-height: 450px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-media {
            max-width: 100%;
            width: 500px;
            height: auto;
            border-radius: 1.5rem;
            box-shadow: 0 0 40px rgba(15, 255, 192, 0.4), 0 0 15px rgba(255, 224, 102, 0.3);
            border: 3px solid var(--primary);
            transition: all 0.4s ease-in-out;
            object-fit: cover;
            height: 400px;
        }

        .hero-media:hover {
            transform: scale(1.05) rotate(2deg);
            box-shadow: 0 0 60px rgba(15, 255, 192, 0.6), 0 0 25px rgba(255, 224, 102, 0.5);
        }
        
        /* Botones */
        .btn-glow {
            background: linear-gradient(90deg, var(--secondary) 0%, var(--primary) 100%);
            color: var(--dark);
            font-family: 'Orbitron', sans-serif;
            font-weight: 700;
            border: none;
            border-radius: 2rem;
            padding: 0.8rem 2rem;
            box-shadow: 0 0 16px var(--primary), 0 0 4px var(--secondary);
            transition: all 0.3s ease;
            text-shadow: 0 0 6px #fff;
            position: relative;
            overflow: hidden;
        }
        
        .btn-glow:hover {
            box-shadow: 0 0 32px var(--primary), 0 0 8px var(--secondary);
            transform: translateY(-2px);
            color: var(--dark);
        }
        
        .btn-glow::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                to bottom right,
                rgba(255, 255, 255, 0) 0%,
                rgba(255, 255, 255, 0.1) 45%,
                rgba(255, 255, 255, 0.1) 50%,
                rgba(255, 255, 255, 0) 55%
            );
            transform: rotate(30deg);
            transition: all 0.3s ease;
        }
        
        .btn-glow:hover::before {
            left: 100%;
        }
        
        .btn-outline-neon {
            border: 2px solid var(--primary);
            color: var(--primary);
            background: transparent;
            font-family: 'Orbitron', sans-serif;
            font-weight: 700;
            border-radius: 2rem;
            padding: 0.8rem 2rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-outline-neon:hover {
            background: var(--primary);
            color: var(--dark);
            box-shadow: 0 0 16px var(--primary);
            transform: translateY(-2px);
        }
        
        .btn-outline-neon::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(15, 255, 192, 0.2),
                transparent
            );
            transition: all 0.5s ease;
        }
        
        .btn-outline-neon:hover::before {
            left: 100%;
        }
        
        /* Tarjetas de estadísticas */
        .stats-card {
            background: var(--dark);
            border-radius: 1.2rem;
            padding: 1.8rem 1rem;
            text-align: center;
            margin-bottom: 1.5rem;
            box-shadow: 0 0 16px rgba(15, 255, 192, 0.3), 0 0 2px rgba(0, 195, 255, 0.1);
            border: 2px solid var(--primary);
            transition: all 0.3s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-5px) scale(1.03);
            box-shadow: 0 0 32px var(--primary), 0 0 8px var(--secondary);
            border: 2px solid var(--secondary);
        }
        
        .stats-card h3 {
            color: var(--primary);
            font-family: 'Orbitron', sans-serif;
            font-weight: bold;
            font-size: 2rem;
            text-shadow: 0 0 8px var(--primary);
            margin-bottom: 0.5rem;
        }
        
        .stats-card i {
            color: var(--secondary);
            font-size: 1.8rem;
            text-shadow: 0 0 8px var(--secondary);
            margin-bottom: 0.8rem;
            display: inline-block;
        }
        
        /* Tarjetas de clases */
        .class-card {
            background: var(--dark);
            border-radius: 1.5rem;
            padding: 2rem 1.5rem;
            box-shadow: 0 0 24px rgba(15, 255, 192, 0.2), 0 0 2px var(--primary);
            min-height: 380px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            border: 2px solid var(--primary);
            transition: all 0.3s ease;
            color: #fff;
            position: relative;
            overflow: hidden;
        }
        
        .class-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--secondary), var(--primary));
        }
        
        .class-card:hover {
            transform: translateY(-5px) scale(1.03);
            box-shadow: 0 0 32px var(--primary), 0 0 8px var(--secondary);
            border: 2px solid var(--secondary);
        }
        
        .class-img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 1rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 0 16px rgba(15, 255, 192, 0.2);
            transition: all 0.3s ease;
        }
        
        .class-card:hover .class-img {
            box-shadow: 0 0 24px var(--primary);
        }
        
        .class-card h4 {
            font-family: 'Orbitron', sans-serif;
            color: var(--primary);
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .class-card p {
            margin-bottom: 1.5rem;
        }
        
        .hero-video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        /* Sección de características moderna y minimalista */
        .features-section {
            position: relative;
            padding: 4rem 0;
            background: transparent;
            margin: 3rem 0;
            overflow: hidden;
            z-index: 1;
        }

        .features-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                135deg,
                rgba(15, 255, 192, 0.05) 0%,
                transparent 100%
            );
            pointer-events: none;
        }

        .feature-card {
            background: rgba(24, 28, 35, 0.9);
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(15, 255, 192, 0.1);
            transition: all 0.3s ease;
            position: relative;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            box-shadow: 0 0 15px rgba(15, 255, 192, 0.1);
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                45deg,
                transparent,
                rgba(15, 255, 192, 0.1) 50%,
                transparent
            );
            animation: energySweep 3s infinite;
            pointer-events: none;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0 25px rgba(15, 255, 192, 0.2);
            border-color: var(--primary);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: rgba(15, 255, 192, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .feature-icon:hover {
            transform: scale(1.1);
            background: rgba(15, 255, 192, 0.2);
        }

        .feature-icon i {
            font-size: 2rem;
            color: var(--primary);
            position: relative;
            z-index: 1;
        }

        .feature-content h3 {
            font-family: 'Orbitron', sans-serif;
            color: var(--primary);
            margin-bottom: 0.8rem;
            font-size: 1.4rem;
        }

        .feature-content p {
            color: var(--light);
            opacity: 0.9;
            line-height: 1.6;
            font-size: 0.95rem;
            margin-bottom: 0;
            max-width: 250px;
        }
        
        /* Sección CTA */
        .cta-section {
            background: var(--dark);
            border-radius: 1.5rem;
            padding: 3rem 2rem;
            color: var(--primary);
            text-align: center;
            margin-top: 3rem;
            box-shadow: 0 0 32px rgba(15, 255, 192, 0.2), 0 0 8px rgba(255, 224, 102, 0.2);
            border: 2px solid var(--primary);
            position: relative;
            overflow: hidden;
        }
        
        .cta-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                135deg,
                rgba(15, 255, 192, 0.05) 0%,
                rgba(255, 224, 102, 0.05) 100%
            );
        }
        
        .cta-section h2 {
            color: var(--secondary);
            font-family: 'Orbitron', sans-serif;
            font-size: 2.2rem;
            text-shadow: 0 0 8px var(--secondary);
            margin-bottom: 1.5rem;
        }
        
        .cta-section p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto 2.5rem;
        }
        
        /* Footer */
        .footer {
            background: var(--dark);
            color: var(--secondary);
            padding: 3rem 0 1.5rem 0;
            margin-top: 4rem;
            border-top: 2px solid var(--primary);
            position: relative;
        }
        
        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                to bottom,
                rgba(15, 255, 192, 0.05) 0%,
                rgba(24, 28, 35, 0.9) 100%
            );
        }
        
        .footer a {
            color: var(--primary);
            text-decoration: none;
            text-shadow: 0 0 8px var(--primary);
            transition: all 0.3s ease;
        }
        
        .footer a:hover {
            color: var(--secondary);
            text-shadow: 0 0 8px var(--secondary);
        }
        
        .footer-links {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .social-links {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .social-links a {
            font-size: 1.5rem;
        }
        
        .copyright {
            text-align: center;
            font-size: 0.9rem;
            opacity: 0.8;
        }
        
        /* Partículas */
        .particles-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            pointer-events: none;
        }
        
        /* Responsive */
        @media (max-width: 1199px) {
            .hero-title {
                font-size: 2.8rem;
            }
        }
        
        @media (max-width: 991px) {
            .hero-title {
                font-size: 2.4rem;
            }
            
            .hero-section {
                padding: 4rem 0;
            }
            
            .class-card {
                min-height: 400px;
            }
            
            .feature-item {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            
            .feature-icon {
                margin-right: 0;
                margin-bottom: 1rem;
            }
        }
        
        @media (max-width: 767px) {
            .hero-title {
                font-size: 2rem;
            }
            
            .hero-subtitle {
                font-size: 1.2rem;
            }
            
            .hero-section {
                text-align: center;
                padding: 3rem 0;
            }
            
            .class-card {
                min-height: auto;
            }
            
            .btn-glow, .btn-outline-neon {
                display: block;
                width: 100%;
                margin-bottom: 1rem;
            }
        }
        
        @media (max-width: 575px) {
            .hero-title {
                font-size: 1.8rem;
            }
            
            .hero-section {
                padding: 2rem 0;
                margin-top: 60px;
            }
            
            .stats-card {
                padding: 1.5rem 0.5rem;
            }
            
            .stats-card h3 {
                font-size: 1.5rem;
            }
            
            .cta-section h2 {
                font-size: 1.8rem;
            }
        }
        
        /* Efectos de Dragon Ball */
        .energy-effect {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            pointer-events: none;
        }
        
        .energy-particle {
            position: absolute;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: #0fffc0;
            animation: energy-particle 5s infinite;
        }
        
        .energy-wave {
            position: absolute;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: #0fffc0;
            animation: energy-wave 5s infinite;
        }
        
        @keyframes energy-particle {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.5);
            }
            100% {
                transform: scale(1);
            }
        }
        
        @keyframes energy-wave {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.5);
            }
            100% {
                transform: scale(1);
            }
        }
        
        /* Decoración de Dragon Ball */
        .dbz-decoration {
            position: absolute;
            width: 50px;
            height: 50px;
            background-image: url('{{ asset('images/dbz-decoration.svg') }}');
            background-size: cover;
        }

        .character-card p {
            font-size: 0.95rem;
            color: var(--light);
            margin-bottom: 1.5rem;
        }

        .character-card .card-img-top {
            width: 100%;
            height: 220px;
            object-fit: contain;
            border-radius: 1rem 1rem 0 0;
            background-color: #000;
        }
        
        .btn-select-char {
            background: linear-gradient(90deg, #ffe066, #0fffc0);
        }
    </style>
</head>
<body>

    <!-- Video de fondo -->
    <div class="video-container">
        <video class="video-bg" autoplay loop muted playsinline>
            <source src="{{ asset('videos/video-inicio.mp4') }}" type="video/mp4">
        </video>
    </div>
    <div class="gradient-overlay"></div>
    <div class="dbz-glow"></div>



<!-- Barra de navegación -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="#">AventuraTec</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
    <a class="btn btn-glow" href="{{ route('login') }}">
        <i class="fas fa-user"></i> Iniciar Sesión
    </a>
</li>
<li class="nav-item">
    <a class="btn btn-outline-neon" href="{{ route('register') }}">
        <i class="fas fa-user-plus"></i> Regístrate
    </a>
</li>
            </ul>
        </div>
    </div>
</nav>

<!-- Sección Hero -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 text-lg-start text-center">
                <h1 class="hero-title">
                    BIENVENIDO a <br><span class="accent">AventuraTec</span>
                </h1>
                <p class="hero-subtitle">
                    La plataforma educativa gamificada que transforma el aprendizaje en una aventura épica.
                </p>
                <a href="{{ route('register') }}" class="btn btn-glow"><i class="fas fa-user-plus"></i> Únete a la Aventura</a>
            </div>
            <div class="col-lg-6">
                <div class="hero-media-wrapper">
                    <video autoplay loop muted playsinline class="hero-media">
                        <source src="{{ asset('videos/goku-inicio.mp4') }}" type="video/mp4">
                        Tu navegador no soporta el tag de video.
                    </video>
                </div>
            </div>
        </div>
        <!-- Decoración de Dragon Ball -->
        <div class="dbz-decoration" style="top: 20px; right: 20px;"></div>
        <div class="dbz-decoration" style="bottom: 20px; left: 20px;"></div>
    </div>
</section>

<!-- Estadísticas -->
<section class="container mt-5">
    <div class="row text-center mb-5">
        <div class="col-6 col-md-3">
            <div class="stats-card">
                <i class="fa-solid fa-user-astronaut"></i>
                <h3>10K+</h3>
                <div>Estudiantes Activos</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stats-card">
                <i class="fa-solid fa-robot"></i>
                <h3>500+</h3>
                <div>Misiones IA</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stats-card">
                <i class="fa-solid fa-trophy"></i>
                <h3>1M+</h3>
                <div>Logros Desbloqueados</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stats-card">
                <i class="fa-solid fa-star"></i>
                <h3>4.9</h3>
                <div>Calificación</div>
            </div>
        </div>
    </div>
</section>

<!-- Personajes -->
<section class="character-section">
    <div class="container">
        <h2 class="text-center mb-5" style="font-family:'Orbitron',sans-serif;font-weight:700;color:#ffe066;">Elige tu Personaje</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="character-card">
                    <video class="card-img-top" autoplay loop muted playsinline>
                        <source src="{{ asset('videos/mago-avanzado.mp4') }}" type="video/mp4">
                    </video>
                    <div class="card-body">
                        <h5 class="card-title">Mago</h5>
                        <p class="card-text">Domina el conocimiento y resuelve retos con inteligencia y creatividad.</p>
                        <a href="{{ route('register') }}" class="btn btn-select-char">Seleccionar Mago</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="character-card">
                    <video class="card-img-top" autoplay loop muted playsinline>
                        <source src="{{ asset('videos/guerrero-avanzado.mp4') }}" type="video/mp4">
                    </video>
                    <div class="card-body">
                        <h5 class="card-title">Guerrero</h5>
                        <p class="card-text">Supera desafíos con determinación, esfuerzo y trabajo en equipo.</p>
                        <a href="{{ route('register') }}" class="btn btn-select-char">Seleccionar Guerrero</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="character-card">
                    <video class="card-img-top" autoplay loop muted playsinline>
                        <source src="{{ asset('videos/sanador-avanzado.mp4') }}" type="video/mp4">
                    </video>
                    <div class="card-body">
                        <h5 class="card-title">Sanador</h5>
                        <p class="card-text">Apoya a tu equipo, comparte conocimiento y ayuda a los demás a crecer.</p>
                        <a href="{{ route('register') }}" class="btn btn-select-char">Seleccionar Sanador</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Características Futuristas modernas y minimalistas -->
<section class="features-section">
    <div class="container">
        <h2 class="text-center mb-5" style="font-family:'Orbitron',sans-serif;font-weight:700;color:#0fffc0;">Características Futuristas</h2>
        
        <div class="row justify-content-center g-4">
            <div class="col-12 col-md-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-solid fa-brain-circuit"></i>
                    </div>
                    <div class="feature-content">
                        <h3>IA Adaptativa</h3>
                        <p>Desafíos personalizados según tu progreso y estilo de aprendizaje. Nuestra IA analiza tu desempeño y ajusta la dificultad en tiempo real.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-md-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-solid fa-bolt-lightning"></i>
                    </div>
                    <div class="feature-content">
                        <h3>Poderes Especiales</h3>
                        <p>Desbloquea habilidades únicas para resolver desafíos educativos. Cada personaje tiene poderes especiales relacionados con diferentes áreas de conocimiento.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-md-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-solid fa-people-group"></i>
                    </div>
                    <div class="feature-content">
                        <h3>Trabajo en Equipo</h3>
                        <p>Forma alianzas con otros estudiantes para superar misiones colaborativas. Aprende a trabajar en equipo mientras resuelves desafíos complejos.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-md-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-solid fa-medal"></i>
                    </div>
                    <div class="feature-content">
                        <h3>Logros y Recompensas</h3>
                        <p>Consigue insignias, trofeos y recompensas exclusivas por tus éxitos académicos. Cada logro te acerca a nuevos niveles y habilidades.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-md-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-solid fa-store"></i>
                    </div>
                    <div class="feature-content">
                        <h3>Tienda de Ítems</h3>
                        <p>Personaliza tu avatar con objetos especiales que puedes adquirir con puntos de conocimiento. Cada ítem tiene beneficios educativos únicos.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-md-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-solid fa-ranking-star"></i>
                    </div>
                    <div class="feature-content">
                        <h3>Clasificaciones</h3>
                        <p>Compite con estudiantes de todo el mundo y sube en el ranking global. Demuestra tus habilidades y alcanza los primeros puestos.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="container">
    <div class="cta-section">
        <h2>¿Listo para comenzar tu aventura?</h2>
        <p>Únete a miles de estudiantes que ya están transformando su educación en una aventura épica con IA.</p>
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="{{ route('register') }}" class="btn btn-glow">Crear cuenta gratis</a>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="container position-relative">
        <div class="footer-links">
            <a href="#">Inicio</a>
            <a href="#">Clases</a>
            <a href="#">Precios</a>
            <a href="#">Contacto</a>
            <a href="#">Términos</a>
            <a href="#">Privacidad</a>
        </div>
        <div class="social-links">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-linkedin-in"></i></a>
            <a href="#"><i class="fab fa-youtube"></i></a>
        </div>
        <div class="copyright">
            &copy; 2023 AventuraTec. Todos los derechos reservados.
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Partículas.js -->
<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
<script>
    // Inicialización de partículas
    document.addEventListener('DOMContentLoaded', function() {
        particlesJS('particles-js', {
            "particles": {
                "number": {
                    "value": 80,
                    "density": {
                        "enable": true,
                        "value_area": 800
                    }
                },
                "color": {
                    "value": ["#ffe066", "#0fffc0"] // Amarillo y verde
                },
                "shape": {
                    "type": "circle",
                    "stroke": {
                        "width": 0,
                        "color": "#000000"
                    },
                    "polygon": {
                        "nb_sides": 5
                    }
                },
                "opacity": {
                    "value": 0.5,
                    "random": true,
                    "anim": {
                        "enable": true,
                        "speed": 1,
                        "opacity_min": 0.1,
                        "sync": false
                    }
                },
                "size": {
                    "value": 3,
                    "random": true,
                    "anim": {
                        "enable": false,
                        "speed": 40,
                        "size_min": 0.1,
                        "sync": false
                    }
                },
                "line_linked": {
                    "enable": true,
                    "distance": 150,
                    "color": "#0fffc0",
                    "opacity": 0.4,
                    "width": 1
                },
                "move": {
                    "enable": true,
                    "speed": 2,
                    "direction": "none",
                    "random": true,
                    "straight": false,
                    "out_mode": "out",
                    "bounce": false,
                    "attract": {
                        "enable": false,
                        "rotateX": 600,
                        "rotateY": 1200
                    }
                }
            },
            "interactivity": {
                "detect_on": "canvas",
                "events": {
                    "onhover": {
                        "enable": true,
                        "mode": "grab"
                    },
                    "onclick": {
                        "enable": true,
                        "mode": "push"
                    },
                    "resize": true
                },
                "modes": {
                    "grab": {
                        "distance": 140,
                        "line_linked": {
                            "opacity": 1
                        }
                    },
                    "bubble": {
                        "distance": 400,
                        "size": 40,
                        "duration": 2,
                        "opacity": 8,
                        "speed": 3
                    },
                    "repulse": {
                        "distance": 200,
                        "duration": 0.4
                    },
                    "push": {
                        "particles_nb": 4
                    },
                    "remove": {
                        "particles_nb": 2
                    }
                }
            },
            "retina_detect": true
        });
    });
</script>
<script>
    // Efecto de scroll para la sección hero
    const heroSection = document.getElementById('hero-section');
    let lastScroll = 0;

    window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset;
        
        if (currentScroll > lastScroll) {
            // Scroll hacia abajo
            heroSection.classList.remove('scrolled');
        } else {
            // Scroll hacia arriba
            if (currentScroll > 50) {
                heroSection.classList.add('scrolled');
            }
        }
        
        lastScroll = currentScroll;
    });
</script>
</body>
</html>
