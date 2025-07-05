<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Iniciar Sesión - AventuraTec</title>

  <!-- Fuentes y estilos -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Orbitron:wght@700&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

  <style>
    :root {
      --primary: #0fffc0;
      --secondary: #ffe066;
      --dark: #181c23;
    }

    body {
      background: radial-gradient(circle at 50% 30%, var(--primary) 0%, var(--dark) 100%);
      min-height: 100vh;
      font-family: 'Montserrat', Arial, sans-serif;
    }

    .login-box {
      background: rgba(24, 28, 35, 0.98);
      border-radius: 18px;
      box-shadow: 0 0 32px 0 rgba(15, 255, 192, 0.4);
      max-width: 400px;
      margin: 60px auto;
      padding: 2.5rem 2rem;
      border: 2.5px solid var(--primary);
    }

    .login-title {
      font-size: 2rem;
      font-family: 'Orbitron', Arial, sans-serif;
      font-weight: 700;
      color: var(--primary);
      text-align: center;
      text-shadow: 0 0 12px var(--primary), 0 0 2px #a259ff;
    }

    .login-subtitle {
      color: #fff;
      text-align: center;
      margin-bottom: 1.5rem;
      text-shadow: 0 0 8px var(--primary);
    }

    label {
      display: block;
      margin-bottom: 0.4rem;
      color: var(--primary);
      font-weight: 500;
      text-shadow: 0 0 4px var(--primary);
    }

    .form-control {
      background: #181c23;
      color: #fff;
      border-radius: 8px;
      border: 2px solid var(--primary);
      margin-bottom: 1.4rem;
      box-shadow: 0 0 8px #0fffc033;
    }

    .form-control::placeholder {
      color: var(--primary);
      opacity: 1;
    }

    .form-control:focus {
      border-color: var(--secondary);
      box-shadow: 0 0 16px var(--secondary);
      background: #222b3a;
      color: #fff;
    }

    .btn-eye {
      position: absolute;
      top: 70%;
      right: 10px;
      transform: translateY(-50%);
      background: none;
      border: none;
      color: white !important;
      cursor: pointer;
      padding: 0;
      margin: 0;
      width: 24px;
      height: 24px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .login-btn {
      width: 100%;
      background: linear-gradient(90deg, var(--secondary) 0%, var(--primary) 100%);
      color: var(--dark);
      font-family: 'Orbitron', Arial, sans-serif;
      font-weight: 700;
      border: none;
      border-radius: 8px;
      padding: 0.8rem;
      font-size: 1.1rem;
      margin-top: 1rem;
      box-shadow: 0 0 16px rgba(15, 255, 192, 0.5);
      text-shadow: 0 0 6px #fff;
      transition: all 0.3s ease;
    }

    .login-btn:hover {
      box-shadow: 0 0 32px var(--primary), 0 0 16px var(--secondary);
      transform: scale(1.03);
    }

    .register-link {
      display: block;
      text-align: center;
      margin-top: 1.2rem;
      color: var(--primary);
      text-decoration: underline;
      text-shadow: 0 0 8px var(--primary);
    }

    .alert {
      background: rgba(220, 53, 69, 0.2);
      border: 1px solid #dc3545;
      color: #ff6b6b;
      border-radius: 8px;
      padding: 0.75rem;
      margin-bottom: 1rem;
      text-shadow: 0 0 4px #ff6b6b;
    }

    .alert-success {
      background: rgba(25, 135, 84, 0.2);
      border: 1px solid #198754;
      color: #51cf66;
      text-shadow: 0 0 4px #51cf66;
    }

    .error-message {
      color: #ff6b6b;
      font-size: 0.875rem;
      margin-top: 0.25rem;
      text-shadow: 0 0 4px #ff6b6b;
    }
  </style>
</head>
<body>
  <div class="login-box">
    <div class="login-title">¡Bienvenido de nuevo!</div>
    <div class="login-subtitle">Inicia sesión para continuar tu aventura</div>

    @if(session('success'))
      <div class="alert alert-success">
        {{ session('success') }}
      </div>
    @endif

    @if($errors->any())
      <div class="alert">
        <ul class="mb-0">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('login') }}" autocomplete="off">
      @csrf

      <!-- Campos falsos para evitar autocompletado -->
      <input type="text" name="fakeusernameremembered" style="display:none">
      <input type="password" name="fakepasswordremembered" style="display:none">

      <!-- Email -->
      <div class="mb-4">
        <label for="email">Correo Electrónico</label>
        <input type="email" id="email" name="email" class="form-control" required autocomplete="off">
      </div>

      <!-- Contraseña -->
      <div class="mb-4 position-relative">
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" class="form-control" required autocomplete="new-password">
        <button type="button" class="btn-eye" onclick="togglePasswordVisibility()">
          <i class="fas fa-eye" id="eyeIcon1"></i>
        </button>
      </div>

      <div class="mb-3">
        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="remember" name="remember">
          <label class="form-check-label" for="remember" style="color: #fff; font-size: 0.9rem;">
            Recordarme
          </label>
        </div>
      </div>

      <button type="submit" class="login-btn">Iniciar Sesión</button>
    </form>

    <a href="{{ route('register') }}" class="register-link">¿No tienes cuenta? Regístrate aquí</a>
  </div>

  <script>
    function togglePasswordVisibility() {
      const passwordInput = document.getElementById('password');
      const eyeIcon = document.getElementById('eyeIcon1');
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
      } else {
        passwordInput.type = 'password';
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
      }
    }
  </script>
</body>
</html>
