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

    if(!$_video_fetch_utils->group_exists($_GET['id']))
        header("Location: /sign_in");

    $_group= $_video_fetch_utils->fetch_group_id($_GET['id']);
    if(!isset($_SESSION['siteusername'])) { header("Location: /login"); } 
    if($_group['group_author'] != $_SESSION['siteusername']) { header("Location: /groups"); } 

    $_base_utils->initialize_page_compass("Editing " . htmlspecialchars($_group['group_title']));

    if($_group['thumbnail'] == ".png" && $_group['filename'] == ".mp4") {
        $_group['status'] = "Corrupted";
    } else if($_group['visibility'] == "v") {
        $_group['status'] = "Approved";
    } else if($_group['visibility'] == "n") {
        $_group['status'] = "Approved";
    } else if($_group['visibility'] == "o") {
        $_group['status'] = "Disapproved";
    } else {
        $_group['status'] = "Unknown";
    }

    if($_group['commenting'] == "a") 
        $_group['commentstatus'] = "Commenting allowed";
    else 
        $_group['commentstatus'] = "Commenting disallowed";
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
            $_group['stars'] = $_video_fetch_utils->get_video_stars($_group['rid']);
            $_group['star_1'] = $_video_fetch_utils->get_video_stars_level($_group['rid'], 1);
            $_group['star_2'] = $_video_fetch_utils->get_video_stars_level($_group['rid'], 2);
            $_group['star_3'] = $_video_fetch_utils->get_video_stars_level($_group['rid'], 3);
            $_group['star_4'] = $_video_fetch_utils->get_video_stars_level($_group['rid'], 4);
            $_group['star_5'] = $_video_fetch_utils->get_video_stars_level($_group['rid'], 5);

            //@$_group['star_ratio'] = ($_group['star_1'] + $_group['star_2'] + $_group['star_3'] + $_group['star_4'] + $_group['star_5']) / $_group['stars'];

            /* 
                5 star - 252
                4 star - 124
                3 star - 40
                2 star - 29
                1 star - 33

                totally 478 

                (252*5 + 124*4 + 40*3 + 29*2 + 33*1) / (252 + 124 + 40 + 29 + 33)
            */

            if($_group['stars'] != 0) {
                @$_group['star_ratio'] = (
                    $_group['star_5'] * 5 + 
                    $_group['star_4'] * 4 + 
                    $_group['star_3'] * 3 + 
                    $_group['star_2'] * 2 + 
                    $_group['star_1'] * 1
                ) / (
                    $_group['star_5'] + 
                    $_group['star_4'] + 
                    $_group['star_3'] + 
                    $_group['star_2'] + 
                    $_group['star_1']
                );

                $_group['star_ratio'] = floor($_group['star_ratio'] * 2) / 2;
            } else { 
                $_group['star_ratio'] = 0;
            }
        ?>
        <div class="www-core-container">
            <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/header.php"); ?>
            <script src="/static/js/alert.js"></script>
            <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/module_sidebar.php"); ?>
            <div class="manage-top">
                <div style="width: 100%;border-top: 1px solid #CACACA;border-bottom: 1px solid #CACACA;">
                    <h3 style="margin-top: 0px;padding: 16px;"><?php echo htmlspecialchars($_group['group_title']); ?></h3>
                </div>
            </div>
                <div class="manage-base" style="margin-top: -674px !important;">
                    <button style="margin-left: 5px;float: right;" type="button" class="www-button www-button-grey" href="/signup" role="button">
                        <span class="www-button-content">
                            <a style="color: #333; text-decoration: none;" href="/get/delete_video?id=<?php echo $_group['rid']; ?>">Delete</a>
                        </span>
                    </button>&nbsp;
                    <button class="www-button www-button-grey" style="margin-left: 5px;float: right;" type="button" class="www-button www-button-grey" href="/signup" role="button">
                        <span class="www-button-content">
                            <a style="color: #333; text-decoration: none;" href="/groups">Cancel </a>
                        </span>
                    </button>&nbsp;
                    <a href="#">
                        <button class="www-button www-button-grey">
                        Basic Info
                        </button>
                    </a>
                    <hr style="border-top: 1px solid #d3d3d3; border-bottom: 0px solid black; padding: 3px;">
                    <div style="width: 650px; padding: 15px; height: 327px;">
                        <button style="position: relative;top: 4px;width: 753px;" class="collapsible active-dropdown">
                            Basic Group Info
                        </button>
                        <div style="display: block;width: 747px;" class="content">
                            <br>
                            <form method="post" action="/post/edit_group?id=<?php echo $_group['rid']; ?>" enctype="multipart/form-data" id="submitform" style="position: relative;top: 0;">
                                <b>Title</b> <br><input style="width: 345px;padding:5px;border-radius:5px;background-color:white;border: 1px solid #d3d3d3;" type="text" name="title" id="upltx" value="<?php echo htmlspecialchars($_group['group_title']); ?>" required="required" row="20"><br><br>
                                <b>Description</b><br>
                                <textarea onkeyup="textCounter(this,'counter',500);"  style="resize: none;width: 345px;padding:5px;border-radius:5px;background-color:white;border: 1px solid #d3d3d3;" id="upltx2" name="comment"><?php echo htmlspecialchars($_group['group_description']); ?></textarea><br><br>
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
                            </div>
                            <br>

                            <button style="position: relative;top: 4px;width: 753px;" class="collapsible active-dropdown">
                                Misc. Group Info
                            </button>
                            <div style="display: block;width: 747px;" class="content">
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
                                    <b>Group Icon [50x50]</b><br>
                                    <input type="file" name="fileToUpload" id="fileToUpload">
                                </div><br>
                            </div>
                        </div>
                        <input style="position: relative;top: 271px;left: 13px;" class="www-button www-button-grey" type="submit" value="Set">
                    </div><br>
                </form>
            </div><br>
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
                        url: '/post/edit_group?id=<?php echo $_group['id']; ?>',
                        type: 'POST',
                        data: data,
                        cache: false,
                        processData: false,
                        contentType: false,
                        success: function(result){
                            alerts++;
                            addAlert("editsuccess_" + alerts, "Successfully updated your group!");
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