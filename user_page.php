<?php

session_start();
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

require_once 'config.php';

$name = $_SESSION['name'];
$tasks = $conn->query("SELECT task_name, task_value, task_schedule, task_isActive FROM `usr_web33_2`.`tasks_$name`");

$taskSuccess = $_SESSION['add-task-success'] ?? '';
$activeForm = $_SESSION['active_form'] ?? 'welcome';
$errors = $_SESSION['add-task-errors'] ?? [];
unset($_SESSION['add-task-errors']);

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

        <div class="container">
            <?php if ($activeForm === 'welcome'): ?>
            <div class="page-container" id="page-container">
                <div class="box <?= isActiveForm('welcome', $activeForm); ?>" id="welcome">
                    <center><h1 class="welcome-text">Welcome, <span><?= $_SESSION['name'] ?></span></h1></center>
                    <!--<p>This is an <span>user</span> page</p>-->
                    <?= showSuccess($taskSuccess); ?>

                    <table>
                        <tr>
                            <td>task_name</td>
                            <td>task_value</td>
                            <td>task_schedule</td>
                            <td>task_isActive</td>
                        </tr>
                        <?PHP
                        while ($row = $tasks->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?=$row['task_name']?></td>
                            <td><?=$row['task_value']?></td>
                            <td><?=$row['task_schedule']?></td>
                            <td><?=$row['task_isActive']?></td>
                        </tr>
                        <?PHP
                        }
                        ?>
                    </table>

                    <!--<p><?php /*while ($row = $tasks->fetch_assoc()) {echo(implode(", ", $row)."<br>");}*/ ?></p>-->
                    <button class="btn-primary" onclick="showForm('add-task')">Add task</button>
                    <button class="btn-primary" onclick="window.location.href='logout.php'">Logout</button>
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
                    <button class="btn-primary" type="submit" name="add-task">Add task</button>
                    <!--<button class="btn-reset" type="reset">Reset</button>-->
                    <button class="btn-primary" type="button" onclick="showForm('welcome')">Back</button>
                </form>
            </div>
        </div>

    <!--<div class="footer">
        <img src="./assets/images/logo_black_transparent.webp" width="200px" onclick="frog()">
    </div>-->

    <script src="main.js"></script>

</body>

</html>