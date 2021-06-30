<?php ob_start(); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/important/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/lib/new/base.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/lib/new/fetch.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/lib/new/insert.php"); ?>
<?php
  $_user_fetch_utils = new user_fetch_utils();
  $_user_insert_utils = new user_insert_utils();
  $_video_fetch_utils = new video_fetch_utils();
  $_base_utils = new config_setup();
 
  $_base_utils->initialize_db_var($conn);
  $_video_fetch_utils->initialize_db_var($conn);
  $_user_insert_utils->initialize_db_var($conn);
  $_user_fetch_utils->initialize_db_var($conn);
?>
<?php
session_start();
$name = $_GET['v'];

if(!isset($_SESSION['siteusername']) || !isset($_GET['v'])) {
    die("You are not logged in or you did not put in an argument");
}

$stmt = $conn->prepare("SELECT * FROM likes WHERE sender = ? AND reciever = ? AND type = 'l'");
$stmt->bind_param("ss", $_SESSION['siteusername'], $name);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows === 1) {
        $_user_insert_utils->remove_like_video($_SESSION['siteusername'], $name);
        goto skip;
    }

$stmt = $conn->prepare("SELECT * FROM likes WHERE sender = ? AND reciever = ? AND type = 'd'");
$stmt->bind_param("ss", $_SESSION['siteusername'], $name);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows === 1) {
        $_user_insert_utils->remove_like_video($_SESSION['siteusername'], $name);
        goto skip;
    }
$stmt->close();

$stmt = $conn->prepare("INSERT INTO likes (sender, reciever, type) VALUES (?, ?, 'l')");
$stmt->bind_param("ss", $_SESSION['siteusername'], $name);

$stmt->execute();
$stmt->close();

// sendIt($_SESSION['siteusername'], "New like", 'You have recieved a new like on ' . $name, "System Message", $conn);
skip:
header('Location: ' . $_SERVER['HTTP_REFERER']);
?>