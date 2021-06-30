<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/important/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/lib/new/base.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/lib/new/fetch.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/lib/new/insert.php"); ?>
<?php
    $_user_fetch_utils = new user_fetch_utils();
    $_video_fetch_utils = new video_fetch_utils();
    $_user_insert_utils = new user_insert_utils();
    $_base_utils = new config_setup();
    
    $_base_utils->initialize_db_var($conn);
    $_video_fetch_utils->initialize_db_var($conn);
    $_user_fetch_utils->initialize_db_var($conn);
    $_user_insert_utils->initialize_db_var($conn);

    $_user = $_user_fetch_utils->fetch_user_username($_GET['n']);
    $_user['subscribed'] = $_user_fetch_utils->if_subscribed(@$_SESSION['siteusername'], $_user['username']);
    $_user['dLinks'] = json_decode($_user['links']);

    $_base_utils->initialize_page_compass(htmlspecialchars($_user['username']));

    if(empty($_user['bio'])) {
        $_user['bio'] = "No bio specified...";
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>FulpTube - <?php echo $_base_utils->return_current_page(); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/static/css/www-core.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="/static/js/channel-customization.js"></script>
        <style>
            #channelbg {
                height: 400%;
                position: absolute;
                right: 0;
                top: 59px;
                left: -46px;
                z-index: -1;
                background-color: <?php echo htmlspecialchars($_user['2012_bgcolor']); ?>;
                background-image: url(/dynamic/banners/<?php echo $_user['2012_bg']; ?>);
                background-position: center-top;
                <?php
                    $bgoption = "";
                            /*
                                <select name="bgoption" id="cars">
                                    <option value="repeaty">Repeat - Y</option>
                                    <option value="repeatx">Repeat - X</option>
                                    <option value="repeatxy">Repeat - X and Y</option>
                                    <option value="stretch">Stretch</option>
                                    <option value="solid">Solid</option>
                                </select>
                            */

                    switch($_user['2012_bgoption']) {
                        case "stretch":
                        echo "background-size: cover;";
                        break;
                        case "solid":
                        echo "";
                        break;
                        case "norepeat":
                        echo "";
                        break;
                        case "repeatxy":
                        echo "background-repeat: repeat;";
                        break;
                        case "repeaty":
                        echo "background-repeat: repeat-y;";
                        break;
                        case "repeatx":
                        echo "background-repeat: repeat-x;";
                        break;
                    }
                ?>
            }
        </style>
    </head>
    <body>
        <div class="www-core-container">
            <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/header.php"); ?>
            <?php 
            if(isset($_SESSION['siteusername']) && $_SESSION['siteusername'] == $_user['username']) 
                require($_SERVER['DOCUMENT_ROOT'] . "/static/module/channel_customization.php");
            ?>
        </div>
        <div class="www-core-container" style="height: 2994px;">
            <div class="user-header-top">
                <img src="/dynamic/pfp/<?php echo $_user['pfp']; ?>"> 
                <?php if(isset($_SESSION['siteusername']) && $_SESSION['siteusername'] == $_user['username']) { ?> <img src="/static/img/edit-icon.png" style="width: 21px;height: 21px;margin-right: 4px;" id="www-dropdown-customization" onclick="dropdownchannel()"> <?php } ?>
                <h1><?php echo htmlspecialchars($_user['username']); ?></h1>
            
                <a href="/get/<?php if($_user['subscribed']) { echo "un"; } ?>subscribe?n=<?php echo htmlspecialchars($_user['username']);?>">
                    <button class="user-subscribe <?php if($_user['subscribed']) { echo "subscribed"; } ?>">
                        <img style="width: 19px;height: 22px;position: relative;left: -7px;" src="/static/img/user_subscribe<?php if($_user['subscribed']) { echo "d"; } ?>.png">
                        <span class="user-subscribe-button-text">Subscribe<?php if($_user['subscribed']) { echo "d"; } ?></span>
                    </button>
                </a>
                <div class="user-info">
                    <div class="user-subscribers">
                        <span class="user-big">
                            <?php echo $_user_fetch_utils->fetch_subs_count($_user['username']); ?>
                        </span>
                        <span class="user-info-small">
                            subscribers
                        </span>
                    </div>
                    <div class="user-subscribers">
                        <span class="user-big">
                            <?php echo $_video_fetch_utils->fetch_views_from_user($_user['username']); ?>
                        </span>
                        <span class="user-info-small">
                            video views
                        </span>
                    </div>
                </div>
            </div>
        <div class="user-header-bottom">
            <ul>
                <li class="selected-user">
                    Featured
                </li>
                <li class="non">
                    <a href="/user/<?php echo htmlspecialchars($_user['username']);?>/feed">Feed</a>
                </li>
                <li class="non">
                    <a href="/user/<?php echo htmlspecialchars($_user['username']);?>/videos">Videos</a>
                </li>
            </ul>
        </div>
        <div class="www-user-left">
            <div class="www-user-left-fix-offset www-user-gradient-uploaded-videos">
                <div class="www-user-uploaded-videos">
                    <h2>Uploaded videos</h2>
                </div>
                <hr>
                <br><br><br>
                <?php
                    $stmt = $conn->prepare("SELECT rid, title, thumbnail, duration, title, author, publish, description FROM videos WHERE visibility = 'v' AND author = ? ORDER BY id DESC LIMIT 13");
                    $stmt->bind_param("s", $_GET['n']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while($video = $result->fetch_assoc()) { 
                ?> 
                <div class="video-channel-video">
                    <div class="video-thumbnail r288" 
                    style="background-image: url('/dynamic/thumbs/<?php echo $video['thumbnail']; ?>'), url('/dynamic/thumbs/default.png');">
                        <div class="video-timestamp">
                            <span>
                            <?php echo $_video_fetch_utils->timestamp($video['duration']); ?>
                            </span>
                        </div>
                    </div>
                    <span class="video-info">
                        <b><a href="/watch?v=<?php echo htmlspecialchars($video['rid']); ?> "><?php echo htmlspecialchars($video['title']); ?> </a></b><br>
                        <span class="author"><?php echo htmlspecialchars($video['author']); ?></span>
                        <span class="views"><?php echo $_video_fetch_utils->get_video_views($video['rid']); ?> views</span>
                        <span class="published"><?php echo $_video_fetch_utils->time_elapsed_string($video['publish']); ?></span>
                    </span>
                </div>
                <?php } ?>
            </div>
        </div>
        <div class="www-user-right">
            <div class="www-user-right-absolute">
                <h2>About <?php echo htmlspecialchars($_user['username']); ?></h2>
                <p>
                    <?php echo $_video_fetch_utils->parseTextNoLink($_user['bio']); ?>
                </p>
                <br>
                <hr class="horizontal-rule">
                <br>
                <div class="www-user-info-right">
                    <span class="by-user">by <?php echo htmlspecialchars($_user['username']); ?></span><br>
                    <span class="section-user">Latest Activity <span class="section-user-info"><?php echo date("M d, Y", strtotime($_user['created'])); ?></span></span><br>
                    <span class="section-user">Date Joined <span class="section-user-info"><?php echo date("M d, Y", strtotime($_user['lastlogin'])); ?></span></span>
                </div>
                <br>
                <?php   
                    $_user['featuredchannelssplit'] = explode(",", $_user['featuredchannels']);
                        if(!empty($_user['featuredchannelssplit'][0])) { ?>
                            <hr class="horizontal-rule">
                            <br>
                            <h2><?php echo htmlspecialchars($_user['customchannelfeatured']); ?></h2>
                            <ul class="featured-channels">
                                <?php   
                                    foreach($_user['featuredchannelssplit'] as $channel) {
                                ?>
                                    <li>
                                        <img src="/dynamic/pfp/<?php echo $_user_fetch_utils->fetch_user_pfp($channel); ?>">
                                        <span class="username">
                                            <b><a href="/user/<?php echo htmlspecialchars($channel); ?>"><?php echo htmlspecialchars($channel); ?></a></b><br>
                                            <?php echo $_user_fetch_utils->fetch_subs_count($channel); ?> subscribers
                                        </span>
                                    </li>
                                <?php } ?>
                            </ul>
                <?php } ?>
            </div>
        </div>
        <div id="channelbg">
            &nbsp;
        </div>
        </div>
        <div class="www-core-container">
        <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/footer.php"); ?>
        </div>
    </body>
</html>