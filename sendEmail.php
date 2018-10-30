<?php

require 'vendor/autoload.php';
require 'init/init.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// initialize mailer
$mail = new PHPMailer(true);

// set configuration
$mail->SMTPDebug = 2;
$mail->isSMTP(); // untuk memberitahu mailer, bahwa akan menggunakan smtp
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'satria.10118068@mahasiswa.unikom.ac.id';
$mail->Password = 'scisswxmzoqnurvl';
$mail->SMTPSecure = 'tls'; 
$mail->Port = 587;

// set sender email
$mail->setFrom('satria.10118068@mahasiswa.unikom.ac.id', 'Admin Belajar CRUD');

// send email
if(isset($_POST['forgot'])) {

	$user = getUser(array(
		'col' => 'email',
		'val' => $_POST['email']
	));

	if($user->rowCount() < 1) {
		header("Location: forgot.php?error=true");
	}

	$user = $user->fetch(PDO::FETCH_OBJ);
	$token = generateToken($_POST['email']);

	// body email
	$mail->addAddress($_POST['email']);
	$mail->isHTML(true);
	$mail->Subject = 'Link lupa password';
	$mail->Body = 'Berikut link untuk reset password: <br/><a href="'.$siteUrl.'forgot-password/reset.php?token='.$token.'">Visit Link</a>';
	$mail->send();

	// delete jika ada forgot password yang emailnya sama
	deleteForgotPassword($_POST['email']);

	// simpan token dan email berdasarkan user_id
	storeForgotPassword(array(
		'user_id' => $user->id,
		'email' => $user->email,
		'token' => $token
	));

	header("Location: forgot.php?success=true");
} elseif(isset($_POST['register'])) {
	if(!registerUser(array_merge($_POST,$_FILES))) {
		header("Location: register.php?success=false");
		exit();
	}
	
	$user = getUser(array(
		'col' => 'email',
		'val' => $_POST['email']
	));

	if($user->rowCount() < 1) {
		header("Location: register.php?error=true");
	}

	$user = $user->fetch(PDO::FETCH_OBJ);
	$token = generateToken($_POST['email']);

	$mail->addAddress($_POST['email']);
	$mail->isHTML(true);
	$mail->Subject = 'Konfirmasi Akun';
	$mail->Body = 'Berikut link untuk konfirmasi akun anda: <br/><a href="'.$siteUrl.'confirm.php?token='.$token.'">Visit Link</a>';
	$mail->send();
	header("Location: register.php?success=true");

	// delete jika ada forgot password yang emailnya sama
	deleteForgotPassword($_POST['email']);

	// simpan token dan email berdasarkan user_id
	storeForgotPassword(array(
		'user_id' => $user->id,
		'email' => $user->email,
		'token' => $token
	));

	header("Location: register.php?success=true");
}