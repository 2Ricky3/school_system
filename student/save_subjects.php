<?php
session_start();

// Check if the student is logged in
if (!isset($_SESSION['student_username'])) {
    header("Location: student_login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['student_id']) && isset($_POST['subjects'])) {
        $student_id = $_POST['student_id'];
        $subjects = $_POST['subjects'];

        // Database connection
        include('../includes/db_connection.php');

        // Clear existing subjects
        $clear_query = "DELETE FROM Student_Subjects WHERE StudentID = ?";
        $stmt = $conn->prepare($clear_query);
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $stmt->close();

        // Insert selected subjects
        $insert_query = "INSERT INTO Student_Subjects (StudentID, SubjectID) VALUES (?, ?)";
        $stmt = $conn->prepare($insert_query);

        foreach ($subjects as $subject_id) {
            $stmt->bind_param("ii", $student_id, $subject_id);
            $stmt->execute();
        }

        $stmt->close();
        $conn->close();

        header("Location: student_home.php");
        exit;
    } else {
        die("Invalid request.");
    }
} else {
    die("Invalid request method.");
}


