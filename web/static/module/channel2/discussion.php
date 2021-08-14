<?php 
    $stmt = $conn->prepare("SELECT * FROM profile_comments WHERE toid = ? ORDER BY id DESC");
    $stmt->bind_param("s", $_user['username']);
    $stmt->execute();
    $result = $stmt->get_result();
?>
<div class="channel-box-profle">
    <div class="channel-box-no-bg" style="border-radius: 3px;padding: 7px;">
        <h2 style="font-weight: normal;">Channel Comments (<?php echo $result->num_rows; ?>)</h2>
        <?php if(!isset($_SESSION['siteusername'])) { ?>
            <div class="comment-alert">
                <a href="/sign_in">Sign In</a> or <a href="/$video">Sign Up</a> now to post a comment!
            </div>
        <?php } else if($_user_fetch_utils->if_blocked($_user['username'], $_SESSION['siteusername'])) { ?>
            <div class="comment-alert">
                <br>
                This user has blocked you!
            </div>
        <?php } else if($_video['commenting'] == "d") { ?>
            <div class="comment-alert">
                This channel has commenting disabled!
            </div>
        <?php } else { ?>
            <form method="post" action="" id="submitform">
                <?php if(isset($error['status'])) { ?>
                    <div class="alert" id="videodoesntexist" style="background-color: #FFA3A3;">
                        <?php echo $error['message']; ?>
                    </div>
                <?php } ?>
                    <textarea 
                        onkeyup="textCounter(this,'counter',500);" 
                        class="comment-textbox" cols="32" id="com" style="width: 98%;"
                        placeholder="Leave a nice comment on this channel" name="comment"></textarea><br><br> 
                    <input disabled class="characters-remaining" maxlength="3" size="3" value="500" id="counter"> <?php if(!isset($cLang)) { ?> characters remaining <?php } else { echo $cLang['charremaining']; } ?> <br>
                    <input type="submit" value="Post" class="www-button www-button-grey" data-callback="onLogin">
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
        <?php } ?><br>
        
        <?php while($comment = $result->fetch_assoc()) {  
            $author = htmlspecialchars($comment['author']);
        ?>
        <hr class="thin-line">
        <div class="comment-watch">
            <a href="/user/<?php echo $author; ?>">
            <img class="comment-pfp" src="/dynamic/pfp/<?php echo $_user_fetch_utils->fetch_user_pfp($comment['author']); ?>">
            </a>
            <span  style="display: inline-block; vertical-align: top;width: 562px;">
                <span class="comment-info" style="display: inline-block;">
                    <b><a style="text-decoration: none;" href="/user/<?php echo $author; ?>">
                        <?php echo $author; ?> 
                    </a></b> 
                    <span style="color: <?php echo htmlspecialchars($_user['primary_color_text']); ?>;">(<?php echo $_video_fetch_utils->time_elapsed_string($comment['date']); ?>)</span>
                    <?php if(isset($_SESSION['siteusername']) && $_SESSION['siteusername'] == $_user['username']) { ?>
                        <a style="float: right;" href="/get/delete_comment_profile?id=<?php echo $comment['id'];?>">Remove Comment</a>
                    <?php } ?>
                </span><br>
                <span class="comment-text" style="display: inline-block;width: 575px;word-wrap: break-word;">
                    <?php echo $_video_fetch_utils->parseTextDescription($comment['comment']); ?>
                </span>
            </span>

        </div>
        <?php } ?>
    </div>
</div><br>