<?php

session_start();

$token = $_POST['token'];
$token_hash = hash("sha256", $token);

$conn = require_once 'config.php';

$sql = "SELECT * FROM users
        WHERE reset_token_hash = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token_hash);
$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

$password = $_POST['password'];
$confirm_password = $_POST['password_confirmation'];

$errors = [];

if ($user === null) {
    $errors[] = "Invalid token";
}

if (strtotime($user["reset_token_expires_at"]) <= time()) {
    $errors[] = "Token expired";
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
    $_SESSION['password-errors'] = $errors;
    header("Location: reset_pwd.php?token=$token");
    exit();
}

$password_hashed = password_hash($password, PASSWORD_DEFAULT);

$sql = "UPDATE users
        SET password = ?,
            reset_token_hash = NULL,
            reset_token_expires_at = NULL
        WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $password_hashed, $user['id']);
$stmt->execute();

$_SESSION['reset-success'] = "Your password has been reset.";
header("Location: index.php");
exit();

$stmt->close();
$conn->close();

?>