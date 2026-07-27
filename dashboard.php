<?php
session_start();
include "db.php";

if(!isset($_SESSION['username']))
{
    header("Location: login.php");
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM posts ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Welcome <?php echo $_SESSION['username']; ?></h2>

<a href="create.php">Create New Post</a> |
<a href="logout.php">Logout</a>

<br><br>

<table border="1" cellpadding="10">
<tr>
    <th>ID</th>
    <th>Title</th>
    <th>Content</th>
    <th>Created At</th>
    <th>Action</th>
</tr>

<?php
while($row=mysqli_fetch_assoc($result))
{
?>
<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo $row['title']; ?></td>
<td><?php echo $row['content']; ?></td>
<td><?php echo $row['created_at']; ?></td>
<td>
<a href="edit.php?id=<?php echo $row['id']; ?>">Edit</a> |
<a href="delete.php?id=<?php echo $row['id']; ?>">Delete</a>
</td>
</tr>

<?php
}
?>

</table>

</body>
</html>