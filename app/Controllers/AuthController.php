<?php
namespace App\Controllers;
use App\Models\UserModel;
class AuthController {
    public function showRegisterForm() {
        return view('auth/register', ['title' => 'Register']);
    }

public function register() {
    // Ensure the session is started before we use it
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // 1. Get data from the form
    $email = $_POST['email'] ?? null;
    $username = $_POST['username'] ?? null;
    $password = $_POST['password'] ?? null;
    $password_confirm = $_POST['password_confirm'] ?? null;

    // 2. Validate all the data
    if (empty($email) || empty($username) || empty($password) || empty($password_confirm)) {
        die('All fields are required.');
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die('Invalid email format.');
    }
    if ($password !== $password_confirm) {
        die('Passwords do not match.');
    }

    // 3. Instantiate the model *before* using it
    $userModel = new UserModel();

    // 4. Check if the email or username is already taken
    if ($userModel->findByEmail($email)) {
        die('An account with this email already exists.');
    }
    if ($userModel->findByUsername($username)) {
        die('This username is already taken.');
    }
    
    // 5. Prepare the data array to send to the model
    $userData = [
        'email' => $email,
        'username' => $username,
        'password' => $password // We pass the plain password, the model will hash it
    ];
    
    // 6. Call the model's create function with the correct argument (a single array)
    $userId = $userModel->create($userData);

    // 7. Handle success or failure
    if ($userId) {
        // Log the user in automatically upon successful registration
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_email'] = $email;
        $_SESSION['username'] = $username; // Add the username to the session
        $_SESSION['is_admin'] = 0; // New users are standard users
        
        // Redirect to the homepage
        header("Location: /");
        exit();
    } else {
        die("An error occurred during registration. Please try again.");
    }
}

    public function showLoginForm() {
        return view('auth/login', ['title' => 'Login']);
    }

    public function login() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            die('Email and password are required.');
        }

        $userModel = new UserModel();
        $user = $userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password_hash'])) {
            // Şifre doğru, giriş başarılı.
            // Kullanıcı bilgilerini ve YETKİSİNİ session'a kaydet.
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['is_admin'] = $user['is_admin']; // <-- YAPILAN DEĞİŞİKLİK BURADA

            // Ana sayfaya yönlendir
            header("Location: /");
            exit();
        } else {
            // Hatalı giriş
            die('Invalid email or password.');
        }
    }

    public function logout() {
        // Session'ı başlatmadan önce kontrol et
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Tüm session verilerini temizle
        session_unset();
        // Session'ı sonlandır
        session_destroy();

        // Kullanıcıyı ana sayfaya yönlendir
        header('Location: /');
        exit();
    }
}