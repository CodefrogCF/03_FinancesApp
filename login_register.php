<?php

session_start();

require_once 'config.php';

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = 'user';

    $errors = [];

    $checkEmail = $conn->query("SELECT email FROM users WHERE email = '$email'");
    if ($checkEmail->num_rows > 0) {
        $errors[] = "Email already registered!";
    }

    if (!preg_match("/^[a-zA-Z0-9_]+$/", $name)) {
        $errors[] = "Username can only contain letters, numbers, and underscores!";
    }

    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long!";
    }

    if (!preg_match("/[a-z]/i", $password)) {
        $errors[] = "Password must contain at least one lowercase letter!";
    }

    if (!preg_match("/[A-Z]/i", $password)) {
        $errors[] = "Password must contain at least one uppercase letter!";
    }

    if (!preg_match("/[0-9]/i", $password)) {
        $errors[] = "Password must contain at least one number!";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match!";
    }

    if (!empty($errors)) {
        $_SESSION['register-errors'] = $errors;
        $_SESSION['active_form'] = 'register';
        header("Location: index.php");
        exit();
    }

    $password_hashed = password_hash($password, PASSWORD_DEFAULT);
    $task_db = "tasks_$name";
    $conn->query("INSERT INTO users (name, email, password, role, user_db) VALUES ('$name', '$email', '$password_hashed', '$role', '$task_db')");
    $conn->query("CREATE TABLE `$task_db` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT , `task_name` VARCHAR(255) NOT NULL , `task_value` FLOAT NOT NULL , `task_schedule` VARCHAR(20) NOT NULL , `task_isActive` VARCHAR(10) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;");
    $conn->query("INSERT INTO $task_db (task_name, task_value, task_schedule, task_isActive) VALUES ('$name-Test-Task', '12.34', 'monthly', 'inactive')");

    $_SESSION['register-success'] = "Account created successfully!";
    $_SESSION['active_form'] = 'login';
    header("Location: index.php");
    exit();
}

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email = '$email'");
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            
            if ($user['role'] === 'admin') {
                header("Location: admin_page.php");
            } else {
                header("Location: user_page.php");
            }
            exit();
        }
    }

    $_SESSION['login_error'] = 'Incorrect email or password!';
    $_SESSION['active_form'] = 'login';
    header("Location: index.php");
    exit();
}


?>