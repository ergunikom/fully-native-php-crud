<?php
$title = "Login - ";
$message = "";

require_once("partials/header.php");

if(isset($_SESSION['user'])) {
	header("Location: index.php");
}

if(isset($_POST['submit'])) {
	$login = loginUser($_POST);
	if(isset($login['message'])) {
		$message = $login['message'];
	} else {
		header("Location: index.php");
	}
}

?>

<center>
	<h1>Login User</h1>
</center>

<form action="" method="post">
	<fieldset>
		<legend>Login Form:</legend>
		<?php if($message != "") {
			echo "<font color=\"red\">".$message."</font>";
		}
		?>
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
		<button type="submit" name="submit">Login</button> | 
		<a href="forgot.php">Lupa Password</a> | 
		<a href="register.php">Register</a> | 
		<a href="index.php">Halaman Utama</a>
	</fieldset>
</form>

<?php
require_once("partials/footer.php");