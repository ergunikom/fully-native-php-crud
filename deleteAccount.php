<?php
require_once("init/init.php");

if(!isset($_SESSION['user'])) {
	header("Location: login.php");
}

$id = $_SESSION['user']->id;

if(isset($_GET['id'])) {
	$id = $_GET['id'];
}

$user = getUser(array(
	'col' => 'id',
	'val' => $id
))->fetch(PDO::FETCH_OBJ);

$query = $conn->db()->prepare("DELETE FROM users WHERE id = ?");
$query->bindValue(1, $id);

try {

	$query->execute();
	$avatarPath = __DIR__.'/uploads/'.$user->avatar;

	if(file_exists($avatarPath)) {
		unlink(__DIR__.'/uploads/'.$user->avatar);
	}

	if(!isset($_GET['id'])) session_destroy();

} catch (PDOException $e) {

	die($e->getMessage());

}

header("Location: index.php");