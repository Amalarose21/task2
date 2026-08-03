<?php
session_start();
include "db.php";

if (!isset($_SESSION['username']))
{
    header("Location: login.php");
    exit();
}

// Allow only admins to delete posts
if ($_SESSION['role'] != "admin")
{
    die("Access Denied");
}

$id = $_GET['id'];

$stmt = mysqli_prepare($conn, "DELETE FROM posts WHERE id=?");

mysqli_stmt_bind_param($stmt, "i", $id);

mysqli_stmt_execute($stmt);

header("Location: dashboard.php");
exit();
?>