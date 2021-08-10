<div class="channel-box-profle">
    <div class="channel-box-no-bg">
        <h2 style="font-weight: normal;"><?php echo htmlspecialchars($_user['custom_header']); ?></h2>
        <span class="channel-stats-minor">
            <p><?php echo $_video_fetch_utils->parseTextDescription($_user['custom_text']); ?></p>
        </span>
    </div>
</div><br>