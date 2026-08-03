<?php
include "db.php";

if(isset($_POST['register']))
{
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = mysqli_prepare($conn, "INSERT INTO users(username, password) VALUES(?, ?)");

mysqli_stmt_bind_param($stmt, "ss", $username, $password);

if(mysqli_stmt_execute($stmt))
{
    echo "<script>alert('Registration Successful');</script>";
}
else
{
    echo "Error: " . mysqli_error($conn);
}
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>User Registration</h2>

<form method="POST">
    <label>Username:</label><br>
    <input type="text" name="username" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <input type="submit" name="register" value="Register">
</form>

<p>Already have an account?
<a href="login.php">Login</a></p>

</body>
</html>