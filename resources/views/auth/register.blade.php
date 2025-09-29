<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Toko Pak Dedi</title>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a56d4;
            --secondary: #f8f9fa;
            --text-primary: #212529;
            --text-secondary: #6c757d;
            --success: #198754;
            --border: #dee2e6;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f7ff;
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .register-container {
            display: flex;
            width: 100%;
            max-width: 1000px;
            min-height: 600px;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .register-left {
            flex: 1;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .register-left::before {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -50px;
            left: -50px;
        }

        .register-left::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            bottom: -100px;
            right: -100px;
        }

        .brand {
            margin-bottom: 40px;
            position: relative;
            z-index: 1;
        }

        .brand h1 {
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .brand p {
            font-size: 16px;
            opacity: 0.9;
            margin-top: 8px;
        }

        .features {
            list-style: none;
            margin-top: 40px;
            position: relative;
            z-index: 1;
        }

        .features li {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            font-size: 15px;
        }

        .features li i {
            margin-right: 12px;
            background: rgba(255, 255, 255, 0.2);
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .register-right {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .register-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .register-header h2 {
            font-size: 26px;
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--text-primary);
        }

        .register-header p {
            color: var(--text-secondary);
            font-size: 15px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-row {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }

        .form-row .form-group {
            flex: 1;
            margin-bottom: 0;
        }

        .input-group {
            position: relative;
        }

        .input-group input {
            width: 100%;
            padding: 16px 16px 16px 48px;
            border: 1px solid var(--border);
            border-radius: 10px;
            font-size: 15px;
            transition: var(--transition);
        }

        .input-group input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
        }

        .toggle-password {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            cursor: pointer;
        }

        .invalid-feedback {
            display: block;
            color: #dc3545;
            font-size: 14px;
            margin-top: 6px;
        }

        .is-invalid {
            border-color: #dc3545 !important;
        }

        .is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.15) !important;
        }

        .password-strength {
            margin-top: 8px;
            height: 6px;
            border-radius: 3px;
            background: #f0f0f0;
            overflow: hidden;
        }

        .password-strength-bar {
            height: 100%;
            width: 0%;
            transition: var(--transition);
            border-radius: 3px;
        }

        .password-requirements {
            margin-top: 10px;
            font-size: 13px;
            color: var(--text-secondary);
        }

        .requirement {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }

        .requirement i {
            margin-right: 5px;
            font-size: 12px;
        }

        .requirement.met {
            color: var(--success);
        }

        .btn-register {
            width: 100%;
            padding: 16px;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 10px;
        }

        .btn-register:hover {
            background-color: var(--primary-dark);
        }

        .terms {
            font-size: 13px;
            color: var(--text-secondary);
            text-align: center;
            margin-top: 20px;
        }

        .terms a {
            color: var(--primary);
            text-decoration: none;
        }

        .terms a:hover {
            text-decoration: underline;
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: var(--text-secondary);
        }

        .login-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .login-link a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .register-container {
                flex-direction: column;
                max-width: 100%;
            }

            .register-left {
                display: none;
            }

            .register-right {
                padding: 30px 20px;
            }

            .form-row {
                flex-direction: column;
                gap: 0;
            }
        }
    </style>
</head>

