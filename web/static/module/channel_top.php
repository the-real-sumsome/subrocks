<center>
    <a href="/user/<?php echo htmlspecialchars($_user['username']); ?>">Videos</a> | 
    <a href="/channel_favorites?n=<?php echo htmlspecialchars($_user['username']); ?>">Favorites</a> |
    <a href="/user/<?php echo htmlspecialchars($_user['username']); ?>/playlists">Playlists</a> |
    <a href="/user/<?php echo htmlspecialchars($_user['username']); ?>/groups">Groups</a> |
    <a href="/channel_friends?n=<?php echo htmlspecialchars($_user['username']); ?>">Friends</a> |  
    <a href="/channel_subscribers?n=<?php echo htmlspecialchars($_user['username']); ?>">Subscribers</a> |   
    <a href="/channel_subscriptions?n=<?php echo htmlspecialchars($_user['username']); ?>">Subscriptions</a>
</center><br>