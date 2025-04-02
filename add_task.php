<?php

session_start();

$conn = require_once 'config.php';

$name = $_SESSION['name'];

$task_name = $_POST['task_name'];
$task_value = $_POST['task_value'];
$task_schedule = $_POST['task_schedule'];
$task_isActive = $_POST['task_isActive'];
$task_db = "tasks_$name";

$errors = [];

if (!preg_match("/^[a-zA-Z0-9_]+$/", $task_name)) {
    $errors[] = "Task name can only contain letters, numbers, and underscores!";
}

if (!preg_match("/^[0-9.]+$/", $task_value)) {
    $errors[] = "Task value can only contain numbers and dots!";
}

if (!preg_match("/^[a-z]+$/", $task_schedule)) {
    $errors[] = "Task schedule can only contain letters!";
}

if (!preg_match("/^[a-z]+$/", $task_isActive)) {
    $errors[] = "Task isActive can only contain letters!";
}

if (!empty($errors)) {
    $_SESSION['add-task-errors'] = $errors;
    $_SESSION['active_form'] = 'add-task';
    header("Location: user_page.php");
    exit();
}

$stmt = "INSERT INTO $task_db (task_name, task_value, task_schedule, task_isActive) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($stmt);
$stmt->bind_param("ssss", $task_name, $task_value, $task_schedule, $task_isActive);
$stmt->execute();

$_SESSION['add-task-success'] = "Task created successfully!";
$_SESSION['active_form'] = 'welcome';
header("Location: user_page.php");
exit();

?>