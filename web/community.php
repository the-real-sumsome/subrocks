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

  $_base_utils->initialize_page_compass("Community");
?>
<!DOCTYPE html>
<html>
    <head>
        <title>SubRocks - <?php echo $_base_utils->return_current_page(); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/static/css/new/www-core.css">
        <style>
            .channel-box-top {
                background: #666;
                color: white;
                padding: 5px;
            }

            .sub_button {
                position: relative;
                bottom: 2px;
            }

            .channel-box-description {
                background: #e6e6e6;
                border: 1px solid #666;
                color: #666;
                padding: 5px;
            }

            .channel-box-no-bg {
                border: 1px solid #666;
                color: black;
                padding: 5px;
            }

            .channel-pfp {
                height: 88px;
                width: 88px;
                border-color: #666;
                border: 3px double #999;
            }

            .channel-stats {
                display: inline-block;
                vertical-align: top;
            }

            .channel-stats-minor {
                font-size: 11px;
            }
            
            .comment-pfp {
                width: 52px;
                height: 52px;
                border-color: #666;
                display: inline-block;
                border: 3px double #999;
            }
        </style>
    </head>
    <body>
        <div class="www-core-container">
            <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/header.php"); ?>
            <h1>Community / Forums</h1>
            <span style="font-size: 11px;" class="grey-text">Talk to other users here.</span>
            <?php
                $stmt = $conn->prepare("SELECT * FROM forum_category ORDER BY id");
                $stmt->execute();
                $result = $stmt->get_result();
                while($category = $result->fetch_assoc()) {
                    $latest_forum_post = $_user_fetch_utils->fetch_latest_forum_post($category['title']);
					$latest_forum_poster = htmlspecialchars($latest_forum_post['author']);
            ?>
                <hr class="thin-line">
                <h3 style="display: inline-block;"><a href="/forum/category?c=<?php echo urlencode($category['title']); ?>"><?php echo htmlspecialchars($category['title']); ?></a></h3>
                <span style="font-size: 11px; color: gray;">(<?php echo $_video_fetch_utils->get_category_threads($category['title']); ?> threads)</span>
                <br>
                <?php echo htmlspecialchars($category['description']); ?><br><br>
                <a href="/forum/thread?v=<?php echo $latest_forum_post['id']; ?>"><h4><?php echo htmlspecialchars($latest_forum_post['title']); ?></h4></a>
                <div class="comment-watch">
					<a href="/user/<?php echo $latest_forum_poster; ?>">
                    <img class="comment-pfp" src="/dynamic/pfp/<?php echo $_user_fetch_utils->fetch_user_pfp($latest_forum_post['author']); ?>">
					</a>
                    <span  style="display: inline-block; vertical-align: top;width: 562px;;">
                        <span class="comment-info" style="display: inline-block;">
                            <b><a style="text-decoration: none;" href="/user/<?php echo $latest_forum_poster; ?>">
                                <?php echo htmlspecialchars($latest_forum_post['author']); ?> 
                            </a></b> 
                            <span style="color: #666;">(<?php echo $_video_fetch_utils->time_elapsed_string($latest_forum_post['date']); ?>)</span>
                        </span><br>
                        <span class="comment-text" style="display: inline-block;">
                            <?php echo $_video_fetch_utils->parseTextDescription($latest_forum_post['contents']); ?>
                        </span>
                    </span>

                </div>
            <?php } ?>
        </div>
        <div class="www-core-container">
        <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/footer.php"); ?>
        </div>

    </body>
</html>