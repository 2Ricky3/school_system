<?php
session_start();

// Check if the student is logged in
if (!isset($_SESSION['student_username'])) {
    header("Location: student_login.php");
    exit;
}

$assignment_id = isset($_GET['assignment_id']) ? intval($_GET['assignment_id']) : 0;

// Fake assignment data
$assignments = [
    1 => 'Math Homework 1',
    2 => 'Science Project',
    3 => 'History Essay',
    4 => 'English Literature Review'
];

$assignment_name = isset($assignments[$assignment_id]) ? $assignments[$assignment_id] : 'Assignment not found';
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

        .drag-drop-container {
            position: relative;
            width: 80%;
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border: 2px dashed #F2CB05;
            border-radius: 10px;
            background-color: #0a2b6b;
        }

        .drag-drop-message {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .upload-area {
            padding: 20px;
            background-color: #0a2b6b;
            border-radius: 5px;
            color: white;
            text-align: center;
            border: 2px dashed #F2CB05;
            position: relative;
        }

        .upload-area.highlight {
            border-color: #F2CB05;
            background-color: #0c3c9e;
        }

        .upload-area input[type="file"] {
            display: none;
        }

        .upload-area.dragover {
            background-color: #0c3c9e;
        }

        .upload-message {
            margin: 10px 0;
            font-size: 16px;
            display: none;
        }

        .upload-message.show {
            display: block;
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
            display: inline-block;
            margin-top: 20px;
        }

        .btn-submit:hover {
            background-color: #F2CB05;
            color: #0439D9;
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
            background-color: #F2CB05;
            color: #0439D9;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="student_home.php">Home</a>
        <a href="view_assignments.php" class="active">Assignments</a>
        <a href="student_logout.php" class="btn-logout">Logout</a>
    </div>

    <div class="content">
        <h1>Assignment: <?php echo htmlspecialchars($assignment_name); ?></h1>
        <p class="info">Please upload your assignment file below.</p>

        <div class="drag-drop-container">
            <div class="upload-area" id="uploadArea">
                <div class="upload-message" id="uploadMessage">No file uploaded</div>
                <input type="file" id="fileInput">
                Drag and drop your file here or click to select
            </div>
            <button class="btn-submit" id="submitButton" disabled>Submit</button>
        </div>
    </div>

    <script>
        const uploadArea = document.getElementById('uploadArea');
        const fileInput = document.getElementById('fileInput');
        const uploadMessage = document.getElementById('uploadMessage');
        const submitButton = document.getElementById('submitButton');
        let fileUploaded = false;

        uploadArea.addEventListener('click', () => fileInput.click());

        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('dragover');
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            fileInput.files = e.dataTransfer.files;
            handleFile();
        });

        fileInput.addEventListener('change', () => {
            handleFile();
        });

        function handleFile() {
            const file = fileInput.files[0];
            if (file) {
                uploadMessage.textContent = file.name;
                uploadMessage.classList.add('show');
                fileUploaded = true;
                submitButton.disabled = false;
            } else {
                uploadMessage.textContent = 'No file uploaded';
                uploadMessage.classList.remove('show');
                fileUploaded = false;
                submitButton.disabled = true;
            }
        }

        submitButton.addEventListener('click', () => {
            if (fileUploaded) {
                const formData = new FormData();
                formData.append('file', fileInput.files[0]);
                formData.append('assignment_id', <?php echo $assignment_id; ?>);

                fetch('upload_assignment.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(result => {
                    alert(result); // Display success message or handle error
                    window.location.href = 'view_assignments.php'; // Redirect after upload
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        });
    </script>
</body>
</html>

