<?php ob_start(); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/important/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/lib/new/base.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/lib/new/fetch.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/lib/new/insert.php"); ?>
<?php
    $_user_fetch_utils = new user_fetch_utils($conn);
    $_video_fetch_utils = new video_fetch_utils($conn);
    $_user_insert_utils = new user_insert_utils($conn);
    $_base_utils = new config_setup($conn);
?>
<?php
$name = $_GET['v'];
$author = $_SESSION['siteusername'];

if(!isset($_SESSION['siteusername']) || !isset($_GET['user'])) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

$stmt = $conn->prepare("SELECT * FROM favorite_video WHERE sender = ? AND reciever = ?");
$stmt->bind_param("ss", $_SESSION['siteusername'], $name);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows === 1) die('You already favorited this video!');
$stmt->close();

$stmt = $conn->prepare("INSERT INTO favorite_video (sender, reciever) VALUES (?, ?)");
$stmt->bind_param("ss", $_SESSION['siteusername'], $name);

$stmt->execute();
$stmt->close();
$author = htmlspecialchars($_SESSION['siteusername']);
header('Location: ' . $_SERVER['HTTP_REFERER']);
?>