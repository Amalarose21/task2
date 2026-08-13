<?php
session_start();
include "db.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$search = "";
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if ($page < 1) {
    $page = 1;
}

$start = ($page - 1) * $limit;

if (isset($_GET['search']) && $_GET['search'] != "") {

    $search = trim($_GET['search']);
    $searchParam = "%" . $search . "%";

    $stmt = mysqli_prepare(
        $conn,
        "SELECT * FROM posts
         WHERE title LIKE ? OR content LIKE ?
         ORDER BY id DESC
         LIMIT ?, ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "ssii",
        $searchParam,
        $searchParam,
        $start,
        $limit
    );

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $countStmt = mysqli_prepare(
        $conn,
        "SELECT COUNT(*) AS total
         FROM posts
         WHERE title LIKE ? OR content LIKE ?"
    );

    mysqli_stmt_bind_param(
        $countStmt,
        "ss",
        $searchParam,
        $searchParam
    );

    mysqli_stmt_execute($countStmt);
    $countResult = mysqli_stmt_get_result($countStmt);

} else {

    $stmt = mysqli_prepare(
        $conn,
        "SELECT * FROM posts
         ORDER BY id DESC
         LIMIT ?, ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "ii",
        $start,
        $limit
    );

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $countStmt = mysqli_prepare(
        $conn,
        "SELECT COUNT(*) AS total
         FROM posts"
    );

    mysqli_stmt_execute($countStmt);
    $countResult = mysqli_stmt_get_result($countStmt);
}

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

<h2>
    Welcome <?php echo htmlspecialchars($_SESSION['username']); ?>
</h2>

<a href="create.php">Create New Post</a> |
<a href="logout.php">Logout</a>

<br><br>

<form method="GET">

    <input
        type="text"
        name="search"
        placeholder="Search by title or content"
        value="<?php echo htmlspecialchars($search); ?>"
    >

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

if (mysqli_num_rows($result) == 0)
{
    echo "<tr>";
    echo "<td colspan='5' style='text-align:center;'>";
    echo "No posts found.";
    echo "</td>";
    echo "</tr>";
}
else
{
    while ($row = mysqli_fetch_assoc($result))
    {
?>

<tr>

<td>
    <?php echo $row['id']; ?>
</td>

<td>
    <?php echo htmlspecialchars($row['title']); ?>
</td>

<td>
    <?php echo htmlspecialchars($row['content']); ?>
</td>

<td>
    <?php echo $row['created_at']; ?>
</td>

<td>

<a href="edit.php?id=<?php echo $row['id']; ?>">
    Edit
</a>

<?php if ($_SESSION['role'] == 'admin') { ?>

    <a
        href="delete.php?id=<?php echo $row['id']; ?>"
        onclick="return confirm('Are you sure you want to delete this post?');"
    >
        Delete
    </a>

<?php } ?>

</td>

</tr>

<?php
    }
}

?>

</table>

<br>

<div style="text-align:center;">

<?php

if ($page > 1)
{
    echo "<a href='?page=" . ($page - 1) .
         "&search=" . urlencode($search) .
         "'>Previous</a> ";
}

for ($i = 1; $i <= $totalPages; $i++)
{
    if ($i == $page)
    {
        echo "<strong>$i</strong> ";
    }
    else
    {
        echo "<a href='?page=$i&search=" .
             urlencode($search) .
             "'>$i</a> ";
    }
}

if ($page < $totalPages)
{
    echo "<a href='?page=" . ($page + 1) .
         "&search=" . urlencode($search) .
         "'>Next</a>";
}

?>

</div>

</body>

</html>