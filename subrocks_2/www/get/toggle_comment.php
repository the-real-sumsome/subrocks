<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/important/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/lib/new/base.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/lib/new/fetch.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/lib/new/update.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/lib/new/delete.php"); ?>
<?php
    $_user_fetch_utils = new user_fetch_utils($conn);
    $_video_fetch_utils = new video_fetch_utils($conn);
    $_video_delete_utils = new video_delete_utils($conn);
    $_base_utils = new config_setup($conn);
    $_video_update_utils = new video_update_utils($conn);

    $video = $_video_fetch_utils->fetch_video_rid($_GET['id']);
?>
<?php

if($video['author'] == $_SESSION['siteusername']) {
    if($video['commenting'] == "a") {
        $_video_update_utils->update_video_commenting($_GET['id'], "d");
    } else {
        $_video_update_utils->update_video_commenting($_GET['id'], "a");
    }
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
?>