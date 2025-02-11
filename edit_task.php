<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
require_once 'db.php';

if (!isset($_GET['id'])) {
    header("Location: tasks.php");
    exit();
}
$task_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM tasks WHERE id = $task_id AND user_id = $user_id";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    echo "Task not found.";
    exit();
}
$task = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Task</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .dashboard nav {
            margin-bottom: 20px;
            text-align: center;
        }
        .dashboard nav a {
            margin: 0 15px;
            text-decoration: none;
            color: #007bff;
            font-weight: 500;
        }
        .dashboard nav a:hover {
            text-decoration: underline;
        }
        .container {
            margin-top: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        form label {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container dashboard">
        <h2>Edit Task</h2>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="tasks.php">My Tasks</a>
            <a href="logout.php">Logout</a>
        </nav>
        <form action="update_task.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $task['id']; ?>">
            <label for="task">Task:</label>
            <input type="text" name="task" id="task" value="<?php echo htmlspecialchars($task['task']); ?>" required>
            
            <label for="due_date">Due Date:</label>
            <input type="date" name="due_date" id="due_date" value="<?php echo isset($task['due_date']) ? $task['due_date'] : ''; ?>">
            
            <label for="status">Status:</label>
            <select name="status" id="status">
                <option value="pending" <?php echo $task['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="completed" <?php echo $task['status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
            </select>
            <button type="submit">Update Task</button>
        </form>
    </div>
</body>
</html>
