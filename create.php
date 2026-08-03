<?php
session_start();
include "db.php";

if(!isset($_SESSION['username']))
{
    header("Location: login.php");
    exit();
}

if(isset($_POST['submit']))
{
    $title = trim($_POST['title']);
$content = trim($_POST['content']);

if(empty($title) || empty($content))
{
    die("Title and Content are required.");
}

    $stmt = mysqli_prepare($conn, "INSERT INTO posts(title, content) VALUES(?, ?)");

mysqli_stmt_bind_param($stmt, "ss", $title, $content);

if(mysqli_stmt_execute($stmt))
{
    echo "<script>alert('Post Added Successfully');</script>";
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
    <title>Create Post</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Create New Post</h2>

<form method="POST">

<label>Title</label><br>
<input type="text" name="title" required><br><br>

<label>Content</label><br>
<textarea name="content" rows="5" cols="40" required></textarea><br><br>

<input type="submit" name="submit" value="Add Post">

</form>

<br>

<a href="dashboard.php">Back to Dashboard</a>

</body>
</html>