<?php
session_start();
include "db.php";

if(isset($_POST['login']))
{
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0)
    {
        $row = mysqli_fetch_assoc($result);

        if(password_verify($password, $row['password']))
        {
            $_SESSION['username'] = $username;
            header("Location: dashboard.php");
            exit();
        }
        else
        {
            echo "<script>alert('Invalid Password');</script>";
        }
    }
    else
    {
        echo "<script>alert('User Not Found');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

<h2>User Login</h2>

<form method="POST">

<label>Username:</label><br>
<input type="text" name="username" required><br><br>

<label>Password:</label><br>
<input type="password" name="password" required><br><br>

<input type="submit" name="login" value="Login">

</form>

<p>Don't have an account?
<a href="register.php">Register</a>
</p>

</div>

</body>
</html>