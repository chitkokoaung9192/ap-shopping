<?php 
require '../config/config.php';
	$stmt = $pdo ->prepare ("DELETE FROM users where id = " .$_GET['id']);
	$result = $stmt->execute();
	if ($result) {
		echo "<script>alert('Successfully Deleted');window.location.href='user_list.php';</script>";
	}


 ?>