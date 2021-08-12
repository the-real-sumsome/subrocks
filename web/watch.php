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

    if(!$_video_fetch_utils->video_exists($_GET['v']))
        header("Location: /?videodoesntexist");

    if(!isset($_GET['v']))
        header("Location: /?videodoesntexist");

    if(isset($_SESSION['siteusername']) && !$_user_fetch_utils->user_exists(@$_SESSION['siteusername'])) 
        header("Location: /logout");

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

    if($_video_fetch_utils->video_exists($_GET['v'])) {
        $_video_insert_utils->check_view($_GET['v'], $_SERVER["HTTP_CF_CONNECTING_IP"]);
        if(isset($_SESSION['siteusername']) && $_SESSION['siteusername'] != "OfficialB") {
            $_video_insert_utils->add_to_history($_GET['v'], @$_SESSION['siteusername']);
        }
    }
    
    if($_video['likes'] == 0 && $_video['dislikes'] == 0) {
        $_video['likeswidth'] = 50;
        $_video['dislikeswidth'] = 50;
    } else {
        $_video['likeswidth'] = $_video['likes'] / ($_video['likes'] + $_video['dislikes']) * 100;
        $_video['dislikeswidth'] = 100 - $_video['likeswidth'];
    }

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
            $stmt = $conn->prepare("INSERT INTO `comments` (toid, author, comment) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $_GET['v'], $_SESSION['siteusername'], $text);
            $text = $_POST['comment'];
            $stmt->execute();
            $stmt->close();

            $_user_update_utils->update_comment_cooldown_time($_SESSION['siteusername']);

            if(@$_SESSION['siteusername'] != $_video['author']) { 
                $_user_insert_utils->send_message($_video['author'], "New comment", 'I commented "' . $_POST['comment'] . '" on your video "' . $_video['title'] . '"', $_SESSION['siteusername']);
            }
        }
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
        <div class="www-core-container www-watch-page">
            <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/header.php"); ?>
            <script src="/static/js/alert.js"></script>
            <h1 class="video-title"><?php echo htmlspecialchars($_video['title']); ?></h1>
            <div class="www-home-left">
                <iframe id="vid-player" style="border: 0px; overflow: hidden;" src="/2009player/lolplayer?id=<?php echo $_video['rid']; ?>" height="365" width="646"></iframe> <br><br>
                <script>
                    var vid = document.getElementById('vid-player').contentWindow.document.getElementById('video-stream');
                    function hmsToSecondsOnly(str) {
                        var p = str.split(':'),
                            s = 0, m = 1;

                        while (p.length > 0) {
                            s += m * parseInt(p.pop(), 10);
                            m *= 60;
                        }

                        return s;
                    }


                    function setTimePlayer(seconds) {
                        var parsedSec = hmsToSecondsOnly(seconds);
                        document.getElementById('vid-player').contentWindow.document.getElementById('video-stream').currentTime = parsedSec;
                    }
                </script>
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
                    <span style="font-size: 11px; color: gray;vertical-align: middle;padding-bottom: 9px;padding-left: 4px;">
                    <?php echo $_video['stars']; ?> ratings
                    </span>
                    <div class="video-views-watch">
                        <b>Views:</b> <?php echo $_video_fetch_utils->fetch_video_views($_video['rid']); ?>
                    </div><br><br>
                    <div id="share-button" onclick="selectWatch('#share-panel');">
                        <button class="share-icon active" style="vertical-align: middle;">

                        </button>
                        <span class="button-watch-underline">Share</span>
                    </div>

                    <div id="share-button" onclick="selectWatch('#favorite-panel');">
                        <button class="favorite-icon active" style="vertical-align: middle;">

                        </button>
                        <span class="button-watch-underline">Favorite</span>
                    </div>

                    <div id="share-button" onclick="selectWatch('#playlist-panel');">
                        <button class="playlist-icon active" style="vertical-align: middle;">

                        </button>
                        <span class="button-watch-underline">Playlists</span>
                    </div>

                    <div id="share-button" onclick="selectWatch('#flag-panel');">
                        <button class="flag-icon active" style="vertical-align: middle;">

                        </button>
                        <span class="button-watch-underline">Flag</span>
                    </div><br>
                    <button class="up-arrow-watch" style="left: 85px;"></button>
                </div>
                <div class="watch-main-area-bottom">
                    <div id="share-panel">
                        <a href="#">MySpace</a> 
                        <a href="https://twitter.com/intent/tweet?url=https://subrock.rocks/watch?v=<?php echo $_video['rid']; ?>&text=<?php echo htmlspecialchars($_video['title']); ?>&related=Subrocks,Fulptube">Twitter</a> 
                        <a href="https://bwitter.me/share?text=<?php echo htmlspecialchars($_video['title']); ?> | https://subrock.rocks/watch?v=<?php echo $_video['rid']; ?>">Bwitter</a> 
                        <a href="https://facebook.com/sharer/sharer?u=https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fsubrock.rocks/watch?v=<?php echo $_video['rid']; ?>">Facebook</a>
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
                    <?php if(!isset($_SESSION['siteusername'])) { ?>
                        <div class="benifits-outer-front">
                            <div class="benifits-inner-front">
                                <b>Want to make playlists?</b><br>
                                <a href="/sign_up">Sign up for a SubRocks Account</a>
                            </div>
                        </div>
                        <?php } else { ?>
                            <div class="benifits-outer-front">
                                <div class="benifits-inner-front">
                                    <?php
                                        $stmt = $conn->prepare("SELECT * FROM playlists WHERE author = ? ORDER BY id DESC");
                                        $stmt->bind_param("s", $_SESSION['siteusername']);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                    ?>
                                    <?php
                                        while($playlist = $result->fetch_assoc()) { 
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
                                        <a href="/get/add_to_playlist?id=<?php echo $_video['rid']; ?>&playlist=<?php echo $playlist['rid']; ?>"><h3>Add to <?php echo htmlspecialchars($playlist['title']); ?></h3></a>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
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
									<a href="/watch?v=<?php echo $video['rid']; ?>">
                                    <img class="thumbnail" onerror="this.src='/dynamic/thumbs/default.png'" src="/dynamic/thumbs/<?php echo htmlspecialchars($video['thumbnail']); ?>">
                                    </a>
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

                    <div class="alerts_2">
                        <?php if(isset($error['status'])) { ?>
                            <div class="alert" id="videodoesntexist" style="background-color: #FFA3A3;">
                                <?php echo $error['message']; ?>
                            </div>
                        <?php } ?>
                    </div>
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
                            <a href="/sign_in">Sign In</a> or <a href="/sign_up">Sign Up</a> now to post a comment!
                        </div>
                    <?php } else if($_video['commenting'] == "d") { ?>
                        <div class="comment-alert">
                            This video has commenting disabled!
                        </div>
                    <?php } else if($_user_fetch_utils->if_blocked($_video['author'], $_SESSION['siteusername'])) { ?>
                        <div class="comment-alert">
                            The video author has blocked you!
                        </div>
                    <?php } else { ?>
                        <form method="post" action="" id="submitform">
                                <textarea 
                                    onkeyup="textCounter(this,'counter',500);" 
                                    class="comment-textbox" cols="32" id="com" style="width: 98%;"
                                    placeholder="Respond to this video" name="comment"></textarea><br><br> 
                                <input disabled class="characters-remaining" maxlength="3" size="3" value="500" id="counter"> <?php if(!isset($cLang)) { ?> characters remaining <?php } else { echo $cLang['charremaining']; } ?> 
                                <span style="float: right;"><a href="/add_video_response?v=<?php echo $_video['rid']; ?>">Add a Video Response</a></span><br>
                                <input class="www-button www-button-grey" type="submit" value="Post">
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

                                    var comment = $('#com').val();
                                    var id = '<?php echo $_video['rid']; ?>';
                                    var comment_section = document.getElementById("comment_section");
            
                                    if(comment && id)
                                    {
                                        $.ajax
                                        ({
                                        type: 'POST',
                                        url: '/watch_2?v=<?php echo $_video['rid']; ?>',
                                        data: 
                                        {
                                            comment:comment,
                                            id: id
                                        },
                                        success: function (response) 
                                            {
                                                console.log("wtf commented???");
                                                document.getElementById("buffer-comment-lolpenis").style.display = "none";
                                                document.getElementById("abcdefg").innerHTML = comment.replace(/(?:\r\n|\r|\n)/g,"<br/>");
                                                document.getElementById("buffer-comment-lolpenis").style.display = "block";
                                                comment_section.innerHTML = document.getElementById("buffer-comment-lolpenis").innerHTML + comment_section.innerHTML;
                                            }
                                        });
                                    }
                                </script>
                        </form>
                    <?php } ?><br>
                    <div id="comment_section">
                        <hr class="thin-line" style="display: none;">
                        <div id="buffer-comment-lolpenis" class="comment-watch" style="display: none;margin-bottom: 5px !important;">
                            <span class="comment-info">
                                <b><a style="text-decoration: none;" href="/user/<?php echo htmlspecialchars($_SESSION['siteusername']); ?>">
                                    <?php echo htmlspecialchars($_SESSION['siteusername']); ?>
                                </a></b> 
                                <span style="color: #666;">(just now)</span>

                                <span style="float:right; display: inline-block;">

                                </span>
                            </span><br>
                            <span class="comment-text" id="abcdefg">
                                
                            </span><br>
                        </div>


                        <?php while($comment = $result->fetch_assoc()) {  
                            $comment['likes'] = $_video_fetch_utils->fetch_comment_likes($comment['id']) - $_video_fetch_utils->fetch_comment_dislikes($comment['id']);
                            
                            if($comment['likes'] >= 1) 
                                $comment['likes'] = "<span style='vertical-align:middle;color:green;font-weight:bold;'>" . $comment['likes'] . "</span>";
                            if($comment['likes'] <= -1) 
                                $comment['likes'] = "<span style='vertical-align:middle;color:red;font-weight:bold;'>" . $comment['likes'] . "</span>";
                            ?>
                            <hr class="thin-line">
                            <div class="comment-watch" style="margin-bottom: 5px !important;">
                                <span class="comment-info">
                                    <b><a style="text-decoration: none;" href="/user/<?php echo htmlspecialchars($comment['author']); ?>">
                                        <?php echo htmlspecialchars($comment['author']); ?> 
                                    </a></b> 
                                    <span style="color: #666;">(<?php echo $_video_fetch_utils->time_elapsed_string($comment['date']); ?>)
                                        <span class="comment-actions">
                                            <?php if(isset($_SESSION['siteusername']) && $_SESSION['siteusername'] == $_video['author']) { ?>
                                                <a href="/get/delete_comment?id=<?php echo $comment['id'];?>">Remove Comment</a>
                                            <?php } ?>
                                        </span>
                                    </span>

                                    <span style="float:right; display: inline-block;">
                                        <span class="comment-likes"><?php echo $comment['likes']; ?></span>

                                        <a style="text-decoration:none;" href="/get/like_comment?id=<?php echo $comment['id']; ?>">
                                            <button class="like-comment" style="margin-left: 1px;"></button>
                                        </a> 
                                        
                                        <a style="text-decoration:none;" href="/get/dislike_comment?id=<?php echo $comment['id']; ?>">
                                            <button class="dislike-comment" style="margin-left: 5px;"></button>
                                        </a>
                                    </span>

                                    <?php if(isset($_SESSION['siteusername'])) { ?>
                                        <span style="float:right; display: inline-block;margin-right: 9px;">
                                            <a onclick="var comment = document.getElementById('reply_to_' + <?php echo ($comment['id']); ?>); comment.style.display = 'block';">
                                                Reply
                                            </a>        
                                        </span>
                                    <?php } ?>
                                </span><br>
                                <span class="comment-text">
                                    <?php echo $_video_fetch_utils->parseTextComment($comment['comment']); ?>
                                </span><br>

                            </div>
                            <span id="reply_to_<?php echo $comment['id']; ?>" style="display: none;">
                                <hr class="thin-line">
                                <div class="comment-watch">
                                    <span class="comment-info">
                                        <b><a style="text-decoration: none;" href="/user/<?php echo htmlspecialchars($comment['author']); ?>">
                                            Replying to <?php echo htmlspecialchars($comment['author']); ?>
                                        </a></b> 
                                    </span><br>
                                        <span class="comment-text">
                                            <form method="post" action="/post/reply" enctype="multipart/form-data">
                                                <img style="width: 50px;" src=""><textarea style="resize:none;padding:5px;border-radius:5px;background-color:white;border: 1px solid #d3d3d3; width: 577px; resize: none;"cols="32" id="com" placeholder="Share your thoughts" name="comment"></textarea><br>
                                                <input style="float: none; margin-right: 0px; margin-top: 0px;" type="submit" value="Reply" name="replysubmit">
                                                <input style="display: none;" name="id" value="<?php echo $comment['id']; ?>">
                                            </form>
                                    </span><br>
                                </div>
                            </span>
                            <?php
                                $stmtcomment = $conn->prepare("SELECT * FROM comment_reply WHERE toid = ? ORDER BY id DESC");
                                $stmtcomment->bind_param("s", $comment['id']);
                                $stmtcomment->execute();
                                $resultcomment = $stmtcomment->get_result();
                                while($reply = $resultcomment->fetch_assoc()) { 
                            ?>
                                <hr class="thin-line">
                                <div class="comment-watch" style="margin-bottom: 5px !important;width: 605px;margin-left: 30px;">
                                    <span class="comment-info">
                                        <b><a style="text-decoration: none;" href="/user/<?php echo htmlspecialchars($reply['author']); ?>">
                                            <?php echo htmlspecialchars($reply['author']); ?> 
                                        </a></b> 
                                        <span style="color: #666;">(<?php echo $_video_fetch_utils->time_elapsed_string($reply['date']); ?>)
                                            <span class="comment-actions">
                                                <?php if(isset($_SESSION['siteusername']) && $_SESSION['siteusername'] == $_video['author']) { ?>
                                                    <a href="/get/delete_reply?id=<?php echo $reply['id'];?>">Remove Reply</a>
                                                <?php } ?>
                                            </span>
                                        </span>
                                    </span><br>
                                    <span class="comment-text">
                                        <?php echo $_video_fetch_utils->parseTextComment($reply['comment']); ?>
                                    </span><br>

                                </div>
                            <?php } ?>
                        <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="www-home-right">
                <?php if(!empty($_user_fetch_utils->fetch_user_video_banner($_video['author']))) { $_video['banner'] = $_user_fetch_utils->fetch_user_video_banner($_video['author']); ?>
                    <img src="/dynamic/subscribe/<?php echo $_video['banner']; ?>" style="width: 310px;height: 45px;margin-bottom: -3px;">
                <?php } ?>
                <div class="channel-info-video">
                    <a href="#" onclick="subscribe()">
                        <button id="subbutton_change" class="sub_button"><?php if($_video['subscribed'] == true) { ?>Unsubscribe<?php } else { ?>Subscribe<?php } ?></button>
                    </a>
                    <script>
                        var subscribed = <?php echo($_video['subscribed'] ? 'true' : 'false') ?>;
                        var loggedIn = <?php echo(isset($_SESSION['siteusername']) ? 'true' : 'false') ?>;
                        var alerts = 0;

                        function subscribe() {
                            if(loggedIn == true) { 
                                if(subscribed == false) { 
                                    $.ajax({
                                        url: "/get/subscribe?n=<?php echo htmlspecialchars($_video['author']); ?>",
                                        type: 'GET',
                                        success: function(res) {
                                            alerts++;
                                            $("#subbutton_change").text("Unsubscribe");
                                            addAlert("editsuccess_" + alerts, "Successfully added <?php echo htmlspecialchars($_video['author']); ?> to your subscriptions!");
                                            showAlert("#editsuccess_" + alerts);
                                            console.log("DEBUG: " + res);
                                            subscribed = true;
                                        }
                                    });
                                } else {
                                    $.ajax({
                                        url: "/get/unsubscribe?n=<?php echo htmlspecialchars($_video['author']); ?>",
                                        type: 'GET',
                                        success: function(res) {
                                            alerts++;
                                            $("#subbutton_change").text("Subscribe");
                                            addAlert("editsuccess_" + alerts, "Successfully removed <?php echo htmlspecialchars($_video['author']); ?> from your subscriptions!");
                                            showAlert("#editsuccess_" + alerts);
                                            console.log("DEBUG: " + res);
                                            subscribed = false;
                                        }
                                    });
                                }
                            } else {
                                alerts++;
                                addAlert("editsuccess_" + alerts, "You need to log in to add subscriptions!");
                                showAlert("#editsuccess_" + alerts);
                            }
                        }
                    </script>
					<a href="/user/<?php echo htmlspecialchars($_video['author']); ?>">
                    <img src="/dynamic/pfp/<?php echo $_user_fetch_utils->fetch_user_pfp($_video['author']); ?>">
                    </a>
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
                        URL <input value="https://subrock.rocks/watch?v=<?php echo $_video['rid']; ?>"><br>
                        Embed <input style="margin-right: 13px;" value='<iframe style="border: 0px; overflow: hidden;" src="https://subrock.rocks/2009player/lolplayer?id=<?php echo $_video['rid']; ?>" height="365" width="646"></iframe>'>
                    </div>
                </div>
                <?php if(@$_SESSION['siteusername'] == $_video['author']) { ?>
                <div class="channel-info-video" style="margin-top: -11px;background-color: #DDE6F5;border-color: #C5CBD7;">
                    <b>Video Owner Options</b><br><br>
                    <a href="/edit_video?id=<?php echo $_video['rid']; ?>" style="margin-right: 5px;">
                        <button>
                            Edit Video
                        </button>
                    </a>
                    <hr class="thin-line">
                    <span style="font-size: 10px;">
                        <b>edit:</b>
                        <a style="margin-left: 5px;" href="/get/toggle_comment?id=<?php echo $_video['rid']; ?>">toggle commenting</a>
                        <a style="margin-left: 5px;" href="/get/delete_video?id=<?php echo $_video['rid']; ?>">delete</a>
                    </span>
                </div>
                <?php } ?>
                <button type="button" class="collapsible"><img class="www-right-arrow" id="arrow_more">More From: <?php echo htmlspecialchars($_video['author']); ?></button>
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
                                <a href="/watch?v=<?php echo $video['rid']; ?>" class="thumbnail" style="
                                    background-image: url(/dynamic/thumbs/<?php echo $video['thumbnail']; ?>), url('/dynamic/thumbs/default.png');">
                                    <span class="timestamp"><?php echo $_video_fetch_utils->timestamp($video['duration']); ?></span></a>
                                
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
                                <a href="/watch?v=<?php echo $video['rid']; ?>" class="thumbnail" style="
                                    background-image: url(/dynamic/thumbs/<?php echo $video['thumbnail']; ?>), url('/dynamic/thumbs/default.png');">
                                    <span class="timestamp"><?php echo $_video_fetch_utils->timestamp($video['duration']); ?></span></a>
                                
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
        <script>
            function nl2br (str, is_xhtml) {
                if (typeof str === 'undefined' || str === null) {
                    return '';
                }
                var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
                return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
            }

            function showAlert(id) {
                $(id).fadeIn();
            }

            function addAlert(id, text) {
                $(".alerts_2").append("<div class='alert' style='display: none;' id=" + id + ">" + text + "</div>");
            }

            function addError(id, text) {
                $(".alerts_2").append("<div class='alert' style='display: none;background-color: #FFA3A3;' id=" + id + ">" + text + "</div>");
            }

            function addComment(id, text) {
                $("#comment_section").prepend(`
                    <hr class="thin-line">
                    <div id="" class="comment-watch" style="margin-bottom: 5px !important;">
                        <span class="comment-info">
                            <b><a style="text-decoration: none;" href="/user/<?php echo htmlspecialchars($_SESSION['siteusername']); ?>">
                                <?php echo htmlspecialchars($_SESSION['siteusername']); ?> 
                            </a></b> 
                            <span style="color: #666;">(just now)
                            </span>

                            <span style="float:right; display: inline-block;">
                                <span class="comment-likes">0</span>

                                <a style="text-decoration:none;" href="#">
                                    <button class="like-comment" style="margin-left: 1px;"></button>
                                </a> 
                                
                                <a style="text-decoration:none;" href="#">
                                    <button class="dislike-comment" style="margin-left: 5px;"></button>
                                </a>
                            </span>

                            <span style="float:right; display: inline-block;margin-right: 9px;">
                                    
                            </span>
                        </span><br>
                        <span class="comment-text">` + 
                            nl2br(text) 
                        + `</span><br>
                    </div>`);
            }

            //sorry (please rewrite so it's not ass)

            $(function() { 
                $('#submitform' ).submit(
                    function( e ) {
                        var data = new FormData(this);

                        $.ajax( {
                            url: '/post/comment?id=<?php echo $_video['rid']; ?>',
                            type: 'POST',
                            data: data,
                            cache: false,
                            processData: false,
                            contentType: false,
                            success: function(result){
                                alerts++;
                                if(result == "") {
                                    addAlert("commentsuccess_" + alerts, "Successfully commented!");
                                    showAlert("#commentsuccess_" + alerts);
                                    addComment("#commenttemp" + alerts, $("#com").val());
                                } else {
                                    addError("commentsuccess_" + alerts, result);
                                    showAlert("#commentsuccess_" + alerts);
                                }
                                console.log($("#com").val());
                                console.log("DEBUG: " + result);
                            }
                        } );
                        e.preventDefault();
                    } 
                );
            });
        </script>
    </body>
</html>