
<?php if($_user['subscribers'] != 0) { ?>
<div class="channel-box-profle">
    <div class="channel-box-no-bg" style="border-radius: 3px;padding: 7px;min-height: 137px;">
        <h2 style="font-weight: normal;">Subscribers (<?php echo $_user['subscribers']; ?>)</h2><br>
        <?php
            $stmt = $conn->prepare("SELECT sender FROM subscribers WHERE reciever = ? ORDER BY id DESC LIMIT 7");
            $stmt->bind_param("s", $_user['username']);
            $stmt->execute();
            $result = $stmt->get_result();
            while($subscriber = $result->fetch_assoc()) {
                $user = htmlspecialchars($subscriber['sender']);
        ?>
                <div class="grid-item" style="width: 80px;">
                    <a href="/user/<?php echo $user; ?>">
                    <img style="width: 60px;height: 60px;" class="channel-pfp" src="/dynamic/pfp/<?php echo $_user_fetch_utils->fetch_user_pfp($subscriber['sender']); ?>"><br>
                    </a>
                    <a style="font-size: 10px;text-decoration: none;" href="/user/<?php echo $user; ?>"><?php echo $user; ?></a>
                </div>
        <?php } ?>
        <br>
        <a href="/channel_subscribers?n=<?php echo htmlspecialchars($_user['username']); ?>" style="float: right;font-weight: bold;margin-right: 18px;">see all</a>
    </div>
</div><br>
<?php } ?>