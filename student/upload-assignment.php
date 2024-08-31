<?php
session_start();

if (!isset($_GET['assignment_id'])) {
    echo "Assignment ID missing.";
    exit;
}

$assignment_id = $_GET['assignment_id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['assignment_file']) && $_FILES['assignment_file']['error'] === UPLOAD_ERR_OK) {
       
        $upload_dir = __DIR__ . '/uploads/'; 

       
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true); 
        }

       
        $file_name = basename($_FILES['assignment_file']['name']);
        $target_file = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['assignment_file']['tmp_name'], $target_file)) {
          
            header("Location: student_home.php?status=success");
            exit;
        } else {
            echo "Failed to upload file.";
        }
    } else {
        echo "No file uploaded or an error occurred during upload.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Assignment</title>
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

        .navbar a:hover {
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

        .drag-drop-area {
            border: 2px dashed white;
            padding: 30px;
            border-radius: 10px;
            background-color: #0a2b6b;
            margin-bottom: 20px;
        }

        .drag-drop-area.hover {
            background-color: #0c3c9e;
        }

        .drag-drop-area input[type="file"] {
            display: none;
        }

        .drag-drop-text {
            margin: 0;
            font-size: 18px;
        }

        .btn-submit {
            background-color: #F2CB05;
            color: #0439D9;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: none; 
        }

        .btn-submit:hover {
            background-color: #0c3c9e;
            color: #F2CB05;
        }

        .btn-logout {
            background-color: #F2CB05;
            color: #0439D9;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }

        .btn-logout:hover {
            background-color: #0c3c9e;
            color: #F2CB05;
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

    <div class="navbar">
        <a href="student_home.php">Home</a>
        <a href="view_assignments.php">Assignments</a>
        <a href="student_logout.php" class="btn-logout">Logout</a>
    </div>

    <div class="content">
        <h1>Submit Your Assignment</h1>
        <form id="uploadForm" action="" method="POST" enctype="multipart/form-data">
            <div id="dragDropArea" class="drag-drop-area">
                <p class="drag-drop-text">Drag & drop your file here or click to upload</p>
                <input type="file" name="assignment_file" id="assignmentFile" required>
            </div>
            <button type="submit" class="btn-submit" id="submitBtn">Submit Assignment</button>
        </form>
    </div>

    <script>
        const dragDropArea = document.getElementById('dragDropArea');
        const assignmentFileInput = document.getElementById('assignmentFile');
        const submitBtn = document.getElementById('submitBtn');


        dragDropArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            dragDropArea.classList.add('hover');
        });

        dragDropArea.addEventListener('dragleave', () => {
            dragDropArea.classList.remove('hover');
        });

        dragDropArea.addEventListener('drop', (e) => {
            e.preventDefault();
            dragDropArea.classList.remove('hover');
            assignmentFileInput.files = e.dataTransfer.files;
            handleFileUpload();
        });

        dragDropArea.addEventListener('click', () => {
            assignmentFileInput.click();
        });

        assignmentFileInput.addEventListener('change', handleFileUpload);

        function handleFileUpload() {
            if (assignmentFileInput.files.length > 0) {
                submitBtn.style.display = 'inline-block';
            }
        }
    </script>

</body>
</html>
