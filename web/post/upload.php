<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/important/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/lib/new/base.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/lib/new/fetch.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/lib/new/update.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/lib/new/insert.php"); ?>
<?php
    $_user_fetch_utils = new user_fetch_utils();
    $_video_fetch_utils = new video_fetch_utils();
    $_video_insert_utils = new video_insert_utils();
    $_user_insert_utils = new user_insert_utils();
    $_user_update_utils = new user_update_utils();
    $_base_utils = new config_setup();
    
    $_base_utils->initialize_db_var($conn);
    $_video_fetch_utils->initialize_db_var($conn);
    $_video_insert_utils->initialize_db_var($conn);
    $_user_fetch_utils->initialize_db_var($conn);
    $_user_insert_utils->initialize_db_var($conn);
    $_user_update_utils->initialize_db_var($conn);


  $_base_utils->initialize_page_compass("post/upload");
?>
<?php
function startsWith( $haystack, $needle ) {
    $length = strlen( $needle );
    return substr( $haystack, 0, $length ) === $needle;
}
function endsWith( $haystack, $needle ) {
    $length = strlen( $needle );
    if( !$length ) {
        return true;
    }
    return substr( $haystack, -$length ) === $needle;
}

if(!isset($_SESSION['siteusername'])) {
    die();
}

if($_user_fetch_utils->if_upload_cooldown($_SESSION['siteusername'])) { die("cooldown (10 minutes)"); }
$_user_update_utils->update_upload_cooldown_time($_SESSION['siteusername']);

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $XML = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>" . PHP_EOL . "<document>" . PHP_EOL;

    $annotext = array();
    $annowidth = array();
    $annoheight = array();
    $locx = array();
    $locy = array();
    $textcolor = array();
    $rectcolor = array();
    $start = array();
    $end = array();

    foreach($_POST as $key => $value) {
        //death
        if(startsWith($key, "annotext_")) {
            array_push($annotext, array($key, $value));
        } else if(startsWith($key, "annowidth_")) {
            array_push($annowidth, array($key, (double)$value));
        } else if(startsWith($key, "annoheight_")) {
            array_push($annoheight, array($key, (double)$value));
        } else if(startsWith($key, "locx_")) {
            array_push($locx, array($key, (int)$value));
        } else if(startsWith($key, "locy_")) {
            array_push($locy, array($key, (int)$value));
        } else if(startsWith($key, "textcolor_")) {
            array_push($textcolor, array($key, $value));
        } else if(startsWith($key, "rectcolor_")) {
            array_push($rectcolor, array($key, $value));
        } else if(startsWith($key, "start_")) {
            array_push($start, array($key, (double)$value));
        } else if(startsWith($key, "end_")) {
            array_push($end, array($key, (double)$value));
        }
    }

    foreach($annotext as $key => $value) {
        $XML = $XML . "   <annotation width=\"" . $annowidth[$key][1] . "\" height=\"" . $annoheight[$key][1] . "\" x=\"" . $locx[$key][1] . "\" y=\"" . $locy[$key][1] . "\" color=\"" . htmlspecialchars($rectcolor[$key][1]) . "\" start=\"" . $start[$key][1] . "\" end=\"" . $end[$key][1] . "\">" . PHP_EOL;
        $XML = $XML . "       <text size=\"14\" color=\"" . htmlspecialchars($textcolor[$key][1]) . "\">" . htmlspecialchars($annotext[$key][1]) . "</text>" . PHP_EOL;
        $XML = $XML . "    </annotation>" . PHP_EOL;
    }
    $XML = $XML . "</document>" . PHP_EOL;

    $uploadOk = true;
    $movedFile = false;

    $songFileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION));
    $target_name = md5_file($_FILES["fileToUpload"]["tmp_name"]) . "." . $songFileType;

    // get duration
    $dur = shell_exec('ffprobe -i ' . $_FILES['fileToUpload']['tmp_name'] . ' -show_entries format=duration -v quiet -of csv="p=0"');
    $dur = round($dur);

    //what the hell 
    // for the queue system
    // this is really dumb but this is the only solution i found!!!
    $newFile = "../dynamic/temp/" . md5_file($_FILES["fileToUpload"]["tmp_name"]) . ".mp4";
    $queuerFile = "/dynamic/temp/" . md5_file($_FILES["fileToUpload"]["tmp_name"]) . ".mp4";
        move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $newFile);

    if ($uploadOk) {
        function socketSafeChars($input) {
          $find = array(';', '');
          $repl = array('\;', '\\\\');  
          $input = str_replace($find, $repl, $input);
          return $input;
        }

        //$rid = base64_encode(time() . rand(0, 9)) . rand(0, 9) . rand(0, 9);
        $title = socketSafeChars($_POST['title']);
        $description = socketSafeChars($_POST['comment']);
        $tags = socketSafeChars($_POST['tags']);
        $category = socketSafeChars($_POST['category']);
        $privacy = socketSafeChars($_POST['privacy']);
        $XML = socketSafeChars($XML);
        //$filename = md5_file($_FILES["fileToUpload"]["tmp_name"]) . ".mp4";
        $socketfilename = socketSafeChars($_FILES["fileToUpload"]["tmp_name"]);
        //$thumbnail = md5_file($_FILES["fileToUpload"]["tmp_name"]) . ".png";
        $author = socketSafeChars($_SESSION['siteusername']);
        $buffer = "pushQueue;" . $title . ";" . $description . ";" . $tags . ";" . $queuerFile . ";" . $author . ";" . $XML . ";" . $category;

        $fp = fsockopen("queuer", 1024, $errno, $errstr, 30);
        if (!$fp) {
           // echo "$errstr ($errno)<br />\n";
        } else {
            fwrite($fp, $buffer);
            fclose($fp);
        }

        skipcomment:

        //echo print_r($_POST);

        //$stmt->execute();
        //$stmt->close();
    }
    skip:
}
?>