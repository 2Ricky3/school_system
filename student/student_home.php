<?php
session_start();

if (!isset($_SESSION['student_username'])) {
    header("Location: student_login.php");
    exit;
}

$student_username = $_SESSION['student_username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Home</title>
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
            justify-content: center;
            align-items: center;
            background-color: #056CF2;
            padding: 10px 0;
            margin: 0;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            margin: 0 5px;
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

        .subject-list {
            margin-top: 20px;
            text-align: left;
            padding: 0 20px;
        }

        .subject-list table {
            width: 100%;
            border-collapse: collapse;
        }

        .subject-list th, .subject-list td {
            border: 1px solid #fff;
            padding: 10px;
            text-align: center;
        }

        .subject-list th {
            background-color: #F2CB05;
            color: #0439D9;
        }

        .subject-list tr:nth-child(even) {
            background-color: #0c3c9e;
        }

        .subject-list tr:nth-child(odd) {
            background-color: #0a2b6b;
        }

        .btn-select-subjects {
            background-color: #F2CB05;
            color: #0439D9;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }

        .btn-select-subjects:hover {
            background-color: #0c3c9e;
            color: #F2CB05;
        }

        .btn-view-assignments {
            background-color: #F2CB05;
            color: #003BFA;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn-view-assignments:hover {
            background-color: #FADD00;
            color: #5600FA;
            transform: scale(1.05);
        }


        .popup-message {
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 20px;
            border-radius: 10px;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            font-size: 18px;
            display: none;
            z-index: 9999;
        }

        .popup-message button {
            background-color: #F2CB05;
            color: #0439D9;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }

        .popup-message button:hover {
            background-color: #056CF2;
            color: #F2CB05;
        }
    </style>
</head>
<body>

<?php

if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
    <div class="popup-message" id="popupMessage">
        <p>Submitted successfully!</p>
        <button onclick="closePopup()">OK</button>
    </div>
    <script>
  
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('popupMessage').style.display = 'block';
        });

 
        function closePopup() {
            document.getElementById('popupMessage').style.display = 'none';
        }
    </script>
<?php endif; ?>

<div class="navbar">
    <a href="student_home.php" class="<?php echo ($current_page == 'home') ? 'active' : ''; ?>">Home</a>
    <a href="student_logout.php" class="btn-select-subjects">Logout</a>
</div>

<div class="content">
    <h1>Welcome, <?php echo htmlspecialchars($student_username); ?>!</h1>
    <p class="info">This is your student dashboard.</p>
    <p class="info">Here you can view your subjects and grades.</p>

    <?php

    include('../includes/db_connection.php');

 
    $student_id_query = "SELECT StudentID FROM Student WHERE Username = ?";
    $stmt = $conn->prepare($student_id_query);
    $stmt->bind_param("s", $student_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $student_id = $row['StudentID'];
    } else {
        die("Error: Student ID not found.");
    }

    $stmt->close();


    $subjects_query = "SELECT s.SubjectName, ss.SubjectID, ss.Grade 
                       FROM Student_Subjects ss
                       JOIN Subject s ON ss.SubjectID = s.SubjectID
                       WHERE ss.StudentID = ?";
    $stmt = $conn->prepare($subjects_query);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $subjects_result = $stmt->get_result();
    $selected_subjects = $subjects_result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    $conn->close();
    ?>

    <?php if (empty($selected_subjects)): ?>
        <p>No subjects selected yet. Please select your subjects.</p>
        <a href="select_subjects.php" class="btn-select-subjects">Select Your Subjects</a>
    <?php else: ?>
        <div class="subject-list">
            <h2>Your Selected Subjects</h2>
            <table>
                <tr>
                    <th>Subject</th>
                    <th>Grade</th>
                    <th>View Assignments</th>
                </tr>
                <?php foreach ($selected_subjects as $subject): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($subject['SubjectName']); ?></td>
                        <td><?php echo htmlspecialchars($subject['Grade']); ?></td>
                        <td>
                            <form action="view_assignments.php" method="GET">
                                <input type="hidden" name="subject_id" value="<?php echo htmlspecialchars($subject['SubjectID']); ?>">
                                <button class="btn-view-assignments" type="submit">View Assignments</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    <?php endif; ?>
</div>

</body>
</html>

