<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School System</title>
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
            text-align: center;
            background-color: #056CF2;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            margin-bottom: 30px;
            font-size: 24px;
            color: #F2CB05;
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .column {
            flex: 1;
            padding: 0 20px;
        }

        .column h2 {
            margin-bottom: 20px;
            font-size: 18px;
            color: #056CF2;
        }

        .button {
            display: block;
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #F2CB05;
            color: #0439D9;
            border: none;
            border-radius: 4px;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
            transition: transform 0.3s ease, background-color 0.3s ease;
        }

        .button:hover {
            background-color: #056CF2;
            color: #FFFFFF;
            transform: scale(1.1);
        }

        .admin-options {
            margin-top: 20px;
        }

        .admin-options h2 {
            color: #056CF2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>High School Glen</h1>
        <div class="row">
            <!-- Student Options -->
            <div class="column">
                <h2>Students</h2>
                <a href="student/student_login.php" class="button">Student Login</a>
                <a href="student/student_signup.php" class="button">Student Sign Up</a>
            </div>

            <!-- Teacher Options -->
            <div class="column">
                <h2>Teachers</h2>
                <a href="teacher/teacher_login.php" class="button">Teacher Login</a>
                <a href="teacher/teacher_signup.php" class="button">Teacher Sign Up</a>
            </div>
        </div>

        <!-- Admin Options -->
        <div class="admin-options">
            <h2>Admin</h2>
            <a href="admin/admin_login.php" class="button">Admin Login</a>
        </div>
    </div>
</body>
</html>
