<?php

session_start();

$token = $_GET['token'];
$token_hash = hash("sha256", $token);

$conn = require_once 'config.php';

$sql = "SELECT * FROM users
        WHERE account_activation_hash = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token_hash);
$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if ($user === null) {
    die("Invalid activation token");
}

$stmt = "UPDATE users
        SET account_activation_hash = NULL
        WHERE id = ?";

$stmt = $conn->prepare($stmt);
$stmt->bind_param("s", $user['id']);
$stmt->execute();

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

    <div class="container">
        <div class="pwd-reset-box">
            <h2>Account activated!</h2>
            <p>You can now <a href="./index.php">log in</a>.</p>
        </div>
    </div>

    <div class="footer">
        <img src="./assets/images/logo_black_transparent.webp" width="200px" onclick="frog()">
    </div>

    <script src="main.js"></script>
</body>
</html>