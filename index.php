<?php

require_once("partials/header.php");

if(isset($_POST['logout'])) {
	session_destroy();
	header("Location: index.php");
}

?>

<center>
	<h1>Belajar CRUD</h1>
	<p>Belajar CRUD dengan menggunakan PHP dan PDO menyenangkan</p>
</center>
<hr />

<?php
if(isset($_SESSION['user'])) {
?>

	<center>
		<div class="avatar">
			<img src="<?= $siteUrl.'uploads/'.$_SESSION['user']->avatar ?>" alt="<?= $_SESSION['user']->avatar ?>">
		</div>
		<h3>Selamat datang, <?= $_SESSION['user']->name ?></h3>
		<br />
		<form action="" method="post">
			<a href="setting.php">Pengaturan</a> | 
			<button type="submit" name="logout">Logout</button>
		</form>
		<br />
		<br />
	</center>

<?php
} else {
?>

	<center>
		<a href="login.php">Login</a> |
		<a href="register.php">Register</a>
	</center>

<?php
}

?>
<center>
	<br><br>
	<h3>User Yang Terdaftar</h3>
	<table border="1" cellpadding="10" cellspacing="0" width="50%">
		<thead>
			<tr>
				<th width="10%">No</th>
				<th>Nama</th>
				<th>Email</th>
				<th>Username</th>
				<?php if (isset($_SESSION['user'])): ?>
				<th>Aksi</th>	
				<?php endif ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach (getUsers()->fetchAll(PDO::FETCH_OBJ) as $idx=>$row): ?>
				<tr>
					<td><?= ++$idx ?></td>
					<td><?= $row->name ?></td>
					<td><a href="mailto:<?= $row->email ?>"><?= $row->email ?></a></td>
					<td><?= $row->username ?></td>
					<?php if (isset($_SESSION['user'])): ?>
						<td>
							<a href="deleteAccount.php?id=<?= $row->id ?>">Hapus</a>
						</td>
					<?php endif ?>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
</center>
<?php

require_once("partials/footer.php");
?>