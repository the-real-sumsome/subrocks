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

  $_base_utils->initialize_page_compass("Community");

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
            <div style="margin-left: 176px;">
            <h1>Community / Groups</h1>
            <span style="font-size: 11px;" class="grey-text">Find groups with the same interests as yours.</span><br><br>
            </div>
            <div style="overflow:auto;position: relative;height: 415px;bottom: 53px;">
                <div class="www-videos-left">
                    <h2>Groups</h2><br>
                    <ul class="videos-list">
                    <?php $categories = ["None", "Film & Animation", "Autos & Vehicles", "Music", "Pets & Animals", "Sports", "Travel & Events", "Gaming", "People & Blogs", "Comedy", "Entertainment", "News & Politics", "Howto & Style", "Education", "Science & Technology", "Nonprofits & Activism"]; ?>
                        <?php foreach($categories as $categoryTag) { ?>
                            <?php if($categoryTag == $category) { ?>
                                <li class=""><?php echo $categoryTag; ?></li>
                            <?php } else { ?>
                                <li class=""><a href="/community?c=<?php echo urlencode($categoryTag); ?>"><?php echo $categoryTag; ?></a></li>
                            <?php } ?>
                        <?php } ?>
                    </ul>
                </div>
                <div class="www-videos-right" style="position: relative;top: 46px;">
                    <h3><?php echo htmlspecialchars($category); ?></h3>
                    <div class="videos-box">
                        <div class="videos-title-box-browse">
                        
                        </div>
                        <div class="videos-title-box-contents">
                            <table style="width: 100%;">
                                <tr>
                                    <!-- <th style="margin: 5px; width: 5%;"></th> -->
                                    <th style="width: 100%;"></th>
                                    <th style="margin: 5px; width: 20%;"></th>
                                </tr>
                                <?php
                                if($category != "None") { 
                                    $stmt56 = $conn->prepare("SELECT * FROM user_groups WHERE group_category = ? ORDER BY id DESC");
                                    $stmt56->bind_param("s", $category);
                                    $stmt56->execute();
                                    $result854 = $stmt56->get_result();
                                    $result56 = $result854->num_rows;
                                } else {
                                    $stmt56 = $conn->prepare("SELECT * FROM user_groups ORDER BY id DESC");
                                    $stmt56->execute();
                                    $result854 = $stmt56->get_result();
                                    $result56 = $result854->num_rows;
                                }
                                ?>
                                <?php
                                $results_per_page = 5;

                                if($category != "None") { 
                                    $stmt = $conn->prepare("SELECT * FROM user_groups WHERE group_category = ? ORDER BY id DESC");
                                    $stmt->bind_param("s", $category);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $results = $result->num_rows;
                                } else {
                                    $stmt = $conn->prepare("SELECT * FROM user_groups ORDER BY id DESC");
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $results = $result->num_rows;
                                }

                                $number_of_result = $result->num_rows;
                                $number_of_page = ceil ($number_of_result / $results_per_page);  

                                if (!isset ($_GET['page']) ) {  
                                    $page = 1;  
                                } else {  
                                    $page = (int)$_GET['page'];  
                                }  

                                $page_first_result = ($page - 1) * $results_per_page;  

                                $stmt->close();

                                if($category != "None") { 
                                    $stmt = $conn->prepare("SELECT * FROM user_groups WHERE group_category = ? ORDER BY id DESC LIMIT ?, ?");
                                    $stmt->bind_param("sss", $category, $page_first_result, $results_per_page);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                } else {
                                    $stmt = $conn->prepare("SELECT * FROM user_groups ORDER BY id DESC LIMIT ?, ?");
                                    $stmt->bind_param("ss", $page_first_result, $results_per_page);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                }

                                while($group = $result->fetch_assoc()) { ?>
                                <tr style="margin-top: 5px;" id="videoslist">
                                    <td class="video-manager-left">
                                        <img src="/dynamic/thumbs/<?php echo $group['group_picture']; ?>" style="width:50px;height:50px;">
                                        <span style="display: inline-block;float: right;"></span>
                                    </div>
                                        <span class="video-manager-info" style="width: 93%;">
                                        <a class="video-manager-title" href="view_group?v=<?php echo $group['id']; ?>"><?php echo htmlspecialchars($group['group_title']); ?></a>
                                        <span style="font-size: 11px;" class="grey-text"> - <?php echo $_video_fetch_utils->parseDescriptionVideoManager($group['group_description']); ?></span>
                                        <span class="grey-text" style="font-size:11px;float:right;">[<?php echo htmlspecialchars($group['group_author']); ?>] [<?php echo $_user_fetch_utils->fetch_group_member_count($group['id']); ?> members]</span>
                                        <br>
                                        <span style="color: #919191;"><?php echo date("F d, Y g:sA", strtotime($group['group_creation'])); ?></span><br>
                                        <br>         
                                    </td>
                                </tr>
                            <?php } ?>
                            </table>
                        </div>
                    </div>

                    <center>
                    <?php for($page = 1; $page<= $number_of_page; $page++) {  ?>
                        <a href="community?page=<?php echo $page ?>"><?php echo $page; ?></a>&nbsp;
                    <?php } ?>
                    </center>  
                </div>
            </div>
            <div style="width: 794px;float: right;">
                <hr class="thin-line">
                <h1>Community / Forums</h1>
                <span style="font-size: 11px;" class="grey-text">Talk to other users here.</span>
                <?php
                    $stmt = $conn->prepare("SELECT * FROM forum_category ORDER BY id");
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while($category = $result->fetch_assoc()) {
                        $latest_forum_post = $_user_fetch_utils->fetch_latest_forum_post($category['title']);
                        $latest_forum_poster = htmlspecialchars($latest_forum_post['author']);
                ?>
                    <hr class="thin-line">
                    <h3 style="display: inline-block;"><a href="/forum/category?c=<?php echo urlencode($category['title']); ?>"><?php echo htmlspecialchars($category['title']); ?></a></h3>
                    <span style="font-size: 11px; color: gray;">(<?php echo $_video_fetch_utils->get_category_threads($category['title']); ?> threads)</span>
                    <br>
                    <?php echo htmlspecialchars($category['description']); ?><br><br>
                    <a href="/forum/thread?v=<?php echo $latest_forum_post['id']; ?>"><h4><?php echo htmlspecialchars($latest_forum_post['title']); ?></h4></a>
                    <div class="comment-watch">
                        <a href="/user/<?php echo $latest_forum_poster; ?>">
                        <img class="comment-pfp" src="/dynamic/pfp/<?php echo $_user_fetch_utils->fetch_user_pfp($latest_forum_post['author']); ?>">
                        </a>
                        <span  style="display: inline-block; vertical-align: top;width: 562px;;">
                            <span class="comment-info" style="display: inline-block;">
                                <b><a style="text-decoration: none;" href="/user/<?php echo $latest_forum_poster; ?>">
                                    <?php echo htmlspecialchars($latest_forum_post['author']); ?> 
                                </a></b> 
                                <span style="color: #666;">(<?php echo $_video_fetch_utils->time_elapsed_string($latest_forum_post['date']); ?>)</span>
                            </span><br>
                            <span class="comment-text" style="display: inline-block;">
                                <?php echo $_video_fetch_utils->parseTextDescription($latest_forum_post['contents']); ?>
                            </span>
                        </span>

                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="www-core-container">
        <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/footer.php"); ?>
        </div>

    </body>
</html>