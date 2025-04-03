<?php

session_start();
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

require_once 'config.php';

$name = $_SESSION['name'];
$tasks = $conn->query("SELECT id, task_name, task_value, task_schedule, task_isActive FROM `usr_web33_2`.`tasks_$name`");
$tasksValue = $conn->query("SELECT SUM(task_value) AS total_value FROM `usr_web33_2`.`tasks_$name` WHERE task_isActive = 'active'");
$sumRow = $tasksValue->fetch_assoc();
$totalValue = number_format($sumRow['total_value'], 2, '.', '');

$taskNamesResult = $conn->query("SELECT id, task_name FROM `usr_web33_2`.`tasks_$name`");
$taskNames = [];
while ($row = $taskNamesResult->fetch_assoc()) {
    $taskNames[] = ['id' => $row['id'], 'name' => $row['task_name']];
}

$taskSuccess = $_SESSION['add-task-success'] ?? '';
$activeForm = $_SESSION['active_form'] ?? 'welcome';
$errors = $_SESSION['add-task-errors'] ?? [];
unset($_SESSION['add-task-errors']);
$errorsRemoveTask = $_SESSION['remove-task-errors'] ?? [];
unset($_SESSION['remove-task-errors']);


function showSuccess($message) {
    return !empty($message) ? "<p class='success-message' id='success-message'>$message</p>" : '';
}

function showError($error) {
    return !empty($error) ? "<p class='error-message' id='error-message'>$error</p>" : '';
}

function isActiveForm($formName, $activeForm) {
    return $formName === $activeForm ? 'active' : '';
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

        <div class="user-container">

            <?php if ($activeForm === 'welcome'): ?>
            <div class="page-container" id="page-container">
                <div class="box <?= isActiveForm('welcome', $activeForm); ?>" id="welcome">
                    <center><h1 class="welcome-text">Welcome, <span><?= $_SESSION['name'] ?></span></h1></center>
                    <p>Total Value of <span>Active</span> Tasks: <?= $totalValue ?>$</p>
                    <?= showSuccess($taskSuccess); ?>
                    <?php
                    if (!empty($errorsRemoveTask)) {
                        foreach ($errorsRemoveTask as $error) {
                            echo showError($error);
                        }
                    }
                    ?>

                    <table class="table">
                        <tr>
                            <!--<td>id</td>-->
                            <td>task</td>
                            <td>value</td>
                            <td>schedule</td>
                            <!--<td>isActive</td>-->
                        </tr>
                        <?PHP
                        while ($row = $tasks->fetch_assoc()) {
                        ?>
                        <tr>
                            <!--<td></?=$row['id']?></td>-->
                            <td><?=$row['task_name']?></td>
                            <td><?=number_format($row['task_value'], 2, '.', '')?>$</td>
                            <td><?=$row['task_schedule']?></td>
                            <!--<td></?=$row['task_isActive']?></td>-->
                        </tr>
                        <?PHP
                        }
                        ?>
                    </table>

                    <div class="btn-container">
                        <button class="btn-primary" onclick="window.location.href='logout.php'">Logout</button>
                        <button class="btn-reset" onclick="showForm('remove-task')">Remove</button>
                        <button class="btn-primary" onclick="showForm('add-task')">Add</button>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="form-box <?= isActiveForm('add-task', $activeForm); ?>" id="add-task">
                <?php
                if (!empty($errors)) {
                    foreach ($errors as $error) {
                        echo showError($error);
                    }
                }
                ?>
                <form action="add_task.php" method="post">
                    <input type="text" placeholder="Task name" name="task_name" required>
                    <input type="float" placeholder="Task value" name="task_value" required>
                    <select name="task_schedule" required>
                        <option value="onetime">Onetime</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                        <option value="quarterly">Quarterly</option>
                        <option value="yearly">Yearly</option>
                    </select>
                    <input type="text" value="active" name="task_isActive" hidden required>
                    <div class="btn-container">
                        <!--<button class="btn-reset" type="reset">Reset</button>-->
                        <button class="btn-primary" type="button" onclick="showForm('welcome')">Back</button>
                        <button class="btn-primary" type="submit" name="add-task">Add</button>
                    </div>
                </form>
            </div>

            <div class="form-box <?= isActiveForm('remove-task', $activeForm); ?>" id="remove-task">
                <form action="remove_task.php" method="post">
                    <select name="task_name" required>
                        <option value="" disabled selected>Select Task</option>
                        <?php foreach ($taskNames as $task): ?>
                        <option value="<?= $task['id'] ?>"><?= $task['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="btn-container">
                        <!--<button class="btn-primary" onclick="window.location.href='logout.php'">Logout</button>-->
                        <button class="btn-primary" type="button" onclick="showForm('welcome')">Back</button>
                        <button class="btn-reset" type="submit" name="remove-task">Remove</button>
                    </div>
                </form>
            </div>

        </div>

    <div class="footer">
        <img src="./assets/images/logo_black_transparent.webp" width="200px" onclick="frog()">
    </div>

    <script src="main.js"></script>

</body>

</html>