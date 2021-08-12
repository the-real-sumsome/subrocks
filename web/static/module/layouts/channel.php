<!DOCTYPE html>
<html>
    <head>
        <title>SubRocks - <?php echo $_base_utils->return_current_page(); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/static/css/new/www-core.css">
        <script src='https://www.google.com/recaptcha/api.js' async defer></script>
        <script>function onLogin(token){ document.getElementById('submitform').submit(); }</script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="/static/js/channel-customization.js"></script>
        <style>
            .channel-box-top {
                background: <?php echo htmlspecialchars($_user['primary_color']); ?>;
                color: <?php echo htmlspecialchars($_user['text_color']); ?>;
                padding: 5px;
            }

            .sub_button {
                position: relative;
                bottom: 2px;
            }

            .channel-box-description {
                background: <?php echo htmlspecialchars($_user['secondary_color']); ?>;
                border: 1px solid <?php echo htmlspecialchars($_user['primary_color']); ?>;
                color: <?php echo htmlspecialchars($_user['primary_color_text']); ?>;
                padding: 5px;
            }

            .thin-line {
                border-bottom: 1px solid <?php echo htmlspecialchars($_user['primary_color']); ?>;
                border-top: 0px solid <?php echo htmlspecialchars($_user['primary_color']); ?>;
            }

            .channel-box-no-bg {
                border: 1px solid <?php echo htmlspecialchars($_user['primary_color']); ?>;
                color: <?php echo htmlspecialchars($_user['primary_color_text']); ?>;
                padding: 5px;
                background-color: <?php echo htmlspecialchars($_user['third_color']); ?>;
            }

            .channel-pfp {
                height: 88px;
                width: 88px;
                border-color: <?php echo htmlspecialchars($_user['primary_color']); ?>;
                border: 3px double <?php echo htmlspecialchars($_user['primary_color']); ?>;
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
                border-color: <?php echo htmlspecialchars($_user['primary_color']); ?>;
                display: inline-block;
                border: 3px double <?php echo htmlspecialchars($_user['primary_color']); ?>;;
            }

            .featured-video-info {
                border: 1px solid <?php echo htmlspecialchars($_user['primary_color']); ?>;
                color: black;
                padding: 5px;
                background-color: <?php echo htmlspecialchars($_user['third_color']); ?>;
                font-size: 10px;
                margin-top: -3px;
                margin-bottom: 11px;
            }

            .www-channel-left a {
                color: <?php echo htmlspecialchars($_user['primary_color_text']); ?>;
            }

            .www-channel-right a {
                color: <?php echo htmlspecialchars($_user['primary_color_text']); ?>;
            }
        </style>
        <style>
            body {
                position: absolute;
                right: 0;
                top: 0px;
                left: 0px;
                z-index: -1;
                background-color: <?php echo htmlspecialchars($_user['2012_bgcolor']); ?>;
                background-image: url(/dynamic/banners/<?php echo $_user['2012_bg']; ?>);
                background-position: top;
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
            <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/channel_header.php"); ?><br>
            <?php 
            if(isset($_SESSION['siteusername']) && $_SESSION['siteusername'] == $_user['username']) 
                require($_SERVER['DOCUMENT_ROOT'] . "/static/module/channel_customization.php");
            ?><br>
            <?php if(!empty($_user['banner'])) { ?>
                <img src="/dynamic/banners/<?php echo $_user['banner']; ?>" style="width: 970px;height: 75px;">
            <?php } ?>
            <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/channel_top.php"); ?>
            <div class="www-channel-left">
                <div class="channel-box-profle">
                    <div class="channel-box-top">
                        <h3 style="display: inline-block;"><?php echo htmlspecialchars(substr($_user['username'], 0, 16)); ?>'s Channel</h3>

                        <?php if(@$_SESSION['siteusername'] != $_user['username']) { ?>
                        <a href="/get/<?php if($_user['subscribed'] == true) { ?>un<?php } ?>subscribe?n=<?php echo htmlspecialchars($_user['username']); ?>">
                            <button style="margin: 0px;" class="sub_button"><?php if($_user['subscribed'] == true) { ?>Unsubscribe<?php } else { ?>Subscribe<?php } ?></button>
                        </a>
                        <?php } else { ?>
                        <a href="#">
                            <button style="margin: 0px;" class="sub_button" onclick="dropdownchannel()">Edit Channel</button>
                        </a>
                        <?php } ?>
                    </div>
                    <div class="channel-box-description">
						<a href="/user/<?php echo htmlspecialchars($_user['username']); ?>">
                        <img class="channel-pfp" src="/dynamic/pfp/<?php echo $_user['pfp']; ?>">
						</a>
                        <span class="channel-stats">
                            <h3><?php echo htmlspecialchars($_user['username']); ?></h3>
                            <span class="channel-stats-minor">
                                Joined: <b><?php echo date("M d, Y", strtotime($_user['created'])); ?></b><br>
                                Last Sign In: <b><?php echo date("M d, Y", strtotime($_user['lastlogin'])); ?></b><br>
                                Videos Watched: <b><?php echo $_video_fetch_utils->fetch_history_ammount($_user['username']); ?></b><br>
                                Subscribers: <b><?php echo $_user_fetch_utils->fetch_subs_count($_user['username']); ?></b><br>
                                Channels Views: <b><?php echo $_user_fetch_utils->get_channel_views($_user['username']); ?></b><br>
                            </span>
                        </span><br>
                        <span style="display: inline-block;width: 295px;word-wrap: break-word;">
                            <?php echo $_video_fetch_utils->parseTextDescription($_user['bio']); ?>
                        </span>
                    </div>
                </div><br>
                <div class="channel-box-profle">
                    <div class="channel-box-top" style="height: 12px;">
                        <h3 style="display: inline-block;">Contact with <?php echo htmlspecialchars($_user['username']); ?></h3>
                    </div>
                    <div class="channel-box-no-bg">
						<a href="/user/<?php echo htmlspecialchars($_user['username']); ?>">
                        <div style="display: inline-block;width:116px;height:95px;"><img src="/dynamic/pfp/<?php echo $_user['pfp']; ?>" width="100%" height="100%"></div>
                        </a>
						<span style="display: inline-block;vertical-align: top;line-height: 30px;line-height: 21px;font-size: 11px;">
                            <img src="/static/img/message.png" style="vertical-align: middle;"> <a href="/inbox/send?to=<?php echo htmlspecialchars($_user['username']); ?>">Send Message</a><br>
                            <img src="/static/img/comment.png" style="vertical-align: middle;"> <a href="#">Add Contact</a><br>
                            <?php if($_user_fetch_utils->if_blocked($_SESSION['siteusername'], $_user['username']) == false) { ?>
                                <img src="/static/img/comment.png" style="vertical-align: middle;"> <a href="/get/block?user=<?php echo htmlspecialchars($_user['username']); ?>">Block</a><br>
                            <?php } else { ?>
                                <img src="/static/img/comment.png" style="vertical-align: middle;"> <a href="/get/unblock?user=<?php echo htmlspecialchars($_user['username']); ?>">Unblock</a><br>
                            <?php } ?>
                            <img src="/static/img/share.png" style="vertical-align: middle;"> <a href="/get/add_friend?sending=<?php echo htmlspecialchars($_user['username']); ?>">Add Friend</a>
                        </span><br>
                        <center><a href="/user/<?php echo htmlspecialchars($_user['username']); ?>">http://subrock.rocks/user/<?php echo htmlspecialchars($_user['username']); ?></a></center>

                    </div>
                </div><br>

                <div class="channel-box-profle">
                    <div class="channel-box-top" style="height: 12px;">
                        <h3 style="display: inline-block;">Recent Activity</h3>
                    </div>
                    <div class="channel-box-no-bg" style="color: #666;">
                        <?php
                            $stmt = $conn->prepare("SELECT rid, title, thumbnail, duration, title, author, publish, description FROM videos WHERE author = ? ORDER BY id DESC LIMIT 5");
                            $stmt->bind_param("s", $_user['username']);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            while($video = $result->fetch_assoc()) {
                        ?>
                            <img style="vertical-align: middle;" src="/static/img/upload.png"> <b><?php echo htmlspecialchars($video['author']); ?> uploaded a video</b> (<?php echo $_video_fetch_utils->time_elapsed_string($video['publish']); ?>)
                            <div class="video-item-watch" style="margin-left: 20px;">
                                <a href="/watch?v=<?php echo $video['rid']; ?> class="thumbnail" style="
                                    background-image: url(/dynamic/thumbs/<?php echo $video['thumbnail']; ?>), url('/dynamic/thumbs/default.png');"><span class="timestamp"><?php echo $_video_fetch_utils->timestamp($video['duration']); ?></span></a>
                                
                                <div class="video-info-watch" style="width: 170px;">
                                    <a style="display: inline-block;width: 174px;word-wrap: break-word;" href="/watch?v=<?php echo $video['rid']; ?>"><b><?php echo $_video_fetch_utils->parse_title($video['title']);  ?></b></a><br>
                                    <span class="video-info-small-wide">
                                        <span class="video-views"><?php echo $_video_fetch_utils->fetch_video_views($video['rid']); ?> views</span><br>
                                        <a style="padding-left: 0px;" class="video-author-wide" href="/user/<?php echo htmlspecialchars($video['author']); ?>"><?php echo htmlspecialchars($video['author']); ?></a>
                                    </span>
                                </div>
                                
                            </div>
                            <hr class="thin-line">
                        <?php } if($result->num_rows == 0)  { echo "This user has not been doing anything recently."; } ?>
                    </div>
                </div><br>

                <?php if($_user['subscriptions'] != 0) { ?>
                <div class="channel-box-profle">
                    <div class="channel-box-top">
                        <h3 style="display: inline-block;">Subscriptions (<?php echo $_user['subscriptions']; ?>)</h3>
                    </div>
                    <div class="channel-box-no-bg">
                        <?php
                            $stmt = $conn->prepare("SELECT reciever FROM subscribers WHERE sender = ? ORDER BY id DESC LIMIT 9");
                            $stmt->bind_param("s", $_user['username']);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            while($subscriber = $result->fetch_assoc()) {
                                if($_user_fetch_utils->user_exists($subscriber['reciever'])) {
									$user = htmlspecialchars($subscriber['reciever']);
                        ?>

                                <div class="grid-item" style="width: 90px;">
									<a href="/user/<?php echo $user; ?>">
                                    <img class="channel-pfp" style="width: 58px; height: 58px;" src="/dynamic/pfp/<?php echo $_user_fetch_utils->fetch_user_pfp($subscriber['reciever']); ?>"><br>
                                    </a>
									<a style="font-size: 10px;text-decoration: none;" href="/user/<?php echo $user; ?>"><?php echo htmlspecialchars($subscriber['reciever']); ?></a>
                                </div>
                        <?php } } ?>
                    </div>
                </div><br>
                <?php } ?>
            </div>
            <div class="www-channel-right">
                <?php if($_user['featured'] != "None" && $_video_fetch_utils->video_exists($_user['featured'])) { 
                    $video = $_video_fetch_utils->fetch_video_rid($_user['featured']); ?>
                    <center>
                        <iframe style="border: 0px; overflow: hidden;" src="/2009player/lolplayer?id=<?php echo $_user['featured']; ?>" height="365" width="646"></iframe>
                    </center>
                    <div class="featured-video-info">
                        <h3><a href="/watch?v=<?php echo htmlspecialchars($_user['featured']); ?>"><?php echo htmlspecialchars($video['title']);  ?></a></h3>
                        From: <a href="/user/<?php echo htmlspecialchars($_user['username']); ?>"><?php echo htmlspecialchars($_user['username']); ?></a><br>
                        Views: <?php echo $_video_fetch_utils->fetch_video_views($video['rid']); ?></a><br>
                    </div>
                <?php } ?>

                <?php if($_user['videos'] != 0) { ?>
                <div class="channel-box-profle">
                    <div class="channel-box-top" style="height: 12px;">
                        <h3 style="display: inline-block;">Videos (<?php echo $_user['videos']; ?>)</h3>
                    </div>
                    <div class="channel-box-no-bg">
                        <?php
                            $stmt = $conn->prepare("SELECT rid, title, thumbnail, duration, title, author, publish, description FROM videos WHERE author = ? ORDER BY id DESC LIMIT 8");
                            $stmt->bind_param("s", $_user['username']);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            while($video = $result->fetch_assoc()) {
                        ?>

                                <div class="grid-item" style="">
									<a href="/watch?v=<?php echo $video['rid']; ?>">
                                    <img class="thumbnail" onerror="this.src='/dynamic/thumbs/default.png'" src="/dynamic/thumbs/<?php echo $_video_fetch_utils->parseTitle($video['thumbnail']); ?>">
                                    </a>
									<div class="video-info-grid">
                                        <a style="display: inline-block;width: 127px;word-wrap: break-word;" href="/watch?v=<?php echo $video['rid']; ?>"><?php echo $_video_fetch_utils->parse_title($video['title']);  ?></a><br>
                                        <span class="video-info-small">
                                            <span class="video-views"><?php echo $_video_fetch_utils->fetch_video_views($video['rid']); ?> views</span><br>
                                            <a href="/user/<?php echo htmlspecialchars($video['author']); ?>"><?php echo htmlspecialchars($video['author']); ?></a>
                                        </span>
                                    </div>
                                </div>
                        <?php } ?>
                    </div>
                </div><br>
                <?php } ?>

                <?php if($_user['favorites'] != 0) { ?>
                <div class="channel-box-profle">
                    <div class="channel-box-top" style="height: 12px;">
                        <h3 style="display: inline-block;">Favorites (<?php echo $_user['favorites']; ?>)</h3>
                    </div>
                    <div class="channel-box-no-bg">
                        <?php
                            $stmt = $conn->prepare("SELECT reciever FROM favorite_video WHERE sender = ? ORDER BY id DESC LIMIT 4");
                            $stmt->bind_param("s", $_user['username']);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            while($video = $result->fetch_assoc()) {
                                $video = $_video_fetch_utils->fetch_video_rid($video['reciever']);
                                if($_video_fetch_utils->video_exists($video['rid'])) {
                        ?>

                                <div class="grid-item" style="">
									<a href="/watch?v=<?php echo $video['rid']; ?>">
                                    <img class="thumbnail" onerror="this.src='/dynamic/thumbs/default.png'" src="/dynamic/thumbs/<?php echo htmlspecialchars($video['thumbnail']); ?>">
                                    </a>
									<div class="video-info-grid">
                                        <a href="/watch?v=<?php echo $video['rid']; ?>"><?php echo htmlspecialchars($video['title']);  ?></a><br>
                                        <span class="video-info-small">
                                            <span class="video-views"><?php echo $_video_fetch_utils->fetch_video_views($video['rid']); ?> views</span><br>
                                            <a href="/user/<?php echo htmlspecialchars($video['author']); ?>"><?php echo htmlspecialchars($video['author']); ?></a>
                                        </span>
                                    </div>
                                </div>
                        <?php } } ?>
                    </div>
                </div><br>
                <?php } ?>

                <?php if($_user['subscribers'] != 0) { ?>
                <div class="channel-box-profle">
                    <div class="channel-box-top" style="height: 12px;">
                        <h3 style="display: inline-block;">Subscribers (<?php echo $_user['subscribers']; ?>)</h3>
                    </div>
                    <div class="channel-box-no-bg">
                        <?php
                            $stmt = $conn->prepare("SELECT sender FROM subscribers WHERE reciever = ? ORDER BY id DESC LIMIT 8");
                            $stmt->bind_param("s", $_user['username']);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            while($subscriber = $result->fetch_assoc()) {
								$user = htmlspecialchars($subscriber['sender']);
                        ?>

                                <div class="grid-item" style="">
									<a href="/user/<?php echo $user; ?>">
                                    <img class="channel-pfp" src="/dynamic/pfp/<?php echo $_user_fetch_utils->fetch_user_pfp($subscriber['sender']); ?>"><br>
                                    </a>
									<a style="font-size: 10px;text-decoration: none;" href="/user/<?php echo $user; ?>"><?php echo $user; ?></a>
                                </div>
                        <?php } ?>
                    </div>
                </div><br>
                <?php } ?>

                <?php if($_user['friends'] != 0) { ?>
                <div class="channel-box-profle">
                    <div class="channel-box-top" style="height: 12px;">
                        <h3 style="display: inline-block;">Friends (<?php echo $_user['friends']; ?>)</h3>
                    </div>
                    <div class="channel-box-no-bg">
                        <?php
                            $stmt = $conn->prepare("SELECT * FROM friends WHERE reciever = ? AND status = 'a' LIMIT 4");
                            $stmt->bind_param("s", $_user['username']);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            while($friend = $result->fetch_assoc()) { 
								$user = htmlspecialchars($friend['sender']);
                        ?>
                            <div class="grid-item" style="">
								<a href="/user/<?php echo $user; ?>">
                                <img class="channel-pfp" src="/dynamic/pfp/<?php echo $_user_fetch_utils->fetch_user_pfp($friend['sender']); ?>"><br>
                                </a>
								<a style="font-size: 10px;text-decoration: none;" href="/user/<?php echo $user; ?>"><?php echo $user; ?></a>
                            </div>
                        <?php } ?>

                        <?php
                            $stmt = $conn->prepare("SELECT * FROM friends WHERE sender = ? AND status = 'a' LIMIT 4");
                            $stmt->bind_param("s", $_user['username']);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            while($friend = $result->fetch_assoc()) { 
								$user = htmlspecialchars($friend['reciever']);
                        ?>
                            <div class="grid-item" style="">
								<a href="/user/<?php echo $user; ?>">
                                <img class="channel-pfp" src="/dynamic/pfp/<?php echo $_user_fetch_utils->fetch_user_pfp($friend['reciever']); ?>"><br>
                                </a>
								<a style="font-size: 10px;text-decoration: none;" href="/user/<?php echo $user; ?>"><?php echo $user; ?></a>
                            </div>
                        <?php } ?>
                    </div>
                </div><br>
                <?php } ?>

                <?php 
                    $stmt = $conn->prepare("SELECT * FROM profile_comments WHERE toid = ? ORDER BY id DESC");
                    $stmt->bind_param("s", $_user['username']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                ?>
                <div class="channel-box-profle">
                    <div class="channel-box-top" style="height: 12px;">
                        <h3 style="display: inline-block;">Channel Comments (<?php echo $result->num_rows; ?>)</h3>
                    </div>
                    <div class="channel-box-no-bg">
                        <?php if(!isset($_SESSION['siteusername'])) { ?>
                            <div class="comment-alert">
                                <a href="/sign_in">Sign In</a> or <a href="/$video">Sign Up</a> now to post a comment!
                            </div>
                        <?php } else if($_user_fetch_utils->if_blocked($_user['username'], $_SESSION['siteusername'])) { ?>
                            <div class="comment-alert">
                                <br>
                                This user has blocked you!
                            </div>
                        <?php } else if($_video['commenting'] == "d") { ?>
                            <div class="comment-alert">
                                This channel has commenting disabled!
                            </div>
                        <?php } else { ?>
                            <form method="post" action="" id="submitform">
                                <?php if(isset($error['status'])) { ?>
                                    <div class="alert" id="videodoesntexist" style="background-color: #FFA3A3;">
                                        <?php echo $error['message']; ?>
                                    </div>
                                <?php } ?>
                                <small><small style="font-size: 11px; color: #555;">This site is protected by reCAPTCHA and the Google
                                    <a class="grey-link" href="https://policies.google.com/privacy">Privacy Policy</a> and
                                    <a class="grey-link" href="https://policies.google.com/terms">Terms of Service</a> apply.</small></small><br>
                            
                                    <textarea 
                                        onkeyup="textCounter(this,'counter',500);" 
                                        class="comment-textbox" cols="32" id="com" style="width: 98%;"
                                        placeholder="Leave a nice comment on this channel" name="comment"></textarea><br><br> 
                                    <input disabled class="characters-remaining" maxlength="3" size="3" value="500" id="counter"> <?php if(!isset($cLang)) { ?> characters remaining <?php } else { echo $cLang['charremaining']; } ?> 
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
                                    <span style="color: #666;">(<?php echo $_video_fetch_utils->time_elapsed_string($comment['date']); ?>)</span>
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
                </div>
            </div>
        </div>
        <div id="channelbg">
            &nbsp;
        </div>
    </body>
</html>