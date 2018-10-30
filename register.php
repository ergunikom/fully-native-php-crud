<?php

$title = "Register - ";

require_once("partials/header.php");

if(isset($_SESSION['user'])) {
	header("Location: index.php");
}

?>

<center>
	<h1>Register User</h1>
</center>

<form action="sendEmail.php" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend>Register Form:</legend>
		<?php if(isset($_GET['success'])) {
			$message = $_GET['success'] == "true" ? "Register berhasil. silahkan konfrimasi akun." : "Gagal register. pastikan sudah mengisi data dengan benar.";
			echo "<font color=\"red\">".$message."</font>";
		}
		?>

		<?php if(isset($_SESSION['message'])) {
			$message = $_SESSION['message'];
			echo "<br/><font color=\"red\">".$message."</font>";
			unset($_SESSION['message']);
		}
		?>
		<div>
			<label>Nama</label> <br />
			<input type="text" placeholder="Nama" name="nama">
		</div>
		<br/>
		<div>
			<label>Email</label> <br />
			<input type="text" placeholder="Email" name="email">
		</div>
		<br/>
		<div>
			<label>Avatar</label> <br />
			<input type="file" name="avatar">
		</div>
		<br/>
		<div>
			<label>Username</label> <br />
			<input type="text" placeholder="Username" name="username">
		</div>
		<br/>
		<div>
			<label>Password</label> <br />
			<input type="password" placeholder="Password" name="password">
		</div>
		<br/>
		<button type="submit" name="register">Register</button> | 
		<a href="login.php">Login</a> | 
		<a href="index.php">Halaman Utama</a>
	</fieldset>
</form>

<?php
require_once("partials/footer.php");