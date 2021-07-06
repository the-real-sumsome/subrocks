<script>
function dropdownchannel() {
  if ($("#channel-customize").first().is(":hidden")) {
    $("#channel-customize").slideDown("slow");
  } else {
    $("#channel-customize").slideUp("slow");
  }
}

function pictures_table() {
    document.getElementById("misc-table").style.display = "none";
    document.getElementById("bg-table").style.display = "none";
    document.getElementById("pictures-table").style.display = "block";
    document.getElementById("pictures").className = "selected-user";
    document.getElementById("text").className = "non";
    document.getElementById("bg").className = "non";
};

function text_table() {
    document.getElementById("misc-table").style.display = "block";
    document.getElementById("bg-table").style.display = "none";
    document.getElementById("pictures-table").style.display = "none";
    document.getElementById("pictures").className = "non";
    document.getElementById("text").className = "selected-user";
    document.getElementById("bg").className = "non";
};

function bg_table() {
    document.getElementById("misc-table").style.display = "none";
    document.getElementById("bg-table").style.display = "block";
    document.getElementById("pictures-table").style.display = "none";
    document.getElementById("pictures").className = "non";
    document.getElementById("text").className = "non";
    document.getElementById("bg").className = "selected-user";
};
</script>
<div class="channel-customization-base" id="channel-customize" style="display: none;">
    <div class="user-header-bottom" style="margin: -5px;">
        <div class="www-header-list" style="margin-top: 0px;">
            <a class="www-header-item" href="#" onclick="pictures_table()">Main</a>
            <a class="www-header-item" href="#" onclick="bg_table()">Background</a>
            <a class="www-header-item" href="#" onclick="text_table()">Colors</a>
        </div>
    </div>

    <table id="pictures-table" style="position: relative;right: 43px;">
        <tr>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <td style="vertical-align: top;">
                <h1>Avatar</h1>
                Preview your avatar below. To upload a new avatar, choose a new file.<br>
                <img src="/dynamic/pfp/<?php echo $_user['pfp']; ?>" style="width: 100px; height: 100px;"><br>
                <form method="post" action="/post/channel_update" enctype="multipart/form-data">
                    <input type="file" name="fileToUpload" id="avatar-upload">
                    <!--<button class="yt-uix-button yt-uix-button-default" id="av-uplod">Select File</button>-->
                    <input class="yt-uix-button yt-uix-button-default" type="submit" value="Upload Image" name="pfpset">
                </form><br><br>

            </td>
            <td style="vertical-align: top;     padding-left: 94px; width: 432px;">
                <h1>Bio</h1>
                Set text (Max 2000 characters)<br>
                <form method="post" action="/post/channel_update" enctype="multipart/form-data">
                    <textarea style="width: 345px;padding: 0px;background-color:white;border: 1px solid #d3d3d3;" id="biomd" placeholder="Bio" name="bio"><?php echo htmlspecialchars($_user['bio']); ?></textarea><br>
                    <input class="yt-uix-button yt-uix-button-default" name="bioset" type="submit" value="Set">
                </form><br><br>

                <h1>Featured Video</h1>
                Must not have extra whitespace/spaces at the end. Example: MTYwNTQ4ODc4Mja=24 is valid. DO NOT INCLUDE THE SITE DOMAIN, JUST THE ID<br>
                <form method="post" action="/post/channel_update" enctype="multipart/form-data">
                <input style="padding:5px;border-radius:5px;background-color:white;border: 1px solid #d3d3d3;width: 345px;" id="biomd" placeholder="Video ID" value="<?php echo htmlspecialchars($_user['featured']);?>" name="videoid">
                    <input class="yt-uix-button yt-uix-button-default" name="featuredset" type="submit" value="Set">
                </form><br><br>
            </td>
            <!--
            <script>
                $('#bg-uplod').on('click', function() {
                    $('#background-upload').trigger('click');
                }); 

                $('#av-uplod').on('click', function() {
                    $('#avatar-upload').trigger('click');
                });

                $(document).ready(function(){
                    $("#background-upload").click(function(){
                        $(this).val("");
                    });

                    $("#background-upload").change(function(){
                        var path = $(this).val();
                        var filename = path.replace(/^.*\\/, "");
                        alert(filename);
                    });
                });

                $(document).ready(function(){
                    $("#avatar-upload").click(function(){
                        $(this).val("");
                    });

                    $("#avatar-upload").change(function(){
                        var path = $(this).val();
                        var filename = path.replace(/^.*\\/, "");
                        alert(filename);
                    });
                });
            </script>
            -->
        </tr>
    </table>

    <table id="misc-table" style="display: none;position: relative;right: 43px;">
        <tr>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <td style="vertical-align: top;">
                <h1>Primary Color</h1>
                This will change the background of your boxes' tabs"<br>
                <form method="post" action="/post/channel_update" enctype="multipart/form-data">
                    Choose a color: <input type="color" id="solidcolor" name="solidcolor" value="<?php echo htmlspecialchars($_user['primary_color']); ?>"><br>
                    <input class="yt-uix-button yt-uix-button-default" name="primary" type="submit" value="Set">
                </form><br><br>
                <h1>Channel Box Color</h1>
                This will change the infobox's background color.<br>
                <form method="post" action="/post/channel_update" enctype="multipart/form-data">
                    Choose a color: <input type="color" id="solidcolor" name="solidcolor" value="<?php echo htmlspecialchars($_user['secondary_color']); ?>"><br>
                    <input class="yt-uix-button yt-uix-button-default" name="secondary" type="submit" value="Set">
                </form><br><br>

            </td>
            <td style="vertical-align: top;     padding-left: 94px; width: 432px;">
            <h1>Background Color</h1>
                This will change the background of all the other boxes.<br>
                <form method="post" action="/post/channel_update" enctype="multipart/form-data">
                    Choose a color: <input type="color" id="solidcolor" name="solidcolor" value="<?php echo htmlspecialchars($_user['third_color']); ?>"><br>
                    <input class="yt-uix-button yt-uix-button-default" name="third" type="submit" value="Set">
                </form><br><br>
                <h1>Text Color</h1>
                This will change the color of the box ribbons.<br>
                <form method="post" action="/post/channel_update" enctype="multipart/form-data">
                    Choose a color: <input type="color" id="solidcolor" name="solidcolor" value="<?php echo htmlspecialchars($_user['text_color']); ?>"><br>
                    <input class="yt-uix-button yt-uix-button-default" name="textcolor" type="submit" value="Set">
                </form><br><br>
                <h1>Text Main Color</h1>
                This will change the color of the box ribbons.<br>
                <form method="post" action="/post/channel_update" enctype="multipart/form-data">
                    Choose a color: <input type="color" id="solidcolor" name="solidcolor" value="<?php echo htmlspecialchars($_user['primary_color_text']); ?>"><br>
                    <input class="yt-uix-button yt-uix-button-default" name="textprimarycolor" type="submit" value="Set">
                </form><br><br>
            </td>
            <!--
            <script>
                $('#bg-uplod').on('click', function() {
                    $('#background-upload').trigger('click');
                }); 

                $('#av-uplod').on('click', function() {
                    $('#avatar-upload').trigger('click');
                });

                $(document).ready(function(){
                    $("#background-upload").click(function(){
                        $(this).val("");
                    });

                    $("#background-upload").change(function(){
                        var path = $(this).val();
                        var filename = path.replace(/^.*\\/, "");
                        alert(filename);
                    });
                });

                $(document).ready(function(){
                    $("#avatar-upload").click(function(){
                        $(this).val("");
                    });

                    $("#avatar-upload").change(function(){
                        var path = $(this).val();
                        var filename = path.replace(/^.*\\/, "");
                        alert(filename);
                    });
                });
            </script>
            -->
        </tr>
    </table>

    <table id="bg-table" style="display: none;"> 
        <tr>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <td style="vertical-align: top;">
                <h1>Background Options</h1>
                Choose how your background will be displayed.<br>
                <form method="post" action="/post/channel_update" enctype="multipart/form-data">
                    <select name="bgoption" id="ifyouarereadingthisYOUSUCK">
                        <option value="repeaty">Repeat - Y</option>
                        <option value="repeatx">Repeat - X</option>
                        <option value="norepeat">No Repeat</option>
                        <option value="repeatxy">Repeat - X and Y</option>
                        <option value="stretch">Stretch</option>
                        <option value="solid">Solid</option>
                    </select><br>
                    Choose a color: <input type="color" id="solidcolor" name="solidcolor" value="<?php echo htmlspecialchars($_user['2012_bgcolor']); ?>"><br>
                    <input class="yt-uix-button yt-uix-button-default" name="bgoptionset" type="submit" value="Set">
                </form><br><br>
            </td>
            <td style="vertical-align: top;     padding-left: 94px; width: 432px;">
                <h1>Background</h1>
                Choose Image (Max file size: 10MB)<br>
                <form method="post" action="/post/channel_update" enctype="multipart/form-data">
                    <input type="file" name="fileToUpload" id="background-upload">
                    <!--<button class="yt-uix-button yt-uix-button-default" id="av-uplod">Select File</button>-->
                    <input class="yt-uix-button yt-uix-button-default" type="submit" value="Upload Background" name="bgset">
                </form><br><br>
            </td>
            <!--
            <script>
                $('#bg-uplod').on('click', function() {
                    $('#background-upload').trigger('click');
                }); 

                $('#av-uplod').on('click', function() {
                    $('#avatar-upload').trigger('click');
                });

                $(document).ready(function(){
                    $("#background-upload").click(function(){
                        $(this).val("");
                    });

                    $("#background-upload").change(function(){
                        var path = $(this).val();
                        var filename = path.replace(/^.*\\/, "");
                        alert(filename);
                    });
                });

                $(document).ready(function(){
                    $("#avatar-upload").click(function(){
                        $(this).val("");
                    });

                    $("#avatar-upload").change(function(){
                        var path = $(this).val();
                        var filename = path.replace(/^.*\\/, "");
                        alert(filename);
                    });
                });
            </script>
            -->
        </tr>
    </table>
</div>