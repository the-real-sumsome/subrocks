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
$name = $_GET['n'];
$author = $_SESSION['siteusername'];
if(isset($_SESSION['siteusername'])) { 
    $_user_insert_utils->send_message($_GET['user'], "New subscriber", 'I subscribed to your channel!', $_SESSION['siteusername']);
}

if($name == $_SESSION['siteusername']) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

if(!$_user_fetch_utils->user_exists($_GET['n'])) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

$stmt = $conn->prepare("SELECT * FROM subscribers WHERE sender = ? AND reciever = ?");
$stmt->bind_param("ss", $_SESSION['siteusername'], $name);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows === 1) die('You already are subscribed to this person!');
$stmt->close();

$stmt = $conn->prepare("INSERT INTO subscribers (sender, reciever) VALUES (?, ?)");
$stmt->bind_param("ss", $_SESSION['siteusername'], $name);

$stmt->execute();
$stmt->close();
$author = htmlspecialchars($_SESSION['siteusername']);
header('Location: ' . $_SERVER['HTTP_REFERER']);
?>