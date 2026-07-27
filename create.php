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
    $title = $_POST['title'];
    $content = $_POST['content'];

    $sql = "INSERT INTO posts(title, content) VALUES('$title','$content')";

    if(mysqli_query($conn, $sql))
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