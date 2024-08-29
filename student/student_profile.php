<?php
session_start();

// Check if the student is logged in
if (!isset($_SESSION['student_username'])) {
    header("Location: student_login.php");
    exit;
}

$student_username = $_SESSION['student_username'];
$current_page = 'profile'; // Set the current page to 'profile'

// Fetch student profile information from the database (example only)
include('../includes/db_connection.php');
$sql = "SELECT * FROM Student WHERE Username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $student_username);
$stmt->execute();
$result = $stmt->get_result();
$student_info = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #0439D9;
            color: white;
            margin: 0;
            padding: 0;
        }

        .navbar {
            display: flex;
            justify-content: space-around;
            background-color: #056CF2;
            padding: 10px 0;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .navbar a.active, .navbar a:hover {
            background-color: #F2CB05;
            color: #0439D9;
        }

        .content {
            padding: 20px;
            text-align: center;
        }

        h1 {
            font-size: 36px;
            margin-bottom: 20px;
        }

        .profile-info {
            font-size: 24px;
            margin-bottom: 10px;
            text-align: left;
            display: inline-block;
        }

        .profile-info span {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="student_home.php" class="<?php echo ($current_page == 'home') ? 'active' : ''; ?>">Home</a>
        <a href="student_profile.php" class="<?php echo ($current_page == 'profile') ? 'active' : ''; ?>">Profile</a>
    </div>

    <div class="content">
        <h1>Profile: <?php echo htmlspecialchars($student_username); ?></h1>
        <div class="profile-info">
            <p><span>Full Name:</span> <?php echo htmlspecialchars($student_info['FullName']); ?></p>
            <p><span>Email:</span> <?php echo htmlspecialchars($student_info['Email']); ?></p>
            <p><span>Enrollment Date:</span> <?php echo htmlspecialchars($student_info['EnrollmentDate']); ?></p>
            <!-- Add more profile-specific information here -->
        </div>
    </div>
</body>
</html>
