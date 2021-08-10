<?php 
require($_SERVER['DOCUMENT_ROOT'] . "/static/important/config.inc.php");

if(empty($_SESSION['siteusername'])) {
    die(":)");
}

if(isset($_SESSION['siteusername'])) {
    $_SESSION = [];
    session_destroy();
}
header("Location: /");
die();