<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/important/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/lib/new/base.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/lib/new/fetch.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/lib/new/insert.php"); ?>
<?php
    $_user_fetch_utils = new user_fetch_utils();
    $_video_fetch_utils = new video_fetch_utils();
    $_video_insert_utils = new video_insert_utils();
    $_user_insert_utils = new user_insert_utils();
    $_base_utils = new config_setup();
    
    $_base_utils->initialize_db_var($conn);
    $_video_fetch_utils->initialize_db_var($conn);
    $_video_insert_utils->initialize_db_var($conn);
    $_user_fetch_utils->initialize_db_var($conn);
    $_user_insert_utils->initialize_db_var($conn);
?>
<?php 
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $error = false;

    if(!isset($_SESSION['siteusername'])){ $error = true; }
    if(!$_POST['comment']){ $error = true; }
    if(strlen($_POST['comment']) > 1000){ $error = true; }

    if($error == false) {
        $stmt = $conn->prepare("INSERT INTO `comment_reply` (toid, author, comment) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $_POST['id'], $_SESSION['siteusername'], $text);
        $text = $_POST['comment'];
        $stmt->execute();
        $stmt->close();
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
}
?>