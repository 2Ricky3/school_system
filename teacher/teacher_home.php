<?php
session_start();

// Check if the teacher is logged in
if (!isset($_SESSION['teacher_username'])) {
    header("Location: teacher_login.php");
    exit;
}

// Get the logged-in teacher's username
$teacher_username = $_SESSION['teacher_username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #0439D9;
            color: white;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #056CF2;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar a {
            color: white;
            padding: 10px;
            text-decoration: none;
            border-bottom: 3px solid transparent;
            transition: border-bottom 0.3s ease-in-out;
        }

        .navbar a.active, .navbar a:hover {
            border-bottom: 3px solid #F2CB05;
        }

        .content {
            padding: 20px;
            text-align: center;
        }

        h1 {
            color: #F2CB05;
            font-size: 36px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div>
            <a href="teacher_home.php" class="active">Home</a>
            <a href="teacher_profile.php">Profile</a>
        </div>
        <a href="teacher_logout.php">Log out</a>
    </div>
    <div class="content">
        <h1>Welcome, <?php echo htmlspecialchars($teacher_username); ?>!</h1>
        <p>This is your teacher dashboard.</p>
    </div>
</body>
</html>
