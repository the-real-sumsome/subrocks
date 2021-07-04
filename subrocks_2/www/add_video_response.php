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

  $_base_utils->initialize_page_compass("Adding a video response");

  $_video = $_video_fetch_utils->fetch_video_rid($_GET['v']);
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
            <img style="height: 46px;vertical-align: top;width: 83px;" src="/dynamic/thumbs/<?php echo $_video['thumbnail']; ?>">
            You are posting a Video Response to: <a href="/watch?v=<?php echo htmlspecialchars($_video['rid']); ?>"><?php echo htmlspecialchars($_video['title']); ?></a><br><br>
            <div style="width: 600px; background-color: #9b9b9b;">
                <div style="display: inline-block;padding-top: 6px; padding-left: 6px;font-size: 14px;width: 118px;background-color: #646464; color: white;height: 20px;">
                    <b>Choose a Video</b>
                </div>
                <div style="display: inline-block;padding-top: 6px; padding-left: 6px;font-size: 14px;width: 118px; color: #000;height: 20px;">
                    <b>Upload a Video</b>
                </div>
            </div>
            <div style="background-color: whitesmoke; width: 590px; padding: 5px">
                <div style="width: 251px;">
                    <b>Choose one of your existing videos as a response</b><br>* Indicates that the video has already been used for another video response. Selecting a video marked as already having been used will remove the old link.
                    <div style="background-color: #9b9b9b;float: right; position: relative; left: 344px; bottom: 83px;">
                        Select the video you want to respond with:<br><br>
                        <div style="background-color: white; overflow-y: scroll; height: 300px; border: 1px solid black;">
                        <?php
                            $stmt = $conn->prepare("SELECT * FROM videos WHERE author = ?");
                            $stmt->bind_param("s", $_SESSION['siteusername']);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            if($result->num_rows === 0) exit('No rows');
                            while($row = $result->fetch_assoc()) { ?>
                                <a style="color: black;" href="/get/add_video_response?reciever=<?php echo $_GET['v']; ?>&sending=<?php echo $row['rid']; ?>"><?php echo htmlspecialchars($row['title']); ?></a><br>
                        <?php
                            }
                            $stmt->close();
                        ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="www-core-container">
        <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/footer.php"); ?>
        </div>

    </body>
</html>