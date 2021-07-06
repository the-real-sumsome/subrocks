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

  $_base_utils->initialize_page_compass("Homepage");
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
            <div class="www-home-left">
                <?php if(isset($_SESSION['siteusername'])) { ?>
                    <h2>Subscribed Channels</h2><br>
                    <div class="grid-view">
                        <?php
                        $stmt = $conn->prepare("SELECT rid, title, thumbnail, duration, title, author, publish, description FROM videos ORDER BY id DESC LIMIT 250");
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $i = 0;
                        while($video = $result->fetch_assoc()) { 
                            if(in_array($video['author'], $subscribed)) { 
                                $i++;
                                if($i <= 4) { 
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
                        </div>
                        <?php } } } if($i == 0) { echo "There are no videos from your subscriptions!<br><br>"; } ?>
                    </div>
                <?php } ?>
                <h2>Recently Viewed Videos</h2><br>
                <div class="grid-view">
                    <?php
                    $stmt = $conn->prepare("SELECT video FROM history ORDER BY id DESC LIMIT 4");
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while($video = $result->fetch_assoc()) { 
                        $video = $_video_fetch_utils->fetch_video_rid($video['video']);
                        if($_video_fetch_utils->video_exists($video['rid'])) { 
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
                    </div>
                    <?php } } ?>
                </div>
                <hr class="thin-line">
                <h2 style="display: inline-block;">Featured Videos</h2>
                <h3 class="featured-videos-text"><a href="/search?f=featured">See More Featured Videos</a></h3>
                <hr class="thin-line">
                <br>
                <?php
                    $stmt = $conn->prepare("SELECT rid, title, thumbnail, duration, title, author, publish, description FROM videos WHERE featured = 'v' ORDER BY id DESC LIMIT 12");
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while($video = $result->fetch_assoc()) { 
                        $video['stars'] = $_video_fetch_utils->get_video_stars($video['rid']);
                        $video['star_1'] = $_video_fetch_utils->get_video_stars_level($video['rid'], 1);
                        $video['star_2'] = $_video_fetch_utils->get_video_stars_level($video['rid'], 2);
                        $video['star_3'] = $_video_fetch_utils->get_video_stars_level($video['rid'], 3);
                        $video['star_4'] = $_video_fetch_utils->get_video_stars_level($video['rid'], 4);
                        $video['star_5'] = $_video_fetch_utils->get_video_stars_level($video['rid'], 5);
                    
                        //@$video['star_ratio'] = ($video['star_1'] + $video['star_2'] + $video['star_3'] + $video['star_4'] + $video['star_5']) / $video['stars'];
                    
                        /* 
                            5 star - 252
                            4 star - 124
                            3 star - 40
                            2 star - 29
                            1 star - 33
                    
                            totally 478 
                    
                            (252*5 + 124*4 + 40*3 + 29*2 + 33*1) / (252 + 124 + 40 + 29 + 33)
                        */
                    
                        if($video['stars'] != 0) {
                            @$video['star_ratio'] = (
                                $video['star_5'] * 5 + 
                                $video['star_4'] * 4 + 
                                $video['star_3'] * 3 + 
                                $video['star_2'] * 2 + 
                                $video['star_1'] * 1
                            ) / (
                                $video['star_5'] + 
                                $video['star_4'] + 
                                $video['star_3'] + 
                                $video['star_2'] + 
                                $video['star_1']
                            );
                    
                            $video['star_ratio'] = floor($video['star_ratio'] * 2) / 2;
                        } else { 
                            $video['star_ratio'] = 0;
                        }
                ?> 
                <div class="video-item">
                    <div class="thumbnail" style="
                        background-image: url(/dynamic/thumbs/<?php echo $video['thumbnail']; ?>), url('/dynamic/thumbs/default.png');"><span class="timestamp"><?php echo $_video_fetch_utils->timestamp($video['duration']); ?></span></div>
                    
                    <div class="video-info">
                        <a href="/watch?v=<?php echo $video['rid']; ?>"><b><?php echo htmlspecialchars($video['title']); ?></b></a><br>
                        <?php echo $_video_fetch_utils->parseTextNoLink($video['description']); ?><br>
                        <span class="video-info-small-wide">
                            <span class="stars-watch">
                            <?php if($video['star_ratio'] == 0) { // THIS SHIT FUCKING SUCKS I DON'T KNOW HOW TO MAKE IT ANY BETTER THOUGH ?>
                            <img src="/static/img/full_star_s.png">
                            <img src="/static/img/full_star_s.png">
                            <img src="/static/img/full_star_s.png">
                            <img src="/static/img/full_star_s.png">
                            <img src="/static/img/full_star_s.png">
                            <?php } ?>
                            <?php if($video['star_ratio'] == 0.5) { ?>
                            <img src="/static/img/half_star_s.png">
                            <img src="/static/img/empty_star_s.png">
                            <img src="/static/img/empty_star_s.png">
                            <img src="/static/img/empty_star_s.png">
                            <img src="/static/img/empty_star_s.png">
                            <?php } ?>
                            <?php if($video['star_ratio'] == 1) { ?>
                            <img src="/static/img/full_star_s.png">
                            <img src="/static/img/empty_star_s.png">
                            <img src="/static/img/empty_star_s.png">
                            <img src="/static/img/empty_star_s.png">
                            <img src="/static/img/empty_star_s.png">
                            <?php } ?>
                            <?php if($video['star_ratio'] == 1.5) { ?>
                            <img src="/static/img/full_star_s.png">
                            <img src="/static/img/half_star_s.png">
                            <img src="/static/img/empty_star_s.png">
                            <img src="/static/img/empty_star_s.png">
                            <img src="/static/img/empty_star_s.png">
                            <?php } ?>
                            <?php if($video['star_ratio'] == 2) { ?>
                            <img src="/static/img/full_star_s.png">
                            <img src="/static/img/full_star_s.png">
                            <img src="/static/img/empty_star_s.png">
                            <img src="/static/img/empty_star_s.png">
                            <img src="/static/img/empty_star_s.png">
                            <?php } ?>
                            <?php if($video['star_ratio'] == 2.5) { ?>
                            <img src="/static/img/full_star_s.png">
                            <img src="/static/img/full_star_s.png">
                            <img src="/static/img/half_star_s.png">
                            <img src="/static/img/empty_star_s.png">
                            <img src="/static/img/empty_star_s.png">
                            <?php } ?>
                            <?php if($video['star_ratio'] == 3) { ?>
                            <img src="/static/img/full_star_s.png">
                            <img src="/static/img/full_star_s.png">
                            <img src="/static/img/full_star_s.png">
                            <img src="/static/img/empty_star_s.png">
                            <img src="/static/img/empty_star_s.png">
                            <?php } ?>
                            <?php if($video['star_ratio'] == 3.5) { ?>
                            <img src="/static/img/full_star_s.png">
                            <img src="/static/img/full_star_s.png">
                            <img src="/static/img/full_star_s.png">
                            <img src="/static/img/half_star_s.png">
                            <img src="/static/img/empty_star_s.png">
                            <?php } ?>
                            <?php if($video['star_ratio'] == 4) { ?>
                            <img src="/static/img/full_star_s.png">
                            <img src="/static/img/full_star_s.png">
                            <img src="/static/img/full_star_s.png">
                            <img src="/static/img/full_star_s.png">
                            <img src="/static/img/empty_star_s.png">
                            <?php } ?>
                            <?php if($video['star_ratio'] == 4.5) { ?>
                            <img src="/static/img/full_star_s.png">
                            <img src="/static/img/full_star_s.png">
                            <img src="/static/img/full_star_s.png">
                            <img src="/static/img/full_star_s.png">
                            <img src="/static/img/half_star_s.png">
                            <?php } ?>
                            <?php if($video['star_ratio'] == 5) { ?>
                            <img src="/static/img/full_star_s.png">
                            <img src="/static/img/full_star_s.png">
                            <img src="/static/img/full_star_s.png">
                            <img src="/static/img/full_star_s.png">
                            <img src="/static/img/full_star_s.png">
                            <?php } ?>
                            </span>

                            <span style="padding-left: 13px;" class="video-views"><?php echo $_video_fetch_utils->fetch_video_views($video['rid']); ?> views</span>
                            <a class="video-author-wide" href="/user/<?php echo htmlspecialchars($video['author']); ?>"><?php echo htmlspecialchars($video['author']); ?></a>
                        </span>
                    </div>
                    
                </div>
                <?php } ?>
            </div>
            <div class="www-home-right">
                <?php if(!isset($_SESSION['siteusername'])) { ?>
                <div class="benifits-outer-front">
                    <div class="benifits-inner-front">
                        <b>Want to upload videos?</b><br>
                        <a href="/sign_up">Sign up for a SubRocks Account</a>
                    </div>
                </div><br>
                <?php } else { ?>
                <div class="channel-latest-stats">
                    <h3>Welcome back, <?php echo htmlspecialchars($_SESSION['siteusername']); ?></h3>
                    You have <a href="/inbox"><?php echo $_user_fetch_utils->fetch_unread_pms($_SESSION['siteusername']); ?> new messages!</a><br>
                    You've watched <b><?php echo $_video_fetch_utils->fetch_history_ammount($_SESSION['siteusername']); ?></b> videos.<br>
                    Your account was created <b><?php echo $_video_fetch_utils->time_elapsed_string($_user_fetch_utils->fetch_creation_date($_SESSION['siteusername'])); ?></b>. Wow!<br>
                    Your videos have been watched <b><?php echo $_video_fetch_utils->fetch_views_from_user($_SESSION['siteusername']); ?></b> times.
                </div>
                <?php } ?>
                <div class="whats-new">
                    <h3>What's New</h3>
                    <p class="whats-new-text">
                        SubRocks is out! If you're seeing this... This is a YouTube recreation during 2009. SubRocks welcomes anyone.<br><br><b>Have fun!</b>
                    </p>
                    <h3>Contests</h3>
                    <p class="whats-new-text">
                        We're planning to make people submit contest submissions and participate in video contests. Winners recieve a shoutout on the front page on the site!
                    </p>
                </div>
            </div>
        </div>
        <div class="www-core-container">
        <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/footer.php"); ?>
        </div>

    </body>
</html>