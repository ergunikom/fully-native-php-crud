<?php

$title = "Setting User - ";

require_once("partials/header.php");

if(!isset($_SESSION['user'])) {
	header("Location: login.php");
}

if(isset($_POST['setting'])) {
	if(!updateUser($_SESSION['user']->id, array_merge($_POST,$_FILES))) {
		header("Location: setting.php?success=false");
	} else {
		header("Location: setting.php?success=true");
	}
}

?>

<center>
	<h1>Setting User</h1>
</center>

<form action="" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend>Setting User:</legend>
		<?php if(isset($_GET['success'])) {
			$message = $_GET['success'] == "true" ? "Berhasil menyimpan data." : "Gagal menyimpan data.";
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
			<input type="text" placeholder="Nama" value="<?= $_SESSION['user']->name ?>" name="nama">
		</div>
		<br/>
		<div>
			<label>Email</label> <br />
			<input type="text" placeholder="Email" value="<?= $_SESSION['user']->email ?>" name="email" disabled>
		</div>
		<br/>
		<div>
			<label>Avatar</label> <br />
			<input type="file" name="avatar">
		</div>
		<br/>
		<div>
			<label>Username</label> <br />
			<input type="text" value="<?= $_SESSION['user']->username ?>" placeholder="Username" name="username" disabled>
		</div>
		<br/>
	</fieldset>
	<br />
	<fieldset>
		<legend>Ganti Password</legend>
		<div>
			<label>Password</label> <br />
			<input type="password" placeholder="Password" name="password">
		</div>
		<br />
		<div>
			<label>Masukan Ulang Password</label> <br />
			<input type="password" placeholder="Password" name="password_confirmation">
		</div>
	</fieldset>
	<br />
	<button type="submit" name="setting">Simpan</button> | 
	<a href="deleteAccount.php">Hapus Akun</button> | 
	<a href="index.php">Halaman Utama</a>
</form>

<?php
require_once("partials/footer.php");