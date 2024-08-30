<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['student_username'])) {
    echo 'Please log in first.';
    exit;
}

if (isset($_FILES['file']) && isset($_POST['assignment_id'])) {
    $assignment_id = intval($_POST['assignment_id']);
    $file = $_FILES['file'];

    if ($file['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'school_system/'; 
        $uploadFile = $uploadDir . basename($file['name']);

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

