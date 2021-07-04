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
    
    $_base_utils->initialize_page_compass("Inbox");
?>
<?php $mail = $_user_fetch_utils->fetch_mail($_GET['id']);?>
<?php
    if(!isset($_SESSION['siteusername'])) {
        header("Location: /sign_in");
    }
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
            <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/module_sidebar.php"); ?>
            <div style="width: 777px; background-color: white;position: border: 1px solid #d3d3d3; float: right;">
                    <div style="width: 100%;border-top: 1px solid #CACACA;border-bottom: 1px solid #CACACA;">
                        <h3 style="margin-top: 0px;padding: 16px;"><?php echo $mail['subject']; ?></h3>
                    </div>
                    <div style="padding: 10px;">
                    <b><?php if(!isset($cLang)) { ?> From: <?php } else { echo $cLang['from']; } ?></b> <?php echo htmlspecialchars($mail['owner']); ?><br>
                        <b><?php if(!isset($cLang)) { ?> Date <?php } else { echo $cLang['date']; } ?> :</b> <?php echo date("Y-m-d", strtotime($mail['date'])); ?><br><br>
                        <b><?php if(!isset($cLang)) { ?> Message <?php } else { echo $cLang['message']; } ?> :</b><br>
                        <?php echo $_video_fetch_utils->parseTextDescription($mail['message']); ?><br><br>
                        <a style="color: #333; text-decoration: none;" href="/inbox/send?to=<?php echo htmlspecialchars($mail['owner']); ?>&subject=RE: <?php echo htmlspecialchars($mail['subject']); ?>">
                        <button type="button" class="yt-uix-button yt-uix-button-default" href="/signup" role="button">
                            <span class="yt-uix-button-content">
                                Reply
                            </span>
                        </button></a><br>
                    </div>
                </div>
            </div>
        </div>
        <div class="www-core-container">
        <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/footer.php"); ?>
        </div>

    </body>
</html>