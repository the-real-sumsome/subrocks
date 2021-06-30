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

    $_base_utils->initialize_page_compass("Videos");

    $category = "None";

    // "None", "Film & Animation", "Autos & Vehicles", "Music", "Pets & Animals", "Sports", "Travel & Events", "Gaming", "People & Blogs", "Comedy", "Entertainment", "News & Politics", "Howto & Style", "Education", "Science & Technology", "Nonprofits & Activism"
    //handle category

    if(isset($_GET['c'])) 
        $category = ($_GET['c']);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>SubRocks - <?php echo $_base_utils->return_current_page(); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/static/css/new/www-core.css">

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
        <div class="www-core-container">
            <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/header.php"); ?>
            <div class="www-videos-left">
                <h2>Channels</h2><br>
                <ul class="videos-list">
                    <?php $categories = ["None", "Film & Animation", "Autos & Vehicles", "Music", "Pets & Animals", "Sports", "Travel & Events", "Gaming", "People & Blogs", "Comedy", "Entertainment", "News & Politics", "Howto & Style", "Education", "Science & Technology", "Nonprofits & Activism"]; ?>
                    <?php foreach($categories as $categoryTag) { ?>
                        <?php if($categoryTag == $category) { ?>
                            <li class=""><?php echo $categoryTag; ?></li>
                        <?php } else { ?>
                            <li class=""><a href="#"><?php echo $categoryTag; ?></a></li>
                        <?php } ?>
                    <?php } ?>
                </ul>
            </div>
            <div class="www-videos-right">
                <h3><?php echo htmlspecialchars($category); ?></h3>
                <div class="videos-box">
                    <div class="videos-title-box-browse">
                    
                    </div>
                    <div class="videos-title-box-contents">
                            <?php
                            $stmt56 = $conn->prepare("SELECT username, pfp FROM users ORDER BY lastlogin DESC");
                            $stmt56->execute();
                            $result854 = $stmt56->get_result();
                            $result56 = $result854->num_rows;
                            ?>
                            <?php
                            $results_per_page = 20;

                            $stmt = $conn->prepare("SELECT username, pfp FROM users ORDER BY lastlogin DESC");
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $results = $result->num_rows;

                            $number_of_result = $result->num_rows;
                            $number_of_page = ceil ($number_of_result / $results_per_page);  

                            if (!isset ($_GET['page']) ) {  
                                $page = 1;  
                            } else {  
                                $page = (int)$_GET['page'];  
                            }  

                            $page_first_result = ($page - 1) * $results_per_page;  

                            $stmt->close();

                            $stmt = $conn->prepare("SELECT username, pfp FROM users ORDER BY lastlogin DESC LIMIT ?, ?");
                            $stmt->bind_param("ss", $page_first_result, $results_per_page);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            while($user = $result->fetch_assoc()) { ?>
                                <div class="grid-item" style="animation: scale-up-recent 0.4s cubic-bezier(0.390, 0.575, 0.565, 1.000) both;">
                                    <img class="channel-pfp" src="/dynamic/pfp/<?php echo $user['pfp']; ?>"><br>
                                    <a style="font-size: 10px;text-decoration: none;" href="/user/<?php echo htmlspecialchars($user['username']); ?>"><?php echo htmlspecialchars($user['username']); ?></a>
                                </div>
                        <?php } ?>
                    </div>
                </div>

                <center>
                <?php for($page = 1; $page<= $number_of_page; $page++) {  ?>
                    <a href="channels?page=<?php echo $page ?>"><span class="yt-uix-button-content"><?php echo $page; ?></span></a>&nbsp;
                <?php } ?>
                </center>  
            </div>
        </div>
        <div class="www-core-container">
        <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/footer.php"); ?>
        </div>

    </body>
</html>