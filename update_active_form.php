<?php
session_start();

$data = json_decode(file_get_contents('php://input'), true);
if (isset($data['active_form'])) {
    $_SESSION['active_form'] = $data['active_form'];
}
?>
