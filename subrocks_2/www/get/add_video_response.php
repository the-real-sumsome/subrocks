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
$stmt = $conn->prepare("INSERT INTO video_response (toid, author, video) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $_GET['reciever'], $_SESSION['siteusername'], $_GET['sending']);
$stmt->execute();
$stmt->close();

header('Location: /watch?v=' . htmlspecialchars($_GET['reciever']));
?>