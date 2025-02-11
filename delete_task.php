<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
require_once 'db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'];
    $sql = "DELETE FROM tasks WHERE id = $id AND user_id = $user_id";
    if ($conn->query($sql) === TRUE) {
        header("Location: tasks.php");
        exit();
    } else {
        echo "Error deleting task: " . $conn->error;
    }
}
?>
