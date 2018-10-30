<?php require_once(__DIR__.'/../init/init.php') ?>

<!DOCTYPE html>
<html>
<head>
	<title><?= isset($title) ? $title : '' ?>Belajar CRUD</title>
	<style>
		* {
			box-sizing: border-box;
		}
		input {
			border: 1px solid #ddd;
			outline: none;
			padding: 8px;
			width: 260px;
			margin-top: 2px;
		}
		input:focus {
			border-color: #999;
		}
		fieldset {
			border: 1px solid #999;
		}
		.avatar {
			width: 100px;
			height: 100px;
			border-radius: 100%;
			overflow: hidden;
			border: 2px solid #ddd;
		}
		.avatar img {
			width: 100%;
			height: 100%;
			object-fit: cover;
		}
	</style>
</head>
<body>