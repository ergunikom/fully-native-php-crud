<?php
$title = "Reset Password";
$message = "";

require_once(__DIR__."/../partials/header.php");

if(!isset($_GET['token']) || isset($_SESSION['user'])) {
	header("location: ../index.php");
}

// mengambil data berdasarkan token
$forgotPassword = selectForgotPassword(array(
	'col' => 'token',
	'val' => $_GET['token']
));

// cek apakah ada token dalam database pada table forgot_passwords
if($forgotPassword->rowCount() < 1) {
	header("location: ../index.php");
}

// ambil data token yang sudah di cek
$forgotPassword = $forgotPassword->fetch(PDO::FETCH_OBJ);

// cek apakah user melakukan submit reset password
if(isset($_POST['submit'])) {

	// cek apakah update password berhasil dilakukan
	if(updatePassword(

		array_merge($_POST, array('id'=>$forgotPassword->user_id))

	)) {
		// hapus token yang sudah digunakan untuk reset password
		deleteForgotPassword($forgotPassword->email);
		$message = "Password berhasil diganti. Silahkan klik tombol login.";
	} else {
		$message = "Password gagal diganti. Pastikan diisi dengan benar.";
	}
}

?>

<center>
	<h1>Reset Password</h1>
</center>

<form action="" method="post">
	<fieldset>
		<legend>Form reset password:</legend>
		<?php if($message != "") {
			echo "<font color=\"red\">".$message."</font>";
		}
		?>
		<div>
			<label>Masukan Password Baru</label> <br />
			<input type="password" placeholder="Password" name="password">
		</div>
		<br/>
		<div>
			<label>Masukan Ulang Password</label> <br />
			<input type="password" placeholder="Retype Password" name="password_confirmation">
		</div>
		<br/>
		<button type="submit" name="submit">Reset Password</button> | 
		<a href="../login.php">Login</a> | 
		<a href="../index.php">Halaman Utama</a>
	</fieldset>
</form>

<?php
require_once(__DIR__."/../partials/footer.php");

?>

