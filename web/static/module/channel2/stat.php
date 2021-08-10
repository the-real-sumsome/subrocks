<div class="channel-box-profle">
    <div class="channel-box-no-bg">
        <h2 style="font-weight: normal;">Profile</h2><hr class="thin-line">
        <span class="channel-stats-minor">
            Joined: <b style="float: right;"><?php echo date("M d, Y", strtotime($_user['created'])); ?></b><hr class="thin-line">
            Last Sign In: <b style="float: right;"><?php echo date("M d, Y", strtotime($_user['lastlogin'])); ?></b><hr class="thin-line">
            Videos Watched: <b style="float: right;"><?php echo $_video_fetch_utils->fetch_history_ammount($_user['username']); ?></b><hr class="thin-line">
            Subscribers: <b style="float: right;"><?php echo $_user_fetch_utils->fetch_subs_count($_user['username']); ?></b><hr class="thin-line">
            Channels Views: <b style="float: right;"><?php echo $_user_fetch_utils->get_channel_views($_user['username']); ?></b><hr class="thin-line">
            Country: <b style="float: right;"><?php echo htmlspecialchars($_user['country']); ?></b><hr class="thin-line">
            <?php if($_user['website'] != "") { ?>
                Website: <b style="float: right;"><?php echo htmlspecialchars($_user['website']); ?></b><hr class="thin-line">
            <?php } ?>
            <p><b>About Me:</b><br><?php echo $_video_fetch_utils->parseTextDescription($_user['bio']); ?></p>
        </span>
    </div>
</div><br>