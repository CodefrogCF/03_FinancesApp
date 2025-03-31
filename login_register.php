<?php

session_start();
require_once 'config.php';

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'user';

    $checkEmail = $conn->query("SELECT email FROM users WHERE email = '$email'");
    if ($checkEmail->num_rows > 0) {
        $_SESSION['register-error'] = "Email already registered!";
        $_SESSION['active_form'] = 'register';
    } else {
        $task_db = "tasks_$name";
        $conn->query("INSERT INTO users (name, email, password, role, user_db) VALUES ('$name', '$email', '$password', '$role', '$task_db')");
        //$conn->query("INSERT INTO users_tasks (user, task_db) VALUES ('$name', '$task_db')");
        $conn->query("CREATE TABLE `usr_web33_2`.`$task_db` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT , `task_name` VARCHAR(255) NOT NULL , `task_value` FLOAT NOT NULL , `task_schedule` VARCHAR(255) NOT NULL , `task_isActive` VARCHAR(10) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;");
        $conn->query("INSERT INTO $task_db (task_name, task_value, task_schedule, task_isActive) VALUES ('$name-Test-Task', '12.34', 'monthly', 'inactive')");
    }

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
            
            if ($user['role'] == 'admin') {
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