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

    if(!$_video_fetch_utils->video_exists($_GET['id']))
        header("Location: /sign_in");

    $_video = $_video_fetch_utils->fetch_video_rid($_GET['id']);
    if(!isset($_SESSION['siteusername'])) { header("Location: /login"); } 
    if($_video['author'] != $_SESSION['siteusername']) { header("Location: /video_manager"); } 

    $_base_utils->initialize_page_compass("Editing " . htmlspecialchars($_video['title']));

    if($_video['thumbnail'] == ".png" && $_video['filename'] == ".mp4") {
        $_video['status'] = "Corrupted";
    } else if($_video['visibility'] == "v") {
        $_video['status'] = "Approved";
    } else if($_video['visibility'] == "n") {
        $_video['status'] = "Approved";
    } else if($_video['visibility'] == "o") {
        $_video['status'] = "Disapproved";
    } else {
        $_video['status'] = "Unknown";
    }

    if($_video['commenting'] == "a") 
        $_video['commentstatus'] = "Commenting allowed";
    else 
        $_video['commentstatus'] = "Commenting disallowed";
?>
<!DOCTYPE html>
<html>
    <head>
        <title>SubRocks - <?php echo $_base_utils->return_current_page(); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/static/css/new/www-core.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <style>
            .collapsible {
                border: 1px solid #dddddd;
                background: rgb(230,230,230);
                background: -moz-linear-gradient(0deg, rgba(230,230,230,1) 0%, rgba(255,255,255,1) 100%, rgba(255,255,255,1) 100%);
                background: -webkit-linear-gradient(0deg, rgba(230,230,230,1) 0%, rgba(255,255,255,1) 100%, rgba(255,255,255,1) 100%);
                background: linear-gradient(0deg, rgba(230,230,230,1) 0%, rgba(255,255,255,1) 100%, rgba(255,255,255,1) 100%);
                filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#e6e6e6",endColorstr="#ffffff",GradientType=1); 
                font-size: 13px;
                padding: 5px;
            }

            .content {
                padding: 3px;
                background-color: #f9f9f9;
            }
        </style>
    </head>
    <body>
        <?php 
            $_video['stars'] = $_video_fetch_utils->get_video_stars($_video['rid']);
            $_video['star_1'] = $_video_fetch_utils->get_video_stars_level($_video['rid'], 1);
            $_video['star_2'] = $_video_fetch_utils->get_video_stars_level($_video['rid'], 2);
            $_video['star_3'] = $_video_fetch_utils->get_video_stars_level($_video['rid'], 3);
            $_video['star_4'] = $_video_fetch_utils->get_video_stars_level($_video['rid'], 4);
            $_video['star_5'] = $_video_fetch_utils->get_video_stars_level($_video['rid'], 5);

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
        <div class="www-core-container">
            <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/header.php"); ?>
            <script src="/static/js/alert.js"></script>
            <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/module_sidebar.php"); ?>
            <div class="manage-top">
                <div style="width: 100%;border-top: 1px solid #CACACA;border-bottom: 1px solid #CACACA;">
                    <h3 style="margin-top: 0px;padding: 16px;"><?php echo htmlspecialchars($_video['title']); ?></h3>
                </div>
            </div>
                <div class="manage-base" style="margin-top: -674px !important;">
                    <button style="margin-left: 5px;float: right;" type="button" class="www-button www-button-grey" href="/signup" role="button">
                        <span class="www-button-content">
                            <a style="color: #333; text-decoration: none;" href="/get/delete_video?id=<?php echo $_video['rid']; ?>">Delete</a>
                        </span>
                    </button>&nbsp;
                    <button class="www-button www-button-grey" style="margin-left: 5px;float: right;" type="button" class="www-button www-button-grey" href="/signup" role="button">
                        <span class="www-button-content">
                            <a style="color: #333; text-decoration: none;" href="/get/toggle_comment?id=<?php echo $_video['rid']; ?>">Toggle Comments</a>
                        </span>
                    </button>&nbsp;
                    <button class="www-button www-button-grey" style="margin-left: 5px;float: right;" type="button" class="www-button www-button-grey" href="/signup" role="button">
                        <span class="www-button-content">
                            <a style="color: #333; text-decoration: none;" href="video_manager">Cancel </a>
                        </span>
                    </button>&nbsp;
                    <a href="#">
                        <button class="www-button www-button-grey">
                        Basic Info
                        </button>
                    </a>
                    <hr style="border-top: 1px solid #d3d3d3; border-bottom: 0px solid black; padding: 3px;">
                    <div style="width: 650px; padding: 15px; height: 327px;">
                        <button style="position: relative;top: 4px;width: 369px;" class="collapsible active-dropdown">
                            Basic Video Info
                        </button>
                        <div style="display: block;width: 363px;" class="content">
                            <br>
                            <form method="post" action="/post/edit_video?id=<?php echo $_video['rid']; ?>" enctype="multipart/form-data" id="submitform" style="position: relative;top: 0;">
                                <b>Title</b> <br><input style="width: 345px;padding:5px;border-radius:5px;background-color:white;border: 1px solid #d3d3d3;" type="text" name="title" id="upltx" value="<?php echo htmlspecialchars($_video['title']); ?>" required="required" row="20"><br><br>
                                <b>Description</b><br>
                                <textarea onkeyup="textCounter(this,'counter',500);"  style="resize: none;width: 345px;padding:5px;border-radius:5px;background-color:white;border: 1px solid #d3d3d3;" id="upltx2" name="comment"><?php echo htmlspecialchars($_video['description']); ?></textarea><br><br>
                                <input disabled style="width: 20px;background-color: transparent; border: 0px solid transparent;" maxlength="3" size="3" value="500" id="counter"> characters remaining 
                                <br><br>
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
                                <b>Tags</b> 
                                <br>
                                <input id="tags" value="<?php echo htmlspecialchars($_video['tags']); ?>" style="width: 345px;padding:5px;border-radius:5px;background-color:white;border: 1px solid #d3d3d3;" placeholder="Seperate tags with commas" type="text" name="tags" required="required" row="20"><br><br>
                            
                                <b>Recommended Tags</b><br><br>
                                <div style="width: 345px;">
                                    <span class="upload-add-tag" onclick="addTag('game');">+ game</span>
                                    <span class="upload-add-tag" onclick="addTag('funny');">+ funny</span>
                                    <span class="upload-add-tag" onclick="addTag('education');">+ education</span>
                                    <span class="upload-add-tag" onclick="addTag('blog');">+ blog</span>
                                    <span class="upload-add-tag" onclick="addTag('travel');">+ travel</span>
                                    <span class="upload-add-tag" onclick="addTag('science');">+ science</span>
                                </div>
                                <script>
                                    var tags = document.getElementById("tags");

                                    function addTag(tag) {
                                        tags.value = tags.value + ", " + tag;
                                    } 
                                </script><br>
                            </div>
                            <br>

                            <button style="position: relative;top: 4px;width: 369px;" class="collapsible active-dropdown">
                                Misc. Video Info
                            </button>
                            <div style="display: block;width: 363px;" class="content">
                                <div style="display: none;">
                                    <b>Privacy Settings</b> <img src="/static/info.png" title="This is a way to make your video inaccessible to people." style="vertical-align: middle;"><br>
                                    <select id="privacy" name="privacy" style="padding: 3px;border-radius:5px; border: 1px solid #d3d3d3;">
                                        <option value="pub">Public</option>
                                        <option value="lnk">Link Only</option>
                                        <option value="prv">Private</option>
                                    </select>
                                </div>
                                <div>
                                    <br>
                                    <b>Category</b><br>
                                    <select id="category" name="category" style="padding: 3px;border-radius:5px; border: 1px solid #d3d3d3;">  
                                        <?php $categories = ["None", "Film & Animation", "Autos & Vehicles", "Music", "Pets & Animals", "Sports", "Travel & Events", "Gaming", "People & Blogs", "Comedy", "Entertainment", "News & Politics", "Howto & Style", "Education", "Science & Technology", "Nonprofits & Activism"]; ?>
                                        <?php foreach($categories as $categoryTag) { ?>
                                            <option value="<?php echo $categoryTag; ?>"><?php echo $categoryTag; ?></option>
                                        <?php } ?>
                                    </select>
                                </div><br>
                                <div>
                                    <b>Thumbnail</b><br>
                                    <input type="file" name="fileToUpload" id="fileToUpload">
                                </div><br>

                                <div>
                                    <b>Comment Status: </b><br>
                                    <?php echo $_video['commentstatus']; ?><br><br>
                                    <b>Video Status: </b><br>
                                    <?php echo $_video['status']; ?>
                                </div><br>
                            </div>
                        </div>
                        <input style="position: relative;top: 271px;left: 13px;" class="www-button www-button-grey" type="submit" value="Set">
                    </div><br>
                </form>
            </div><br>
            <iframe style="width: 372px;height: 294px;float: right;position: relative;bottom: 635px;left: 0px;border: 0px; overflow: hidden;" src="/2009player/lolplayer?id=<?php echo $_video['rid']; ?>" height="365" width="646"></iframe>
            <div style="background-color: #f9f9f9;position: relative;left: 598px;bottom: 340px;width: 362px;padding: 5px;">
                <h3><a style="color: #000;font-weight;normal;" href="/watch?v=<?php echo htmlspecialchars($_video['rid']); ?>"><?php echo htmlspecialchars($_video['title']);  ?></a></h3>
                From: <a href="/user/<?php echo htmlspecialchars($_video['author']); ?>">
                        <?php echo htmlspecialchars($_video['author']); ?>
                    </a>
                        | <?php echo date("M d, Y", strtotime($_video['publish'])); ?> | <?php echo $_video_fetch_utils->fetch_video_views($_video['rid']); ?> views
                    <br>
                <p style="font-size: 10px;width: 213px;">
                    <?php echo $_video_fetch_utils->parseTextNoLink($_video['description']); ?>
                </p>
                
                <div style="float:right;padding: 1px;border-radius: 3px;position: relative;bottom: 30px;">
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
                </div>
            </div>
        </div>
        <div class="www-core-container">
        <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/footer.php"); ?>
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
            var alerts = 0; 
            $('#submitform' ).submit(
                function( e ) {
                    var data = new FormData(this);
                    jQuery.each(jQuery('#fileToUpload')[0].files, function(i, file) {
                        data.append('file-'+i, file);
                    });

                    $.ajax( {
                        url: '/post/edit_video?id=<?php echo $_video['rid']; ?>',
                        type: 'POST',
                        data: data,
                        cache: false,
                        processData: false,
                        contentType: false,
                        success: function(result){
                            alerts++;
                            addAlert("editsuccess_" + alerts, "Successfully updated your video!");
                            showAlert("#editsuccess_" + alerts);
                            console.log("DEBUG: " + result);
                        }
                    } );
                    e.preventDefault();
                } 
            );
        </script>
    </body>
</html>