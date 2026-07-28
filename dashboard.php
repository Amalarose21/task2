<?php
session_start();
include "db.php";

if(!isset($_SESSION['username']))
{
    header("Location: login.php");
    exit();
}

$search = "";

$limit = 5; // Posts per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if ($page < 1) {
    $page = 1;
}

$start = ($page - 1) * $limit;

if(isset($_GET['search']) && $_GET['search'] != "")
{
    $search = mysqli_real_escape_string($conn, $_GET['search']);

    $query = "SELECT * FROM posts
              WHERE title LIKE '%$search%'
              OR content LIKE '%$search%'
              ORDER BY id DESC
              LIMIT $start, $limit";

    $countQuery = "SELECT COUNT(*) AS total FROM posts
                   WHERE title LIKE '%$search%'
                   OR content LIKE '%$search%'";
}
else
{
    $query = "SELECT * FROM posts
              ORDER BY id DESC
              LIMIT $start, $limit";

    $countQuery = "SELECT COUNT(*) AS total FROM posts";
}

$result = mysqli_query($conn, $query);

$countResult = mysqli_query($conn, $countQuery);
$totalRows = mysqli_fetch_assoc($countResult)['total'];
$totalPages = ceil($totalRows / $limit);
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

<form method="GET" action="">
    <input type="text" name="search"
           placeholder="Search by title or content"
           value="<?php echo htmlspecialchars($search); ?>">
    <input type="submit" value="Search">
    <a href="dashboard.php">Reset</a>
</form>

<br>

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

<br>

<div style="text-align:center;">
<?php
if($page > 1)
{
    echo "<a href='?page=".($page-1)."&search=$search'>Previous</a> ";
}

for($i = 1; $i <= $totalPages; $i++)
{
    if($i == $page)
    {
        echo "<strong>$i</strong> ";
    }
    else
    {
        echo "<a href='?page=$i&search=$search'>$i</a> ";
    }
}

if($page < $totalPages)
{
    echo "<a href='?page=".($page+1)."&search=$search'>Next</a>";
}
?>
</div>

</body>
</html>