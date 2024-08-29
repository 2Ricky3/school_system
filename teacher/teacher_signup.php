<?php
// Include the database connection
include('../includes/db_connection.php');

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name']; // Get the name from the form
    $password = $_POST['password']; // Get the password from the form
    $teacher_code = $_POST['teacher_code']; // Get the teacher code from the form

    // Verify the teacher code
    $valid_teacher_code = 'TEACHER123'; // Replace with the actual code you want to use

    if ($teacher_code === $valid_teacher_code) {
        // Hash the password before storing it
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert the teacher into the database
        $sql = "INSERT INTO Teacher (UniqueCode, Name, Password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sss", $teacher_code, $name, $hashed_password);

            if ($stmt->execute()) {
                // Redirect to teacher home page after successful sign-up
                $_SESSION['teacher_name'] = $name;
                header("Location: teacher_home.php");
                exit;
            } else {
                echo "<p class='error'>Error: " . $stmt->error . "</p>";
            }

            $stmt->close();
        } else {
            echo "<p class='error'>Error: " . $conn->error . "</p>";
        }
    } else {
        echo "<p class='error'>Invalid teacher code.</p>";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Sign Up</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #0439D9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #056CF2;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #F2CB05;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-size: 16px;
            color: #F2CB05;
            margin-bottom: 5px;
            text-align: left;
        }

        input {
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        button {
            padding: 10px;
            background-color: #F2CB05;
            color: #0439D9;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease, color 0.3s ease, transform 0.3s ease;
        }

        button:hover {
            background-color: #056CF2;
            color: #FFFFFF;
            transform: scale(1.1);
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }

        a {
            display: block;
            margin-top: 10px;
            color: #F2CB05;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Teacher Sign Up</h2>
        <form method="POST" action="teacher_signup.php">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="teacher_code">Teacher Code:</label>
            <input type="text" id="teacher_code" name="teacher_code" required>

            <button type="submit">Sign Up</button>
        </form>
        <a href="teacher_login.php">Already have an account? Login here</a>
    </div>
</body>
</html>
