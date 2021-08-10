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

$search = "%" . $_GET['q'] . "%";

if(isset($_GET['q'])) {
    $stmt = $conn->prepare("SELECT * FROM videos WHERE lower(title) LIKE lower(?) ORDER BY id DESC LIMIT 20");
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $result = $stmt->get_result();
    $id = 0;

    while($row = $result->fetch_assoc()) {
        $videoF = $_video_fetch_utils->fetch_video_rid($row['rid']);
        $id++;
        $array->video[$id] = $videoF;
        $array->exists = 1;
        echo json_encode($array);
    }
} else {
    $array->response = "You did not specify a query.";
    echo json_encode($array);
}