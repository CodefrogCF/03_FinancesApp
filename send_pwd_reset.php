<?php

session_start();

$email = $_POST['email'];
$token = bin2hex(random_bytes(16));
$token_hash = hash("sha256", $token);
$expiry = date("Y-m-d H:i:s", time() + 60 * 30);
$conn = require_once 'config.php';

$sql = "UPDATE users
        SET reset_token_hash = ?,
            reset_token_expires_at = ?
        WHERE email = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $token_hash, $expiry, $email);

if ($stmt->execute()) {
    $_SESSION['reset-success'] = "Password reset token created successfully!";
    $_SESSION['active_form'] = 'login';
    header("Location: index.php");
    exit();
};

$stmt->close();
$conn->close();

?>