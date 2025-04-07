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

$stmt->execute();

if ($conn->affected_rows) {

    $mail = require __DIR__ . '/mailer.php';

    $mail->setFrom($_ENV['MAIL_USERNAME']);
    $mail->addAddress($email);
    $mail->Subject = "Password reset";
    $mail->Body = <<<END

    Click <a href="https://frogfinances.tech/reset_pwd.php?token=$token">here</a> to reset your password.

    END;

    try {
        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

}

$_SESSION['reset-success'] = "Your password reset link has been sent to your email address.";
$_SESSION['active_form'] = 'login';
header("Location: index.php");
exit();

$stmt->close();
$conn->close();

?>