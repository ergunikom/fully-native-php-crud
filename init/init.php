<?php

session_start();

require_once("db.php");
require_once("functions.php");

$conn = new Database();
$siteUrl = "http://localhost/latihan_crud/";