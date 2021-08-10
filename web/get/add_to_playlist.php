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
$playlist = $_video_fetch_utils->fetch_playlist_rid($_GET['playlist']);

if($playlist['author'] == $_SESSION['siteusername']) {
    $b = json_decode($playlist['videos']);
    array_push($b, $_GET['id']);
    $b = json_encode($b);
    
    $stmt = $conn->prepare("UPDATE playlists SET videos = ? WHERE rid = ?");
    $stmt->bind_param("ss", $b, $_GET['playlist']);
    $videos = $playlist['videos'] . "|" . $_GET['playlist'];
    $stmt->execute();
    $stmt->close();
}

header('Location: /playlists');
?>