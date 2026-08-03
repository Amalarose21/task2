<?php
session_start();
include "db.php";

if(!isset($_SESSION['username']))
{
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

$stmt = mysqli_prepare($conn, "SELECT * FROM posts WHERE id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if(isset($_POST['update']))
{
    $title = trim($_POST['title']);
$content = trim($_POST['content']);

if(empty($title) || empty($content))
{
    die("Title and Content are required.");
}
    $stmt = mysqli_prepare($conn, "UPDATE posts SET title=?, content=? WHERE id=?");

mysqli_stmt_bind_param($stmt, "ssi", $title, $content, $id);

mysqli_stmt_execute($stmt);

header("Location: dashboard.php");
exit();
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