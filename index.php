<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School System</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
            color: #F2CB05;
        }

        .container {
            text-align: center;
            background-color: #056CF2;
            padding: 50px;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            max-width: 700px;
            width: 100%;
        }

        .logo {
            width: 100px; /* Adjust the logo size as needed */
            margin-bottom: 20px;
        }

        h1 {
            margin-bottom: 30px;
            font-size: 32px;
            font-weight: bold;
            color: #F2CB05;
        }

        .column {
            background-color: #0439D9;
            padding: 30px;
            border-radius: 8px;
            color: #F2CB05;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            margin-bottom: 20px;
        }

        .column h2 {
            font-size: 22px;
            margin-bottom: 15px;
            color: #F2CB05;
        }

        .button {
            display: inline-block;
            width: 100%;
            padding: 15px;
            margin-bottom: 15px;
            background-color: #F2CB05;
            color: #0439D9;
            border: none;
            border-radius: 6px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            transition: transform 0.3s ease, background-color 0.3s ease, color 0.3s ease;
        }

        .button:hover {
            background-color: #FFFFFF;
            color: #056CF2;
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            text-decoration: none;
        }

        .admin-options {
            text-align: center;
            margin-top: 20px;
        }

        .admin-options h2 {
            font-size: 20px;
            color: #F2CB05;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Logo Image -->
        <img src="assets/logo.png" alt="High School Glen Logo" class="logo">
        
        <!-- Main Heading -->
        <h1>High School Glen</h1>
        
        <div class="row">
            <div class="col-md-6 column">
                <h2>Students</h2>
                <a href="student/student_login.php" class="button">Student Login</a>
                <a href="student/student_signup.php" class="button">Student Sign Up</a>
            </div>

            <div class="col-md-6 column">
                <h2>Teachers</h2>
                <a href="teacher/teacher_login.php" class="button">Teacher Login</a>
                <a href="teacher/teacher_signup.php" class="button">Teacher Sign Up</a>
            </div>
        </div>
        <div class="admin-options mt-4">
            <h2>Admin</h2>
            <a href="admin/admin_login.php" class="button">Admin Login</a>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
