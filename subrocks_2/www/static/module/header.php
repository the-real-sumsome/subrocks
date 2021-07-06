<div class="www-header-mast">
    <a href="/"><img src="/static/img/fulptube.png" class="www-logo"></a>
    <span class="www-motto">
    Smashing Rocks &trade;
    </span>
    <div class="www-user-info">
        <?php if(!isset($_SESSION['siteusername'])) { ?>
            <strong><a style="border-left: 0px;" class="first" href="/sign_up">Sign Up</a></strong> 
            <a href="/quicklist">QuickList</a> (?) 
            <a href="/help">Help</a> 
            <a class="" href="/sign_in">Sign In</a>
        <?php } else { ?>
            
            <div class="dropdown">
                <button onclick="dropdown()" class="dropbtn"><?php echo htmlspecialchars($_SESSION['siteusername']); ?> <img style="margin-left: 5px;width: 8px;" src="/static/img/dropdown.png"></button>
                <div id="dropdown-header" class="dropdown-content">
                    <a href="/user/<?php echo htmlspecialchars($_SESSION['siteusername']); ?>">My Channel</a>
                    <a href="/channel_subscriptions?n=<?php echo htmlspecialchars($_SESSION['siteusername']); ?>">Subscriptions</a>
                    <a href="/inbox/">Inbox</a>
                    <a href="/video_manager">My Videos</a>
                    <a href="/favorites">Favorites</a>
                    <a href="/friends">Friends</a>
                </div>
            </div>
            <?php if($_user_fetch_utils->fetch_unread_pms($_SESSION['siteusername']) == 0) { ?>
                <a href="/inbox"><img style="width: 16px;vertical-align: middle;" src="/static/img/mail.png"></a>
            <?php } else { ?>
                <a href="/inbox"><img style="width: 16px;vertical-align: middle;" src="/static/img/mail_notif.png"> (<?php echo $_user_fetch_utils->fetch_unread_pms($_SESSION['siteusername']); ?>)</a>
            <?php } ?>
            <a href="/logout">Logout</a> 
        <?php } ?>
    </div>
    <br>
    <div class="www-header-list">
        <a class="www-header-item" href="/">Home</a>
        <a class="www-header-item" href="/videos">Videos</a>
        <a class="www-header-item" href="/channels">Channels</a>
        <a class="www-header-item" href="/community">Community</a>

        <form class="search-form" autocomplete="off" action="/search_query">
            <input name="q" class="search-box">
            <input type="submit" class="search-button" value="Search">
        </form>

        <a href="/upload_video">
            <button class="upload_button">
                Upload
            </button>
        </a>
    </div>
</div>
<?php 
    if(isset($_SESSION['siteusername']))
        $_base_utils->update_login_time($_SESSION['siteusername']); 
?>

<div class="alerts">
    <div class="alert" id="editsuccess">
        BY THE WAY, THIS IS A BETA!!! THE DATABASE WILL BE RESET WHEN THE SITE RELEASES!
    </div>

    <?php if(isset($_GET['userdoesntexist'])) { ?>
    <div class="alert" id="userdoesntexist" style="background-color: #FFA3A3;">
        This user does not exist!
    </div>
    <?php } ?>

    <?php if(isset($_GET['videodoesntexist'])) { ?>
    <div class="alert" id="videodoesntexist" style="background-color: #FFA3A3;">
        This video does not exist!
    </div>
    <?php } ?>
</div>
<script>
function dropdown() {
  document.getElementById("dropdown-header").classList.toggle("show");
}

// Close the dropdown menu if the user clicks outside of it
window.onclick = function(event) {
  if (!event.target.matches('.dropbtn')) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
} 
</script>
<?php
$subscribed = array();

if(isset($_SESSION['siteusername'])) {
    $stmt = $conn->prepare("SELECT * FROM subscribers WHERE sender = ? LIMIT 25");
    $stmt->bind_param("s", $_SESSION['siteusername']);
    $stmt->execute();
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()) {
        array_push($subscribed, $row['reciever']);
    }
}
?>