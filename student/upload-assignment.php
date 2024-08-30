<?php
session_start();

// Check if the student is logged in
if (!isset($_SESSION['student_username'])) {
    echo 'Please log in first.';
    exit;
}

// Check if file was uploaded
if (isset($_FILES['file']) && isset($_POST['assignment_id'])) {
    $assignment_id = intval($_POST['assignment_id']);
    $file = $_FILES['file'];

    if ($file['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'school_system/'; // Directory where files will be saved
        $uploadFile = $uploadDir . basename($file['name']);

        // Move the uploaded file to the server
        if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
            echo 'File successfully uploaded!';
        } else {
            echo 'Failed to move uploaded file.';
        }
    } else {
        echo 'File upload error: ' . $file['error'];
    }
} else {
    echo 'No file uploaded or assignment ID missing.';
}
?>
