<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'], $_POST['task'], $_POST['status'])) {
    $id      = intval($_POST['id']);
    $user_id = $_SESSION['user_id'];
    $task    = $conn->real_escape_string(trim($_POST['task']));
    $status  = $conn->real_escape_string($_POST['status']);
    $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : null;
    $due_date_sql = $due_date ? "'$due_date'" : "NULL";
    
    $sql = "UPDATE tasks SET task = '$task', status = '$status', due_date = $due_date_sql WHERE id = $id AND user_id = $user_id";
    if ($conn->query($sql) === TRUE) {
        header("Location: tasks.php");
        exit();
    } else {
        echo "Error updating task: " . $conn->error;
    }
}
?>
