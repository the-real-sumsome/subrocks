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
                border: 1px solid <?php echo htmlspecialchars($_user['border_color']); ?>;
                color: <?php echo htmlspecialchars($_user['primary_color_text']); ?>;
                padding: 5px;
            }

            .thin-line {
                border-bottom: 1px solid <?php echo htmlspecialchars($_user['primary_color']); ?>;
                border-top: 0px solid <?php echo htmlspecialchars($_user['primary_color']); ?>;
            }

            .channel-box-no-bg {
                border: 1px solid <?php echo htmlspecialchars($_user['border_color']); ?>;
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
                border: 1px solid <?php echo htmlspecialchars($_user['border_color']); ?>;
                color: black;
                padding: 5px;
                background-color: <?php echo htmlspecialchars($_user['third_color']); ?>;
                font-size: 10px;
                margin-top: -3px;
                margin-bottom: 11px;
            }

            input[type="color"] {
                background: transparent url(/static/img/spritesheet_main.png) repeat-x scroll 0 -800px;
                width: 35px;
            }

            .www-channel-left a {
                color: <?php echo htmlspecialchars($_user['primary_color_text']); ?>;
            }

            .www-channel-right a {
                color: <?php echo htmlspecialchars($_user['primary_color_text']); ?>;
            }

            .benifits-outer-front {
                background-color: <?php echo htmlspecialchars($_user['third_color']); ?>;
            }

            .benifits-inner-front {
                background-color: <?php echo htmlspecialchars($_user['third_color']); ?>;
                color: <?php echo htmlspecialchars($_user['primary_color_text']); ?>;
            }

            .benifits-inner-front a {
                color: <?php echo htmlspecialchars($_user['primary_color_text']); ?>;
            }

            .featured-video-info a {
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
                background-color: <?php echo htmlspecialchars($_user['2009_bgcolor']); ?>;
                background-image: url(/dynamic/banners/<?php echo $_user['2009_bg']; ?>);
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

                    switch($_user['2009_bgoption']) {
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

            .www-channel-top {
                background-color: <?php echo htmlspecialchars($_user['third_color']); ?>;
                color: <?php echo htmlspecialchars($_user['text_color']); ?>;
            }

            .www-channel-two-left {
                float: left;
                width: calc( 68% - 20px );
                padding: 5px;
            }

            .www-channel-two-right {
                float: right;
                width: calc( 32.7% - 20px );
                padding: 5px;
            }

            .www-channel-section {
                background: <?php echo htmlspecialchars($_user['primary_color']); ?>;
                color: <?php echo htmlspecialchars($_user['primary_color_text']); ?>;
                padding: 5px;
                border-radius: 3px;
            }

            .right_arrow {
                width: 0;
                height: 0;
                float: left;
                border: 1px solid #000;
                border-width: 25px 0 23px 12px;
                border-top-color: transparent;
                border-bottom-color: transparent;
                font-size: 0;
                border-left-color: <?php echo htmlspecialchars($_user['third_color']); ?>;
                line-height: 0;
                position: relative;
                bottom: -11px;
            }
        </style>
    </head>
    <body>
        <div class="www-core-container">
            <div style="background-color: white;padding: 5px;border-radius: 5px;margin-top: -8px;border-top-left-radius: 0px;border-top-right-radius: 0px;">
                <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/header.php"); ?>
            </div><br>
            <?php 
            if(isset($_SESSION['siteusername']) && $_SESSION['siteusername'] == $_user['username']) 
                require($_SERVER['DOCUMENT_ROOT'] . "/static/module/channel_new_new_customization.php");
            ?><br>
            <?php $_user['featured_channels'] = explode(",", $_user['featured_channels']); ?>
            <?php if(!empty($_user['banner'])) { ?>
                <img src="/dynamic/banners/<?php echo $_user['banner']; ?>" style="width: 970px;height: 75px;">
            <?php } ?>
            <div style="border-radius:3px;padding:5px;overflow: auto;background-color: <?php echo htmlspecialchars($_user['primary_color']); ?>;opacity: <?php echo htmlspecialchars($_user['transparency']); ?>">
            <div class="www-channel-top" style="overflow: auto;">    
                <div style="background: <?php echo htmlspecialchars($_user['secondary_color']); ?>;color: <?php echo htmlspecialchars($_user['primary_color_text']); ?>;padding: 2px;border-radius: 5px;">
                    <div class="www-channel-top">
						<a href="/user/<?php echo htmlspecialchars($_user['username']); ?>">
                        <img class="channel-pfp" src="/dynamic/pfp/<?php echo $_user['pfp']; ?>" style="vertical-align: middle;margin-right: 5px;width: 43px;height: 43px;">
                        </a>
						<h3 style="color:<?php echo htmlspecialchars($_user['primary_color']); ?>;display: inline-block;"><?php echo htmlspecialchars(substr($_user['username'], 0, 16)); ?>'s Channel</h3>
                        <?php if(@$_SESSION['siteusername'] != $_user['username']) { ?>
                            <a href="/get/<?php if($_user['subscribed'] == true) { ?>un<?php } ?>subscribe?n=<?php echo htmlspecialchars($_user['username']); ?>">
                                <button style="margin: 0px;float: none;margin-left: 15px;" class="sub_button"><?php if($_user['subscribed'] == true) { ?>Unsubscribe<?php } else { ?>Subscribe<?php } ?></button>
                            </a>
                            <?php } else { ?>
                            <a href="#">
                                <button style="margin: 0px;float: none;margin-left: 15px;" class="sub_button" onclick="dropdownchannel()">Edit Channel</button>
                            </a>
                        <?php } ?>
                    </div>
                    <div style="float:right;background: <?php echo htmlspecialchars($_user['secondary_color']); ?>;color: <?php echo htmlspecialchars($_user['primary_color_text']); ?>;display: inline-block;height: 100%;width: 49%;position: relative;bottom: 60px;height: 61px;margin-bottom: -60px;">
                        <div class="right_arrow"></div>
                            <div <?php if($_user['videos'] != 0) { ?>style="float: right;margin-top: 22px;"<?php } else { ?>style="margin-top: 22px;margin-left: 33px;"<?php } ?>>
                            <?php if($_user['videos'] != 0) { ?>
                            <style>
                                .view-button .contents {
                                    position: relative;
                                    top: 5px;
                                    left: 5px;
                                }

                                .box {
                                    width: 16px;
                                    height: 11px;
                                    left: 0;
                                    top: 0;
                                }

                                .tri {
                                    position: absolute;
                                    top: 2px;
                                    left: 7px;
                                    width: 1px;
                                    height: 1px;
                                    border-width: 3px;
                                        border-right-width: 3px;
                                    border-style: solid;
                                        border-right-style: solid;
                                    border-right: none;
                                }
                                .view-button .a {
                                    position: absolute;
                                    display: block;
                                    font-size: 0;
                                    zoom: 1;
                                    cursor: pointer;
                                }

                                .view-button .xl {
                                    left: 0;
                                }

                                .view-button .a {
                                    background-color: <?php echo htmlspecialchars($_user['third_color']); ?>;
                                    width: 6px;
                                    height: 3px;
                                }

                                .view-button .yt {
                                    top: 0;
                                }

                                .view-button .xc {
                                    left: 7px;
                                }

                                .view-button .xr {
                                    left: 14px;
                                }

                                .view-button .yc {
                                    top: 4px;
                                }

                                .view-button .yb {
                                    top: 8px;
                                }

                                .box {
                                    width: 16px;
                                    height: 11px;
                                    left: 0;
                                    top: 0;
                                }

                                .view-button {
                                    background-color: <?php echo htmlspecialchars($_user['primary_color']); ?>;
                                    box-sizing: border-box;
                                    -webkit-box-sizing: border-box;
                                    -moz-box-sizing: border-box;
                                    position: relative;
                                    width: 31px;
                                    height: 21px;
                                    float: right;
                                    display: block;
                                    margin-right: 7px;
                                    border-radius: 4px;
                                    -moz-border-radius: 4px;
                                    -webkit-border-radius: 4px;
                                    zoom: 1;
                                    top: -3px;
                                }

                                .tri {
                                    border-top-color: #5f1718;
                                    border-bottom-color: #5f1718;
                                    background-color: #5f1718;
                                }
                            </style>
                            <a href="#" onclick="grid()" class="view-button">
                                <div class="contents">
                                    <div class="a yt xl"></div>
                                    <div class="a yt xc"></div>
                                    <div class="a yt xr"></div>
                                    <div class="a yc xl"></div>
                                    <div class="a yc xc"></div>
                                    <div class="a yc xr"></div>
                                    <div class="a yb xl"></div>
                                    <div class="a yb xc"></div>
                                    <div class="a yb xr"></div>
                                </div>
                            </a>
                            <style>
                                .box {
                                    width: 16px;
                                    height: 11px;
                                    left: 0;
                                    top: 0;
                                }

                                .a {
                                    position: absolute;
                                    display: block;
                                    font-size: 0;
                                    zoom: 1;
                                    cursor: pointer;
                                }

                                .tri {
                                    position: absolute;
                                    top: 2px;
                                    left: 7px;
                                    width: 1px;
                                    height: 1px;
                                    border-width: 3px;
                                        border-right-width: 3px;
                                    border-style: solid;
                                        border-right-style: solid;
                                    border-right: none;
                                }

                                .tri {
                                    border-top-color: <?php echo htmlspecialchars($_user['third_color']); ?>;
                                    border-bottom-color: <?php echo htmlspecialchars($_user['third_color']); ?>;
                                    background-color: <?php echo htmlspecialchars($_user['third_color']); ?>;
                                }

                                .yt {
                                    top: 0;
                                }

                                .yc {
                                    top: 4px;
                                }

                                .yb {
                                    top: 8px;
                                }
                            </style>
                            <a href="#" onclick="main()" class="view-button">
                                <div class="contents" id="test2">
                                    <div class="a box"></div>		
                                    <div class="a tri" style="height: 6px;position: absolute;top: 0;color:<?php echo htmlspecialchars($_user['third_color']); ?>;"></div>
                                    <div class="a yt"></div>
                                    <div class="a yc"></div>
                                    <div class="a yb"></div>
                                </div>
                            </a>
                            <?php } else { ?>
                                <?php echo htmlspecialchars($_user['username']); ?> has no videos available.
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <span id="www-main">
                <?php if($_user['videos'] != 0) { ?>
                <div class="www-channel-two-left">
                    <br>
                    <?php if($_user['featured'] != "None" && $_video_fetch_utils->video_exists($_user['featured'])) { 
                        $video = $_video_fetch_utils->fetch_video_rid($_user['featured']); ?>
                        <?php 
                            $_video['stars'] = $_video_fetch_utils->get_video_stars($video['rid']);
                            $_video['star_1'] = $_video_fetch_utils->get_video_stars_level($video['rid'], 1);
                            $_video['star_2'] = $_video_fetch_utils->get_video_stars_level($video['rid'], 2);
                            $_video['star_3'] = $_video_fetch_utils->get_video_stars_level($video['rid'], 3);
                            $_video['star_4'] = $_video_fetch_utils->get_video_stars_level($video['rid'], 4);
                            $_video['star_5'] = $_video_fetch_utils->get_video_stars_level($video['rid'], 5);

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
                        ?>
                        <center>
                            <iframe style="border: 0px; overflow: hidden;" src="/2009player/lolplayer?id=<?php echo $_user['featured']; ?>" height="365" width="646"></iframe>
                        </center><br>
                        <style>
                            #info {
                                background: transparent url(/static/img/channel-sprites-vfl1Jm6Bm.gif) no-repeat scroll 0 0;
                                margin-top: 2px;
                                margin-left: 9px;
                                vertical-align: text-top;
                                width: 10px;
                                margin-right: 5px;
                                overflow: hidden;
                                background-position: -83px 0;
                                padding: 2px;
                                padding-bottom: 9px;
                            }

                            #favorite {
                                background: transparent url(/static/img/channel-sprites-vfl1Jm6Bm.gif) no-repeat scroll 0 0;
                                margin-top: 2px;
                                margin-left: 9px;
                                vertical-align: text-top;
                                width: 10px;
                                margin-right: 5px;
                                overflow: hidden;
                                background-position: 0 0;
                                padding: 2px;
                                padding-bottom: 9px;
                            }

                            #share {
                                background: transparent url(/static/img/channel-sprites-vfl1Jm6Bm.gif) no-repeat scroll 0 0;
                                margin-top: 2px;
                                margin-left: 9px;
                                vertical-align: text-top;
                                width: 10px;
                                margin-right: 5px;
                                overflow: hidden;
                                background-position: -16px 0;
                                padding: 2px;
                                padding-bottom: 9px;
                            }

                            #flag {
                                background: transparent url(/static/img/channel-sprites-vfl1Jm6Bm.gif) no-repeat scroll 0 0;
                                margin-top: 2px;
                                margin-left: 9px;
                                vertical-align: text-top;
                                width: 10px;
                                margin-right: 5px;
                                overflow: hidden;
                                background-position: -50px 0;
                                padding: 2px;
                                padding-bottom: 9px;
                            }

                            #selected {
                                font-weight:bold;
                            }

                            #select-video-options span {
                                position: relative;
                                bottom: 10px;
                            }
                        </style>
                        <div id="select-video-options">
                            <span onclick="selectWatch('#info-panel');" id="selected"><img id="info"> Info</span> 
                            <span onclick="selectWatch('#favorite-panel');"><img id="favorite"> Favorite</span> 
                            <span onclick="selectWatch('#share-panel');"><img id="share"> Share</span>
                            <span onclick="selectWatch('#flag-panel');"><img id="flag"> Flag</span>
                        </div>
                        <div id="info-panel" class="featured-video-info" style="min-height: 124px;color: <?php echo htmlspecialchars($_user['text_color']); ?>;width: 100%;border-radius: 3px;background: <?php echo htmlspecialchars($_user['primary_color']); ?>;">
                            <h2><a style="color: <?php echo htmlspecialchars($_user['text_color']); ?>;" href="/watch?v=<?php echo htmlspecialchars($_user['featured']); ?>"><?php echo htmlspecialchars($video['title']);  ?></a></h2>
                            From: <a style="color: <?php echo htmlspecialchars($_user['text_color']); ?>;" href="/user/<?php echo htmlspecialchars($video['author']); ?>">
                            <?php echo htmlspecialchars($video['author']); ?>
                                </a>
                                 | <?php echo date("M d, Y", strtotime($video['publish'])); ?> | <?php echo $_video_fetch_utils->fetch_video_views($video['rid']); ?> views
                                <br>
                            <p style="font-size: 10px;">
                                <?php echo $_video_fetch_utils->parseTextDescription($video['description']); ?>
                            </p>

                            <div style="float:right;background-color: white;padding: 1px;border-radius: 3px;">
                            <?php if($_video['star_ratio'] == 0) { // THIS SHIT FUCKING SUCKS I DON'T KNOW HOW TO MAKE IT ANY BETTER THOUGH ?>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=1"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=2"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=3"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=4"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=5"><img src="/static/img/full_star.png"></a>
                            <?php } ?>
                            <?php if($_video['star_ratio'] == 0.5) { ?>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=1"><img src="/static/img/half_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=2"><img src="/static/img/empty_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=3"><img src="/static/img/empty_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=4"><img src="/static/img/empty_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=5"><img src="/static/img/empty_star.png"></a>
                            <?php } ?>
                            <?php if($_video['star_ratio'] == 1) { ?>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=1"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=2"><img src="/static/img/empty_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=3"><img src="/static/img/empty_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=4"><img src="/static/img/empty_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=5"><img src="/static/img/empty_star.png"></a>
                            <?php } ?>
                            <?php if($_video['star_ratio'] == 1.5) { ?>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=1"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=2"><img src="/static/img/half_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=3"><img src="/static/img/empty_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=4"><img src="/static/img/empty_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=5"><img src="/static/img/empty_star.png"></a>
                            <?php } ?>
                            <?php if($_video['star_ratio'] == 2) { ?>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=1"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=2"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=3"><img src="/static/img/empty_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=4"><img src="/static/img/empty_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=5"><img src="/static/img/empty_star.png"></a>
                            <?php } ?>
                            <?php if($_video['star_ratio'] == 2.5) { ?>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=1"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=2"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=3"><img src="/static/img/half_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=4"><img src="/static/img/empty_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=5"><img src="/static/img/empty_star.png"></a>
                            <?php } ?>
                            <?php if($_video['star_ratio'] == 3) { ?>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=1"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=2"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=3"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=4"><img src="/static/img/empty_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=5"><img src="/static/img/empty_star.png"></a>
                            <?php } ?>
                            <?php if($_video['star_ratio'] == 3.5) { ?>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=1"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=2"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=3"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=4"><img src="/static/img/half_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=5"><img src="/static/img/empty_star.png"></a>
                            <?php } ?>
                            <?php if($_video['star_ratio'] == 4) { ?>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=1"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=2"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=3"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=4"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=5"><img src="/static/img/empty_star.png"></a>
                            <?php } ?>
                            <?php if($_video['star_ratio'] == 4.5) { ?>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=1"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=2"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=3"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=4"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=5"><img src="/static/img/half_star.png"></a>
                            <?php } ?>
                            <?php if($_video['star_ratio'] == 5) { ?>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=1"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=2"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=3"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=4"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=5"><img src="/static/img/full_star.png"></a>
                            <?php } ?>
                            </div>
                        </div>

                        <div id="favorite-panel" class="featured-video-info" style="display: none;min-height: 124px;color: <?php echo htmlspecialchars($_user['text_color']); ?>;width: 100%;border-radius: 3px;background: <?php echo htmlspecialchars($_user['primary_color']); ?>;">
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
                                        <?php if($video['favorited'] == false) { ?>
                                            <a href="/get/favorite?v=<?php echo $video['rid']; ?>"><h3>Favorite Video</h3></a>
                                        <?php } else { ?>
                                            <a href="/get/unfavorite?v=<?php echo $video['rid']; ?>"><h3>Unfavorite Video</h3></a>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                        <div id="playlist-panel" class="featured-video-info" style="display: none;min-height: 124px;color: <?php echo htmlspecialchars($_user['text_color']); ?>;width: 100%;border-radius: 3px;background: <?php echo htmlspecialchars($_user['primary_color']); ?>;">
                            <a href="#">MySpace</a> 
                            <a href="https://twitter.com/intent/tweet?url=https://subrock.rocks/watch?v=<?php echo $video['rid']; ?>&text=<?php echo htmlspecialchars($video['title']); ?>&related=Subrocks,Fulptube">Twitter</a> 
                            <a href="https://bwitter.me/share?text=<?php echo htmlspecialchars($video['title']); ?> | https://subrock.rocks/watch?v=<?php echo $video['rid']; ?>">Bwitter</a> 
                            <a href="https://facebook.com/sharer/sharer?u=https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fsubrock.rocks/watch?v=<?php echo $video['rid']; ?>">Facebook</a>
                        </div>

                        <div id="flag-panel" class="featured-video-info" style="display: none;min-height: 124px;color: <?php echo htmlspecialchars($_user['text_color']); ?>;width: 100%;border-radius: 3px;background: <?php echo htmlspecialchars($_user['primary_color']); ?>;">
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

                                    <a href="/get/report?v=<?php echo $video['rid']; ?>">Report Video</a>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } else { ?>
                        <?php 
                            $video = $_video_fetch_utils->fetch_latest_video($_user['username']);

                            $_video['stars'] = $_video_fetch_utils->get_video_stars($video['rid']);
                            $_video['star_1'] = $_video_fetch_utils->get_video_stars_level($video['rid'], 1);
                            $_video['star_2'] = $_video_fetch_utils->get_video_stars_level($video['rid'], 2);
                            $_video['star_3'] = $_video_fetch_utils->get_video_stars_level($video['rid'], 3);
                            $_video['star_4'] = $_video_fetch_utils->get_video_stars_level($video['rid'], 4);
                            $_video['star_5'] = $_video_fetch_utils->get_video_stars_level($video['rid'], 5);

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
                        ?>
                        <center>
                            <iframe style="border: 0px; overflow: hidden;" src="/2009player/lolplayer?id=<?php echo $video['rid']; ?>" height="365" width="646"></iframe>
                        </center><br>
                        <style>
                            #info {
                                background: transparent url(/static/img/channel-sprites-vfl1Jm6Bm.gif) no-repeat scroll 0 0;
                                margin-top: 2px;
                                margin-left: 9px;
                                vertical-align: text-top;
                                width: 10px;
                                margin-right: 5px;
                                overflow: hidden;
                                background-position: -83px 0;
                                padding: 2px;
                                padding-bottom: 9px;
                            }

                            #favorite {
                                background: transparent url(/static/img/channel-sprites-vfl1Jm6Bm.gif) no-repeat scroll 0 0;
                                margin-top: 2px;
                                margin-left: 9px;
                                vertical-align: text-top;
                                width: 10px;
                                margin-right: 5px;
                                overflow: hidden;
                                background-position: 0 0;
                                padding: 2px;
                                padding-bottom: 9px;
                            }

                            #share {
                                background: transparent url(/static/img/channel-sprites-vfl1Jm6Bm.gif) no-repeat scroll 0 0;
                                margin-top: 2px;
                                margin-left: 9px;
                                vertical-align: text-top;
                                width: 10px;
                                margin-right: 5px;
                                overflow: hidden;
                                background-position: -16px 0;
                                padding: 2px;
                                padding-bottom: 9px;
                            }

                            #flag {
                                background: transparent url(/static/img/channel-sprites-vfl1Jm6Bm.gif) no-repeat scroll 0 0;
                                margin-top: 2px;
                                margin-left: 9px;
                                vertical-align: text-top;
                                width: 10px;
                                margin-right: 5px;
                                overflow: hidden;
                                background-position: -50px 0;
                                padding: 2px;
                                padding-bottom: 9px;
                            }

                            #selected {
                                font-weight:bold;
                            }

                            #select-video-options span {
                                position: relative;
                                bottom: 10px;
                            }
                        </style>
                        <div id="select-video-options">
                            <span onclick="selectWatch('#info-panel');" id="selected"><img id="info"> Info</span> 
                            <span onclick="selectWatch('#favorite-panel');"><img id="favorite"> Favorite</span> 
                            <span onclick="selectWatch('#share-panel');"><img id="share"> Share</span>
                            <span onclick="selectWatch('#flag-panel');"><img id="flag"> Flag</span>
                        </div>
                        <div id="info-panel" class="featured-video-info" style="min-height: 124px;color: <?php echo htmlspecialchars($_user['text_color']); ?>;width: 100%;border-radius: 3px;background: <?php echo htmlspecialchars($_user['primary_color']); ?>;">
                            <h2><a style="color: <?php echo htmlspecialchars($_user['text_color']); ?>;" href="/watch?v=<?php echo htmlspecialchars($_user['featured']); ?>"><?php echo htmlspecialchars($video['title']);  ?></a></h2>
                            From: <a style="color: <?php echo htmlspecialchars($_user['text_color']); ?>;" href="/user/<?php echo htmlspecialchars($video['author']); ?>">
                            <?php echo htmlspecialchars($video['author']); ?>
                                </a>
                                 | <?php echo date("M d, Y", strtotime($video['publish'])); ?> | <?php echo $_video_fetch_utils->fetch_video_views($video['rid']); ?> views
                                <br>
                            <p style="font-size: 10px;">
                                <?php echo $_video_fetch_utils->parseTextDescription($video['description']); ?>
                            </p>

                            <div style="float:right;background-color: white;padding: 1px;border-radius: 3px;">
                            <?php if($_video['star_ratio'] == 0) { // THIS SHIT FUCKING SUCKS I DON'T KNOW HOW TO MAKE IT ANY BETTER THOUGH ?>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=1"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=2"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=3"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=4"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=5"><img src="/static/img/full_star.png"></a>
                            <?php } ?>
                            <?php if($_video['star_ratio'] == 0.5) { ?>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=1"><img src="/static/img/half_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=2"><img src="/static/img/empty_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=3"><img src="/static/img/empty_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=4"><img src="/static/img/empty_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=5"><img src="/static/img/empty_star.png"></a>
                            <?php } ?>
                            <?php if($_video['star_ratio'] == 1) { ?>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=1"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=2"><img src="/static/img/empty_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=3"><img src="/static/img/empty_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=4"><img src="/static/img/empty_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=5"><img src="/static/img/empty_star.png"></a>
                            <?php } ?>
                            <?php if($_video['star_ratio'] == 1.5) { ?>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=1"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=2"><img src="/static/img/half_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=3"><img src="/static/img/empty_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=4"><img src="/static/img/empty_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=5"><img src="/static/img/empty_star.png"></a>
                            <?php } ?>
                            <?php if($_video['star_ratio'] == 2) { ?>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=1"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=2"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=3"><img src="/static/img/empty_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=4"><img src="/static/img/empty_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=5"><img src="/static/img/empty_star.png"></a>
                            <?php } ?>
                            <?php if($_video['star_ratio'] == 2.5) { ?>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=1"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=2"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=3"><img src="/static/img/half_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=4"><img src="/static/img/empty_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=5"><img src="/static/img/empty_star.png"></a>
                            <?php } ?>
                            <?php if($_video['star_ratio'] == 3) { ?>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=1"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=2"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=3"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=4"><img src="/static/img/empty_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=5"><img src="/static/img/empty_star.png"></a>
                            <?php } ?>
                            <?php if($_video['star_ratio'] == 3.5) { ?>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=1"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=2"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=3"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=4"><img src="/static/img/half_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=5"><img src="/static/img/empty_star.png"></a>
                            <?php } ?>
                            <?php if($_video['star_ratio'] == 4) { ?>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=1"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=2"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=3"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=4"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=5"><img src="/static/img/empty_star.png"></a>
                            <?php } ?>
                            <?php if($_video['star_ratio'] == 4.5) { ?>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=1"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=2"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=3"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=4"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=5"><img src="/static/img/half_star.png"></a>
                            <?php } ?>
                            <?php if($_video['star_ratio'] == 5) { ?>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=1"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=2"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=3"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=4"><img src="/static/img/full_star.png"></a>
                            <a href="/get/star?v=<?php echo $video['rid']; ?>&rating=5"><img src="/static/img/full_star.png"></a>
                            <?php } ?>
                            </div>
                        </div>

                        <div id="favorite-panel" class="featured-video-info" style="display: none;min-height: 124px;color: <?php echo htmlspecialchars($_user['text_color']); ?>;width: 100%;border-radius: 3px;background: <?php echo htmlspecialchars($_user['primary_color']); ?>;">
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
                                        <?php if($video['favorited'] == false) { ?>
                                            <a href="/get/favorite?v=<?php echo $video['rid']; ?>"><h3>Favorite Video</h3></a>
                                        <?php } else { ?>
                                            <a href="/get/unfavorite?v=<?php echo $video['rid']; ?>"><h3>Unfavorite Video</h3></a>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                        <div id="playlist-panel" class="featured-video-info" style="display: none;min-height: 124px;color: <?php echo htmlspecialchars($_user['text_color']); ?>;width: 100%;border-radius: 3px;background: <?php echo htmlspecialchars($_user['primary_color']); ?>;">
                            <a href="#">MySpace</a> 
                            <a href="https://twitter.com/intent/tweet?url=https://subrock.rocks/watch?v=<?php echo $video['rid']; ?>&text=<?php echo htmlspecialchars($video['title']); ?>&related=Subrocks,Fulptube">Twitter</a> 
                            <a href="https://bwitter.me/share?text=<?php echo htmlspecialchars($video['title']); ?> | https://subrock.rocks/watch?v=<?php echo $video['rid']; ?>">Bwitter</a> 
                            <a href="https://facebook.com/sharer/sharer?u=https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fsubrock.rocks/watch?v=<?php echo $video['rid']; ?>">Facebook</a>
                        </div>

                        <div id="flag-panel" class="featured-video-info" style="display: none;min-height: 124px;color: <?php echo htmlspecialchars($_user['text_color']); ?>;width: 100%;border-radius: 3px;background: <?php echo htmlspecialchars($_user['primary_color']); ?>;">
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

                                    <a href="/get/report?v=<?php echo $video['rid']; ?>">Report Video</a>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>

                <script>
                    function selectWatch(id) {
                        if(id == "#info-panel") {
                            $("#info-panel").fadeIn(75);
                            $("#favorite-panel").fadeOut(5);
                            $("#playlist-panel").fadeOut(5);
                            $("#flag-panel").fadeOut(5);
                        }

                        if(id == "#favorite-panel") {
                            $("#info-panel").fadeOut(5);
                            $("#favorite-panel").fadeIn(75);
                            $("#playlist-panel").fadeOut(5);
                            $("#flag-panel").fadeOut(5);
                        }

                        if(id == "#share-panel") {
                            $("#info-panel").fadeOut(5);
                            $("#favorite-panel").fadeOut(5);
                            $("#playlist-panel").fadeIn(75);
                            $("#flag-panel").fadeOut(5);
                        }

                        if(id == "#flag-panel") {
                            $("#info-panel").fadeOut(5);
                            $("#favorite-panel").fadeOut(5);
                            $("#playlist-panel").fadeOut(5);
                            $("#flag-panel").fadeIn(75);
                        }
                    }
                </script>
                <div class="www-channel-two-right">
                    <div class="www-channel-section" style="margin-top: 13px;overflow-y: scroll;max-height: 545px;">
                        <h3 style="display: inline-block;color: white;">Uploads (<?php echo $_user['videos']; ?>)</h3><br><br>
                        <?php
                            $stmt = $conn->prepare("SELECT rid, title, thumbnail, duration, title, author, publish, description FROM videos WHERE author = ? ORDER BY id DESC LIMIT 10");
                            $stmt->bind_param("s", $_user['username']);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            while($video = $result->fetch_assoc()) {
                        ?>
                            <div class="video-item-watch">
                                <a href="/watch?v=<?php echo $video['rid']; ?>" class="thumbnail" style="background-image: url(/dynamic/thumbs/<?php echo $video['thumbnail']; ?>), url('/dynamic/thumbs/default.png');">
                                    <span class="timestamp"><?php echo $_video_fetch_utils->timestamp($video['duration']); ?></span></a>
                                
                                <div class="video-info-watch">
                                    <a style="color: <?php echo htmlspecialchars($_user['text_color']); ?>;" href="/watch?v=<?php echo $video['rid']; ?>"><b><?php echo htmlspecialchars($video['title']); ?></b></a><br>
                                    <span class="video-info-small-wide">
                                        <span style="color: <?php echo htmlspecialchars($_user['primary_color_text']); ?>;" class="video-views"><?php echo $_video_fetch_utils->fetch_video_views($video['rid']); ?> views</span><br>
                                        <a style="padding-left: 0px;color: <?php echo htmlspecialchars($_user['text_color']); ?>;" class="video-author-wide" href="/user/<?php echo htmlspecialchars($video['author']); ?>"><?php echo htmlspecialchars($video['author']); ?></a>
                                    </span>
                                </div>
                                
                            </div>
                        <?php } ?>
                        <hr class="thin-line">
                        <?php if($_user['favorites'] != 0) { ?>
                            <h3 style="display: inline-block;color: white;">Favorites (<?php echo $_user['favorites']; ?>)</h3><br><br>
                        <?php } ?>
                        <?php
                            $stmt = $conn->prepare("SELECT reciever FROM favorite_video WHERE sender = ? ORDER BY id DESC LIMIT 10");
                            $stmt->bind_param("s", $_user['username']);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            while($video = $result->fetch_assoc()) {
                                $video = $_video_fetch_utils->fetch_video_rid($video['reciever']);
                                if($_video_fetch_utils->video_exists($video['rid'])) { ?>
                                    <div class="video-item-watch">
                                        <a href="/watch?v=<?php echo $video['rid']; ?>" class="thumbnail" style="
                                            background-image: url(/dynamic/thumbs/<?php echo $video['thumbnail']; ?>), url('/dynamic/thumbs/default.png');">
                                            <a class="quicklist-add" style="top: 30px;" href="/get/add_to_quicklist?v=<?php echo $video['rid']; ?>"></a>
                                            <span class="timestamp"><?php echo $_video_fetch_utils->timestamp($video['duration']); ?></span></a>
                                        
                                        <div class="video-info-watch">
                                            <a style="color: <?php echo htmlspecialchars($_user['text_color']); ?>;" href="/watch?v=<?php echo $video['rid']; ?>"><b><?php echo htmlspecialchars($video['title']); ?></b></a><br>
                                            <span class="video-info-small-wide">
                                                <span class="video-views"><?php echo $_video_fetch_utils->fetch_video_views($video['rid']); ?> views</span><br>
                                                <a style="padding-left: 0px;color: <?php echo htmlspecialchars($_user['text_color']); ?>;" class="video-author-wide" href="/user/<?php echo htmlspecialchars($video['author']); ?>"><?php echo htmlspecialchars($video['author']); ?></a>
                                            </span>
                                        </div>
                                        
                                    </div>
                        <?php } }?>
                    </div>
                </div>
                </span>
                <span id="www-grid" style="display: none;">
                <div style="min-height: 525px;color: <?php echo htmlspecialchars($_user['primary_color_text']); ?>;padding-top: 8px;">
                    <?php
                        $stmt = $conn->prepare("SELECT rid, title, thumbnail, duration, title, author, publish, description FROM videos WHERE author = ? ORDER BY id DESC LIMIT 8");
                        $stmt->bind_param("s", $_user['username']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        while($video = $result->fetch_assoc()) {
                    ?>
                        <div class="grid-item" style="">
							<a href="/user/<?php echo htmlspecialchars($video['author']); ?>">
                            <img class="thumbnail" onerror="this.src='/dynamic/thumbs/default.png'" src="/dynamic/thumbs/<?php echo $_video_fetch_utils->parseTitle($video['thumbnail']); ?>">
                            </a>
							<div class="video-info-grid">
                                <a style="color: <?php echo htmlspecialchars($_user['text_color']); ?>;display: inline-block;width: 127px;word-wrap: break-word;" href="/watch?v=<?php echo $video['rid']; ?>"><?php echo $_video_fetch_utils->parse_title($video['title']);  ?></a><br>
                                <span class="video-info-small">
                                    <span class="video-views" style="color: <?php echo htmlspecialchars($_user['text_color']); ?>;"><?php echo $_video_fetch_utils->fetch_video_views($video['rid']); ?> views</span><br>
                                    <a style="color: <?php echo htmlspecialchars($_user['text_color']); ?>;" href="/user/<?php echo htmlspecialchars($video['author']); ?>"><?php echo htmlspecialchars($video['author']); ?></a>
                                </span>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                </span>
            </div>
            <?php } else { ?>
                <?php if(isset($_SESSION['siteusername']) && $_SESSION['siteusername'] == $_user['username'] && $_user['videos'] == 0) { ?>
                    <div style="color: <?php echo htmlspecialchars($_user['primary_color_text']); ?>;padding: 5px;border: 2px solid <?php echo htmlspecialchars($_user['secondary_color']); ?>;position: relative;bottom: 3px;">
                        <h1>Welcome to your SubRocks channel!</h1>
                        You can:<br>
                        •  Upload videos, favorite others' videos, or group videos into playlists -- they'll all show up here for your channel viewers to watch and enjoy.<br>
                        •  Customize the look and feel of your channel by clicking the "Edit Channel" buttons at the top of the page or "edit" links thoughout the channel.<br>
                    </div>
                <?php } ?>
            </div>
            <?php } ?>
            </div>
            <br>
            <script>
                function main() {
                    $("#www-main").fadeIn(75);
                    $("#www-grid").fadeOut(75);
                }

                function grid() {
                    $("#www-main").fadeOut(75);
                    $("#www-grid").fadeIn(75);
                }
            </script>
            
            <div style="border-radius:3px;padding:5px;overflow: auto;background-color: <?php echo htmlspecialchars($_user['primary_color']); ?>;opacity: <?php echo htmlspecialchars($_user['transparency']); ?>">
                <div class="www-channel-left">
                    <?php 
                        error_reporting(0);
                        ini_set('display_errors', 0);

                        $_user['module'] = 0;
                        foreach($_user['2009_user_left'] as $module) {
                            $_user['module']++;
                            echo "<!--mod" . $_user['module'] . "-->";
                            require($_SERVER['DOCUMENT_ROOT'] . "/static/module/channel2/" . clean($module) . ".php");
                        }
                    ?>
                </div>
                <div class="www-channel-right">
                    <?php 
                        $_user['module'] = 0;
                        foreach($_user['2009_user_right'] as $module) {
                            $_user['module']++;
                            echo "<!--mod" . $_user['module'] . "-->";
                            require($_SERVER['DOCUMENT_ROOT'] . "/static/module/channel2/" . clean($module) . ".php");
                        }
                    ?>
                </div>
            </div>
        </div>
        <div id="channelbg">
            &nbsp;
        </div>
    </body>
</html>