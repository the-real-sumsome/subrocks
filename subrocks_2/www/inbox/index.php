<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/important/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/lib/new/base.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/lib/new/fetch.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/lib/new/insert.php"); ?>
<?php
    $_user_fetch_utils = new user_fetch_utils($conn);
    $_video_fetch_utils = new video_fetch_utils($conn);
    $_video_insert_utils = new video_insert_utils($conn);
    $_user_insert_utils = new user_insert_utils($conn);
    $_base_utils = new config_setup($conn);
    
    $_base_utils->initialize_page_compass("Inbox");
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
            <div style="width: 777px; background-color: white;position: border: 1px solid #d3d3d3; float: right;">
                        <div style="width: 100%;border-top: 1px solid #CACACA;border-bottom: 1px solid #CACACA;">
                            <h3 style="margin-top: 0px;padding: 16px;">Inbox</h3>
                        </div>
                        <div style="padding: 10px;">
                        <button type="button" class=" yt-uix-button yt-uix-button-default" href="/signup" role="button">
                            <span class="yt-uix-button-content">
                                <a style="color: #333; text-decoration: none;" href="/inbox/read"><?php if(!isset($cLang)) { ?> Mark All as Read <?php } else { echo $cLang['markAll']; } ?> </a>
                            </span>
                        </button><br><br>
                        <table style="width: 101%;">
                            <tr>
                                <th>From</th>
                                <th>Subject</th>
                                <th>Date</th>
                            </tr>
                            <?php
                            $stmt56 = $conn->prepare("SELECT * FROM pms WHERE touser = ? AND readed = 'n' ORDER BY id DESC");
                            $stmt56->bind_param("s", $_SESSION['siteusername']);
                            $stmt56->execute();
                            $result854 = $stmt56->get_result();
                            $result56 = $result854->num_rows;
                            ?>
                                  <?php
                            $results_per_page = 50;

                            $stmt = $conn->prepare("SELECT * FROM pms WHERE touser = ? AND readed = 'n' ORDER BY id DESC");
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

                            $stmt6 = $conn->prepare("SELECT * FROM pms WHERE touser = ? AND readed = 'n' ORDER BY id DESC LIMIT ?, ?");
                            $stmt6->bind_param("sss", $_SESSION['siteusername'], $page_first_result, $results_per_page);
                            $stmt6->execute();
                            $result6 = $stmt6->get_result();

                            while($row6 = $result6->fetch_assoc()) { ?>
                            <tr>
                                <td><a style="text-decoration: none;" href="/user/<?php echo htmlspecialchars($row6['owner']); ?>"><img src="/dynamic/pfp/<?php echo $_user_fetch_utils->fetch_user_pfp($row6['owner']); ?>" style="vertical-align: middle;width: 16px;height: 16px;"> <?php echo htmlspecialchars($row6['owner']); ?></a></td>
                                <td><a href="view?id=<?php echo $row6['id']; ?>"><?php echo htmlspecialchars($row6['subject']); ?></a></td>
                                <td><?php echo date("Y-m-d", strtotime($row6['date'])); ?></td>
                            </tr>
                            <?php } ?>
                        </table><br><br>
                        <center>
                        <?php for($page = 1; $page<= $number_of_page; $page++) { ?>
                        <a href="index?page=<?php echo $page ?>"><?php echo $page; ?></a>&nbsp;
                        <?php } ?>    
                        </center>
                        </div>
                    </div>
        </div>
        <div class="www-core-container">
        <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/footer.php"); ?>
        </div>

    </body>
</html>