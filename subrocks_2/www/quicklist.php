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

  if(!isset($_SESSION['siteusername']))
    header("Location: /sign_in");

  $_base_utils->initialize_page_compass("Video Manager");
?>
<?php
if($_SERVER['REQUEST_METHOD'] == 'POST' && @$_POST['send']) {
    $_($_POST['to'], $_POST['subject'], $_POST['message'], $_SESSION['siteusername'], $conn);
    

    echo "<script>
        window.location = 'https://fulptube.rocks/inbox/';
    </script>";
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>SubRocks - <?php echo $_base_utils->return_current_page(); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/static/css/new/www-core.css">
        <style>
            table {
                font-family: arial, sans-serif;
                border-collapse: collapse;
                width: 100%;
            }

            td, th {
                text-align: left;
                padding: 3px;
            }

            th {
                border: 1px solid #dddddd;
                background: rgb(230,230,230);
                background: -moz-linear-gradient(0deg, rgba(230,230,230,1) 0%, rgba(255,255,255,1) 100%, rgba(255,255,255,1) 100%);
                background: -webkit-linear-gradient(0deg, rgba(230,230,230,1) 0%, rgba(255,255,255,1) 100%, rgba(255,255,255,1) 100%);
                background: linear-gradient(0deg, rgba(230,230,230,1) 0%, rgba(255,255,255,1) 100%, rgba(255,255,255,1) 100%);
                filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#e6e6e6",endColorstr="#ffffff",GradientType=1); 
            }

            tr:nth-child(even) {
                background-color: #f9f9f9;
            }
        </style>
    </head>
    <body>
        <div class="www-core-container">
            <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/header.php"); ?>
            <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/module_sidebar.php"); ?>
            <div class="manage-top">
                <div style="width: 100%;border-top: 1px solid #CACACA;border-bottom: 1px solid #CACACA;">
                    <h3 style="margin-top: 0px;padding: 16px;">Quicklist</h3>
                </div>
            </div>
                <?php
                    $search = $_SESSION['siteusername'];
                    $stmt56 = $conn->prepare("SELECT * FROM quicklist_videos WHERE author = ?");
                    $stmt56->bind_param("s", $search);
                    $stmt56->execute();
                    $result854 = $stmt56->get_result();
                    $result56 = $result854->num_rows;

                    $results_per_page = 12;

                    $stmt = $conn->prepare("SELECT * FROM quicklist_videos WHERE author = ? ORDER BY id DESC");
                    $stmt->bind_param("s", $_SESSION['siteusername']);
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
                ?>
            <div class="manage-base">
                    <?php
                        $stmt6 = $conn->prepare("SELECT * FROM quicklist_videos WHERE author = ? ORDER BY id DESC LIMIT ?, ?");
                        $stmt6->bind_param("sss", $search, $page_first_result, $results_per_page);
                        $stmt6->execute();
                        $result6 = $stmt6->get_result();

                        while($video = $result6->fetch_assoc()) {   
                            if($_video_fetch_utils->video_exists($video['video'])) {
                                $video = $_video_fetch_utils->fetch_video_rid($video['video']);
                    ?> 
                        <div class="grid-item" style="animation: scale-up-recent 0.4s cubic-bezier(0.390, 0.575, 0.565, 1.000) both;">
                            <img class="thumbnail" onerror="this.src='/dynamic/thumbs/default.png'" src="/dynamic/thumbs/<?php echo htmlspecialchars($video['thumbnail']); ?>">
                            <div class="video-info-grid">
                                <a href="/watch?v=<?php echo $video['rid']; ?>"><?php echo htmlspecialchars($video['title']); ?></a><br>
                                <span class="video-info-small">
                                    <span class="video-views"><?php echo $_video_fetch_utils->fetch_video_views($video['rid']); ?> views</span><br>
                                    <a href="/user/<?php echo htmlspecialchars($video['author']); ?>"><?php echo htmlspecialchars($video['author']); ?></a>
                                </span>
                            </div>
                        </div>
                    <?php } } ?><br><br>
                <?php for($page = 1; $page<= $number_of_page; $page++) { ?>
                    <a href="video_manager?page=<?php echo $page ?>" style="padding: 3px;">
                        <button class="www-button www-button-grey"><?php echo $page; ?></button>
                    </a>
                <?php } ?>   
            </div>
        </div>
        <div class="www-core-container">
        <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/footer.php"); ?>
        </div>

    </body>
</html>