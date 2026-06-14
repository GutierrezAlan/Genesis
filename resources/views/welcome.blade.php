<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ShopModern</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h1 {
            color: #333;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .login-header p {
            color: #666;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-group input::placeholder {
            color: #999;
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            font-size: 13px;
        }

        .remember {
            display: flex;
            align-items: center;
        }

        .remember input {
            width: 16px;
            height: 16px;
            margin-right: 6px;
            cursor: pointer;
        }

        .forgot-link {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .forgot-link:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 25px 0;
            color: #999;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #ddd;
        }

        .divider span {
            margin: 0 10px;
            font-size: 13px;
        }

        .register-link {
            text-align: center;
            color: #666;
            font-size: 14px;
        }

        .register-link button {
            background: none;
            border: none;
            color: #667eea;
            cursor: pointer;
            font-weight: 600;
            text-decoration: underline;
            padding: 0;
            font-size: 14px;
            transition: color 0.3s;
        }

        .register-link button:hover {
            color: #764ba2;
        }

        .error-message {
            background: #fee;
            color: #c33;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
            border-left: 4px solid #c33;
        }

        .success-message {
            background: #efe;
            color: #3c3;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
            border-left: 4px solid #3c3;
        }

        /* MODAL STYLES */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s ease;
            align-items: center;
            justify-content: center;
        }

        .modal-overlay.active {
            display: flex;
            opacity: 1;
        }

        .modal-content {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
            animation: modalSlideIn 0.3s ease-out;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .modal-close {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 28px;
            font-weight: bold;
            color: #aaa;
            cursor: pointer;
            transition: color 0.3s;
            line-height: 1;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-close:hover {
            color: #333;
        }

        .register-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .register-header h1 {
            color: #333;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .register-header p {
            color: #666;
            font-size: 14px;
        }

        .password-requirement {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }

        .register-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            margin-top: 20px;
        }

        .register-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 14px;
        }

        .login-link button {
            background: none;
            border: none;
            color: #667eea;
            cursor: pointer;
            font-weight: 600;
            text-decoration: underline;
            padding: 0;
            font-size: 14px;
        }

        .login-link button:hover {
            color: #764ba2;
        }

        .terms {
            font-size: 12px;
            color: #666;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eee;
            display: flex;
            align-items: flex-start;
        }

        .terms input {
            margin-right: 8px;
            cursor: pointer;
            margin-top: 2px;
        }

        .terms label {
            margin: 0;
            font-weight: normal;
            cursor: pointer;
        }

        .terms a {
            color: #667eea;
            text-decoration: none;
        }

        .terms a:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .login-container,
            .modal-content {
                padding: 30px 20px;
            }

            .login-header h1,
            .register-header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <!-- LOGIN FORM -->
    <div class="login-container">
        <div class="login-header">
            <h1>🛒 ShopModern</h1>
            <p>Inicia sesión en tu cuenta</p>
        </div>

        @if ($errors->any())
            <div class="error-message">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if (session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ url('/login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="tu@correo.com"
                    value="{{ old('email') }}"
                    required
                    autofocus
                >
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="••••••••"
                    required
                >
            </div>

            <div class="remember-forgot">
                <label class="remember">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <span>Recuérdame</span>
                </label>
                <a href="#" class="forgot-link">¿Olvidaste tu contraseña?</a>
            </div>

            <button type="submit" class="login-btn">Iniciar Sesión</button>
        </form>

        <div class="divider">
            <span>¿No tienes cuenta?</span>
        </div>

        <div class="register-link">
            <button type="button" onclick="openRegisterModal()">Crear una cuenta nueva</button>
        </div>
    </div>

    <!-- MODAL OVERLAY -->
    <div class="modal-overlay" id="registerModal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeRegisterModal()">&times;</span>

            <div class="register-header">
                <h1>Crear Cuenta</h1>
                <p>Únete a ShopModern</p>
            </div>

            <form action="{{ route('auth.register') }}" method="POST" id="registerForm">
                @csrf

                <div class="form-group">
                    <label for="modal-name">Nombre Completo</label>
                    <input 
                        type="text" 
                        id="modal-name" 
                        name="name" 
                        placeholder="Juan Pérez"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="modal-email">Correo Electrónico</label>
                    <input 
                        type="email" 
                        id="modal-email" 
                        name="email" 
                        placeholder="tu@correo.com"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="modal-password">Contraseña</label>
                    <input 
                        type="password" 
                        id="modal-password" 
                        name="password" 
                        placeholder="••••••••"
                        required
                    >
                    <div class="password-requirement">
                        ✓ Mínimo 8 caracteres
                    </div>
                </div>

                <div class="form-group">
                    <label for="modal-password-confirm">Confirmar Contraseña</label>
                    <input 
                        type="password" 
                        id="modal-password-confirm" 
                        name="password_confirmation" 
                        placeholder="••••••••"
                        required
                    >
                </div>

                <div class="terms">
                    <input type="checkbox" id="modal-terms" name="terms" required>
                    <label for="modal-terms">
                        Acepto los <a href="#">términos y condiciones</a>
                    </label>
                </div>

                <button type="submit" class="register-btn">Crear Cuenta</button>
            </form>

            <div class="login-link">
                ¿Ya tienes cuenta? 
                <button type="button" onclick="closeRegisterModal()">Volver al login</button>
            </div>
        </div>
    </div>

    <script>
        function openRegisterModal() {
            const modal = document.getElementById('registerModal');
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeRegisterModal() {
            const modal = document.getElementById('registerModal');
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
            document.getElementById('registerForm').reset();
        }

        // Cerrar modal al hacer clic en el overlay
        document.getElementById('registerModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeRegisterModal();
            }
        });

        // Cerrar modal con tecla ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeRegisterModal();
            }
        });
    </script>
</body>
</html>
