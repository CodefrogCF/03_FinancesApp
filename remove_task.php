<?php

session_start();

$conn = require_once 'config.php';

$name = $_SESSION['name'];

$task_index = $_POST['task_index'];
$task_db = "tasks_$name";

$errors = [];

$stmt = $conn->prepare("SELECT id FROM $task_db WHERE id = ?");
$stmt->bind_param("i", $task_index);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    $errors[] = "Task index not found!";
}

if (!empty($errors)) {
    $_SESSION['remove-task-errors'] = $errors;
    $_SESSION['active_form'] = 'welcome';
    header("Location: user_page.php");
    exit();
}

$stmt->close();

$stmt = $conn->prepare("DELETE FROM $task_db WHERE id = ?");
$stmt->bind_param("i", $task_index);
$stmt->execute();

$_SESSION['add-task-success'] = "Task removed successfully!";
$_SESSION['active_form'] = 'welcome';
header("Location: user_page.php");
exit();

?>