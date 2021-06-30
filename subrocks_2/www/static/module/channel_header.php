<div class="www-header-mast" style="background-color: white;margin-top: -10px;border-bottom-left-radius: 5px;
border-bottom-right-radius: 5px;"><br>
    <a href="/"><img src="/static/img/fulptube.png" class="www-logo"></a>
    <span class="www-motto" style="top: 18px;">
    <a href="/videos">Videos</a> | <a href="/channels">Channels</a> | <a href="/commnunity">Community</a> | <a href="/upload_video">Upload</a>
    </span>
    <div class="www-user-info">
        <?php if(!isset($_SESSION['siteusername'])) { ?>
            <strong><a style="border-left: 0px;" class="first" href="/sign_up">Sign Up</a></strong> 
            <a href="/quicklist">QuickList</a> (?) 
            <a href="/help">Help</a> 
            <a class="" href="/sign_in">Sign In</a>
        <?php } else { ?>
            <?php echo htmlspecialchars($_SESSION['siteusername']); ?>
        <?php } ?>
    </div><br>
    <form class="search-form-alt" autocomplete="off" style="float: right;" action="/search_query">
            <input name="q" class="search-box-alt">
            <input type="submit" value="Search">
        </form>
    <br>
</div>
<?php 
    if(isset($_SESSION['siteusername']))
        $_base_utils->update_login_time($_SESSION['siteusername']); 
?>

<div class="alerts">
    <div class="alert" style="display: none;" id="editsuccess">
        Dew dew
    </div>
</div>