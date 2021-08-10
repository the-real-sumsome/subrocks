<?php ob_start(); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/important/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/lib/new/base.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/lib/new/fetch.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/lib/new/insert.php"); ?>
<?php
    $_user_fetch_utils = new user_fetch_utils();
    $_video_fetch_utils = new video_fetch_utils();
    $_user_insert_utils = new user_insert_utils();
    $_base_utils = new config_setup();
    
    $_base_utils->initialize_db_var($conn);
    $_video_fetch_utils->initialize_db_var($conn);
    $_user_fetch_utils->initialize_db_var($conn);
    $_user_insert_utils->initialize_db_var($conn);

?>
<?php

if($_SESSION['siteusername'] == $_GET['sending'])
    die("You can't friend yourself!");

$stmt = $conn->prepare("SELECT * FROM friends WHERE sender = ? AND reciever = ?");
$stmt->bind_param("ss", $_SESSION['siteusername'], $_GET['sending']);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows === 1) die('You already sent a friend request to this person');
$stmt->close();

$stmt = $conn->prepare("INSERT INTO friends (sender, reciever, status) VALUES (?, ?, 'u')");
$stmt->bind_param("ss", $_SESSION['siteusername'], $_GET['sending']);

$stmt->execute();
$stmt->close();

$_user_insert_utils->send_message($_GET['sending'], "New friend request", 'I sent you a new friend request!', $_SESSION['siteusername']);
header('Location: /friends');
?>