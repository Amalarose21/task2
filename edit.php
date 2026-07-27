<?php
session_start();
include "db.php";

if(!isset($_SESSION['username']))
{
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

$result = mysqli_query($conn, "SELECT * FROM posts WHERE id=$id");
$row = mysqli_fetch_assoc($result);

if(isset($_POST['update']))
{
    $title = $_POST['title'];
    $content = $_POST['content'];

    mysqli_query($conn,"UPDATE posts SET title='$title', content='$content' WHERE id=$id");

    header("Location: dashboard.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Post</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Edit Post</h2>

<form method="POST">

Title<br>
<input type="text" name="title" value="<?php echo $row['title']; ?>" required><br><br>

Content<br>
<textarea name="content" rows="5" cols="40" required><?php echo $row['content']; ?></textarea><br><br>

<input type="submit" name="update" value="Update">

</form>

</body>
</html>