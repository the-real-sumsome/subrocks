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

    $_category = $_user_fetch_utils->fetch_category_name($_GET['c']);

    if(isset($_SESSION['siteusername']) && !$_user_fetch_utils->user_exists(@$_SESSION['siteusername'])) 
        header("Location: /logout");

    if(!isset($_SESSION['siteusername'])) {
        header("Location: /sign_in");
    }

    $_base_utils->initialize_page_compass($_category['title']);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>SubRocks - <?php echo $_base_utils->return_current_page(); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/static/css/new/www-core.css">
        <script src='https://www.google.com/recaptcha/api.js' async defer></script>
        <script>function onLogin(token){ document.getElementById('submitform').submit(); }</script>
        <style>
            .channel-box-top {
                background: #666;
                color: white;
                padding: 5px;
            }

            .sub_button {
                position: relative;
                bottom: 2px;
            }

            .channel-box-description {
                background: #e6e6e6;
                border: 1px solid #666;
                color: #666;
                padding: 5px;
            }

            .channel-box-no-bg {
                border: 1px solid #666;
                color: black;
                padding: 5px;
            }

            .channel-pfp {
                height: 88px;
                width: 88px;
                border-color: #666;
                border: 3px double #999;
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
                border-color: #666;
                display: inline-block;
                border: 3px double #999;
            }
        </style>
    </head>
    <body>
        <?php
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                if(!isset($_SESSION['siteusername'])){ $error = "you are not logged in"; goto skipcomment; }
                if(!$_POST['comment']){ $error = "your comment cannot be blank"; goto skipcomment; }
                if(strlen($_POST['comment']) > 1000){ $error = "your comment must be shorter than 1000 characters"; goto skipcomment; }
                //if(!isset($_POST['g-recaptcha-response'])){ $error = "captcha validation failed"; goto skipcomment; }
                //if(!$_user_insert_utils->validateCaptcha($config['recaptcha_secret'], $_POST['g-recaptcha-response'])) { $error = "captcha validation failed"; goto skipcomment; }
                //if(ifBlocked(@$_SESSION['siteusername'], $user['username'], $conn)) { $error = "This user has blocked you!"; goto skipcomment; } 
        
                $stmt = $conn->prepare("INSERT INTO `forum_thread` (category, author, contents, title) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $_category['title'], $_SESSION['siteusername'], $text, $title);
                $title = $_POST['title'];
                $text = $_POST['comment'];
                $stmt->execute();
                $stmt->close();
        
                skipcomment:

                echo "<script>
                    window.location = 'category?c=" . $_category['title'] . "';
                </script>";
            }
        ?>
        <div class="www-core-container">
            <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/header.php"); ?>
            <h1>New Thread</h1>
            <span style="font-size: 11px;" class="grey-text">Make sure to not be rude and to make your thread not violate the Terms of Service.</span>
            <hr class="thin-line">
            <form method="post" action="" id="submitform">
                    <input type="text" name="title" required="required" placeholder="Title" style="width: 300px;"><br>
                    <textarea 
                        onkeyup="textCounter(this,'counter',500);" 
                        class="comment-textbox" cols="32" id="com" style="width: 300px;"
                        placeholder="Make a new forum post" name="comment"></textarea><br><br> 
                    <input disabled class="characters-remaining" maxlength="3" size="3" value="500" id="counter"> <?php if(!isset($cLang)) { ?> characters remaining <?php } else { echo $cLang['charremaining']; } ?> 
                    <br>
                    <input type="submit" value="Post" class="www-button www-button-grey">
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
        </div>
        </div>
        <div class="www-core-container">
        <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/footer.php"); ?>
        </div>

    </body>
</html>