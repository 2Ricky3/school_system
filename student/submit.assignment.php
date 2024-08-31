<?php
session_start();


if (!isset($_SESSION['student_username'])) {
    header("Location: student_login.php");
    exit;
}


$assignments = [
    ['AssignmentID' => 1, 'AssignmentName' => 'Thoery 1'],
    ['AssignmentID' => 2, 'AssignmentName' => 'Thoery 2'],
    ['AssignmentID' => 3, 'AssignmentName' => 'Historical Essay'],
    ['AssignmentID' => 4, 'AssignmentName' => 'English Literature Review']
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Assignments</title>
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

        .assignment-list {
            list-style-type: none;
            padding: 0;
        }

        .assignment-list li {
            background-color: #0a2b6b;
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .assignment-list li:hover {
            background-color: #0c3c9e;
        }

        .btn-logout {
            background-color: #F2CB05;
            color: #0439D9;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }

        .btn-logout:hover {
            background-color: #F2CB05;
            color: #0439D9;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="student_home.php">Home</a>
        <a href="view_assignments.php" class="active">Assignments</a>
        <a href="student_logout.php" class="btn-logout">Logout</a>
    </div>

    <div class="content">
        <h1>Your Assignments</h1>
        <ul class="assignment-list">
            <?php foreach ($assignments as $assignment): ?>
                <li onclick="window.location.href='submit.assignment.php?assignment_id=<?php echo $assignment['AssignmentID']; ?>'">
                    <?php echo htmlspecialchars($assignment['AssignmentName']); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
