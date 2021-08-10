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

  if(!isset($_SESSION['siteusername']))
    header("Location: /sign_in");

  $playlist = $_video_fetch_utils->fetch_playlist_rid($_GET['v']);
  $_base_utils->initialize_page_compass(htmlspecialchars($playlist['title']));

  $buffer = json_decode($playlist['videos']);
  @$rid = $buffer[0];
  if(!empty($rid)) {
      @$video = $_video_fetch_utils->fetch_video_rid($rid);
  } else {
      $video['thumbnail'] = "";
      $video['duration'] = 0;
  }

  $videos = count($buffer);
?>
<?php
if($_SERVER['REQUEST_METHOD'] == 'POST' && @$_POST['send']) {
    $_($_POST['to'], $_POST['subject'], $_POST['message'], $_SESSION['siteusername'], $conn);
    

    echo "<script>
        window.location = 'https://fulptube.rocks/inbox/';
    </script>";
}

    @$rid = $buffer[0];
    if(!empty($rid)) {
        @$video = $_video_fetch_utils->fetch_video_rid($rid);
    } else {
        $video['thumbnail'] = "";
        $video['duration'] = 0;
    }
?>
<?php $videos = json_decode($playlist['videos']); ?>
<!DOCTYPE html>
<html>
    <head>
        <title>SubRocks - <?php echo $_base_utils->return_current_page(); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/static/css/new/www-core.css">
        <style>
            table {
                font-family: arial, sans-serif;
                border-collapse: collapse;
                width: 100%;
            }

            td, th {
                text-align: left;
                padding: 3px;
            }

            th {
                border: 1px solid #dddddd;
                background: rgb(230,230,230);
                background: -moz-linear-gradient(0deg, rgba(230,230,230,1) 0%, rgba(255,255,255,1) 100%, rgba(255,255,255,1) 100%);
                background: -webkit-linear-gradient(0deg, rgba(230,230,230,1) 0%, rgba(255,255,255,1) 100%, rgba(255,255,255,1) 100%);
                background: linear-gradient(0deg, rgba(230,230,230,1) 0%, rgba(255,255,255,1) 100%, rgba(255,255,255,1) 100%);
                filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#e6e6e6",endColorstr="#ffffff",GradientType=1); 
            }

            tr:nth-child(even) {
                background-color: #f9f9f9;
            }
        </style>
    </head>
    <body>
        <div class="www-core-container">
            <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/header.php"); ?>
                <h3 style="display: inline-block;"><?php echo htmlspecialchars($playlist['title']); ?></h3> <br>
                <span style="font-size: 11px;" class="grey-text">by <?php echo htmlspecialchars($playlist['author']); ?><br>
                created <?php echo date("M d, Y", strtotime($playlist['created'])); ?></span><br><br>
                <p>
                    <b>Description:</b><br>
                    <?php echo $_video_fetch_utils->parseTextDescription($playlist['description']); ?>
                </p>
                <div style="float: right;position: relative;bottom: 108px;">
                    <div class="video-thumbnail r120" 
                    style="background-image: url('/dynamic/thumbs/<?php echo $video['thumbnail']; ?>'), url('/dynamic/thumbs/default.png');">
                        <div class="video-timestamp">
                            <span>
                            <?php echo $_video_fetch_utils->timestamp($video['duration']); ?>
                            </span>
                        </div>
                    </div>
                </div>
                <table style="width: 100%;position:relative;bottom: 62px;">
                    <tr>
                        <!-- <th style="margin: 5px; width: 5%;"></th> -->
                        <th style="width: 80%;"></th>
                        <th style="margin: 5px; width: 20%;"></th>
                    </tr>
                    <?php
                        $id = 1;
                        foreach($videos as $videoID) {
                            if(!empty($videoID)) {
                                $video = $_video_fetch_utils->fetch_video_rid($videoID); $id++; ?>
                            <tr style="margin-top: 5px;" id="videoslist">
                                <td class="video-manager-left">
                                    <span style="display: inline-block;float: right;"></span>
                                    <div class="video-thumbnail r120" 
                                    style="background-image: url('/dynamic/thumbs/<?php echo $video['thumbnail']; ?>'), url('/dynamic/thumbs/default.png');">
                                    <div class="video-timestamp">
                                        <span>
                                        <?php echo $_video_fetch_utils->timestamp($video['duration']); ?>
                                        </span>
                                    </div>
                                </div>
                                    <span class="video-manager-info">
                                    <a class="video-manager-title" href="watch?v=<?php echo $video['rid']; ?>"><?php echo htmlspecialchars($video['title']); ?></a>
                                    <br>
                                    <span style="color: #919191;">
                                        <span style="color: #333;">
                                            <?php echo date("F d, Y g:sA", strtotime($video['publish'])); ?> | 
                                            <small><?php echo $_video_fetch_utils->parseDescriptionVideoManager($video['description']); ?></small>
                                        </span>
                                    </span><br>   
                                </td>
                                <td class="video-manager-stats">
                                    <span class="video-manager-span" style="width:140px;">
                                        <span style="color: grey;font-weight:bold;">Views: </span><span style="float:right;"><?php echo $_video_fetch_utils->fetch_video_views($video['rid']); ?></span><br>
                                    </span><br>

                                    <span class="video-manager-span" style="width:140px;">
                                        <span style="color: grey;font-weight:bold;">Comments: </span><span style="float:right;"><?php echo $_video_fetch_utils->get_comments_from_video($video['rid']); ?></span>
                                    </span>

                                    <span class="video-manager-span" style="width:140px;">
                                        <span style="color: grey;font-weight:bold;">Video Responses: </span><span style="float:right;"><?php echo $_video_fetch_utils->get_video_responses($video['rid']); ?></span>
                                    </span>
                                </td>
                            </tr>
                    <?php } } ?>
                </table>
                <?php if($id == 1) { echo '<br><span style="font-size: 11px;" class="grey-text">This playlist has no videos. Ask the playlist creator to add some!</span>'; } ?>
                <?php for($page = 1; $page<= $number_of_page; $page++) { ?>
                    <a href="view_playlist?v=<?php echo htmlspecialchars($_GET['v']); ?>">
                        <button class="www-button www-button-grey"><?php echo $page; ?></button>
                    </a>
                <?php } ?>   
        </div>
        <div class="www-core-container">
        <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/footer.php"); ?>
        </div>

    </body>
</html>