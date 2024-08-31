<?php
session_start();

if (!isset($_SESSION['TeacherID'])) {
    header("Location: teacher_login.php");
    exit();
}

// Database connection
include('../includes/db_connection.php');

$teacher_id = $_SESSION['TeacherID'];

// Get teacher's subjects
$subject_query = "SELECT SubjectID, SubjectName FROM Subject WHERE TeacherID = ?";
$stmt = $conn->prepare($subject_query);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$subjects_result = $stmt->get_result();
$subjects = $subjects_result->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['subject'])) {
        $selected_subject_id = $_POST['subject'];
        
       
        $students_query = "SELECT s.StudentID, s.Username, g.Grade 
                           FROM Student s 
                           JOIN Student_Subjects g ON s.StudentID = g.StudentID 
                           WHERE g.SubjectID = ?";
        $stmt = $conn->prepare($students_query);
        $stmt->bind_param("i", $selected_subject_id);
        $stmt->execute();
        $students_result = $stmt->get_result();
        $students = $students_result->fetch_all(MYSQLI_ASSOC);
    } elseif (isset($_POST['update_grade'])) {
        $student_id = $_POST['student_id'];
        $subject_id = $_POST['subject_id'];
        $new_grade = $_POST['new_grade'];

        
        $update_grade_query = "UPDATE Student_Subjects SET Grade = ? WHERE StudentID = ? AND SubjectID = ?";
        $stmt = $conn->prepare($update_grade_query);
        $stmt->bind_param("sii", $new_grade, $student_id, $subject_id);

        if ($stmt->execute()) {
            
            header("Location: teacher_home.php");
            exit(); 
        } else {
            echo "<p>Error updating grade.</p>";
        }

        $stmt->close();
    }
} else {
    $students = [];
}
$stmt->close();
$conn->close();
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

        .info {
            font-size: 24px;
            margin-bottom: 10px;
        }

        form {
            margin: 20px 0;
        }

        select {
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        input[type="submit"] {
            background-color: #056CF2;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #0439D9;
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

        .grade-form {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="teacher_home.php" class="active">Home</a>
        <a href="teacher_logout.php">Logout</a>
    </div>

    <div class="content">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['Name']); ?>!</h1>
        <p class="info">Your unique code is: <?php echo htmlspecialchars($_SESSION['UniqueCode']); ?></p>

        <form method="post" action="">
            <label for="subject">Choose a subject:</label>
            <select name="subject" id="subject" required>
                <option value="">Select a subject</option>
                <?php foreach ($subjects as $subject): ?>
                    <option value="<?php echo htmlspecialchars($subject['SubjectID']); ?>">
                        <?php echo htmlspecialchars($subject['SubjectName']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="submit" value="View Students">
        </form>

        <?php if (!empty($students)): ?>
            <h2>Students for the selected subject</h2>
            <table>
                <tr>
                    <th>Username</th>
                    <th>Grade</th>
                    <th>Change Grade</th>
                </tr>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($student['Username']); ?></td>
                        <td><?php echo htmlspecialchars($student['Grade']); ?></td>
                        <td>
                            <form method="post" action="" class="grade-form">
                                <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($student['StudentID']); ?>">
                                <input type="hidden" name="subject_id" value="<?php echo htmlspecialchars($selected_subject_id); ?>">
                                <input type="text" name="new_grade" value="<?php echo htmlspecialchars($student['Grade']); ?>" required>
                                <input type="submit" name="update_grade" value="Save">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
            <p>No students found for this subject.</p>
        <?php endif; ?>
    </div>
</body>
</html>
