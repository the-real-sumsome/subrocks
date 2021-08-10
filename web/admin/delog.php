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
if($_user_fetch_utils->is_admin($_SESSION['siteusername'])) {
    $_user_insert_utils->send_logs("DELOG " . $_GET['username'] . " by " . $_SESSION['siteusername']);

    $stmt = $conn->prepare("DELETE FROM users WHERE username = ?");
    $stmt->bind_param("s", $_GET['username']);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM comments WHERE author = ?");
    $stmt->bind_param("s", $_GET['username']);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM pms WHERE owner = ?");
    $stmt->bind_param("s", $_GET['username']);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM comment_reply WHERE author = ?");
    $stmt->bind_param("s", $_GET['username']);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM videos WHERE author = ?");
    $stmt->bind_param("s", $_GET['username']);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM subscribers WHERE reciever = ?");
    $stmt->bind_param("s", $_GET['username']);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM channel_views WHERE channel = ?");
    $stmt->bind_param("s", $_GET['username']);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM comment_likes WHERE sender = ?");
    $stmt->bind_param("s", $_GET['username']);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM favorite_video WHERE sender = ?");
    $stmt->bind_param("s", $_GET['username']);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM friends WHERE reciever = ?");
    $stmt->bind_param("s", $_GET['username']);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM friends WHERE sender = ?");
    $stmt->bind_param("s", $_GET['username']);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM history WHERE author = ?");
    $stmt->bind_param("s", $_GET['username']);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM forum_thread WHERE author = ?");
    $stmt->bind_param("s", $_GET['username']);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM forum_replies WHERE author = ?");
    $stmt->bind_param("s", $_GET['username']);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM pms WHERE owner = ?");
    $stmt->bind_param("s", $_GET['username']);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM pms WHERE touser = ?");
    $stmt->bind_param("s", $_GET['username']);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM profile_comments WHERE toid = ?");
    $stmt->bind_param("s", $_GET['username']);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM profile_comments WHERE author = ?");
    $stmt->bind_param("s", $_GET['username']);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM quicklist_videos WHERE author = ?");
    $stmt->bind_param("s", $_GET['username']);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM stars WHERE sender = ?");
    $stmt->bind_param("s", $_GET['username']);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM subscribers WHERE sender = ?");
    $stmt->bind_param("s", $_GET['username']);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM comment_likes WHERE sender = ?");
    $stmt->bind_param("s", $_GET['username']);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM video_response WHERE author = ?");
    $stmt->bind_param("s", $_GET['username']);
    $stmt->execute();
    $stmt->close();

    header("Location: /admin/");
}
?>