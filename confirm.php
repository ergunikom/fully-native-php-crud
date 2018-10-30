<?php

require_once("init/init.php");

if(!isset($_GET['token']) || isset($_SESSION['user'])) header("Location: index.php");

$tokenData = selectForgotPassword(array(
	'col' => 'token',
	'val' => $_GET['token']
));

if($tokenData->rowCount() < 1) {
	header("Location: index.php");
}

$tokenData = $tokenData->fetch(PDO::FETCH_OBJ);

$query = $conn->db()->prepare("UPDATE users SET is_active = ? WHERE id = ?");
$query->bindValue(1, true);
$query->bindValue(2,  $tokenData->user_id);

?>
<!DOCTYPE html>
<html>
	<meta http-equiv="refresh" content="2;url=<?= $siteUrl ?>login.php">
<head>
	<title>Berhasil Konfirmasi Akun</title>
</head>
<body>

</body>
</html>
<?php
if($query->execute()) {
	deleteForgotPassword($tokenData->email);
	echo "Berhasil konfirmasi akun";
}else {
	echo "Gagal konfirmasi akun";
}