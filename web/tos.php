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

    $_base_utils->initialize_page_compass("TOS");
?>
<!DOCTYPE html>
<html>
    <head>
        <title>SubRocks - <?php echo $_base_utils->return_current_page(); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/static/css/new/www-core.css">
        <style>
        .content {
            background-color: white;
            overflow: hidden;
            transition: max-height 0.2s ease-out;
            background: whitesmoke;
            padding: 3px;
            border-radius: 3px;
        }
        </style>
    </head>
    <body>
        <div class="www-core-container">
            <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/header.php"); ?>
                <center>
                    <img src="/static/img/fulptube.png" style="width: 151px">
                </center><br>
                <button class="collapsible">SubRocks Terms and Service</button>
                <div class="content">
                    <p>
                        <b>General Rules</b><br>
                        Just don't be a huge idiot. This is a site made for fun. <br>
                        This is a site aimed towards recreating 2009 YouTube. <br>
                        SubRocks is not associated in any way with Google LLC.<br>
                        All content found violating the USA's laws and federal regulations will be removed and the account associated with it banned.<br>
                        All attempts at ban evasion will NOT BE TOLERATED and will end up with a ban from SubRocks.<br>
                        No NSFW, no bigotry, no homophobia, and just use common sense. Thanks.<br><br>
                        <b>Bug Reports + Video Reports</b><br>
                        If you see a video breaking the TOS, please notify us at the discord in the footer.<br>
                        Found a bug? report it in the #bug-reports channel in the discord.<br><br>
                        <b>Contact</b><br>
                        Email: spacemygithub@gmail.com<br>
                        Official Discord Server: In the footer<br>
                        Discord: bhief cazinga#5644
                    </p><br>
                </div><br>

                <button class="collapsible">SubRocks Privacy Policy</button>
                <div class="content">
                    <p>
                        <b>Privacy</b><br>
                        We do NOT store IPs. All that is stored is your password, email, and username.<br>
                        Other things that are stored are your videos, comments, etcettera. You can request to retrieve them.<br>
                        We do not sell data. <br>
                        Have any privacy concerns? The discord is located at the footer.<br>
                    </p><br>
                </div><br>

                <button class="collapsible">SubRocks Copyright</button>
                <div class="content">
                    <p>
                        <b>Copyright</b><br>
                        Make sure you're not making money off of music that is playing in a video's background.<br>
                        You can upload raw full songs as long as you give the original publisher credit.<br>
                        Impersonation of other trademarked or copyrighted companies is not tolerated.<br>
                        You have to make it easily noticeable that your channel is a parody if it is.<br><br>
                        <b>Copyright Infringement</b><br>
                        To file a copyright infringement with us, you will have to email spacemygithub@gmail.com. <br>Send the infringing video, and proof that you own the content that you are talking about. <br>If it is a user that is infringing copyrights, give us the user profile.<br><b>Don't make false claims!</b>
                    </p><br>
                </div><br>

                <button class="collapsible">SubRocks Q&A</button>
                <div class="content">
                    <p>
                        <b>I forgot my password.</b><br>
                        Join the Discord for support regarding passwords.<br><br>
                        <b>Why?</b><br>
                        I made SubRocks as a project for fun and I never expected so much traction and attention for it. <br>I'm keeping this site up as long as I can.<br>You should join the Discord if you want to talk to the community.<br><br>
                        <b>Who's the owner of this site?</b><br>
                        <a href="/user/bhief">chief bazinga</a><br>
                    </p><br>
                </div><br>
            </div>
            <script>
                var coll = document.getElementsByClassName("collapsible");
                var i;

                for (i = 0; i < coll.length; i++) {
                coll[i].addEventListener("click", function() {
                        this.classList.toggle("active");
                        var content = this.nextElementSibling;
                        if (content.style.display === "block") {
                        content.style.display = "none";
                        } else {
                        content.style.display = "block";
                        }
                    });
                } 
            </script>
        </div>
        <div class="www-core-container">
        <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/footer.php"); ?>
        </div>

    </body>
</html>