<?php

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$host = $_ENV['SQL_HOST'];
$user = $_ENV['SQL_USER'];
$password = $_ENV['SQL_PASSWORD'];
$database = $_ENV['SQL_DATABASE'];

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

return $conn;

?>