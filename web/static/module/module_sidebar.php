<style>
#sidebar-item { 
  padding: 7px;
  text-align: left;
}

#sidebar-extra { 
  padding: 10px;
  text-align: left;
  height: 963px;
}

#sidebar-item:hover {
  background-color: white;
  font-weight: bold;
  background: rgb(215,215,215);
  background: -moz-linear-gradient(0deg, rgba(215,215,215,1) 0%, rgba(236,236,236,1) 100%);
  background: -webkit-linear-gradient(0deg, rgba(215,215,215,1) 0%, rgba(236,236,236,1) 100%);
  background: linear-gradient(0deg, rgba(215,215,215,1) 0%, rgba(236,236,236,1) 100%);
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#d7d7d7",endColorstr="#ececec",GradientType=1); 
}

#sidebar-item a {
  color: black;
}

ul {
    list-style-type: none;
    margin: 0px;
    padding: 0px;
    border-right: 1px solid #CACACA;
    border-top: 1px solid #CACACA;
}

li a {
    text-decoration: none;
}

</style>
<div style="width: 19.6%;display: inline-block;margin-top: -28px;">
<br><br>
<center>
<ul style="width: 193px;">
    <li id="sidebar-item">
      <a href="/inbox/send"><button style="padding: 2px .8333em;float: none;margin-left: 48px;" id="search-button">Compose</button></a>
    </li>
    <li id="sidebar-item">
      <a href="/favorites">Favorites</a>
    </li>
    <li id="sidebar-item">
      <a href="/video_manager">Uploads</a>
    </li>
    <li id="sidebar-item">
      <a href="/playlists">Playlists</a>
    </li>
    <li id="sidebar-item">
      <a href="/groups">Groups</a>
    </li>
    <li id="sidebar-item">
      <a href="/channel_subscriptions?n=<?php echo htmlspecialchars($_SESSION['siteusername']); ?>">My Subscriptions</a>
    </li>
    <li id="sidebar-item">
      <a href="/inbox/">Inbox</a>
    </li>
    <li id="sidebar-item">
      <a href="/friends">Friends</a>
    </li>
    <li id="sidebar-item">
      <a href="/quicklist">Quicklist</a>
    </li>
    <li id="sidebar-extra">
      
    </li>
</ul>
</center>
</div>