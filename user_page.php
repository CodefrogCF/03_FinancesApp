<?php

session_start();
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

require_once 'config.php';
$name = $_SESSION['name'];
$tasks = $conn->query("SELECT task_name, task_value, task_schedule, task_isActive FROM `usr_web33_2`.`tasks_$name`");

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
        <a href="#"><img src="./assets/images/logo-192x192.webp" class="logo"></a>
        <a href="#"><h1>FinancesApp</h1></a>
    </div>

    <div class="container">
        <div class="page-container">
            <div class="box">
                <h1 class="welcome-text">Welcome, <span><?= $_SESSION['name'] ?></span></h1>
                <p>This is an <span>user</span> page</p>
                <p><?php while ($row = $tasks->fetch_assoc()) {echo(implode(", ", $row)."<br>");} ?></p>
                <button onclick="window.location.href='logout.php'">Logout</button>
            </div>
        </div>
    </div>

    <div class="footer">
        <img src="./assets/images/logo_black_transparent.webp" width="200px" onclick="frog()">
    </div>

    <script src="main.js"></script>
</body>

</html>