<body>
    <div class="register-container">
        <div class="register-left">
            <div class="brand">
                <h1>{{ config('app.name') }}</h1>
                <p>Sistem Informasi Inventory dan Penjualan UMKM</p>
            </div>
        </div>
        <div class="register-right">
            <div class="register-header">
                <h2>Buat Akun Baru</h2>
                <p>Isi data di bawah ini untuk mulai menggunakan sistem</p>
            </div>
            <form action="{{ route('register') }}" method="post" id="registerForm">
                @csrf

                <div class="form-row">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <input type="text" name="first_name"
                                class="form-control @error('first_name') is-invalid @enderror" placeholder="Nama Depan"
                                value="{{ old('first_name') }}" required autocomplete="given-name" autofocus>
                        </div>
                        @error('first_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <input type="text" name="last_name"
                                class="form-control @error('last_name') is-invalid @enderror"
                                placeholder="Nama Belakang" value="{{ old('last_name') }}" required
                                autocomplete="family-name">
                        </div>
                        @error('last_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-group">
                        <div class="input-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            placeholder="Alamat Email" value="{{ old('email') }}" required autocomplete="email">
                    </div>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="input-group">
                        <div class="input-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            placeholder="Kata Sandi" name="password" required autocomplete="new-password" id="password">
                        <div class="toggle-password" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </div>
                    </div>
                    <div class="password-strength">
                        <div class="password-strength-bar" id="passwordStrengthBar"></div>
                    </div>
                    <div class="password-requirements">
                        <div class="requirement" id="lengthReq">
                            <i class="fas fa-times"></i> Minimal 8 karakter
                        </div>
                        <div class="requirement" id="uppercaseReq">
                            <i class="fas fa-times"></i> Ada huruf kapital
                        </div>
                        <div class="requirement" id="numberReq">
                            <i class="fas fa-times"></i> Ada angka
                        </div>
                    </div>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="input-group">
                        <div class="input-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <input type="password" class="form-control" placeholder="Konfirmasi Kata Sandi"
                            name="password_confirmation" required autocomplete="new-password" id="confirmPassword">
                        <div class="toggle-password" id="toggleConfirmPassword">
                            <i class="fas fa-eye"></i>
                        </div>
                    </div>
                    <div id="confirmMessage" class="invalid-feedback" style="display: none;">
                        Kata sandi tidak cocok
                    </div>
                </div>

                <button type="submit" class="btn-register" id="submitButton">Daftar Sekarang</button>

            </form>

            <div class="login-link">
                Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        document.getElementById('toggleConfirmPassword').addEventListener('click', function () {
            const confirmInput = document.getElementById('confirmPassword');
            const icon = this.querySelector('i');

            if (confirmInput.type === 'password') {
                confirmInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                confirmInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        // Password strength checker
        const passwordInput = document.getElementById('password');
        const strengthBar = document.getElementById('passwordStrengthBar');
        const lengthReq = document.getElementById('lengthReq');
        const uppercaseReq = document.getElementById('uppercaseReq');
        const numberReq = document.getElementById('numberReq');
        const confirmPassword = document.getElementById('confirmPassword');
        const confirmMessage = document.getElementById('confirmMessage');
        const submitButton = document.getElementById('submitButton');

        passwordInput.addEventListener('input', checkPasswordStrength);
        confirmPassword.addEventListener('input', checkPasswordMatch);

        function checkPasswordStrength() {
            const password = passwordInput.value;
            let strength = 0;

            // Check length
            if (password.length >= 8) {
                strength += 33;
                lengthReq.classList.add('met');
                lengthReq.innerHTML = '<i class="fas fa-check"></i> At least 8 characters';
            } else {
                lengthReq.classList.remove('met');
                lengthReq.innerHTML = '<i class="fas fa-times"></i> At least 8 characters';
            }

            // Check uppercase letters
            if (/[A-Z]/.test(password)) {
                strength += 33;
                uppercaseReq.classList.add('met');
                uppercaseReq.innerHTML = '<i class="fas fa-check"></i> One uppercase letter';
            } else {
                uppercaseReq.classList.remove('met');
                uppercaseReq.innerHTML = '<i class="fas fa-times"></i> One uppercase letter';
            }

            // Check numbers
            if (/[0-9]/.test(password)) {
                strength += 34;
                numberReq.classList.add('met');
                numberReq.innerHTML = '<i class="fas fa-check"></i> One number';
            } else {
                numberReq.classList.remove('met');
                numberReq.innerHTML = '<i class="fas fa-times"></i> One number';
            }

            // Update strength bar
            strengthBar.style.width = strength + '%';

            if (strength < 33) {
                strengthBar.style.background = '#dc3545';
            } else if (strength < 66) {
                strengthBar.style.background = '#ffc107';
            } else {
                strengthBar.style.background = '#198754';
            }

            checkPasswordMatch();
        }

        function checkPasswordMatch() {
            if (passwordInput.value !== confirmPassword.value) {
                confirmMessage.style.display = 'block';
                confirmPassword.classList.add('is-invalid');
                submitButton.disabled = true;
                submitButton.style.opacity = '0.7';
                submitButton.style.cursor = 'not-allowed';
            } else {
                confirmMessage.style.display = 'none';
                confirmPassword.classList.remove('is-invalid');
                submitButton.disabled = false;
                submitButton.style.opacity = '1';
                submitButton.style.cursor = 'pointer';
            }
        }
    </script>
</body>

</html>