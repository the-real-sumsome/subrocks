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

    $_base_utils->initialize_page_compass("Admin Panel");

    if(!$_user_fetch_utils->is_admin($_SESSION['siteusername']))
        header("Location: /");
?>
<?php
 function processExists($processName) {
    $exists= false;
    exec("ps -A | grep -i $processName | grep -v grep", $pids);
    if (count($pids) > 0) {
        $exists = true;
    }
    return $exists;
}

$stat1 = file('/proc/stat'); 
sleep(1); 
$stat2 = file('/proc/stat'); 
$info1 = explode(" ", preg_replace("!cpu +!", "", $stat1[0])); 
$info2 = explode(" ", preg_replace("!cpu +!", "", $stat2[0])); 
$dif = array(); 
$dif['user'] = $info2[0] - $info1[0]; 
$dif['nice'] = $info2[1] - $info1[1]; 
$dif['sys'] = $info2[2] - $info1[2]; 
$dif['idle'] = $info2[3] - $info1[3]; 
$total = array_sum($dif); 
$cpu = array(); 
foreach($dif as $x=>$y) $cpu[$x] = round($y / $total * 100, 1);

function convert($size)
{
    $unit=array('b','kb','mb','gb','tb','pb');
    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
}

function abbreviateNumber($num) {
    if ($num >= 0 && $num < 1000) {
      $format = floor($num);
      $suffix = '';
    } 
    else if ($num >= 1000 && $num < 1000000) {
      $format = floor($num / 1000);
      $suffix = 'K+';
    } 
    else if ($num >= 1000000 && $num < 1000000000) {
      $format = floor($num / 1000000);
      $suffix = 'M+';
    } 
    else if ($num >= 1000000000 && $num < 1000000000000) {
      $format = floor($num / 1000000000);
      $suffix = 'B+';
    } 
    else if ($num >= 1000000000000) {
      $format = floor($num / 1000000000000);
      $suffix = 'T+';
    }
    
    return !empty($format . $suffix) ? $format . $suffix : 0;
  }

  function get_server_memory_usage(){

    $free = shell_exec('free');
    $free = (string)trim($free);
    $free_arr = explode("\n", $free);
    $mem = explode(" ", $free_arr[1]);
    $mem = array_filter($mem);
    $mem = array_merge($mem);
    $memory_usage = $mem[2]/$mem[1]*100;

    return $memory_usage;
}

$ffmpeg = processExists("ffmpeg"); 
if($ffmpeg == true) { $ffmpeg = "Active"; } else { $ffmpeg = "Inactive"; }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>SubRocks - <?php echo $_base_utils->return_current_page(); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/static/css/new/www-core.css">
    </head>
    <body>
        <div class="www-core-container">
            <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/header.php"); ?>
            <h1>Admin Panel</h1>
            <?php
                echo "<b style='color: #c12c2c; font-size: 20px;'>Server</b><br>";
                echo "<b style='color: #c12c2c'>" . $cpu['sys'] . "%</b>: CPU Usage<br>";
                echo "<b style='color: #c12c2c'>" . convert(memory_get_usage(true)) . "</b>: RAM allocated to PHP<br>";
                echo "<b style='color: #c12c2c;'>" . phpversion() . "</b>: PHP Version<br>";
                echo "<b style='color: #c12c2c'>" . $ffmpeg . "</b>: FFMpeg status<br><hr class='thin-line'><br>";
        ?>
            <form action="delog">
                <b>Completely wipe user from database and their videos</b> <br><span style="font-size: 11px;" class="grey-text">Does not delete the video files itselves.</span>
                <span style="float: right;">
                    <input name="username" placeholder="Username"> 
                    <input type="submit" value="Remove">
                </span>
            </form><br>
            <form action="deletevid">
                <b>Delete Specific Video</b> <br><span style="font-size: 11px;" class="grey-text">DO NOT include subrocks.rocks/watch?v= in your query. Only video ID.</span>
                <span style="float: right;">
                    <input name="vid" placeholder="Video ID"> 
                    <input type="submit" value="Remove">
                </span>
            </form><br>
            <form action="featurevid">
                <b>Feature a specific video</b> <br><span style="font-size: 11px;" class="grey-text">If you feature a shitty video, I will unfeature it.</span>
                <span style="float: right;">
                    <input name="vid" placeholder="Video ID"> 
                    <input type="submit" value="Feature" style="width: 63px;">
                </span>
            </form><br>
            <form action="deletethread">
                <b>Delete forum thread</b> <br><span style="font-size: 11px;" class="grey-text">Copy the <b>v</b> parameter in your thread that you are trying to delete. ex: /forum/thread?v=<b>3</b></span>
                <span style="float: right;">
                    <input name="id" placeholder="Thread ID"> 
                    <input type="submit" value="Delete" style="width: 63px;">
                </span>
            </form>
        </div>
        <div class="www-core-container">
        <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/footer.php"); ?>
        </div>

    </body>
</html>