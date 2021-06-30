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

    $_video = $_video_fetch_utils->fetch_video_rid($_GET['id']);
    if(!isset($_SESSION['siteusername'])) { header("Location: /login"); } 
    if($_video['author'] != $_SESSION['siteusername']) { header("Location: /video_manager"); } 

    $_base_utils->initialize_page_compass("Editing " . htmlspecialchars($_video['title']));

    if($_video['thumbnail'] == ".png" && $_video['filename'] == ".mp4") {
        $_video['status'] = "Corrupted";
    } else if($_video['visibility'] == "v") {
        $_video['status'] = "Approved";
    } else if($_video['visibility'] == "n") {
        $_video['status'] = "Waiting for Approval";
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
        <title>FulpTube - <?php echo $_base_utils->return_current_page(); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/static/css/www-core.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    </head> 
    <body>
        <div class="www-core-container" style="height: 1300px;">
            <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/header.php"); ?>
            <script src="/static/js/alert.js"></script>
            <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/module_sidebar.php"); ?>
            <div class="manage-top">
                <h1><?php echo htmlspecialchars($_video['title']); ?></h1>
            </div>
            <div class="manage-base">
                <form method="post" action="/post/edit_video?id=<?php echo $_video['rid']; ?>" enctype="multipart/form-data" id="submitform">
                <button style="margin-left: 5px;float: right;background-color: #f8f8f8; color: #333; border-color: #d3d3d3;" type="button" class="www-button www-button-grey" href="/signup" role="button">
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

                        <input class="www-button www-button-grey" type="submit" value="Set">
                        <div style="position:relative;left: 390px; top: -211px; display: none;">
                            <b>Privacy Settings</b> <img src="/static/info.png" title="This is a way to make your video inaccessible to people." style="vertical-align: middle;"><br>
                            <select id="privacy" name="privacy" style="padding: 3px;border-radius:5px; border: 1px solid #d3d3d3;">
                                <option value="pub">Public</option>
                                <option value="lnk">Link Only</option>
                                <option value="prv">Private</option>
                            </select>
                        </div>
                        <div style="position:relative;left: 390px; top: -326px;">
                            <b>Category</b><br>
                            <select id="category" name="category" style="padding: 3px;border-radius:5px; border: 1px solid #d3d3d3;">  
                                <?php $categories = ["None", "Film & Animation", "Autos & Vehicles", "Music", "Pets & Animals", "Sports", "Travel & Events", "Gaming", "People & Blogs", "Comedy", "Entertainment", "News & Politics", "Howto & Style", "Education", "Science & Technology", "Nonprofits & Activism"]; ?>
                                <?php foreach($categories as $categoryTag) { ?>
                                    <option value="<?php echo $categoryTag; ?>"><?php echo $categoryTag; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div style="position:relative;left: 390px;top: -311px;">
                            <b>Thumbnail</b><br>
                            <input type="file" name="fileToUpload" id="fileToUpload">
                        </div>

                        <div style="position:relative;left: 390px;top: -286px;">
                            <b>Comment Status: </b><br>
                            <?php echo $_video['commentstatus']; ?><br><br>
                            <b>Video Status: </b><br>
                            <?php echo $_video['status']; ?>
                        </div>
                    </div><br>
                </form>
            </div>
        </div>
        <div class="www-core-container">
        <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/footer.php"); ?>
        </div>

        <script>
            var alerts = 0; 
            $('#submitform' ).submit(
                function( e ) {
                    $.ajax( {
                        url: '/post/edit_video?id=<?php echo $_video['rid']; ?>',
                        type: 'POST',
                        data: new FormData( this ),
                        processData: false,
                        enctype: 'multipart/form-data',
                        contentType: false,
                        success: function(result){
                            alerts++;
                            addAlert("editsuccess_" + alerts, "Successfully updated your video!");
                            showAlert("#editsuccess_" + alerts);
                        }
                    } );
                    e.preventDefault();
                } 
            );
        </script>
    </body>
</html>