<div class="channel-box-profle">
    <div class="channel-box-no-bg">
        <h2 style="font-weight: normal;">Recent Activity</h2><br>
        <div style="border: 1px solid <?php echo htmlspecialchars($_user['secondary_color']); ?>;padding: 5px;">
            <?php
                $stmt = $conn->prepare("SELECT rid, title, thumbnail, duration, title, author, publish, description FROM videos WHERE author = ? ORDER BY id DESC LIMIT 5");
                $stmt->bind_param("s", $_user['username']);
                $stmt->execute();
                $result = $stmt->get_result();
                while($video = $result->fetch_assoc()) {
            ?>
                <img style="vertical-align: middle;" src="/static/img/upload.png"> <b style="color:<?php echo htmlspecialchars($_user['primary_color_text']); ?>;"><?php echo htmlspecialchars($video['author']); ?> uploaded a video</b> (<?php echo $_video_fetch_utils->time_elapsed_string($video['publish']); ?>)
                <hr class="thin-line" style="border-bottom-style: dotted;">
            <?php } if($result->num_rows == 0)  { echo "This user has not been doing anything recently."; } ?>
        </div>
    </div>
</div><br>