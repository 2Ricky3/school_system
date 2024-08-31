<?php
session_start();
include('../includes/db_connection.php');


if (!isset($_SESSION['student_username'])) {
    header("Location: student_login.php");
    exit;
}

$student_username = $_SESSION['student_username'];


$sql = "SELECT StudentID FROM student WHERE Username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $student_username);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$student_id = $row['StudentID'];


if (isset($_POST['subjects']) && is_array($_POST['subjects'])) {
    $subjects = $_POST['subjects'];


    $delete_sql = "DELETE FROM Student_Subjects WHERE StudentID = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $student_id);
    $delete_stmt->execute();

    $insert_sql = "INSERT INTO Student_Subjects (StudentID, SubjectID, Grade) VALUES (?, ?, 'N/A')";
    $insert_stmt = $conn->prepare($insert_sql);

    foreach ($subjects as $subject_id) {
        $insert_stmt->bind_param("ii", $student_id, $subject_id);
        $insert_stmt->execute();
    }

    $insert_stmt->close();
}

$conn->close();
header("Location: student_home.php");
exit;
