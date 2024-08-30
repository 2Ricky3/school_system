<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['AdminID'])) {
    header("Location: admin_login.php");
    exit();
}

// Database connection
include('../includes/db_connection.php');

// Fetch all teachers
$teachers_query = "SELECT TeacherID, Name FROM Teacher";
$teachers_result = $conn->query($teachers_query);

// Fetch all students
$students_query = "SELECT StudentID, Username FROM Student";
$students_result = $conn->query($students_query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete_teacher'])) {
        $teacher_id = $_POST['teacher_id'];
        
        // Delete the teacher from the Teacher table
        $delete_teacher_query = "DELETE FROM Teacher WHERE TeacherID = ?";
        $stmt = $conn->prepare($delete_teacher_query);
        $stmt->bind_param("i", $teacher_id);

        if ($stmt->execute()) {
            echo "<p>Teacher deleted successfully.</p>";
        } else {
            echo "<p>Error deleting teacher.</p>";
        }
        $stmt->close();
    } elseif (isset($_POST['delete_student'])) {
        $student_id = $_POST['student_id'];
        
        // Delete the student from the Student table
        $delete_student_query = "DELETE FROM Student WHERE StudentID = ?";
        $stmt = $conn->prepare($delete_student_query);
        $stmt->bind_param("i", $student_id);

        if ($stmt->execute()) {
            echo "<p>Student deleted successfully.</p>";
        } else {
            echo "<p>Error deleting student.</p>";
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Home</title>
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
            justify-content: space-between;
            background-color: #056CF2;
            padding: 10px 20px;
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #fff;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #F2CB05;
            color: #0439D9;
        }

        tr:nth-child(even) {
            background-color: #0c3c9e;
        }

        tr:nth-child(odd) {
            background-color: #0a2b6b;
        }

        .button {
            background-color: #F2CB05;
            color: #0439D9;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #F2CB05;
            color: #0439D9;
        }

        .logout-button {
            background-color: #F2CB05;
            color: #0439D9;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .logout-button:hover {
            background-color: #F2CB05;
            color: #0439D9;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="admin_home.php" class="active">Home</a>
        <form method="post" action="admin_logout.php" style="margin: 0;">
            <input type="submit" value="Logout" class="logout-button">
        </form>
    </div>

    <div class="content">
        <h1>Admin Dashboard</h1>

        <h2>All Teachers</h2>
        <table>
            <tr>
                <th>Name</th>
                <th>Action</th>
            </tr>
            <?php while ($teacher = $teachers_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($teacher['Name']); ?></td>
                    <td>
                        <form method="post" action="" style="margin: 0;">
                            <input type="hidden" name="teacher_id" value="<?php echo htmlspecialchars($teacher['TeacherID']); ?>">
                            <input type="submit" name="delete_teacher" value="Delete" class="button">
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>

        <h2>All Students</h2>
        <table>
            <tr>
                <th>Username</th>
                <th>Action</th>
            </tr>
            <?php while ($student = $students_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($student['Username']); ?></td>
                    <td>
                        <form method="post" action="" style="margin: 0;">
                            <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($student['StudentID']); ?>">
                            <input type="submit" name="delete_student" value="Delete" class="button">
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
