<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/important/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/lib/new/base.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/lib/new/fetch.php"); ?>
<?php
  $_user_fetch_utils = new user_fetch_utils();
  $_video_fetch_utils = new video_fetch_utils();
  $_base_utils = new config_setup();
 
  $_base_utils->initialize_db_var($conn);
  $_video_fetch_utils->initialize_db_var($conn);
  $_user_fetch_utils->initialize_db_var($conn);

  $_base_utils->initialize_page_compass("post/upload");
?>
<?php

if(!isset($_SESSION['siteusername'])) {
    echo "<script>
    window.location = '/login';
  </script>";
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // keeping this in, just so the queuer doesnt die if it isnt programmed for the lack of annotations data
    $XML = "0";
    
    $uploadOk = true;

    // get duration + validate video
    $dur = shell_exec('ffprobe -i ' . $_FILES['fileToUpload']['tmp_name'] . ' -show_entries format=duration -v quiet -of csv="p=0"');
    if(empty($dur)) {
        $uploadOk = false;
    } else {
        $dur = round($dur);
    }
    
    //what the hell 
    // for the queue system
    // this is really dumb but this is the only solution i found!!!
    $newFile = "/dynamic/temp/" . md5_file($_FILES["fileToUpload"]["tmp_name"]) . ".mp4";
    
    if ($uploadOk) {
        function socketSafeChars($input) {
          $find = array(';', '');
          $repl = array('\;', '\\\\');  
          $input = str_replace($find, $repl, $input);
          return $input;
        }
        
        move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $newFile);

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
        $buffer = "pushQueue;" . $title . ";" . $description . ";" . $tags . ";" . $newFile . ";" . $author . ";" . $XML . ";" . $category;

        $fp = fsockopen("queuer", 1024, $errno, $errstr, 30);
        if (!$fp) {
            echo "$errstr ($errno)<br />\n";
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
