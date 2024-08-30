<?php
session_start();

// Check if the student is logged in
if (!isset($_SESSION['student_username'])) {
    echo 'Error: Not logged in';
    exit;
}

$student_username = $_SESSION['student_username'];
$assignment_id = isset($_POST['assignment_id']) ? intval($_POST['assignment_id']) : 0;
$student_id = isset($_POST['student_id']) ? intval($_POST['student_id']) : 0;

// Database connection
include('../includes/db_connection.php');

// Check if the file was uploaded without errors
if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
    $file = $_FILES['file'];
    $file_name = basename($file['name']);
    $target_file = "../school_system/" . $file_name;

    // Move the uploaded file to the target directory
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        // Insert file information into the database
        $insert_query = "INSERT INTO Submissions (AssignmentID, StudentID, FileName) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("iis", $assignment_id, $student_id, $file_name);
        
        if ($stmt->execute()) {
            echo 'Success';
        } else {
            echo 'Error inserting into database';
        }
        
        $stmt->close();
    } else {
        echo 'Error moving uploaded file';
    }
} else {
    echo 'Error uploading file';
}

$conn->close();
?>
