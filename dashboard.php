<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
require_once 'db.php';

$user_id  = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Get total tasks count
$sqlTotal = "SELECT COUNT(*) AS total_tasks FROM tasks WHERE user_id = $user_id";
$resultTotal = $conn->query($sqlTotal);
$total_tasks = ($resultTotal && $resultTotal->num_rows > 0) ? $resultTotal->fetch_assoc()['total_tasks'] : 0;

// Get pending tasks count
$sqlPending = "SELECT COUNT(*) AS pending_tasks FROM tasks WHERE user_id = $user_id AND status = 'pending'";
$resultPending = $conn->query($sqlPending);
$pending_tasks = ($resultPending && $resultPending->num_rows > 0) ? $resultPending->fetch_assoc()['pending_tasks'] : 0;

// Get completed tasks count
$sqlCompleted = "SELECT COUNT(*) AS completed_tasks FROM tasks WHERE user_id = $user_id AND status = 'completed'";
$resultCompleted = $conn->query($sqlCompleted);
$completed_tasks = ($resultCompleted && $resultCompleted->num_rows > 0) ? $resultCompleted->fetch_assoc()['completed_tasks'] : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - To-Do List</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Global Styles */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            color: #333;
        }
        /* Navigation */
        nav {
            background: #ffffff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }
        nav li {
            margin-right: 20px;
        }
        nav a {
            text-decoration: none;
            color: #007bff;
            padding: 5px 10px;
            transition: background 0.3s;
            border-radius: 4px;
        }
        nav a:hover {
            background: #e2e6ea;
        }
        /* Dashboard Header */
        .dashboard-header {
            text-align: center;
            padding: 20px;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: white;
        }
        .dashboard-header h1 {
            margin: 0;
            font-size: 2.5em;
        }
        .dashboard-header p {
            margin: 5px 0 0;
            font-size: 1.2em;
        }
        /* Dashboard Content */
        .dashboard-content {
            padding: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }
        /* Statistics Section */
        .stats {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            margin-bottom: 30px;
        }
        .stat {
            background: white;
            padding: 20px;
            margin: 10px;
            flex: 1 1 250px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat h2 {
            margin: 0;
            font-size: 2em;
            color: #007bff;
        }
        .stat p {
            margin: 5px 0 0;
            font-size: 1.2em;
        }
        /* Task List Sections */
        .task-list {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .task-list h2 {
            margin-top: 0;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        .task-item {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .task-item:last-child {
            border-bottom: none;
        }
        .task-item input[type="checkbox"] {
            margin-right: 15px;
            transform: scale(1.2);
        }
        /* Founders Section */
        .founders {
            text-align: center;
            margin: 40px 0;
        }
        .founders h2 {
            margin-bottom: 20px;
        }
        .founders .founder {
            display: inline-block;
            margin: 20px;
        }
        .founders img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 2px solid #007bff;
            margin-bottom: 10px;
        }
        /* Footer */
        footer {
            background: #343a40;
            color: white;
            text-align: center;
            padding: 20px 10px;
        }
        footer p {
            margin: 0;
            font-size: 14px;
        }
        /* Responsive */
        @media (max-width: 768px) {
            .stats {
                flex-direction: column;
                align-items: center;
            }
            .stat {
                width: 80%;
            }
            .founders .founder {
                margin: 20px;
            }
        }
    </style>
</head>
<body>
    <nav>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="tasks.php">Tasks</a></li>
        </ul>
        <ul>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
    
    <header class="dashboard-header">
        <h1>Welcome, <?php echo htmlspecialchars($username); ?></h1>
        <p>Your Personal To-Do List Dashboard</p>
    </header>
    
    <div class="dashboard-content">
        <!-- Statistics Section -->
        <section class="stats">
            <div class="stat">
                <h2><?php echo $total_tasks; ?></h2>
                <p>Total Tasks</p>
            </div>
            <div class="stat">
                <h2><?php echo $pending_tasks; ?></h2>
                <p>Pending Tasks</p>
            </div>
            <div class="stat">
                <h2><?php echo $completed_tasks; ?></h2>
                <p>Completed Tasks</p>
            </div>
        </section>
        
        <!-- Pending Tasks List -->
        <section class="task-list">
            <h2>Pending Tasks</h2>
            <?php
            $sqlPendingList = "SELECT task FROM tasks WHERE user_id = $user_id AND status = 'pending' ORDER BY created_at DESC";
            $resultPendingList = $conn->query($sqlPendingList);
            if ($resultPendingList && $resultPendingList->num_rows > 0) {
                while ($row = $resultPendingList->fetch_assoc()) {
                    echo '<div class="task-item"><input type="checkbox"> <span>' . htmlspecialchars($row['task']) . '</span></div>';
                }
            } else {
                echo '<p>No pending tasks available.</p>';
            }
            ?>
        </section>
        
        <!-- Completed Tasks List -->
        <section class="task-list">
            <h2>Completed Tasks</h2>
            <?php
            $sqlCompletedList = "SELECT task FROM tasks WHERE user_id = $user_id AND status = 'completed' ORDER BY created_at DESC";
            $resultCompletedList = $conn->query($sqlCompletedList);
            if ($resultCompletedList && $resultCompletedList->num_rows > 0) {
                while ($row = $resultCompletedList->fetch_assoc()) {
                    echo '<div class="task-item"><input type="checkbox" checked> <span>' . htmlspecialchars($row['task']) . '</span></div>';
                }
            } else {
                echo '<p>No completed tasks available.</p>';
            }
            ?>
        </section>
        
        <!-- Founders Section -->
        <section class="founders">
            <h2>Our Founders</h2>
            <div class="founder">
                <img src="image1.png" alt="Pratik Deuja">
                <p>Pratik Deuja</p>
            </div>
            <div class="founder">
                <img src="image2.jpg" alt="Bibash Sharma">
                <p>Bibash Sharma</p>
            </div>
            <div class="founder">
                <img src="image3.jpg" alt="Bikash Lama">
                <p>Bikash Lama</p>
            </div>
            <div class="founder">
                <img src="image4.png" alt="Ashish Adhikari">
                <p>Ashish Adhikari</p>
            </div>
        </section>
    </div>
    
    <footer>
        <p>&copy; 2025 To-Do List. All rights reserved.</p>
    </footer>
</body>
</html>
