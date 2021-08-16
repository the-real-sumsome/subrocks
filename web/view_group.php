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

    if(!$_video_fetch_utils->group_exists($_GET['v']))
        header("Location: /?groupdoesntexist");

    $group = $_video_fetch_utils->fetch_group_id($_GET['v']);
    $_base_utils->initialize_page_compass(htmlspecialchars($group['group_title']));

    $group['joined'] = false;

    if($_user_fetch_utils->if_joined_group($_SESSION['siteusername'], $_GET['v']))
        $group['joined'] = true;

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $error = array();

        if(!isset($_SESSION['siteusername'])){ $error['message'] = "you are not logged in"; $error['status'] = true; }
        if(!$_POST['comment']){ $error['message'] = "your comment cannot be blank"; $error['status'] = true; }
        if(strlen($_POST['comment']) > 1000){ $error['message'] = "your comment must be shorter than 1000 characters"; $error['status'] = true; }
        //if(!isset($_POST['g-recaptcha-response'])){ $error['message'] = "captcha validation failed"; $error['status'] = true; }
        //if(!$_user_insert_utils->validateCaptcha($config['recaptcha_secret'], $_POST['g-recaptcha-response'])) { $error['message'] = "captcha validation failed"; $error['status'] = true; }
        if($_user_fetch_utils->if_cooldown($_SESSION['siteusername'])) { $error['message'] = "You are on a cooldown! Wait for a minute before posting another comment."; $error['status'] = true; }
        //if(ifBlocked(@$_SESSION['siteusername'], $user['username'], $conn)) { $error = "This user has blocked you!"; $error['status'] = true; } 

        if(!isset($error['message'])) {
            $stmt = $conn->prepare("INSERT INTO `group_discussion` (toid, author, comment) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $group['id'], $_SESSION['siteusername'], $text);
            $text = ($_POST['comment']);
            $stmt->execute();
            $stmt->close();

            $_user_update_utils->update_comment_cooldown_time($_SESSION['siteusername']);
        }
    }
?>
<?php $videos = json_decode($group['videos']); ?>
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
            <h3 style="display: inline-block;"><?php echo htmlspecialchars($group['group_title']); ?></h3>
            <a href="/get/<?php if($group['joined']) { ?>un<?php } ?>join_group?id=<?php echo $group['id']; ?>">
                <button type="button" class=" www-button www-button-grey" role="button">
                    <?php if($group['joined']) { ?>Leave<?php } else { ?>Join<?php } ?> Group
                </button>
            </a>
            <br>
            <span style="font-size: 11px;" class="grey-text">by <?php echo htmlspecialchars($group['group_author']); ?><br>
            created <?php echo date("M d, Y", strtotime($group['group_creation'])); ?></span><br><br>
            <p>
                <h3>Description:</h3>
                <?php echo $_video_fetch_utils->parseTextDescription($group['group_description']); ?>
            </p>
            <div style="float: right;position: relative;bottom: 108px;">
                <img src="/dynamic/thumbs/<?php echo $group['group_picture']; ?>" style="width:50px;height:50px;">
            </div>
            
            <h3>Users: </h3>
            <?php 
                $stmt = $conn->prepare("SELECT * FROM group_members WHERE togroup = ? LIMIT 10");
                $stmt->bind_param("s", $_GET['v']);
                $stmt->execute();
                $result = $stmt->get_result();

                if($result->num_rows == 0) { echo "There are no members in this group, why not join? "; } 
            ?>
            <?php while($user = $result->fetch_assoc()) { $user = $_user_fetch_utils->fetch_user_username($user['username']); ?>
                <div class="grid-item" style="animation: scale-up-recent 0.4s cubic-bezier(0.390, 0.575, 0.565, 1.000) both;">
                    <img class="channel-pfp" src="/dynamic/pfp/<?php echo $user['pfp']; ?>"><br>
                    <a style="font-size: 10px;text-decoration: none;" href="/user/<?php echo htmlspecialchars($user['username']); ?>"><?php echo htmlspecialchars($user['username']); ?></a>
                
                    <?php if($group['group_author'] == @$_SESSION['siteusername']) { ?>
                        <br>
                        <a href="/get/kick_from_group?id=<?php echo $group['id']; ?>&user=<?php echo htmlspecialchars($user['username']); ?>">
                            <button type="button" class=" www-button www-button-grey" role="button">
                                Kick
                            </button>
                        </a>
                    <?php } ?>
                </div>
            <?php } ?><br><br>
            <h3>Group Wall: </h3>
            <?php if(!isset($_SESSION['siteusername'])) { ?>
                <div class="comment-alert">
                    <a href="/sign_in">Sign In</a> or <a href="/$video">Sign Up</a> now to post a comment!
                </div>
            <?php } else { ?>
                <form method="post" action="" id="submitform">
                    <?php if(isset($error['status'])) { ?>
                        <div class="alert" id="videodoesntexist" style="background-color: #FFA3A3;">
                            <?php echo $error['message']; ?>
                        </div>
                    <?php } ?>
                        <textarea 
                            onkeyup="textCounter(this,'counter',500);" 
                            class="comment-textbox" cols="32" id="com" style="width: 98%;"
                            placeholder="Leave a nice comment on this channel" name="comment"></textarea><br><br> 
                        <input disabled class="characters-remaining" maxlength="3" size="3" value="500" id="counter"> <?php if(!isset($cLang)) { ?> characters remaining <?php } else { echo $cLang['charremaining']; } ?> <br>
                        <input type="submit" value="Post" class="www-button www-button-grey" data-callback="onLogin">
                        <script>
                        function textCounter(field,field2,maxlimit) {
                            var countfield = document.getElementById(field2);
                            if ( field.value.length > maxlimit ) {
                                field.value = field.value.substring( 0, maxlimit );
                                return false;
                            } else {
                                countfield.value = maxlimit - field.value.length;
                            }
                            }
                        </script>
                </form>
            <?php } ?><br>
            <?php 
                $stmt = $conn->prepare("SELECT * FROM group_discussion WHERE toid = ? ORDER BY id DESC");
                $stmt->bind_param("s", $group['id']);
                $stmt->execute();
                $result = $stmt->get_result();
            ?>
            <?php while($comment = $result->fetch_assoc()) {  
                $author = htmlspecialchars($comment['author']);
            ?>
            <hr class="thin-line">
            <div class="comment-watch">
                <a href="/user/<?php echo $author; ?>">
                <img class="comment-pfp" src="/dynamic/pfp/<?php echo $_user_fetch_utils->fetch_user_pfp($comment['author']); ?>">
                </a>
                <span  style="display: inline-block; vertical-align: top;width: 562px;">
                    <span class="comment-info" style="display: inline-block;">
                        <b><a style="text-decoration: none;" href="/user/<?php echo $author; ?>">
                            <?php echo $author; ?> 
                        </a></b> 
                        <span style="color: <?php echo htmlspecialchars($_user['primary_color_text']); ?>;">(<?php echo $_video_fetch_utils->time_elapsed_string($comment['date']); ?>)</span>
                        <?php if(isset($_SESSION['siteusername']) && $_SESSION['siteusername'] == $_user['username']) { ?>
                            <a style="float: right;" href="/get/delete_comment_profile?id=<?php echo $comment['id'];?>">Remove Comment</a>
                        <?php } ?>
                    </span><br>
                    <span class="comment-text" style="display: inline-block;width: 575px;word-wrap: break-word;">
                        <?php echo $_video_fetch_utils->parseTextDescription($comment['comment']); ?>
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