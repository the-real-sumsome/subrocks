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

    $_video = $_video_fetch_utils->fetch_video_rid($_GET['v']);
    $_base_utils->initialize_page_compass(htmlspecialchars($_video['title']));

    $_video['likes'] = $_video_fetch_utils->get_video_likes($_GET['v']);
    $_video['dislikes'] = $_video_fetch_utils->get_video_dislikes($_GET['v']);
    $_video['subscribed'] = $_user_fetch_utils->if_subscribed(@$_SESSION['siteusername'], $_video['author']);
    $_video['liked'] = $_user_fetch_utils->if_liked_video(@$_SESSION['siteusername'], $_video['rid']);

    $_video_insert_utils->check_view($_GET['v'], @$_SESSION['siteusername']);
    
    if($_video['likes'] == 0 && $_video['dislikes'] == 0) {
        $_video['likeswidth'] = 0;
        $_video['dislikeswidth'] = 0;
    } else {
        $_video['likeswidth'] = $_video['likes'] / ($_video['likes'] + $_video['dislikes']) * 100;
        $_video['dislikeswidth'] = 100 - $_video['likeswidth'];
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(!isset($_SESSION['siteusername'])){ $error = "you are not logged in"; goto skipcomment; }
        if(!$_POST['comment']){ $error = "your comment cannot be blank"; goto skipcomment; }
        if(strlen($_POST['comment']) > 1000){ $error = "your comment must be shorter than 1000 characters"; goto skipcomment; }
        if(!isset($_POST['g-recaptcha-response'])){ $error = "captcha validation failed"; goto skipcomment; }
        if(!$_user_insert_utils->validateCaptcha($config['recaptcha_secret'], $_POST['g-recaptcha-response'])) { $error = "captcha validation failed"; goto skipcomment; }
        //if(ifBlocked(@$_SESSION['siteusername'], $user['username'], $conn)) { $error = "This user has blocked you!"; goto skipcomment; } 

        $stmt = $conn->prepare("INSERT INTO `comments` (toid, author, comment) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $_GET['v'], $_SESSION['siteusername'], $text);
        $text = $_POST['comment'];
        $stmt->execute();
        $stmt->close();

        $_user_insert_utils->send_message($_video['author'], "New comment", 'I commented "' . $_POST['comment'] . '" on your video "' . $_video['title'] . '"', $_SESSION['siteusername']);
        skipcomment:
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>FulpTube - <?php echo $_base_utils->return_current_page(); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/static/css/www-core.css">
        <script src='https://www.google.com/recaptcha/api.js' async defer></script>
        <script>function onLogin(token){ document.getElementById('submitform').submit(); }</script>
    </head>
    <body>
        <div class="www-core-container" style="height: 2500px;">
            <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/header.php"); ?>
            <div class="www-watch-left">
                <h2 class="watch-header-title"><?php echo htmlspecialchars($_video['title']); ?></h2>
                <a href="/user/<?php echo htmlspecialchars($_video['author']); ?>">
                    <button class="www-button www-button-grey"><?php echo htmlspecialchars($_video['author']); ?></button>
                </a>
                <a href="/get/<?php if($_video['subscribed']) { echo "un"; } ?>subscribe?n=<?php echo htmlspecialchars($_video['author']); ?>" style="position: relative;left: -4px;">
                    <button class="www-button www-button-grey">
                        <img src="/static/img/subscribe<?php if($_video['subscribed']) { echo "d"; } ?>.png" class="likebutton"> &nbsp;
                        <span class="likebutton">Subscribe<?php if($_video['subscribed']) { echo "d"; } ?></span>
                    </button>
                </a>
                    <button class="www-button www-button-grey"><?php echo $_video_fetch_utils->fetch_videos_from_user($_video['author']); ?> videos</button>
                    <br><br>
                <video width="640" height="390" controls>
                    <source src="/dynamic/videos/<?php echo $_video['filename']; ?>" type="video/mp4">
                </video> <br><br>
                <a href="/get/like?v=<?php echo $_video['rid']; ?>">
                    <button class="www-button www-button-grey">
                        <img src="/static/img/like<?php if($_video['liked']) { echo "d"; } ?>_video.png" class="likebutton">
                        <span class="likebutton">&nbsp;Like</span>
                    </button>
                </a>
                <a href="/get/dislike?v=<?php echo $_video['rid']; ?>" style="position: relative; left: -3px;">
                    <button class="www-button www-button-grey">
                        <img src="/static/img/dlike_video.png" class="likebutton">
                        <span class="likebutton"></span>
                    </button>
                </a>

                <button class="www-button www-button-grey">
                    Add to
                </button>

                <button class="www-button www-button-grey">
                    Share
                </button>
                <div class="watch-views">
                    <b><?php echo $_video_fetch_utils->fetch_video_views($_video['rid']); ?></b>
                    <button class="www-button www-button-grey">
                        <img src="/static/img/analyrics.png">
                    </button>
                </div><br><br>
                <span class="uploader-watch">Uploaded by <a href="/user/<?php echo htmlspecialchars($_video['author']); ?>"><?php echo htmlspecialchars($_video['author']); ?></a> on <?php echo date("M d, Y", strtotime($_video['publish'])); ?></span>
                <div class="sparkbars-watchpage-right">
                    <div class="video-extras-sparkbars">
                    <div class="video-extras-sparkbar-likes" style="    float: left;
                        height: 4px;
                        border-right: 1px solid #fff;
                        background: #060;width:<?php echo $_video['likeswidth']; ?>%"></div>
                    <div class="video-extras-sparkbar-dislikes" style="    float: right;
                        height: 4px;
                        margin-right: -1px;
                        background: #c00;width:<?php echo $_video['dislikeswidth']; ?>%"></div>
                    </div>

                    <span class="video-extras-likes-dislikes">
                    <span class="likes"><?php echo $_video['likes']; ?></span> likes, <span class="dislikes"><?php echo $_video['dislikes']; ?></span> dislikes
                    </span>
                </div><br><br>

                <div class="watch-description">
                    <?php echo $_video_fetch_utils->parseTextNoLink($_video['description']); ?><br><br>
                    <span class="grey-text" id="show-more" style="font-size: 11px;">
                        <b>Category:</b> <?php echo htmlspecialchars($_video['category']); ?> <br>
                        <b>Tags:</b> <?php echo htmlspecialchars($_video['tags']); ?> 
                    </span>
                </div>
                <div class="watch-showmore">
                    Show more
                </div><br><br>
                <?php 
                    $stmt = $conn->prepare("SELECT * FROM comments WHERE toid = ? ORDER BY id DESC");
                    $stmt->bind_param("s", $_GET['v']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                ?>
                <div class="commentsection-header">
                    <strong>All Comments</strong> (<?php echo $result->num_rows; ?>)
                </div>
                <?php if(!isset($_SESSION['siteusername'])) { ?>
                    <div class="comment-alert">
                        <a href="/sign_in">Sign In</a> or <a href="/create_account">Sign Up</a> now to post a comment!
                    </div>
                <?php } else if($_video['commenting'] == "d") { ?>
                    <div class="comment-alert">
                        This video has commenting disabled!
                    </div>
                <?php } else { ?>
                    <form method="post" action="" id="submitform">
                        <small><small style="font-size: 11px; color: #555;">This site is protected by reCAPTCHA and the Google
                            <a class="grey-link" href="https://policies.google.com/privacy">Privacy Policy</a> and
                            <a class="grey-link" href="https://policies.google.com/terms">Terms of Service</a> apply.</small></small><br>
                    
                            <img style="width: 50px;height: 50px;" src="/dynamic/pfp/<?php echo $_user_fetch_utils->fetch_user_pfp($_SESSION['siteusername']); ?>">
                            <textarea 
                                onkeyup="textCounter(this,'counter',500);" 
                                class="comment-textbox" cols="32" id="com" 
                                placeholder="Respond to this video" name="comment"></textarea><br><br> 
                            <input disabled class="characters-remaining" maxlength="3" size="3" value="500" id="counter"> <?php if(!isset($cLang)) { ?> characters remaining <?php } else { echo $cLang['charremaining']; } ?> 
                            <div class="watch-misc-options">
                            <a class="grey-link" href="video_response?v=<?php echo htmlspecialchars($_GET['v']); ?>">Create a video response</a> or 
                            <input type="submit" value="Post" class="g-recaptcha" style="
                                height: 2.95em;
                                padding: 0 .91em;
                                border-width: 1px;
                                border-style: solid;
                                outline: 0;
                                font-weight: 700;
                                font-size: 11px;
                                white-space: nowrap;
                                word-wrap: normal;
                                vertical-align: middle;
                                cursor: pointer;
                                *overflow: visible;
                                -moz-border-radius: 2px;
                                -webkit-border-radius: 2px;
                                border-radius: 2px;
                                text-shadow: 0 1px 0 #fff;
                                border-color: #ccc #ccc #aaa;
                                color: #525252;
                                position: relative;
                                bottom: 24px;
                                left: 156px;
                                background-color: #e0e0e0;
                                -moz-box-shadow: inset 0 0 1px #fff;
                                -ms-box-shadow: inset 0 0 1px #fff;
                                -webkit-box-shadow: inset 0 0 1px #fff;
                                box-shadow: inset 0 0 1px #fff;
                                filter: progid:DXImageTransform.Microsoft.Gradient(GradientType=0,StartColorStr=#fffafafa,EndColorStr=#ffdcdcdc);
                                background-image: -moz-linear-gradient(top,#fafafa 0,#dcdcdc 100%);
                                background-image: -ms-linear-gradient(top,#fafafa 0,#dcdcdc 100%);
                                background-image: -o-linear-gradient(top,#fafafa 0,#dcdcdc 100%);
                                background-image: -webkit-gradient(linear,left top,left bottom,color-stop(0,#fafafa),color-stop(100%,#dcdcdc));
                                background-image: -webkit-linear-gradient(top,#fafafa 0,#dcdcdc 100%);
                                background-image: linear-gradient(to bottom,#fafafa 0,#dcdcdc 100%);
                            " data-sitekey="<?php echo $config['recaptcha_sitekey']; ?>" data-callback="onLogin">
                                            </div> 

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
                <?php } ?><br><br>
                <?php while($comment = $result->fetch_assoc()) {  ?>
                    <div class="comment-watch">
                        <span class="comment-text">
                            <?php echo $_video_fetch_utils->parseTextNoLink($comment['comment']); ?>
                        </span><br>
                        <span class="comment-info">
                            <a class="grey-link" href="/user/<?php echo htmlspecialchars($comment['author']); ?>">
                                <?php echo htmlspecialchars($comment['author']); ?>
                            </a>
                            
                            <a class="grey-link" style="font-size: 11px;margin-left: 7px;">
                            <?php echo $_video_fetch_utils->time_elapsed_string($comment['date']); ?>
                            </a>
                        </span>
                    </div>
                <?php } ?>
            </div>
            <div class="www-watch-right">
                <?php
                    $stmt = $conn->prepare("SELECT rid, title, thumbnail, duration, title, author, publish, description FROM videos WHERE visibility = 'v' ORDER BY RAND() LIMIT 20");
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while($video = $result->fetch_assoc()) { 
                ?> 
                <div class="video-item">
                    <div class="video-thumbnail r120" 
                         style="background-image: url('/dynamic/thumbs/<?php echo $video['thumbnail']; ?>'), url('/dynamic/thumbs/default.png');">
                        <div class="video-timestamp">
                            <span>
                            <?php echo $_video_fetch_utils->timestamp($video['duration']); ?>
                            </span>
                        </div>
                    </div>
                    <span class="video-info">
                        <b><a href="/watch?v=<?php echo $video['rid']; ?>"><?php echo htmlspecialchars($video['title']); ?></a></b><br>
                        <span class="video-details">
                        by <?php echo htmlspecialchars($video['author']); ?><br>
                        <?php echo $_video_fetch_utils->fetch_video_views($video['rid']); ?> views
                        </span>
                    </span>
                </div>
                    <?php } ?>
            </div>
        </div>
        <div class="www-core-container">
        <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/footer.php"); ?>
        </div>
    </body>
</html>