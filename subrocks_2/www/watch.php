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

    if(!$_video_fetch_utils->video_exists($_GET['v']))
        header("Location: /?videodoesntexist");

    if(!isset($_GET['v']))
        header("Location: /?videodoesntexist");

    // Cannot use a scalar value as an array ....? This worked in PHP 8 but doesn't in PHP 7.4 for some reason..... Oh well!
    //error_reporting(E_ERROR | E_PARSE);
    $_video = $_video_fetch_utils->fetch_video_rid($_GET['v']);
    $_base_utils->initialize_page_compass(htmlspecialchars($_video['title']));

    $_video['likes'] = $_video_fetch_utils->get_video_likes($_GET['v']);
    $_video['dislikes'] = $_video_fetch_utils->get_video_dislikes($_GET['v']);
    $_video['subscribed'] = $_user_fetch_utils->if_subscribed(@$_SESSION['siteusername'], $_video['author']);
    $_video['favorited'] = $_user_fetch_utils->if_favorited(@$_SESSION['siteusername'], $_video['rid']);
    $_video['liked'] = $_user_fetch_utils->if_liked_video(@$_SESSION['siteusername'], $_video['rid']);
    $_video['video_responses'] = $_video_fetch_utils->get_video_responses($_video['rid']);

    $_video['stars'] = $_video_fetch_utils->get_video_stars($_GET['v']);
    $_video['star_1'] = $_video_fetch_utils->get_video_stars_level($_GET['v'], 1);
    $_video['star_2'] = $_video_fetch_utils->get_video_stars_level($_GET['v'], 2);
    $_video['star_3'] = $_video_fetch_utils->get_video_stars_level($_GET['v'], 3);
    $_video['star_4'] = $_video_fetch_utils->get_video_stars_level($_GET['v'], 4);
    $_video['star_5'] = $_video_fetch_utils->get_video_stars_level($_GET['v'], 5);

    //@$_video['star_ratio'] = ($_video['star_1'] + $_video['star_2'] + $_video['star_3'] + $_video['star_4'] + $_video['star_5']) / $_video['stars'];

    /* 
        5 star - 252
        4 star - 124
        3 star - 40
        2 star - 29
        1 star - 33

        totally 478 

        (252*5 + 124*4 + 40*3 + 29*2 + 33*1) / (252 + 124 + 40 + 29 + 33)
    */

    if($_video['stars'] != 0) {
        @$_video['star_ratio'] = (
            $_video['star_5'] * 5 + 
            $_video['star_4'] * 4 + 
            $_video['star_3'] * 3 + 
            $_video['star_2'] * 2 + 
            $_video['star_1'] * 1
        ) / (
            $_video['star_5'] + 
            $_video['star_4'] + 
            $_video['star_3'] + 
            $_video['star_2'] + 
            $_video['star_1']
        );

        $_video['star_ratio'] = floor($_video['star_ratio'] * 2) / 2;
    } else { 
        $_video['star_ratio'] = 0;
    }
    $_video_insert_utils->check_view($_GET['v'], @$_SESSION['siteusername']);
    $_video_insert_utils->add_to_history($_GET['v'], @$_SESSION['siteusername']);
    
    if($_video['likes'] == 0 && $_video['dislikes'] == 0) {
        $_video['likeswidth'] = 50;
        $_video['dislikeswidth'] = 50;
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
        <title>SubRocks - <?php echo $_base_utils->return_current_page(); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/static/css/new/www-core.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src='https://www.google.com/recaptcha/api.js' async defer></script>
        <script>function onLogin(token){ document.getElementById('submitform').submit(); }</script>
        <style>
        .grecaptcha-badge { 
            visibility: hidden;
        }
        </style>
            <meta property="og:title" content="<?php echo addslashes(htmlspecialchars($_video['title'])); ?>">
        <meta property="og:description" content="<?php echo addslashes(htmlspecialchars($_video['description'])); ?>">
        <meta property="og:image" content="/dynamic/thumbs/<?php echo htmlspecialchars($_video['thumbnail']); ?>">
    </head>
    <body>
        <div class="www-core-container">
            <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/header.php"); ?>
            <h1 class="video-title"><?php echo htmlspecialchars($_video['title']); ?></h1>
            <div class="www-home-left">
                <iframe style="border: 0px; overflow: hidden;" src="/2009player/lolplayer?id=<?php echo $_video['rid']; ?>" height="365" width="646"></iframe> <br><br>
                <?php if($_video['featured'] == "v") { ?>
                    <div class="watch-main-info-featured">
                        This video has been featured! See more featured videos on the <a href="/">front page!</a>
                    </div><br>
                <?php } ?>
                <div class="watch-main-info">
                    <h2>Rate: </h2> 
                    <?php if($_video['star_ratio'] == 0) { // THIS SHIT FUCKING SUCKS I DON'T KNOW HOW TO MAKE IT ANY BETTER THOUGH ?>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=1"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=2"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=3"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=4"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=5"><img src="/static/img/full_star.png"></a>
                    <?php } ?>
                    <?php if($_video['star_ratio'] == 0.5) { ?>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=1"><img src="/static/img/half_star.png"></a>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=2"><img src="/static/img/empty_star.png"></a>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=3"><img src="/static/img/empty_star.png"></a>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=4"><img src="/static/img/empty_star.png"></a>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=5"><img src="/static/img/empty_star.png"></a>
                    <?php } ?>
                    <?php if($_video['star_ratio'] == 1) { ?>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=1"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=2"><img src="/static/img/empty_star.png"></a>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=3"><img src="/static/img/empty_star.png"></a>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=4"><img src="/static/img/empty_star.png"></a>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=5"><img src="/static/img/empty_star.png"></a>
                    <?php } ?>
                    <?php if($_video['star_ratio'] == 1.5) { ?>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=1"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=2"><img src="/static/img/half_star.png"></a>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=3"><img src="/static/img/empty_star.png"></a>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=4"><img src="/static/img/empty_star.png"></a>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=5"><img src="/static/img/empty_star.png"></a>
                    <?php } ?>
                    <?php if($_video['star_ratio'] == 2) { ?>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=1"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=2"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=3"><img src="/static/img/empty_star.png"></a>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=4"><img src="/static/img/empty_star.png"></a>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=5"><img src="/static/img/empty_star.png"></a>
                    <?php } ?>
                    <?php if($_video['star_ratio'] == 2.5) { ?>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=1"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=2"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=3"><img src="/static/img/half_star.png"></a>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=4"><img src="/static/img/empty_star.png"></a>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=5"><img src="/static/img/empty_star.png"></a>
                    <?php } ?>
                    <?php if($_video['star_ratio'] == 3) { ?>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=1"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=2"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=3"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=4"><img src="/static/img/empty_star.png"></a>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=5"><img src="/static/img/empty_star.png"></a>
                    <?php } ?>
                    <?php if($_video['star_ratio'] == 3.5) { ?>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=1"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=2"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=3"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=4"><img src="/static/img/half_star.png"></a>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=5"><img src="/static/img/empty_star.png"></a>
                    <?php } ?>
                    <?php if($_video['star_ratio'] == 4) { ?>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=1"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=2"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=3"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=4"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=5"><img src="/static/img/empty_star.png"></a>
                    <?php } ?>
                    <?php if($_video['star_ratio'] == 4.5) { ?>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=1"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=2"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=3"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=4"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=5"><img src="/static/img/half_star.png"></a>
                    <?php } ?>
                    <?php if($_video['star_ratio'] == 5) { ?>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=1"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=2"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=3"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=4"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_video['rid']; ?>&rating=5"><img src="/static/img/full_star.png"></a>
                    <?php } ?>
                    <span style="font-size: 11px; color: gray;vertical-align: middle;">
                    <?php echo $_video['stars']; ?> ratings
                    </span>
                    <div class="video-views-watch">
                        <b>Views:</b> <?php echo $_video_fetch_utils->fetch_video_views($_video['rid']); ?>
                    </div><br><br>
                    <div id="share-button" onclick="selectWatch('#share-panel');">
                        <button class="share-icon active">

                        </button>
                        <span class="button-watch-underline">Share</span>
                    </div>

                    <div id="share-button" onclick="selectWatch('#favorite-panel');">
                        <button class="favorite-icon active">

                        </button>
                        <span class="button-watch-underline">Favorite</span>
                    </div>

                    <div id="share-button" onclick="selectWatch('#playlist-panel');">
                        <button class="playlist-icon active">

                        </button>
                        <span class="button-watch-underline">Playlists</span>
                    </div>

                    <div id="share-button" onclick="selectWatch('#flag-panel');">
                        <button class="flag-icon active">

                        </button>
                        <span class="button-watch-underline">Flag</span>
                    </div><br>
                    <button class="up-arrow-watch" style="left: 85px;"></button>
                </div>
                <div class="watch-main-area-bottom">
                    <div id="share-panel">
                        <a href="@">MySpace</a> 
                        <a href="https://twitter.com/share?text=<?php echo htmlspecialchars($_video['title']); ?> | https://subrocks/watch?v=<?php echo $_video['rid']; ?>">Twitter</a> 
                        <a href="https://bwitter.me/share?text=<?php echo htmlspecialchars($_video['title']); ?> | https://subrocks/watch?v=<?php echo $_video['rid']; ?>">Bwitter</a> 
                        <a href="https://facebook.com/sharer/sharer?u=<?php echo htmlspecialchars($_video['title']); ?> | https://subrocks/watch?v=<?php echo $_video['rid']; ?>">Facebook</a>
                    </div>

                    <div id="favorite-panel" style="display: none;">
                    <?php if(!isset($_SESSION['siteusername'])) { ?>
                        <div class="benifits-outer-front">
                            <div class="benifits-inner-front">
                                <b>Want to favorite this video?</b><br>
                                <a href="/sign_up">Sign up for a SubRocks Account</a>
                            </div>
                        </div>
                        <?php } else { ?>
                            <div class="benifits-outer-front">
                                <div class="benifits-inner-front">
                                    <?php if($_video['favorited'] == false) { ?>
                                        <a href="/get/favorite?v=<?php echo $_video['rid']; ?>"><h3>Favorite Video</h3></a>
                                    <?php } else { ?>
                                        <a href="/get/unfavorite?v=<?php echo $_video['rid']; ?>"><h3>Unfavorite Video</h3></a>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                    <div id="playlist-panel" style="display: none;">
                        <div class="benifits-outer-front">
                            <div class="benifits-inner-front">
                                <b>This feature is not available yet!</b><br>
                            </div>
                        </div>
                    </div>

                    <div id="flag-panel" style="display: none;">
                        <?php if(!isset($_SESSION['siteusername'])) { ?>
                        <div class="benifits-outer-front">
                            <div class="benifits-inner-front">
                                <b>Want to flag this video?</b><br>
                                <a href="/sign_up">Sign up for a SubRocks Account</a>
                            </div>
                        </div>
                        <?php } else { ?>
                            <div class="benifits-outer-front">
                                <div class="benifits-inner-front">
                                By clicking on the link below, you agree that this video is actually breaking the rules in our <a href="#">Terms of Service</a>.<br><br>

                                <a href="/get/report?v=<?php echo $_video['rid']; ?>">Report Video</a>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div><br>
                <div class="watch-main-info">
                    <?php if($_video['video_responses'] != 0) { ?>
                        <button type="button" class="collapsible active-dropdown"><img class="www-right-arrow" id="arrow_more">Video Responses (<?php echo $_video['video_responses']; ?>)</button>
                        <div class="content" style="display: block;">
                            <?php 
                                $stmt = $conn->prepare("SELECT * FROM video_response WHERE toid = ? ORDER BY id DESC LIMIT 4");
                                $stmt->bind_param("s", $_GET['v']);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                while($video = $result->fetch_assoc()) { 
                                    if($_video_fetch_utils->video_exists($video['video'])) { 
                                        $vRid = $video['id'];
                                        $video = $_video_fetch_utils->fetch_video_rid($video['video']);
                            ?>
                                <div class="grid-item" style="animation: scale-up-recent 0.4s cubic-bezier(0.390, 0.575, 0.565, 1.000) both;">
                                    <img class="thumbnail" onerror="this.src='/dynamic/thumbs/default.png'" src="/dynamic/thumbs/<?php echo htmlspecialchars($video['thumbnail']); ?>">
                                    <div class="video-info-grid">
                                        <a href="/watch?v=<?php echo $video['rid']; ?>"><?php echo htmlspecialchars($video['title']); ?></a><br>
                                        <span class="video-info-small">
                                            <span class="video-views"><?php echo $_video_fetch_utils->fetch_video_views($video['rid']); ?> views</span><br>
                                            <a href="/user/<?php echo htmlspecialchars($video['author']); ?>"><?php echo htmlspecialchars($video['author']); ?></a>
                                        </span>
                                    </div>
                                    <?php if(@$_SESSION['siteusername'] == $_video['author']) { ?>
                                        <br><a href="/get/delete_video_response?id=<?php echo $vRid; ?>"><button>Delete</button></a>
                                    <?php } ?>
                                </div>
                            <?php } } ?>
                        </div><br>
                    <?php } ?>

                    <?php 
                        $stmt = $conn->prepare("SELECT * FROM comments WHERE toid = ? ORDER BY id DESC");
                        $stmt->bind_param("s", $_GET['v']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                    ?>
                    <button type="button" class="collapsible active-dropdown"><img class="www-right-arrow" id="arrow_more">Text Comments (<?php echo $result->num_rows; ?>)</button>
                    <div class="content" style="display: block;">

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
                        
                                <textarea 
                                    onkeyup="textCounter(this,'counter',500);" 
                                    class="comment-textbox" cols="32" id="com" style="width: 98%;"
                                    placeholder="Respond to this video" name="comment"></textarea><br><br> 
                                <input disabled class="characters-remaining" maxlength="3" size="3" value="500" id="counter"> <?php if(!isset($cLang)) { ?> characters remaining <?php } else { echo $cLang['charremaining']; } ?> 
                                <span style="float: right;"><a href="/add_video_response?v=<?php echo $_video['rid']; ?>">Add a Video Response</a></span>
                                <input type="submit" value="Post" class="g-recaptcha" data-sitekey="<?php echo $config['recaptcha_sitekey']; ?>" data-callback="onLogin">
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
                    <?php while($comment = $result->fetch_assoc()) {  
                        $comment['likes'] = $_video_fetch_utils->fetch_comment_likes($comment['id']) - $_video_fetch_utils->fetch_comment_dislikes($comment['id']);
                        
                        if($comment['likes'] >= 1) 
                            $comment['likes'] = "<span style='vertical-align:middle;color:green;font-weight:bold;'>" . $comment['likes'] . "</span>";
                        if($comment['likes'] <= -1) 
                            $comment['likes'] = "<span style='vertical-align:middle;color:red;font-weight:bold;'>" . $comment['likes'] . "</span>";
                        ?>
                        <hr class="thin-line">
                        <div class="comment-watch">
                            <span class="comment-info">
                                <b><a style="text-decoration: none;" href="/user/<?php echo htmlspecialchars($comment['author']); ?>">
                                    <?php echo htmlspecialchars($comment['author']); ?> 
                                </a></b> 
                                <span style="color: #666;">(<?php echo $_video_fetch_utils->time_elapsed_string($comment['date']); ?>)</span>

                                <span style="float:right; display: inline-block;">
                                    <span class="comment-likes"><?php echo $comment['likes']; ?></span>

                                    <a href="/get/like_comment?id=<?php echo $comment['id']; ?>">
                                        <button class="like-comment"></button>
                                    </a> 
                                    
                                    <a href="/get/dislike_comment?id=<?php echo $comment['id']; ?>">
                                        <button class="dislike-comment"></button>
                                    </a>
                                </span>
                            </span><br>
                            <span class="comment-text">
                                <?php echo $_video_fetch_utils->parseTextDescription($comment['comment']); ?>
                            </span><br>

                        </div>
                    <?php } ?>
                    </div>
                </div>
            </div>
            <div class="www-home-right">
                <div class="channel-info-video">
                    <a href="/get/<?php if($_video['subscribed'] == true) { ?>un<?php } ?>subscribe?n=<?php echo htmlspecialchars($_video['author']); ?>">
                        <button class="sub_button"><?php if($_video['subscribed'] == true) { ?>Unsubscribe<?php } else { ?>Subscribe<?php } ?></button>
                    </a>


                    <img src="/dynamic/pfp/<?php echo $_user_fetch_utils->fetch_user_pfp($_video['author']); ?>">
                    <span class="video-author-info">
                        <a href="/user/<?php echo htmlspecialchars($_video['author']); ?>">
                            <b><?php echo htmlspecialchars($_video['author']); ?></b>
                        </a><br>
                        <?php echo date("M d, Y", strtotime($_video['publish'])); ?><br>
                        (<a class="more-info" id="moreinfo" href="#" onclick="openDescription();">more info</a>)
                    </span><br>
                    <div class="video-info-shortened">
                        <?php echo $_video_fetch_utils->parseTextNoLink($_video['description']); ?>
                    </div>

                    <div class="video-info-full" style="display: none;">
                        <?php echo $_video_fetch_utils->parseTextDescription($_video['description']); ?><br><br>
                        <span class="video-expanded-category">
                            <span class="grey-text">Category: </span> <a href="/videos?c=<?php echo htmlspecialchars(urlencode($_video['category']));?>"><?php echo htmlspecialchars($_video['category']); ?></a><br>
                            <span class="grey-text">Tags: </span> <a href="#"><?php echo htmlspecialchars($_video['tags']); ?></a>
                        </span>
                    </div>

                    <div class="share-video">
                        URL <input value="https://subrocks.net/watch?v=<?php echo $_video['rid']; ?>"><br>
                        Embed <input style="margin-right: 13px;" value="">
                    </div>
                </div>
                <button type="button" class="collapsible active-dropdown"><img class="www-right-arrow" id="arrow_more">More From: <?php echo htmlspecialchars($_video['author']); ?></button>
                <div class="content">
                    <div class="videos-list-watch"><br>
                        <?php
                            $stmt = $conn->prepare("SELECT rid, title, thumbnail, duration, title, author, publish, description FROM videos WHERE author = ? ORDER BY id DESC LIMIT 20");
                            $stmt->bind_param("s", $_video['author']);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            while($video = $result->fetch_assoc()) {
                        ?>
                            <div class="video-item-watch">
                                <div class="thumbnail" style="
                                    background-image: url(/dynamic/thumbs/<?php echo $video['thumbnail']; ?>), url('/dynamic/thumbs/default.png');">
                                    <a class="quicklist-add" style="top: 30px;" href="/get/add_to_quicklist?v=<?php echo $video['rid']; ?>"></a>
                                    <span class="timestamp"><?php echo $_video_fetch_utils->timestamp($video['duration']); ?></span></div>
                                
                                <div class="video-info-watch">
                                    <a href="/watch?v=<?php echo $video['rid']; ?>"><b><?php echo htmlspecialchars($video['title']); ?></b></a><br>
                                    <span class="video-info-small-wide">
                                        <span class="video-views"><?php echo $_video_fetch_utils->fetch_video_views($video['rid']); ?> views</span><br>
                                        <a style="padding-left: 0px;" class="video-author-wide" href="/user/<?php echo htmlspecialchars($video['author']); ?>"><?php echo htmlspecialchars($video['author']); ?></a>
                                    </span>
                                </div>
                                
                            </div>
                        <?php } ?>
                    </div>
                </div><br><br>

                <button type="button" class="collapsible"><img class="www-right-arrow" id="arrow_more">Related Videos</button>
                <div class="content" style="display: block;">
                    <div class="videos-list-watch"><br>
                        <?php
                            $stmt = $conn->prepare("SELECT rid, title, thumbnail, duration, title, author FROM videos ORDER BY rand() LIMIT 20");
                            $stmt->execute();
                            $result = $stmt->get_result();
                            while($video = $result->fetch_assoc()) {
                        ?>
                            <div class="video-item-watch">
                                <div class="thumbnail" style="
                                    background-image: url(/dynamic/thumbs/<?php echo $video['thumbnail']; ?>), url('/dynamic/thumbs/default.png');">
                                    <a class="quicklist-add" style="top: 30px;" href="/get/add_to_quicklist?v=<?php echo $video['rid']; ?>"></a>
                                    <span class="timestamp"><?php echo $_video_fetch_utils->timestamp($video['duration']); ?></span></div>
                                
                                <div class="video-info-watch">
                                    <a href="/watch?v=<?php echo $video['rid']; ?>"><b><?php echo htmlspecialchars($video['title']); ?></b></a><br>
                                    <span class="video-info-small-wide">
                                        <span class="video-views"><?php echo $_video_fetch_utils->fetch_video_views($video['rid']); ?> views</span><br>
                                        <a style="padding-left: 0px;" class="video-author-wide" href="/user/<?php echo htmlspecialchars($video['author']); ?>"><?php echo htmlspecialchars($video['author']); ?></a>
                                    </span>
                                </div>
                                
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <script>
                var coll = document.getElementsByClassName("collapsible");
                var arrow_more = document.getElementById("arrow_more");
                var i;

                for (i = 0; i < coll.length; i++) {
                    coll[i].addEventListener("click", function() {
                        this.classList.toggle("active-dropdown");
                        var content = this.nextElementSibling;
                        if (content.style.display === "block") {
                            content.style.display = "none";
                            content.style.backgroundPosition = "0 -342px";

                            //background-position: ;
                        } else {
                            content.style.display = "block";
                            content.style.backgroundPosition = "0 -322px";
                        }
                    });
                }
                </script>
                <script>
                    function selectWatch(id) {
                        if(id == "#share-panel") {
                            $("#share-panel").fadeIn(0);
                            $("#favorite-panel").fadeOut(0);
                            $("#playlist-panel").fadeOut(0);
                            $("#flag-panel").fadeOut(0);

                            $(".up-arrow-watch").css("left", "85px");
                        }

                        if(id == "#favorite-panel") {
                            $("#share-panel").fadeOut(0);
                            $("#favorite-panel").fadeIn(0);
                            $("#playlist-panel").fadeOut(0);
                            $("#flag-panel").fadeOut(0);

                            $(".up-arrow-watch").css("left", "250px");
                        }

                        if(id == "#playlist-panel") {
                            $("#share-panel").fadeOut(0);
                            $("#favorite-panel").fadeOut(0);
                            $("#playlist-panel").fadeIn(0);
                            $("#flag-panel").fadeOut(0);

                            $(".up-arrow-watch").css("left", "405px");
                        }

                        if(id == "#flag-panel") {
                            $("#share-panel").fadeOut(0);
                            $("#favorite-panel").fadeOut(0);
                            $("#playlist-panel").fadeOut(0);
                            $("#flag-panel").fadeIn(0);

                            $(".up-arrow-watch").css("left", "555px");
                        }
                    }

                    var expanded = false;

                    function openDescription() {
                        if(expanded == false) {
                            $(".video-info-full").css("display", "block");
                            $(".video-info-shortened").css("display", "none");
                            $(".more-info").text("show less");
                            expanded = true;
                        } else {
                            $(".video-info-full").css("display", "none");
                            $(".video-info-shortened").css("display", "block");
                            $(".more-info").text("more info");
                            expanded = false;
                        }
                    }
                </script><br>
            </div>
        </div>
        <div class="www-core-container">
        <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/footer.php"); ?>
        </div>

    </body>
</html>
