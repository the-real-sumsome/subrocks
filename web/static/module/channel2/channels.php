<div class="channel-box-profle">
    <div class="channel-box-no-bg">
        <h2 style="font-weight: normal;">Featured Channels</h2>
        <span class="channel-stats-minor">
            <br>
            <?php foreach($_user['featured_channels'] as $featured_channel) { 
                if($_user_fetch_utils->user_exists($featured_channel)) { 
                    $user = htmlspecialchars($featured_channel);
                ?>
                <a href="/user/<?php echo $user; ?>">
                <img class="comment-pfp" src="/dynamic/pfp/<?php echo $_user_fetch_utils->fetch_user_pfp($featured_channel); ?>">
                </a>
                <span class="channel-stats" style="width: 197px;">
                    <h2 style="font-weight: normal;margin-bottom: 4px;display: inline-block;">
                        <a href="/user/<?php echo $user; ?>"><?php echo $user; ?></a>
                    </h2><br>
                    Subscribers: <?php echo $_user_fetch_utils->fetch_subs_count($featured_channel); ?><br>
                    Channel views: <?php echo $_user_fetch_utils->get_channel_views($featured_channel); ?><br>
                    Videos watched: <?php echo $_video_fetch_utils->fetch_history_ammount($featured_channel); ?>
                </span><br><br>
            <?php } } ?>
        </span>
    </div>
</div><br>