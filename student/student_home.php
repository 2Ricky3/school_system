<?php
session_start();

// Check if the student is logged in
if (!isset($_SESSION['student_username'])) {
    header("Location: student_login.php");
    exit;
}

$student_username = $_SESSION['student_username'];

if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
    <p class="info">Assignment submitted successfully!</p>
<?php endif;

// Database connection
include('../includes/db_connection.php');

// Get the student ID
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

// Check if subjects are already selected
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
    justify-content: center; /* Center the navbar items */
    align-items: center; /* Align items vertically */
    background-color: #056CF2;
    padding: 10px 0;
    margin: 0; /* Ensure no additional margins */
}

.navbar a {
    color: white;
    text-decoration: none;
    padding: 10px 20px;
    border-radius: 5px;
    transition: background-color 0.3s ease;
    margin: 0 5px; /* Add some spacing between buttons */
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

.btn-select-subjects, .btn-view-assignments {
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

.btn-select-subjects:hover, .btn-view-assignments:hover {
    background-color: #0c3c9e;
    color: #F2CB05;
}

.btn-view-assignments {
    background-color: #05F2CB;
    color: #0439D9;
}

.btn-view-assignments:hover {
    background-color: #0b5d6b;
    color: #F2CB05;
}

    </style>
</head>
<body>
<div class="navbar">
    <a href="student_home.php" class="<?php echo ($current_page == 'home') ? 'active' : ''; ?>">Home</a>
    <a href="student_logout.php" class="btn-select-subjects">Logout</a>
</div>


    <div class="content">
        <h1>Welcome, <?php echo htmlspecialchars($student_username); ?>!</h1>
        <p class="info">This is your student dashboard.</p>
        <p class="info">Here you can view your subjects and grades.</p>

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
                                <a href="view_assignments.php?subject_id=<?php echo urlencode($subject['SubjectID']); ?>" class="btn-view-assignments">View Assignments</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

