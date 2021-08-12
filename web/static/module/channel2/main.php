<div class="channel-box-profle">
    <div class="channel-box-description">
		<a href="/user/<?php echo htmlspecialchars($_user['username']); ?>">
        <img class="channel-pfp" src="/dynamic/pfp/<?php echo $_user['pfp']; ?>">
        </a>
		<span class="channel-stats" style="width: 197px;">
            <h2 style="font-weight: normal;margin-bottom: 4px;"><?php echo htmlspecialchars($_user['username']); ?></h2>
            <?php if(@$_SESSION['siteusername'] != $_user['username']) { ?>
            <a href="/get/<?php if($_user['subscribed'] == true) { ?>un<?php } ?>subscribe?n=<?php echo htmlspecialchars($_user['username']); ?>">
                <button style="margin: 0px;float: none;" class="sub_button"><?php if($_user['subscribed'] == true) { ?>Unsubscribe<?php } else { ?>Subscribe<?php } ?></button>
            </a>
            <?php } else { ?>
            <a href="#">
                <button style="margin: 0px;float: none;" class="sub_button" onclick="dropdownchannel()">Edit Channel</button>
            </a>
            <?php } ?>

            <br><br>
            <a href="/inbox/send?to=<?php echo htmlspecialchars($_user['username']); ?>">Send Message</a> |
            <a href="#">Add Contact</a> | 
            <?php if($_user_fetch_utils->if_blocked($_SESSION['siteusername'], $_user['username']) == false) { ?>
                <br><a href="/get/block?user=<?php echo htmlspecialchars($_user['username']); ?>">Block</a> | 
            <?php } else { ?>
                <a href="/get/unblock?user=<?php echo htmlspecialchars($_user['username']); ?>">Unblock</a> | 
            <?php } ?>
            <a href="/get/add_friend?sending=<?php echo htmlspecialchars($_user['username']); ?>">Add Friend</a>
        </span><br>
        <?php $categories = ["director", "musician", "comedian", "guru", "nonprofit"]; ?>
        <?php if(in_array($_user['genre'], $categories)) { ?>  
            <img style="width:94px" src="/static/img/<?php echo htmlspecialchars($_user['genre']); ?>.gif">
        <?php } ?>
    </div>
</div><br>