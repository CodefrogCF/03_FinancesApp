<?php

session_start();

$token = $_GET['token'];
$token_hash = hash("sha256", $token);

$conn = require_once 'config.php';

$sql = "SELECT * FROM users
        WHERE reset_token_hash = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token_hash);
$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

$errors = $_SESSION['password-errors'] ?? [];
unset($_SESSION['password-errors']);

function showError($error) {
    return !empty($error) ? "<p class='error-message'>$error</p>" : '';
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

    <div class="container">
        <div class="pwd-reset-box">
            <h2>Reset Password</h2>
            <?php
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    echo showError($error);
                }
            }
            ?>
            <form action="process_reset_pwd.php" method="post">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                <input type="password" id="password" autocomplete="off" spellcheck="false" placeholder="New password" name="password" required>
                <input type="password" id="password_confirmation" autocomplete="off" spellcheck="false" placeholder="Confirm password" name="password_confirmation" required>
                <button class="btn-primary" type="submit" name="reset-password">Reset Password</button>
            </form>
        </div>
    </div>

    <div class="footer">
        <img src="./assets/images/logo_black_transparent.webp" width="200px" onclick="frog()">
    </div>

    <script src="main.js"></script>
</body>
</html>