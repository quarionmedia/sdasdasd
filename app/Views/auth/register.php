<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <title><?php echo isset($title) ? $title . ' | ' . htmlspecialchars(setting('site_name', 'MuvixTV')) : htmlspecialchars(setting('site_name', 'MuvixTV')); ?></title>
    <link rel="icon" type="image/png" href="/assets/images/<?php echo htmlspecialchars(setting('favicon_path', 'favicon.png')); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Soft floating bubbles */
        .bubble {
            position: absolute;
            border-radius: 50%;
            background: rgba(66, 202, 26, 0.1);
            animation: float-up 6s infinite ease-in-out;
            backdrop-filter: blur(5px);
        }

        .bubble:nth-child(1) { width: 40px; height: 40px; left: 10%; animation-delay: 0s; }
        .bubble:nth-child(2) { width: 20px; height: 20px; left: 20%; animation-delay: 0.5s; }
        .bubble:nth-child(3) { width: 60px; height: 60px; left: 35%; animation-delay: 1s; }
        .bubble:nth-child(4) { width: 80px; height: 80px; left: 50%; animation-delay: 1.5s; }
        .bubble:nth-child(5) { width: 30px; height: 30px; left: 70%; animation-delay: 2s; }
        .bubble:nth-child(6) { width: 50px; height: 50px; left: 85%; animation-delay: 2.5s; }

        @keyframes float-up {
            0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-100px) rotate(360deg); opacity: 0; }
        }

        .container {
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 60px 50px;
            width: 100%;
            max-width: 520px;
            box-shadow: 
                0 8px 32px rgba(0, 0, 0, 0.3),
                0 0 0 1px rgba(66, 202, 26, 0.2);
            position: relative;
            animation: slideIn 0.6s ease-out;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-text {
            font-size: 32px;
            font-weight: 700;
            background: linear-gradient(135deg, #42ca1a, #5ee627);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -1px;
        }

        .welcome-text {
            text-align: center;
            color: #ffffff;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .subtitle {
            text-align: center;
            color: #b3b3b3;
            font-size: 14px;
            margin-bottom: 30px;
        }

        .form-group {
            position: relative;
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #ffffff;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-field {
            width: 100%;
            padding: 16px 20px 16px 50px;
            border: 2px solid #444;
            border-radius: 12px;
            font-size: 16px;
            color: #ffffff;
            background: #1a1a1a;
            transition: all 0.3s ease;
        }

        .input-field:focus {
            outline: none;
            border-color: #42ca1a;
            box-shadow: 0 0 0 3px rgba(66, 202, 26, 0.1);
            transform: translateY(-1px);
        }

        .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            font-size: 16px;
            transition: color 0.3s ease;
        }

        .input-field:focus + .input-icon {
            color: #42ca1a;
        }

        /* Password strength indicator */
        .strength-meter {
            margin-top: 8px;
            height: 4px;
            background: #e2e8f0;
            border-radius: 2px;
            overflow: hidden;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .strength-meter.active {
            opacity: 1;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        .strength-weak { background: linear-gradient(90deg, #fc8181, #f56565); }
        .strength-medium { background: linear-gradient(90deg, #f6ad55, #ed8936); }
        .strength-strong { background: linear-gradient(90deg, #68d391, #48bb78); }

        .strength-text {
            font-size: 12px;
            margin-top: 4px;
            font-weight: 500;
        }

        /* Password match indicator */
        .match-indicator {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0;
            transition: all 0.3s ease;
        }

        .match-indicator.show {
            opacity: 1;
        }

        .match-indicator.success {
            color: #48bb78;
        }

        .match-indicator.error {
            color: #f56565;
        }

        .submit-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #42ca1a, #5ee627);
            border: none;
            border-radius: 12px;
            color: #000000;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            margin-top: 10px;
        }

        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(66, 202, 26, 0.3);
        }

        .submit-btn:hover::before {
            left: 100%;
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .submit-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .terms-text {
            font-size: 12px;
            color: #b3b3b3;
            text-align: center;
            margin-top: 15px;
            line-height: 1.5;
        }

        .terms-text a {
            color: #42ca1a;
            text-decoration: none;
        }

        .terms-text a:hover {
            text-decoration: underline;
        }

        .divider {
            text-align: center;
            margin: 25px 0;
            position: relative;
            color: #a0aec0;
            font-size: 14px;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e2e8f0;
            z-index: 1;
        }

        .divider span {
            background: rgba(0, 0, 0, 0.85);
            padding: 0 15px;
            position: relative;
            z-index: 2;
        }

        .signin-link {
            text-align: center;
            color: #b3b3b3;
            font-size: 14px;
        }

        .signin-link a {
            color: #42ca1a;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .signin-link a:hover {
            color: #5ee627;
        }

        /* Loading state */
        .submit-btn.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .submit-btn.loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Mobile responsiveness */
        @media (max-width: 480px) {
            .container {
                padding: 40px 30px;
                margin: 10px;
                max-width: 90%;
            }
            
            .logo-text {
                font-size: 28px;
            }
            
            .welcome-text {
                font-size: 22px;
            }
        }

        /* Email validation indicator */
        .email-indicator {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0;
            transition: all 0.3s ease;
        }

        .email-indicator.show {
            opacity: 1;
        }

        .email-indicator.valid {
            color: #48bb78;
        }

        /* Smooth transitions for all interactive elements */
        * {
            transition: all 0.3s ease;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(66, 202, 26, 0.3);
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>

    <div class="container">
        <div class="logo">
            <img src="/assets/images/<?php echo htmlspecialchars(setting('logo_path')); ?>" alt="<?php echo htmlspecialchars(setting('site_name')); ?>" style="max-height: 40px;">
        </div>
        
        <div class="welcome-text">Create account</div>
        <div class="subtitle">Join our movie community</div>

        <form action="/register" method="POST" id="registerForm">
            <div class="form-group">
                <label for="email">Email Address</label>
                <div class="input-wrapper">
                    <input type="email" name="email" id="email" class="input-field" required>
                    <i class="fas fa-envelope input-icon"></i>
                    <div class="email-indicator">
                        <i class="fas fa-check"></i>
                    </div>
                </div>
            </div>

            
            <div class="form-group">
                <label for="email">Username</label>
                <div class="input-wrapper">
                    <input type="text" name="username" class="input-field" required>
                    <i class="fas fa-user input-icon"></i>
                    <div class="email-indicator">
                        <i class="fas fa-check"></i>
                    </div>
                </div>
            </div>



            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <input type="password" name="password" id="password" class="input-field" required>
                    <i class="fas fa-lock input-icon"></i>
                </div>
                <div class="strength-meter">
                    <div class="strength-fill"></div>
                </div>
                <div class="strength-text"></div>
            </div>

            <div class="form-group">
                <label for="password_confirm">Confirm Password</label>
                <div class="input-wrapper">
                    <input type="password" name="password_confirm" id="password_confirm" class="input-field" required>
                    <i class="fas fa-lock input-icon"></i>
                    <div class="match-indicator">
                        <i class="fas fa-check"></i>
                    </div>
                </div>
            </div>

            <div class="terms-text">
                By creating an account, you agree to our 
                <a href="#">Terms of Service</a> and 
                <a href="#">Privacy Policy</a>
            </div>

            <button type="submit" class="submit-btn">
                <span>Create Account</span>
            </button>
        </form>

        <div class="divider">
            <span>or</span>
        </div>

        <div class="signin-link">
            Already have an account? <a href="/login">Sign in</a>
        </div>
    </div>

    <script>
        // Form elements
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('password_confirm');
        const submitBtn = document.querySelector('.submit-btn');
        const form = document.getElementById('registerForm');

        // Email validation
        const emailIndicator = document.querySelector('.email-indicator');
        emailInput.addEventListener('input', function() {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (emailRegex.test(this.value)) {
                emailIndicator.classList.add('show', 'valid');
            } else {
                emailIndicator.classList.remove('show', 'valid');
            }
        });

        // Password strength
        const strengthMeter = document.querySelector('.strength-meter');
        const strengthFill = document.querySelector('.strength-fill');
        const strengthText = document.querySelector('.strength-text');

        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            let strengthLabel = '';

            if (password.length >= 8) strength += 25;
            if (/[a-z]/.test(password)) strength += 25;
            if (/[A-Z]/.test(password)) strength += 25;
            if (/[0-9]/.test(password) || /[^a-zA-Z0-9]/.test(password)) strength += 25;

            if (password.length > 0) {
                strengthMeter.classList.add('active');
                strengthFill.style.width = strength + '%';

                if (strength < 50) {
                    strengthFill.className = 'strength-fill strength-weak';
                    strengthLabel = 'Weak password';
                    strengthText.style.color = '#f56565';
                } else if (strength < 75) {
                    strengthFill.className = 'strength-fill strength-medium';
                    strengthLabel = 'Medium password';
                    strengthText.style.color = '#ed8936';
                } else {
                    strengthFill.className = 'strength-fill strength-strong';
                    strengthLabel = 'Strong password';
                    strengthText.style.color = '#48bb78';
                }

                strengthText.textContent = strengthLabel;
            } else {
                strengthMeter.classList.remove('active');
                strengthText.textContent = '';
            }

            checkPasswordMatch();
        });

        // Password match indicator
        const matchIndicator = document.querySelector('.match-indicator');
        
        function checkPasswordMatch() {
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;

            if (confirmPassword.length > 0) {
                matchIndicator.classList.add('show');
                if (password === confirmPassword && password.length > 0) {
                    matchIndicator.classList.add('success');
                    matchIndicator.classList.remove('error');
                    matchIndicator.innerHTML = '<i class="fas fa-check"></i>';
                } else {
                    matchIndicator.classList.add('error');
                    matchIndicator.classList.remove('success');
                    matchIndicator.innerHTML = '<i class="fas fa-times"></i>';
                }
            } else {
                matchIndicator.classList.remove('show', 'success', 'error');
            }
        }

        confirmPasswordInput.addEventListener('input', checkPasswordMatch);

        // Enhanced input animations
        document.querySelectorAll('.input-field').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.parentElement.style.transform = 'scale(1)';
            });
        });

        // Form validation and submission
        form.addEventListener('submit', function(e) {
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;
            const email = emailInput.value;
            const btnText = submitBtn.querySelector('span');
            
            // Validate email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                alert('Please enter a valid email address!');
                emailInput.focus();
                return;
            }
            
            // Validate password strength
            if (password.length < 8) {
                e.preventDefault();
                alert('Password must be at least 8 characters long!');
                passwordInput.focus();
                return;
            }
            
            // Validate password match
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
                confirmPasswordInput.focus();
                return;
            }
            
            // Show loading state
            submitBtn.classList.add('loading');
            btnText.textContent = 'Creating account...';
        });

        // Add floating label effect
        document.querySelectorAll('.input-field').forEach(input => {
            input.addEventListener('input', function() {
                if (this.value.length > 0) {
                    this.style.paddingTop = '20px';
                    this.style.paddingBottom = '12px';
                } else {
                    this.style.paddingTop = '16px';
                    this.style.paddingBottom = '16px';
                }
            });
        });

        // Real-time form validation feedback
        function updateSubmitButton() {
            const emailValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value);
            const passwordValid = passwordInput.value.length >= 8;
            const passwordMatch = passwordInput.value === confirmPasswordInput.value && confirmPasswordInput.value.length > 0;
            
            if (emailValid && passwordValid && passwordMatch) {
                submitBtn.disabled = false;
                submitBtn.style.opacity = '1';
            } else {
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.6';
            }
        }

        emailInput.addEventListener('input', updateSubmitButton);
        passwordInput.addEventListener('input', updateSubmitButton);
        confirmPasswordInput.addEventListener('input', updateSubmitButton);

        // Initialize button state
        updateSubmitButton();
    </script>
</body>
</html>