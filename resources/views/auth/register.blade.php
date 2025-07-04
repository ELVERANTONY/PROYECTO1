<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro - AventuraTec</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Orbitron:wght@700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <style>
    :root {
      --primary: #0fffc0;
      --secondary: #ffe066;
      --dark: #181c23;
      --light: #e0e7ef;
    }
    body {
      background: radial-gradient(circle at 50% 30%, #0fffc0 0%, #181c23 100%);
      min-height: 100vh;
      font-family: 'Montserrat', Arial, sans-serif;
      padding: 2rem 0;
    }
    .register-box {
      background: rgba(24,28,35,0.98);
      border-radius: 18px;
      box-shadow: 0 0 32px 0 rgba(15, 255, 192, 0.4);
      max-width: 500px;
      margin: auto;
      padding: 2.5rem;
      border: 2.5px solid var(--primary);
    }
    .register-title {
      font-size: 2.2rem;
      font-family: 'Orbitron', Arial, sans-serif;
      font-weight: 700;
      color: var(--primary);
      text-align: center;
      text-shadow: 0 0 12px var(--primary);
    }
    .register-subtitle {
      color: #fff;
      text-align: center;
      margin-bottom: 1.5rem;
    }
    .form-control {
      border-radius: 8px;
      border: 2px solid var(--primary);
      background: #222b3a;
      color: #fff;
      margin-bottom: 1rem;
    }
    .form-control::placeholder { color: rgba(255, 255, 255, 0.7); }
    .form-control:focus {
      border-color: var(--secondary);
      box-shadow: 0 0 16px var(--secondary);
      background: #222b3a;
      color: #fff;
    }
    .register-btn {
      width: 100%;
      background: linear-gradient(90deg, var(--secondary) 0%, var(--primary) 100%);
      color: #181c23;
      font-family: 'Orbitron', Arial, sans-serif;
      font-weight: 700;
      border: none;
      border-radius: 8px;
      padding: 0.8rem;
      font-size: 1.1rem;
      box-shadow: 0 0 16px rgba(15, 255, 192, 0.5);
      transition: all 0.3s ease;
    }
    .register-btn:hover {
      box-shadow: 0 0 32px var(--primary), 0 0 16px var(--secondary);
      transform: translateY(-2px);
    }
    .alert {
      background: rgba(220, 53, 69, 0.2);
      border: 1px solid #dc3545;
      color: #ff6b6b;
    }
    .error-message { color: #ff6b6b; font-size: 0.875rem; }
    .btn-eye {
      position: absolute;
      top: 50%;
      right: 15px;
      transform: translateY(-50%);
      background: none;
      border: none;
      color: white !important;
      cursor: pointer;
    }
    .selection-group {
      background: rgba(15, 255, 192, 0.05);
      border: 1px solid rgba(15, 255, 192, 0.2);
      padding: 1rem 1.5rem;
      border-radius: 8px;
      margin-bottom: 1rem;
    }
    .selection-group-title {
      color: var(--primary);
      font-weight: 500;
      margin-bottom: 0.75rem;
      text-shadow: 0 0 4px var(--primary);
    }
    .form-check-input-neon {
      background-color: var(--dark);
      border: 2px solid var(--primary);
    }
    .form-check-input-neon:checked {
      background-color: var(--primary);
      border-color: var(--primary);
      box-shadow: 0 0 12px var(--primary);
    }
    .form-check-input-neon:checked[type=radio] {
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='2' fill='%23181c23'/%3e%3c/svg%3e");
    }
    .form-check-label { color: var(--light); }

    /* Estilos para selección de personaje con tarjetas */
    .character-selection-cards {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-top: 1rem;
    }
    .character-card {
        background: var(--dark);
        border: 2px solid rgba(15, 255, 192, 0.3);
        border-radius: 12px;
        text-align: center;
        padding: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .character-card:hover, .character-card.selected {
        border-color: var(--primary);
        box-shadow: 0 0 16px var(--primary);
        transform: translateY(-5px);
    }
    .character-card-video-wrapper {
        width: 100%;
        height: 180px;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 0.75rem;
        background-color: #000;
    }
    .character-card-video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .character-card-name {
        font-family: 'Orbitron', sans-serif;
        color: var(--secondary);
        font-weight: 700;
    }
    /* Ocultar los radio buttons originales */
    #character-radios {
        display: none;
    }
  </style>
</head>
<body>
  <div class="register-box">
    <div class="register-title">¡Únete a la Aventura!</div>
    <div class="register-subtitle">Crea tu cuenta y comienza tu viaje</div>

    @if($errors->any())
      <div class="alert">
        <ul class="mb-0">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('register') }}" autocomplete="off">
      @csrf

      <!-- Campos falsos para evitar autocompletado -->
      <input type="text" name="fakeusernameremembered" style="display:none">
      <input type="password" name="fakepasswordremembered" style="display:none">

      <!-- Nombre -->
      <input type="text" name="name" placeholder="Nombre completo" class="form-control @error('name') is-invalid @enderror" required autocomplete="off" autocorrect="off" spellcheck="false">
      @error('name') <div class="error-message">{{ $message }}</div> @enderror

      <!-- Email -->
      <input type="email" name="email" placeholder="Correo electrónico" class="form-control @error('email') is-invalid @enderror" required autocomplete="email" autocorrect="off" spellcheck="false">
      @error('email') <div class="error-message">{{ $message }}</div> @enderror

      <!-- Contraseña -->
      <div class="position-relative">
        <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Contraseña" required autocomplete="new-password">
        <button type="button" class="btn-eye" onclick="togglePasswordVisibility('password', 'eyeIcon1')"><i class="fas fa-eye" id="eyeIcon1"></i></button>
      </div>
      @error('password') <div class="error-message">{{ $message }}</div> @enderror

      <!-- Confirmar contraseña -->
      <div class="position-relative">
        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Confirmar contraseña" required autocomplete="new-password">
        <button type="button" class="btn-eye" onclick="togglePasswordVisibility('password_confirmation', 'eyeIcon2')"><i class="fas fa-eye" id="eyeIcon2"></i></button>
      </div>

      <div class="selection-group">
        <div class="selection-group-title">Género</div>
        <div class="d-flex justify-content-around">
          <div class="form-check">
            <input class="form-check-input form-check-input-neon" type="radio" name="gender" id="gender_hombre" value="hombre">
            <label class="form-check-label" for="gender_hombre">Hombre</label>
          </div>
          <div class="form-check">
            <input class="form-check-input form-check-input-neon" type="radio" name="gender" id="gender_mujer" value="mujer">
            <label class="form-check-label" for="gender_mujer">Mujer</label>
          </div>
        </div>
        @error('gender') <div class="error-message d-block text-center">{{ $message }}</div> @enderror
      </div>

      <div class="selection-group">
        <div class="selection-group-title">Rol</div>
        <div class="d-flex justify-content-around">
          <div class="form-check">
            <input class="form-check-input form-check-input-neon" type="radio" name="role" id="role_estudiante" value="estudiante">
            <label class="form-check-label" for="role_estudiante">Estudiante</label>
          </div>
          <div class="form-check">
            <input class="form-check-input form-check-input-neon" type="radio" name="role" id="role_profesor" value="profesor">
            <label class="form-check-label" for="role_profesor">Profesor</label>
          </div>
        </div>
        @error('role') <div class="error-message d-block text-center">{{ $message }}</div> @enderror
      </div>

      <div id="character-selection" class="selection-group" style="display: none;">
        <div class="selection-group-title">Elige tu Personaje</div>

        <div class="character-selection-cards">
            <!-- Mago -->
            <div class="character-card" data-class="mago">
                <div class="character-card-video-wrapper">
                    <video class="character-card-video" autoplay loop muted playsinline src="{{ asset('videos/mago-avanzado.mp4') }}"></video>
                </div>
                <div class="character-card-name">Mago</div>
            </div>
            <!-- Guerrero -->
            <div class="character-card" data-class="guerrero">
                <div class="character-card-video-wrapper">
                    <video class="character-card-video" autoplay loop muted playsinline src="{{ asset('videos/guerrero-avanzado.mp4') }}"></video>
                </div>
                <div class="character-card-name">Guerrero</div>
            </div>
            <!-- Sanador -->
            <div class="character-card" data-class="sanador">
                <div class="character-card-video-wrapper">
                    <video class="character-card-video" autoplay loop muted playsinline src="{{ asset('videos/sanador-avanzado.mp4') }}"></video>
                </div>
                <div class="character-card-name">Sanador</div>
            </div>
        </div>
        
        <div id="character-radios">
          <div class="form-check">
            <input class="form-check-input form-check-input-neon" type="radio" name="character_class" id="class_mago" value="mago">
            <label class="form-check-label" for="class_mago">Mago</label>
          </div>
          <div class="form-check">
            <input class="form-check-input form-check-input-neon" type="radio" name="character_class" id="class_guerrero" value="guerrero">
            <label class="form-check-label" for="class_guerrero">Guerrero</label>
          </div>
          <div class="form-check">
            <input class="form-check-input form-check-input-neon" type="radio" name="character_class" id="class_sanador" value="sanador">
            <label class="form-check-label" for="class_sanador">Sanador</label>
          </div>
        </div>
        @error('character_class') <div class="error-message d-block text-center">{{ $message }}</div> @enderror
      </div>

      <button type="submit" class="register-btn mt-4">¡Comenzar Aventura! 🚀</button>
    </form>

    <div class="text-center mt-3">
      <a href="{{ route('login') }}" style="color: #0fffc0; text-decoration: none;">¿Ya tienes cuenta? Inicia sesión</a>
    </div>
  </div>

  <script>
    function togglePasswordVisibility(fieldId, iconId) {
      const input = document.getElementById(fieldId);
      const icon = document.getElementById(iconId);
      if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
      } else {
        input.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
      }
    }

    function toggleCharacterSelection() {
      const characterSelection = document.getElementById('character-selection');
      const selectedRole = document.querySelector('input[name="role"]:checked')?.value;
      characterSelection.style.display = selectedRole === 'estudiante' ? 'block' : 'none';
    }

    document.addEventListener('DOMContentLoaded', () => {
      toggleCharacterSelection();
      document.querySelectorAll('input[name="role"]').forEach(input =>
        input.addEventListener('change', toggleCharacterSelection)
      );

      // Lógica para las tarjetas de personaje
      const cards = document.querySelectorAll('.character-card');
      cards.forEach(card => {
        card.addEventListener('click', () => {
          // Desmarcar todas las tarjetas
          cards.forEach(c => c.classList.remove('selected'));
          // Marcar la tarjeta seleccionada
          card.classList.add('selected');
          // Seleccionar el radio button correspondiente
          const characterClass = card.dataset.class;
          document.querySelector(`#class_${characterClass}`).checked = true;
        });
      });
    });
  </script>
</body>
</html>
