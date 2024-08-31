<?php
session_start();


if (!isset($_SESSION['student_username'])) {
    header("Location: student_login.php");
    exit;
}

$student_username = $_SESSION['student_username'];
$subject_id = isset($_GET['subject_id']) ? intval($_GET['subject_id']) : '';

if (empty($subject_id)) {
    die("Subject ID not provided.");
}


include('../includes/db_connection.php');


$subjects_query = "SELECT SubjectID, SubjectName FROM Subject";
$subjects_result = $conn->query($subjects_query);

if ($subjects_result === false) {
    die("Error fetching subjects: " . $conn->error);
}


$current_subject_query = "SELECT SubjectID, SubjectName FROM Subject WHERE SubjectID = ?";
$stmt = $conn->prepare($current_subject_query);
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $subject_id);
$stmt->execute();
$current_subject_result = $stmt->get_result();

if ($current_subject_result->num_rows == 1) {
    $current_subject = $current_subject_result->fetch_assoc();
} else {
    die("Current subject not found.");
}

$stmt->close();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_subject_id = intval($_POST['subject_id']);

    $student_id_query = "SELECT StudentID FROM Student WHERE Username = ?";
    $stmt = $conn->prepare($student_id_query);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("s", $student_username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $student_id = $row['StudentID'];
    } else {
        die("Student ID not found.");
    }
    
    $stmt->close();
    

    $update_query = "UPDATE Student_Subjects SET SubjectID = ? WHERE StudentID = ? AND SubjectID = ?";
    $stmt = $conn->prepare($update_query);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("iii", $new_subject_id, $student_id, $subject_id);
    $stmt->execute();
    
    if ($stmt->affected_rows === 1) {
        header("Location: student_home.php");
        exit;
    } else {
        die("Error updating subject: " . $stmt->error);
    }
    
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Subject</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #0439D9;
            color: white;
            margin: 0;
            padding: 0;
        }

        .content {
            padding: 20px;
            text-align: center;
        }

        h1 {
            font-size: 36px;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label, select, button {
            margin: 10px;
        }

        button {
            background-color: #056CF2;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0439D9;
        }
    </style>
</head>
<body>
    <div class="content">
        <h1>Change Subject</h1>
        <form method="POST" action="">
            <label for="subject_id">Select New Subject:</label>
            <select id="subject_id" name="subject_id" required>
                <?php while ($row = $subjects_result->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($row['SubjectID']); ?>"
                        <?php echo ($row['SubjectID'] == $current_subject['SubjectID']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($row['SubjectName']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <button type="submit">Save</button>
        </form>
    </div>
</body>
</html>
