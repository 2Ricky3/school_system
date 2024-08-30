<?php
session_start();

// Check if the student is logged in
if (!isset($_SESSION['student_username'])) {
    header("Location: student_login.php");
    exit;
}

$student_username = $_SESSION['student_username'];

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

// Fetch subjects for selection
$subjects_query = "SELECT SubjectID, SubjectName FROM subject"; // Corrected table name
$result = $conn->query($subjects_query);

if ($result === false) {
    die("Error: " . $conn->error);
}

$subjects = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Subjects</title>
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

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group select {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .button {
            background-color: #F2CB05;
            color: #0439D9;
            border: none;
            padding: 10px 20px;
            text-decoration: none;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #e0b900;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="student_home.php">Home</a>
        <a href="student_profile.php">Profile</a>
    </div>

    <div class="content">
        <h1>Select Your Subjects</h1>

        <form action="save_subjects.php" method="post">
            <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($student_id); ?>">

            <?php if (!empty($subjects)): ?>
                <div class="form-group">
                    <label for="subjects">Choose subjects:</label>
                    <select name="subjects[]" id="subjects" multiple size="10">
                        <?php foreach ($subjects as $subject): ?>
                            <option value="<?php echo htmlspecialchars($subject['SubjectID']); ?>">
                                <?php echo htmlspecialchars($subject['SubjectName']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php else: ?>
                <p>No subjects available.</p>
            <?php endif; ?>

            <button type="submit" class="button">Save Subjects</button>
        </form>
    </div>
</body>
</html>

