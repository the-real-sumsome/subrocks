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
<div class="channel-customization-base" id="channel-customize">
    <div class="user-header-bottom" style="margin: -5px;">
    <ul>
            <li class="selected-user" id="pictures" onclick="pictures_table()">
                Pictures & Text
            </li>
            <li class="non"  id="text" onclick="text_table()">
                Misc
            </li>
            <li class="non"  id="bg" onclick="bg_table()">
               Background
            </li>
        </ul>
    </div>

    <table id="pictures-table">
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
                <h1>Subscribe Button</h1>
                Preview your custom subscribe button below. To upload a new subscribe button, choose a new file.<br>
                <?php if($_user['subbutton'] != "none") { ?><img src="/dynamic/subscribe/<?php echo $_user['subbutton']; ?>" style="width: 159px;
                    height: 29px;"> <?php } else { ?> You haven't set a subscribe button yet.<?php } ?><br>
                <form method="post" action="/post/channel_update" enctype="multipart/form-data">
                    <input type="file" name="fileToUpload">
                    <input class="yt-uix-button yt-uix-button-default" type="submit" value="Upload Custom Button" name="ssubtset">
                </form><br><br>
                <h1>Bio</h1>
                Set text (Max 2000 characters)<br>
                <form method="post" action="/post/channel_update" enctype="multipart/form-data">
                    <textarea style="width: 345px;padding: 0px;background-color:white;border: 1px solid #d3d3d3;" id="biomd" placeholder="Bio" name="bio"><?php echo htmlspecialchars($_user['bio']); ?></textarea><br>
                    <input class="yt-uix-button yt-uix-button-default" name="bioset" type="submit" value="Set">
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

    <table id="misc-table" style="display: none;">
        <tr>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <td style="vertical-align: top;">
                <h1>Featured Video</h1>
                Must not have extra whitespace/spaces at the end. Example: MTYwNTQ4ODc4Mja=24 is valid. DO NOT INCLUDE THE FULPTUBE.ROCKS LINK, JUST THE ID<br>
                <form method="post" action="/post/channel_update" enctype="multipart/form-data">
                <input style="padding:5px;border-radius:5px;background-color:white;border: 1px solid #d3d3d3;width: 345px;" id="biomd" placeholder="Video ID" value="<?php echo htmlspecialchars($_user['featured']);?>" name="videoid">
                    <input class="yt-uix-button yt-uix-button-default" name="featuredset" type="submit" value="Set">
                </form><br><br>
                <h1>Featured Channels Custom Title</h1>
                Make it empty if you don't want it to be anything.<br>
                <form method="post" action="/post/channel_update" enctype="multipart/form-data">
                <input style="padding:5px;border-radius:5px;background-color:white;border: 1px solid #d3d3d3;width: 345px;" id="biomd" placeholder="Custom Title" value="<?php echo htmlspecialchars($_user['customchannelfeatured']);?>" name="featuredtitle">
                    <input class="yt-uix-button yt-uix-button-default" name="customtitleset" type="submit" value="Set">
                </form><br><br>
            </td>
            <td style="vertical-align: top;     padding-left: 94px; width: 432px;">
                <h1>Display Banner</h1>
                Choose if you want your banner to be displayed.<br>
                <form method="post" action="/post/channel_update" enctype="multipart/form-data">
                    <select name="bannerdisplay" id="ifyouarereadingthisYOUSUCK">
                        <option value="d">Display</option>
                        <option value="n">Don't Display</option>
                    </select><br>
                    <input class="yt-uix-button yt-uix-button-default" name="setbannerdisplay" type="submit" value="Set">
                </form><br><br>
                <h1>Featured Layout</h1>
                Choose what layout you want your "featured" tab will look like.. // THIS DOES NOT WORK YET<br>
                <form method="post" action="/post/channel_update" enctype="multipart/form-data">
                    <select name="channellayout" id="ifyouarereadingthisYOUSUCK">
                        <option value="de">Default</option>
                        <option value="po">Playlists Only</option>
                        <option value="pc">Default + Other Channels</option>
                    </select><br>
                    <input class="yt-uix-button yt-uix-button-default" name="setchannellayout" type="submit" value="Set">
                </form><br><br>
                <h1>Featured Channels</h1>
                Seperate by commas. Do not add spaces next to the commas. Example: chief bazinga,Sniped,Joel98 <br>
                <form method="post" action="/post/channel_update" enctype="multipart/form-data">
                <input style="padding:5px;border-radius:5px;background-color:white;border: 1px solid #d3d3d3;width: 345px;" id="biomd" placeholder="Featured Channel" value="<?php echo htmlspecialchars($_user['featuredchannels']);?>" name="videoid">
                    <input class="yt-uix-button yt-uix-button-default" name="channelset" type="submit" value="Set">
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