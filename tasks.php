<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
require_once 'db.php';

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM tasks WHERE user_id = $user_id ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Tasks</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        form label {
            display: block;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container dashboard">
        <h2>My Tasks</h2>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="tasks.php">My Tasks</a>
            <a href="logout.php">Logout</a>
        </nav>
        <!-- Form to add a new task with due date -->
        <div class="task-form">
            <form action="add_task.php" method="POST">
                <input type="text" name="task" placeholder="Enter a new task" required>
                <label for="due_date">Due Date:</label>
                <input type="date" name="due_date" id="due_date">
                <button type="submit">Add Task</button>
            </form>
        </div>
        <!-- Display list of tasks -->
        <table>
            <thead>
                <tr>
                    <th>Task</th>
                    <th>Status</th>
                    <th>Due Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): 
                    while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['task']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td>
                            <?php 
                            if (isset($row['due_date']) && !empty($row['due_date'])) {
                                echo date('F j, Y', strtotime($row['due_date']));
                            } else {
                                echo 'N/A';
                            }
                            ?>
                        </td>
                        <td>
                            <a href="edit_task.php?id=<?php echo $row['id']; ?>">Edit</a> | 
                            <a href="delete_task.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this task?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; else: ?>
                    <tr>
                        <td colspan="4">No tasks found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
