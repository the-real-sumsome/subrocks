<?php if($_user['subscriptions'] != 0) { ?>
<div class="channel-box-profle">
    <div class="channel-box-no-bg" style="border-radius: 3px;padding: 7px;">
        <h2 style="font-weight: normal;">Subscriptions (<?php echo $_user['subscriptions']; ?>)</h2><br>
        <?php
            $stmt = $conn->prepare("SELECT reciever FROM subscribers WHERE sender = ? ORDER BY id DESC LIMIT 9");
            $stmt->bind_param("s", $_user['username']);
            $stmt->execute();
            $result = $stmt->get_result();
            while($subscriber = $result->fetch_assoc()) {
                if($_user_fetch_utils->user_exists($subscriber['reciever'])) {
        ?>

                <div class="grid-item" style="width: 90px;">
                    <img class="channel-pfp" style="width: 58px; height: 58px;" src="/dynamic/pfp/<?php echo $_user_fetch_utils->fetch_user_pfp($subscriber['reciever']); ?>"><br>
                    <a style="font-size: 10px;text-decoration: none;" href="/user/<?php echo htmlspecialchars($subscriber['reciever']); ?>"><?php echo htmlspecialchars($subscriber['reciever']); ?></a>
                </div>
        <?php } } ?>
    </div>
</div><br>
<?php } ?>