<?php
require '../config/config.php';
$stmt =$pdo->prepare("DELETE FROM products WHERE id=".$_GET['id']);
$result =$stmt->execute();
if ($result) {
    echo "<script>alert('Successfully Deleted');window.location.href='index.php';</script>";
}


?>