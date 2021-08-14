<?php if($_user['friends'] != 0) { ?>
<div class="channel-box-profle">
    <div class="channel-box-no-bg" style="border-radius: 3px;padding: 7px;min-height: 137px;">
        <h2 style="font-weight: normal;">Friends (<?php echo $_user['friends']; ?>)</h2><br>
        <?php
            $stmt = $conn->prepare("SELECT * FROM friends WHERE reciever = ? AND status = 'a' LIMIT 4");
            $stmt->bind_param("s", $_user['username']);
            $stmt->execute();
            $result = $stmt->get_result();
            while($friend = $result->fetch_assoc()) { 
                $user = htmlspecialchars($friend['sender']);
        ?>
            <div class="grid-item" style="width: 80px;">
                <a href="/user/<?php echo $user; ?>">
                <img style="width: 60px;height: 60px;" class="channel-pfp" src="/dynamic/pfp/<?php echo $_user_fetch_utils->fetch_user_pfp($friend['sender']); ?>"><br>
                </a>
                <a style="font-size: 10px;text-decoration: none;" href="/user/<?php echo $user; ?>"><?php echo $user; ?></a>
            </div>
        <?php } ?>

        <?php
            $stmt = $conn->prepare("SELECT * FROM friends WHERE sender = ? AND status = 'a' LIMIT 3");
            $stmt->bind_param("s", $_user['username']);
            $stmt->execute();
            $result = $stmt->get_result();
            while($friend = $result->fetch_assoc()) {
                $user = htmlspecialchars($friend['reciever']);
        ?>
            <div class="grid-item" style="width: 80px;">
                <a href="/user/<?php echo $user; ?>">
                <img style="width: 60px;height: 60px;" class="channel-pfp" src="/dynamic/pfp/<?php echo $_user_fetch_utils->fetch_user_pfp($friend['reciever']); ?>"><br>
                </a>
                <a style="font-size: 10px;text-decoration: none;" href="/user/<?php echo $user; ?>"><?php echo $user; ?></a>
            </div>
        <?php } ?>
        <br>
        <a href="/channel_friends?n=<?php echo htmlspecialchars($_user['username']); ?>" style="float: right;font-weight: bold;margin-right: 18px;">see all</a>
    </div>
</div><br>
<?php } ?>
