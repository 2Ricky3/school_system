<?php
session_start();

if (!isset($_SESSION['TeacherID'])) {
    header("Location: teacher_login.php");
    exit();
}


include('../includes/db_connection.php');


if (isset($_POST['student_id'], $_POST['subject_id'], $_POST['grade'])) {
    $student_id = intval($_POST['student_id']);
    $subject_id = intval($_POST['subject_id']);
    $grade = $_POST['grade'];


    $update_query = "UPDATE Student_Subjects SET Grade = ? WHERE StudentID = ? AND SubjectID = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("sii", $grade, $student_id, $subject_id);

    if ($stmt->execute()) {
        header("Location: teacher_home.php?subject_id=" . $subject_id);
        exit();
    } else {
        echo "Error updating grade.";
    }

    $stmt->close();
}

$conn->close();
?>
