<?php
// Include the database connection
include('../includes/db_connection.php');

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $password = $_POST['password'];
    $teacher_code = $_POST['teacher_code'];

    // Verify the teacher code
    $valid_teacher_code = 'TEACHER123'; // Replace with the actual code you want to use

    if ($teacher_code === $valid_teacher_code) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO Teacher (UniqueCode, Name, Password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sss", $teacher_code, $name, $hashed_password);

            if ($stmt->execute()) {
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
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: bold;
            color: #F2CB05;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        label {
            font-size: 18px;
            color: #F2CB05;
            margin-bottom: 5px;
            text-align: left;
        }

        input {
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 16px;
            transition: box-shadow 0.3s ease, border-color 0.3s ease;
        }

        input:focus {
            outline: none;
            border-color: #F2CB05;
            box-shadow: 0 0 8px rgba(242, 203, 5, 0.5);
        }

        button {
            padding: 12px;
            background-color: #F2CB05;
            color: #0439D9;
            border: none;
            border-radius: 6px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease, transform 0.3s ease;
        }

        button:hover {
            background-color: #FFFFFF;
            color: #056CF2;
            transform: scale(1.05);
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }

        a {
            display: block;
            margin-top: 15px;
            color: #F2CB05;
            font-size: 16px;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
            color: #FFFFFF;
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
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <a href="teacher_login.php">Already have an account? Login here</a>
    </div>
</body>
</html>
