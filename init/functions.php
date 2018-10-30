<?php

function registerUser($data)
{
	global $conn;

	$error = false;

	if(empty($data['username']) || empty($data['nama']) || empty($data['password']) || empty($data['email']) || empty($data['avatar'])) {
		$_SESSION['message'] = 'Gagal register. Pastikan semua form diisi dengan benar.';
		$error = true;
	}

	// cek jika username sudah digunakan
	if(getUser(array(
		"col" => "username",
		"val" => $data['username']
	))->rowCount() > 0) {
		$_SESSION['message'] =  'Gagal register. Username sudah digunakan.';
		$error = true;
	}

	// cek jika email sudah digunakan
	if(getUser(array(
		"col" => "email",
		"val" => $data['email']
	))->rowCount() > 0) {
		$_SESSION['message'] =  'Gagal register. Email sudah digunakan.';
		$error = true;
	}

	$avatar = uploadFiles($data['avatar']);

	if(!$avatar) {
		$_SESSION['message'] = 'Gagal mengunggah avatar.';
		$error = true;
	}

	if($error) {
		return false;
	}

	$query = $conn->db()->prepare("INSERT INTO users (name, email, username, avatar, password) VALUES (?,?,?,?,?)");
	$query->bindValue(1, $data['nama']);
	$query->bindValue(2, $data['email']);
	$query->bindValue(3, $data['username']);
	$query->bindValue(4, $avatar);
	$query->bindValue(5, password_hash($data['password'], PASSWORD_DEFAULT));

	return $query->execute();
}

function updateUser($id, $data)
{
	global $conn;

	$error = false;
	$avatar = '';
	$user = getUser(array(
		"col" => "id",
		"val" => $id
	))->fetch(PDO::FETCH_OBJ);

	if(empty($data['nama'])) {
		$_SESSION['message'] = 'Gagal register. Pastikan semua form diisi dengan benar.';
		$error = true;
	}

	if(file_exists($data['avatar']['tmp_name'])) {
		$avatar = uploadFiles($data['avatar']);
		if(!$avatar) {
			$_SESSION['message'] = 'Gagal mengunggah avatar.';
			$error = true;
		}
		$oldAvatar = __DIR__."/../uploads/".$user->avatar;
		if(file_exists($oldAvatar)) unlink($oldAvatar);
	}

	if($error) {
		return false;
	}

	$set = "name = :name";

	if($avatar != '') {
		$set .= ", avatar = :avatar";
	}

	$passCond = (!empty($data['password']) && !empty($data['password_confirmation'])) && ($data['password'] == $data['password_confirmation']);

	if($passCond) {
		$set .= ", password = :password";
	}

	$query = "UPDATE users SET ".$set." WHERE id = :id";

	$query = $conn->db()->prepare($query);
	$query->bindValue(":name", $data['nama']);
	if($avatar != '') {
		$query->bindValue(":avatar", $avatar);
	}
	if($passCond) {
		$query->bindValue(":password", password_hash($data['password'], PASSWORD_DEFAULT));
	}
	$query->bindValue(":id", $id);

	if($query->execute()) {
		$_SESSION['user'] = getUser(array(
			"col" => "id",
			"val" => $id
		))->fetch(PDO::FETCH_OBJ);
		return true;
	}
	return false;
}

function loginUser($data)
{
	global $conn;

	$user = getUser(array(
		"col" => "username",
		"val" => $data['username']
	));

	if($user->rowCount() < 1) {
		return [
			'status' => false,
			'message' => 'Gagal login. Username tidak ditemukan.'
		];
	}

	$user = $user->fetch(PDO::FETCH_OBJ);

	if(!$user->is_active) {
		return [
			'status' => false,
			'message' => 'Gagal login. Belum aktif, silahkan aktifkan user terlebih dahulu.'
		];
	}

	if(password_verify($data['password'], $user->password)) {
		$_SESSION['user'] = $user;
		return true;
	}

	return [
		'status' => false,
		'message' => 'Gagal login. Password yang dimasukan salah.'
	];
}

function getUser($data)
{
	global $conn;
	$query = $conn->db()->prepare("SELECT * FROM users WHERE `".$data['col']."` = ?");
	$query->bindValue(1,$data['val']);
	$query->execute();
	return $query;
}

function getUsers()
{
	global $conn;
	$query = $conn->db()->prepare("SELECT * FROM users");
	$query->execute();
	return $query;
}

function generateToken($email)
{
	$text = $email;
	$text .= time();
	return md5($text);
}

function storeForgotPassword($data)
{
	global $conn;
	
	$query = $conn->db()->prepare("INSERT INTO forgot_passwords (user_id, email, token) VALUES (?,?,?)");
	$query->bindValue(1, $data['user_id']);
	$query->bindValue(2, $data['email']);
	$query->bindValue(3, $data['token']);
	$query->execute();
	return $query;
}

function selectForgotPassword($data)
{
	global $conn;
	$query = $conn->db()->prepare("SELECT * FROM forgot_passwords WHERE `".$data['col']."` = ?");
	$query->bindValue(1, $data['val']);
	$query->execute();
	return $query;
}

function deleteForgotPassword($email)
{
	global $conn;
	$query = $conn->db()->prepare("DELETE FROM forgot_passwords WHERE email = ?");
	$query->bindValue(1, $email);
	$query->execute();
	return $query;
}

function updatePassword($data)
{
	global $conn;

	if($data['password'] !== $data['password_confirmation']) {
		return false;
	}

	$query = $conn->db()->prepare("UPDATE users SET password = ? WHERE id = ?");
	$query->bindValue(1, password_hash($data['password'], PASSWORD_DEFAULT));
	$query->bindValue(2, $data['id']);
	$query->execute();
	return $query;
}

function uploadFiles($file)
{
	$location = __DIR__."/../uploads/";
	$maxSize = 1024000;
	
	if($file['error'] !== 0) {
		die(error("Gagal mengunggah gambar."));
	}

	if(false === $ext = allowedMimes($file)) {
		die(error("Format gambar tidak diizinkan. hanya jpg,png,gif"));
	}

	if($file['size'] > $maxSize) {
		die(error("Ukuran gambar terlalu besar. Maksimal 1024 byte"));
	}

	$fileName = sprintf("%s.%s", md5($file['name'].time()), $ext);
	$fileLocation = $location.$fileName;

	if(move_uploaded_file($file["tmp_name"],$fileLocation)) {
		return $fileName;
	}

	return false;
}

function allowedMimes($file)
{
	$mimesData = array(
		"image/jpeg" => "jpeg",
		"image/jpeg" => "jpg",
		"image/png" => "png",
		"image/x-citrix-png" => "png",
		"image/x-png" => "png",
		"image/gif" => "gif",
	);

	if(array_key_exists($file["type"], $mimesData)) {
		return $mimesData[$file["type"]];
	}

	return false;
}

function throttleLogin()
{
	if(!isset($_SESSION['fail_login'])) {
		$_SESSION['fail_login'] = 0;
	}

	if(isset($_SESSION['fail_login_time'])) {
		if(date("H:i:s") < $_SESSION['fail_login_time']) {
			return false;
		}
		return true;
	}

	if($_SESSION['fail_login'] >= 5) {
		$_SESSION['fail_login_time'] = date("H:i:s", strtotime("+30 Seconds"));
	}

	$_SESSION['fail_login'] += 1;
}

function error($message)
{
	$excpt = new Exception($message);
	return $excpt->getMessage();
	
}