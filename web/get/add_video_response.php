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

if($_user_fetch_utils->video_responded($_GET['reciever'], $_GET['sending']) == 0) {
    $stmt = $conn->prepare("INSERT INTO video_response (toid, author, video) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $_GET['reciever'], $_SESSION['siteusername'], $_GET['sending']);
    $stmt->execute();
    $stmt->close();
}

header('Location: /watch?v=' . htmlspecialchars($_GET['reciever']));
?>