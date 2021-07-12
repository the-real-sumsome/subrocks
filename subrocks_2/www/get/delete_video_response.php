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

    $video_response = $_video_fetch_utils->get_video_response($_GET['id']);
    $video = $_video_fetch_utils->fetch_video_rid($video_response['toid']);
?>
<?php
if($video['author'] == $_SESSION['siteusername']) {
    $stmt = $conn->prepare("DELETE FROM video_response WHERE id = ?");
    $stmt->bind_param("s", $_GET['id']);
    $stmt->execute();
    $stmt->close();
}

header('Location: /watch?v=' . htmlspecialchars($video['rid']));
?>