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
header("Content-Type: application/json");
$array = new stdClass();

if(isset($_GET['name'])) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $_GET['name']);
    $stmt->execute();
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()) {
        if($_user_fetch_utils->user_exists($_GET['name'])) {
            $array->id = $row['id'];
            $array->username = $row['username'];
            $array->created = $row['created'];
            $array->lastlogin = $row['lastlogin'];
            $array->bio = $row['bio'];
            $array->pfp = $row['pfp'];
            $array->banner = $row['banner'];
            $array->featured = $row['featured'];
            $array->status = $row['status'];
            $array->channel_layout = json_decode($row['channel_layout']);
            $array->bg_2012 = $row['2012_bg'];
            $array->bgoption_2012 = $row['2012_bgoption'];
            $array->bgcolor_2012 = $row['2012_bgcolor'];
            $array->exists = 1;
            echo json_encode($array);
        } else {
            $array->exists = 0;
            echo json_encode($array);
        }
    }
} else {
    $array->response = "You did not specify a name.";
    echo json_encode($array);
}