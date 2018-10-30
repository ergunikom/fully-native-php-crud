<?php

$title = "Lupa Password - ";
$message = "";

require_once("partials/header.php");

if(isset($_SESSION['user'])) {
	header("Location: index.php");
}

?>

<center>
	<h1>Lupa Password</h1>
</center>

<form action="sendEmail.php" method="post">
	<?php if(isset($_GET['error'])) {
		echo "<font color=\"red\">Email tidak ditemukan.</font>"; 
	} elseif(isset($_GET['success'])) {
		echo "<font color=\"blue\">Link forgot password sudah terkirim. Cek email anda</font>";
	}
	?>
	<fieldset>
		<legend>Lupa Password:</legend>
		<div>
			<label>Masukan Email</label> <br />
			<input type="email" placeholder="Email" name="email">
		</div>
		<br/>
		<button type="submit" name="forgot">Kirim Email</button> | 
		<a href="login.php">Login</a> | 
		<a href="index.php">Halaman Utama</a>
	</fieldset>
</form>

<?php
require_once("partials/footer.php");