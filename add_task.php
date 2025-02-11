<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['task'])) {
    $user_id = $_SESSION['user_id'];
    $task    = $conn->real_escape_string(trim($_POST['task']));
    $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : null;
    $due_date_sql = $due_date ? "'$due_date'" : "NULL";
    
    $sql = "INSERT INTO tasks (user_id, task, due_date) VALUES ($user_id, '$task', $due_date_sql)";
    if ($conn->query($sql) === TRUE) {
        header("Location: tasks.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
