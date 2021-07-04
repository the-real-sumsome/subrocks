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
<?php
    if($_SERVER['REQUEST_METHOD'] == 'POST' && @$_POST['send']) {
        $_user_insert_utils->send_message($_POST['to'], $_POST['subject'], $_POST['message'], $_SESSION['siteusername']);
        

        echo "<script>
            window.location = '/inbox/';
        </script>";
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
                            <h3 style="margin-top: 0px;padding: 16px;">Compose</h3>
                        </div>
                        <div style="padding: 10px;">
                        <form method="post">
                        <table>
                            <tr>
                                <th></th>
                            </tr>
                            <tr>
                                <td style="padding: 5px;vertical-align: top;"><b style="vertical-align: top;"><?php if(!isset($cLang)) { ?> From: <?php } else { echo $cLang['from']; } ?> </b> <?php echo htmlspecialchars($_SESSION['siteusername']); ?></td>
                            </tr>
                            <tr>
                                <td style="padding: 5px;vertical-align: top;"><b style="vertical-align: top;"><?php if(!isset($cLang)) { ?> To: <?php } else { echo $cLang['to']; } ?> </b> <input value="<?php if(isset($_GET['to'])) { echo htmlspecialchars($_GET['to']); } ?>" style="border-radius: 2px;
background-color: white;
border: 1px solid #d3d3d3;" type="text" name="to"></td>
                            </tr>
                            <tr>
                                <td style="padding: 5px;vertical-align: top;"><b style="vertical-align: top;"><?php if(!isset($cLang)) { ?> Subject: <?php } else { echo $cLang['subject']; } ?></b> &nbsp;<input value="<?php if(isset($_GET['subject'])) { echo htmlspecialchars($_GET['subject']); } ?>" style="border-radius: 2px;
background-color: white;
border: 1px solid #d3d3d3;" type="text" name="subject"></td>
                            </tr>
                            <tr>
                                <td style="padding: 5px;vertical-align: top;"><b style="vertical-align: top;"><?php if(!isset($cLang)) { ?> Message: <?php } else { echo $cLang['messageInbox']; } ?> </b> <textarea style="border-radius: 2px;
background-color: white;
border: 1px solid #d3d3d3;" name="message"></textarea></td>
                            </tr>
                        </table><br>
                        <input class="yt-uix-button yt-uix-button-default" name="send" type="submit" value="<?php if(!isset($cLang)) { ?> Send Message <?php } else { echo $cLang['sendMessage']; } ?> ">
                        </form>
                        </div>
            </div>
        </div>
        <div class="www-core-container">
        <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/footer.php"); ?>
        </div>

    </body>
</html>