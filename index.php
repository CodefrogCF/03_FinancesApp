<?php

session_start();

if (isset($_SESSION['email'])) {
    header("Location: user_page.php");
    exit();
}

$errors = [
    'login' => $_SESSION['login_error'] ?? '',
    'register' => $_SESSION['register-errors'] ?? []
];

$successMessage = $_SESSION['register-success'] ?? '';
$resetSuccess = $_SESSION['reset-success'] ?? '';

$activeForm = $_SESSION['active_form'] ?? 'login';

session_unset();

function showError($error) {
    return !empty($error) ? "<p class='error-message' id='error-message'>$error</p>" : '';
}

function showSuccess($message) {
    return !empty($message) ? "<p class='success-message' id='success-message'>$message</p>" : '';
}

function isActiveForm($formName, $activeForm) {
    return $formName === $activeForm ? 'active' : '';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FrogFinances</title>
    <link rel="icon" href="./assets/favicon.ico">
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <div class="header">
        <a href=""><img src="./assets/images/logo-192x192.webp" class="logo"></a>
        <a href=""><h1>FinancesApp</h1></a>
    </div>

    <div class="main-container">
        <div class="title-card-container">
            <div class="title-card">
                <h1>Welcome to <span>FrogFinances</span></h1>
                <p>A simple finances app to track your spendings</p>
                <p>built with <span>PHP</span>, <span>HTML</span>, and <span>CSS</span>.</p>
                <p>Sign up or log in to get started.</p>
            </div>
        </div>
    </div>

    <div class="container">

        <!-- LOGIN FORM -->
        <div class="form-box <?= isActiveForm('login', $activeForm); ?>" id="login-form">
            <form action="login_register.php" method="post">
                <h2>Login</h2>
                <?= showSuccess($successMessage); ?>
                <?= showSuccess($resetSuccess); ?>
                <?= showError($errors['login']); ?>
                <input type="email" autocomplete="email" placeholder="Email" name="email" required>
                <input type="password" autocomplete="off" spellcheck="false" placeholder="Password" name="password" required>
                <div class="btn-container">
                    <button class="btn-primary" type="submit" name="login">Login</button>
                </div>
                <p>Don't have an account? <a href="#" onclick="showFormLandingPage('register-form')">Sign Up</a></p>
                <p>Forgot password? <a href="#" onclick="showFormLandingPage('forgot-password-form')">Click here</a></p>
            </form>
        </div>

        <!-- REGISTER FORM -->
        <div class="form-box <?= isActiveForm('register', $activeForm); ?>" id="register-form">
            <form action="login_register.php" method="post">
                <h2>Register</h2>
                <?php
                if (!empty($errors['register'])) {
                    foreach ($errors['register'] as $error) {
                        echo showError($error);
                    }
                }
                ?>
                <input type="text" autocomplete="username" placeholder="Username" name="name" required>
                <input type="email" autocomplete="email" placeholder="Email" name="email" required>
                <input type="password" autocomplete="off" spellcheck="false" placeholder="Password" name="password" required>
                <input type="password" autocomplete="off" spellcheck="false" placeholder="Confirm password" name="confirm_password" required>
                <div class="btn-container">
                    <button class="btn-primary" type="submit" name="register">Register</button>
                </div>
                <p>Already have an account? <a href="#" onclick="showFormLandingPage('login-form')">Login</a></p>
            </form>
        </div>

        <!-- FORGOT PASSWORD FORM -->
        <div class="form-box <?= isActiveForm('forgot-password', $activeForm); ?>" id="forgot-password-form">
            <form action="send_pwd_reset.php" method="post">
                <h2>Forgot Password</h2>
                <input type="email" autocomplete="email" placeholder="Email" name="email" required>
                <div class="btn-container">
                    <button class="btn-primary" type="submit" name="forgot-password">Submit</button>
                </div>
                <p>Back to the <a href="#" onclick="showFormLandingPage('login-form')">Login</a></p>
            </form>
        </div>

    </div>

    <div class="footer">
        <img src="./assets/images/logo_black_transparent.webp" width="200px" onclick="frog()">
    </div>

    <script src="main.js"></script>
    <script>
        // Ensure the login form is displayed on the first page load
        document.addEventListener('DOMContentLoaded', function() {
            showFormLandingPage('login-form');
        });
    </script>
</body>
</html>
