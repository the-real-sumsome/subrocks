<?php
session_start();

$config = array(); // add other stuff here
$post = array(); // used for handling post inputs, put sanitized inputs into here!!!

$config['db_host'] = getenv("MYSQL_HOST");
$config['db_user'] = getenv("MYSQL_USER");
$config['db_pass'] = getenv("MYSQL_PASSWORD");
$config['db_name'] = getenv("MYSQL_DATABASE");

$config['recaptcha_secret'] = ""; 
$config['recaptcha_sitekey'] = "";

$conn = mysqli_connect($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);
?>