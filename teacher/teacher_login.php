<?php
// Include the database connection
include('../includes/db_connection.php');

session_start();

$loginSuccess = false;
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $password = $_POST['password'];

    // Prepare the SQL statement to fetch the teacher by name
    $sql = "SELECT * FROM Teacher WHERE Name = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the teacher exists
        if ($result->num_rows > 0) {
            $teacher = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $teacher['Password'])) {
                // Store the teacher's name in the session
                $_SESSION['teacher_name'] = $teacher['Name'];

                // Mark login as successful
                $loginSuccess = true;
            } else {
                // Password mismatch
                $error_message = "Invalid password.";
            }
        } else {
            // No teacher found with the provided name
            $error_message = "No account found with that name.";
        }

        $stmt->close();
    } else {
        // SQL statement preparation error
        $error_message = "Error preparing SQL: " . $conn->error;
    }

    $conn->close();

    // If login was successful, redirect to the home page
    if ($loginSuccess) {
        echo "<script>console.log('Login successful, redirecting...');</script>";
        header("Location: teacher_home.php");
        exit();
    } else {
        // Output any error messages to the console
        echo "<script>console.log('Login failed: $error_message');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Login</title>
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
        <h2>Teacher Login</h2>
        <form method="POST" action="teacher_login.php">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Login</button>
        </form>
        <a href="teacher_signup.php">Don't have an account? Sign up here</a>
    </div>
    <?php
    // Display any error messages on the page
    if (!empty($error_message)) {
        echo "<p class='error'>$error_message</p>";
    }
    ?>
</body>
</html>
