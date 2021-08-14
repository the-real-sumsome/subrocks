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

  $_category = $_user_fetch_utils->fetch_category_name($_GET['c']);

  $_base_utils->initialize_page_compass($_category['title']);
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
            <h1><?php echo htmlspecialchars($_category['title']); ?></h1>
            <span style="font-size: 11px;" class="grey-text"><?php echo htmlspecialchars($_category['description']); ?></span><br>
            <a style="color: white;" href="new_thread?c=<?php echo htmlspecialchars($_category['title']); ?>">
                <button class="www-button www-button-grey">New Thread</button>
            </a>
            <?php
                $stmt = $conn->prepare("SELECT * FROM forum_thread WHERE category = ? ORDER BY id DESC");
                $stmt->bind_param("s", $_category['title']);
                $stmt->execute();
                $result = $stmt->get_result();
                while($thread = $result->fetch_assoc()) {
                    $latest_forum_post_reply = $_user_fetch_utils->fetch_latest_forum_post_reply($thread['id']);
            ?>
                <hr class="thin-line">
                <h3 style="display: inline-block;"><a href="/forum/thread?v=<?php echo $thread['id']; ?>"><?php echo htmlspecialchars($thread['title']); ?></a></h3>
                <span style="color: gray; font-size: 11px;">(<?php echo htmlspecialchars($thread['author']); ?>) (<?php echo $_user_fetch_utils->fetch_thread_replies($thread['id']); ?> replies) <?php echo date("M d, Y", strtotime($thread['date'])); ?></span>
                <br>
                <?php echo $_video_fetch_utils->parseTextNoLink($thread['contents']); ?><br><br>
                <?php if(isset($latest_forum_post_reply['toid'])) { 
                            $author = htmlspecialchars($latest_forum_post_reply['author']);
                ?>
                <div class="comment-watch" style="margin-left: 20px;">
                    <a href="/user/<?php echo $author; ?>">
                    <img class="comment-pfp" style="width: 33px;height: 33px;" src="/dynamic/pfp/<?php echo $_user_fetch_utils->fetch_user_pfp($latest_forum_post_reply['author']); ?>">
                    </a>
                    <span  style="display: inline-block; vertical-align: top;width: 562px;;">
                        <span class="comment-info" style="display: inline-block;">
                            <b><a style="text-decoration: none;" href="/user/<?php echo $author; ?>">
                                <?php echo htmlspecialchars($latest_forum_post_reply['author']); ?> 
                            </a></b> 
                            <span style="color: #666;">(<?php echo $_video_fetch_utils->time_elapsed_string($latest_forum_post_reply['date']); ?>)</span>
                        </span><br>
                        <span class="comment-text" style="display: inline-block;">
                            <?php echo $_video_fetch_utils->parseTextDescription($latest_forum_post_reply['contents']); ?>
                        </span>
                    </span>

                </div>
                <?php } ?>
            <?php } ?>
        </div>
        </div>
        <div class="www-core-container">
        <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/footer.php"); ?>
        </div>

    </body>
</html>