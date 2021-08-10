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
        <style>
            .recent-video-timestamp {
                display: inline-block;
                position: relative;
                left: 57px;
                bottom: 25px;
                padding: 3px;
                width: 63px;
                background: rgb(255,255,255);
                background: -moz-linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 100%);
                background: -webkit-linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 100%);
                background: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 100%);
                filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#ffffff",endColorstr="#ffffff",GradientType=1);
                text-align: right;
                font-weight: bold;
            }

            .recent-video-thumbnail {
                -webkit-box-shadow: 0px 19px 8px -2px #EBEBEB; 
                box-shadow: 0px 19px 8px -2px #EBEBEB;
            }

            @keyframes expandDong {
                0% {
                    opacity:0;
                    transform:  rotate(10deg) scaleX(0) scaleY(0) ;
                }
                100% {
                    opacity:1;
                    transform:  rotate(0deg) scaleX(1) scaleY(1) ;
                }
            }

            .recent-view {  
                animation: 0.5s ease-out 0s 1 expandDong;
            }

            .hover-stuff {
                opacity: 1;
                transition: 0.1s;
            }

        </style>
    </head>
    <body>
        <div class="www-core-container www-frontpage">
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
                            <a href="/watch?v=<?php echo $video['rid']; ?>">
                            <img class="thumbnail" onerror="this.src='/dynamic/thumbs/default.png'" src="/dynamic/thumbs/<?php echo htmlspecialchars($video['thumbnail']); ?>">
                            </a>
                            <div class="video-info-grid" style="text-align: center;width: 129px;">
                                <a style="display: inline-block;width: 126px;word-wrap: anywhere;" href="/watch?v=<?php echo $video['rid']; ?>"><?php echo $_video_fetch_utils->parseTitle($video['title']); ?></a><br>
                                <span class="video-info-small">
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
                    $_history_videos = array();
                    $_history_videos['i'] = 0;

                    $stmt = $conn->prepare("SELECT video FROM history ORDER BY id DESC LIMIT 100");
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while($video = $result->fetch_assoc()) { 
                        $video = $_video_fetch_utils->fetch_video_rid($video['video']);
                        if($_video_fetch_utils->video_exists($video['rid']) && !in_array($video['rid'], $_history_videos) && $_history_videos['i'] != 4) { 
                            array_push($_history_videos, $video['rid']);
                            $_history_videos['i']++;
                    ?> 
                        <div class="grid-item recent-view">
                            <a href="/watch?v=<?php echo $video['rid']; ?>">
                            <img class="thumbnail recent-video-thumbnail" onerror="this.src='/dynamic/thumbs/default.png'" src="/dynamic/thumbs/<?php echo htmlspecialchars($video['thumbnail']); ?>">
                            </a>
                            <span class="recent-video-timestamp">
                                <?php echo $_video_fetch_utils->timestamp($video['duration']); ?>
                            </span>
                            <div class="video-info-grid hover-stuff" style="position: relative;bottom: 20px;">
                                <a style="display: inline-block;width: 126px;word-wrap: anywhere;" href="/watch?v=<?php echo $video['rid']; ?>"><?php echo $_video_fetch_utils->parseTitle($video['title']); ?></a><br>
                                <span class="video-info-small">
                                    <a href="/user/<?php echo htmlspecialchars($video['author']); ?>"><?php echo htmlspecialchars($video['author']); ?></a>
                                </span>
                            </div>
                        </div>
                    <?php } } ?>
                </div>
                <hr class="thin-line">
                <h2 style="display: inline-block;">Featured Videos</h2>
                <h3 class="featured-videos-text"><a href="/search_query?lclk=featured">See More Featured Videos</a></h3>
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
                    <a href="/watch?v=<?php echo $video['rid']; ?>">
                        <div class="thumbnail" style="
                            background-image: url(/dynamic/thumbs/<?php echo $video['thumbnail']; ?>), url('/dynamic/thumbs/default.png');">
                            <a class="quicklist-add" href="/get/add_to_quicklist?v=<?php echo $video['rid']; ?>"></a>
                            
                            <span class="timestamp"><?php echo $_video_fetch_utils->timestamp($video['duration']); ?></span>
                        </div>
                    </a>
                    
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
                <?php 
                    $_featured_vid = $_video_fetch_utils->fetch_rand_featured_vid();
                ?>
                <?php 
                    $_featured_vid['stars'] = $_video_fetch_utils->get_video_stars($_featured_vid['rid']);
                    $_featured_vid['star_1'] = $_video_fetch_utils->get_video_stars_level($_featured_vid['rid'], 1);
                    $_featured_vid['star_2'] = $_video_fetch_utils->get_video_stars_level($_featured_vid['rid'], 2);
                    $_featured_vid['star_3'] = $_video_fetch_utils->get_video_stars_level($_featured_vid['rid'], 3);
                    $_featured_vid['star_4'] = $_video_fetch_utils->get_video_stars_level($_featured_vid['rid'], 4);
                    $_featured_vid['star_5'] = $_video_fetch_utils->get_video_stars_level($_featured_vid['rid'], 5);

                    //@$_featured_vid['star_ratio'] = ($_featured_vid['star_1'] + $_featured_vid['star_2'] + $_featured_vid['star_3'] + $_featured_vid['star_4'] + $_featured_vid['star_5']) / $_featured_vid['stars'];

                    /* 
                        5 star - 252
                        4 star - 124
                        3 star - 40
                        2 star - 29
                        1 star - 33

                        totally 478 

                        (252*5 + 124*4 + 40*3 + 29*2 + 33*1) / (252 + 124 + 40 + 29 + 33)
                    */

                    if($_featured_vid['stars'] != 0) {
                        @$_featured_vid['star_ratio'] = (
                            $_featured_vid['star_5'] * 5 + 
                            $_featured_vid['star_4'] * 4 + 
                            $_featured_vid['star_3'] * 3 + 
                            $_featured_vid['star_2'] * 2 + 
                            $_featured_vid['star_1'] * 1
                        ) / (
                            $_featured_vid['star_5'] + 
                            $_featured_vid['star_4'] + 
                            $_featured_vid['star_3'] + 
                            $_featured_vid['star_2'] + 
                            $_featured_vid['star_1']
                        );

                        $_featured_vid['star_ratio'] = floor($_featured_vid['star_ratio'] * 2) / 2;
                    } else { 
                        $_featured_vid['star_ratio'] = 0;
                    }
                ?>
                <iframe id="vid-player" style="border: 0px; overflow: hidden;" src="/2009player/lolplayer?id=<?php echo $_featured_vid['rid']; ?>" height="200" width="310"></iframe> <br>
                <h3><a style="color: #000;font-weight;normal;" href="/watch?v=<?php echo htmlspecialchars($_featured_vid['rid']); ?>"><?php echo htmlspecialchars($_featured_vid['title']);  ?></a></h3>
                From: <a href="/user/<?php echo htmlspecialchars($_featured_vid['author']); ?>">
                        <?php echo htmlspecialchars($_featured_vid['author']); ?>
                    </a>
                        | <?php echo date("M d, Y", strtotime($_featured_vid['publish'])); ?> | <?php echo $_video_fetch_utils->fetch_video_views($_featured_vid['rid']); ?> views
                    <br>
                <p style="font-size: 10px;width: 213px;">
                    <?php echo $_video_fetch_utils->parseTextNoLink($_featured_vid['description']); ?>
                </p>
                
                <div style="float:right;background-color: white;padding: 1px;border-radius: 3px;position: relative;bottom: 30px;">
                    <?php if($_featured_vid['star_ratio'] == 0) { // THIS SHIT FUCKING SUCKS I DON'T KNOW HOW TO MAKE IT ANY BETTER THOUGH ?>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=1"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=2"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=3"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=4"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=5"><img src="/static/img/full_star.png"></a>
                    <?php } ?>
                    <?php if($_featured_vid['star_ratio'] == 0.5) { ?>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=1"><img src="/static/img/half_star.png"></a>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=2"><img src="/static/img/empty_star.png"></a>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=3"><img src="/static/img/empty_star.png"></a>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=4"><img src="/static/img/empty_star.png"></a>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=5"><img src="/static/img/empty_star.png"></a>
                    <?php } ?>
                    <?php if($_featured_vid['star_ratio'] == 1) { ?>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=1"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=2"><img src="/static/img/empty_star.png"></a>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=3"><img src="/static/img/empty_star.png"></a>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=4"><img src="/static/img/empty_star.png"></a>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=5"><img src="/static/img/empty_star.png"></a>
                    <?php } ?>
                    <?php if($_featured_vid['star_ratio'] == 1.5) { ?>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=1"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=2"><img src="/static/img/half_star.png"></a>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=3"><img src="/static/img/empty_star.png"></a>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=4"><img src="/static/img/empty_star.png"></a>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=5"><img src="/static/img/empty_star.png"></a>
                    <?php } ?>
                    <?php if($_featured_vid['star_ratio'] == 2) { ?>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=1"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=2"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=3"><img src="/static/img/empty_star.png"></a>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=4"><img src="/static/img/empty_star.png"></a>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=5"><img src="/static/img/empty_star.png"></a>
                    <?php } ?>
                    <?php if($_featured_vid['star_ratio'] == 2.5) { ?>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=1"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=2"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=3"><img src="/static/img/half_star.png"></a>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=4"><img src="/static/img/empty_star.png"></a>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=5"><img src="/static/img/empty_star.png"></a>
                    <?php } ?>
                    <?php if($_featured_vid['star_ratio'] == 3) { ?>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=1"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=2"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=3"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=4"><img src="/static/img/empty_star.png"></a>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=5"><img src="/static/img/empty_star.png"></a>
                    <?php } ?>
                    <?php if($_featured_vid['star_ratio'] == 3.5) { ?>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=1"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=2"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=3"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=4"><img src="/static/img/half_star.png"></a>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=5"><img src="/static/img/empty_star.png"></a>
                    <?php } ?>
                    <?php if($_featured_vid['star_ratio'] == 4) { ?>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=1"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=2"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=3"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=4"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=5"><img src="/static/img/empty_star.png"></a>
                    <?php } ?>
                    <?php if($_featured_vid['star_ratio'] == 4.5) { ?>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=1"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=2"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=3"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=4"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=5"><img src="/static/img/half_star.png"></a>
                    <?php } ?>
                    <?php if($_featured_vid['star_ratio'] == 5) { ?>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=1"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=2"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=3"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=4"><img src="/static/img/full_star.png"></a>
                    <a href="/get/star?v=<?php echo $_featured_vid['rid']; ?>&rating=5"><img src="/static/img/full_star.png"></a>
                    <?php } ?>
                </div>
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
                    <h3>Better & improved forums</h3>
                    <p class="whats-new-text">
                        Proper UI consistency, more good design choices, and a redesign is in this update. Check the forums out since they won't melt your eyes out from how bad they are anymore.
                    </p>
                    
                    <h3>Channel genres!</h3>
                    <p class="whats-new-text">
                        You now can set your channel genre to reach more people interested in that specific topic.
                    </p>

                    <h3>Video manager & video editor updates</h3>
                    <p class="whats-new-text">
                        Gave the video manager and edit video page a much needed patch up. The pages look a lot better now, and will actually make sense.
                    </p>

                    <!--
                    <h3>More updates</h3>
                    <p class="whats-new-text">
                        I had enough motivation to work on the site, and the front page looks much better than before. Expect more updates soon.
                    </p>

                    <h3>Spam prevention and more updates</h3>
                    <p class="whats-new-text">
                        I haven't used this section in a very very very long time. Here are the following things that have been added in the latest update from July 28th.<br><br>

                        - added recaptcha to sign up<br>
                        - added anti spam measures that should actually work<br>
                        - added cooldown to inbox, channel page, and forums<br>
                        - made admin tools actually redirect to /admin/ when did an action<br>
                        - implemented proper error handling for stuff regarding signup & login<br>
                        - merged fixed css by @glicz (thanks as always)<br>
                        - added deleting replies so it didn't error
                    </p>

                    <h3>What's New</h3>
                    <p class="whats-new-text">
                        SubRocks is out! If you're seeing this... This is a YouTube recreation during 2009. SubRocks welcomes anyone.<br><br><b>Have fun!</b>
                    </p>
                    <h3>YTPMV/YTP Contest</h3>
                    <p class="whats-new-text">
                        <a href="/blog">Read more about it in our blog...</a>
                    </p>
                    -->
                </div>
            </div>
        </div>
        <div class="www-core-container">
        <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/footer.php"); ?>
        </div>

    </body>
</html>
