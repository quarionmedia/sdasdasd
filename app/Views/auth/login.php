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
            max-width: 500px;
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

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            color: #b3b3b3;
        }

        .checkbox {
            margin-right: 8px;
            accent-color: #42ca1a;
        }

        .forgot-link {
            color: #42ca1a;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .forgot-link:hover {
            color: #5ee627;
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

        .signup-link {
            text-align: center;
            color: #b3b3b3;
            font-size: 14px;
        }

        .signup-link a {
            color: #42ca1a;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .signup-link a:hover {
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
        
        <div class="welcome-text">Welcome back</div>
        <div class="subtitle">Sign in to your account</div>

        <form action="/login" method="POST" id="loginForm">
            <div class="form-group">
                <label for="email">Email Address</label>
                <div class="input-wrapper">
                    <input type="email" name="email" id="email" class="input-field" required>
                    <i class="fas fa-envelope input-icon"></i>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <input type="password" name="password" id="password" class="input-field" required>
                    <i class="fas fa-lock input-icon"></i>
                </div>
            </div>

            <div class="form-options">
                <label class="checkbox-wrapper">
                    <input type="checkbox" name="remember_me" class="checkbox">
                    Remember me
                </label>
                <a href="#" class="forgot-link">Forgot password?</a>
            </div>

            <button type="submit" class="submit-btn">
                <span>Sign In</span>
            </button>
        </form>

        <div class="divider">
            <span>or</span>
        </div>

        <div class="signup-link">
            Don't have an account? <a href="/register">Create one</a>
        </div>
    </div>

    <script>
        // Form submission with loading state
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const submitBtn = document.querySelector('.submit-btn');
            const btnText = submitBtn.querySelector('span');
            
            submitBtn.classList.add('loading');
            btnText.textContent = 'Signing in...';
        });

        // Enhanced input animations
        document.querySelectorAll('.input-field').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.parentElement.style.transform = 'scale(1)';
            });
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
    </script>
</body>
</html>