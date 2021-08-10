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
    document.getElementById("layout-table").style.display = "none";
    document.getElementById("pictures").className = "selected-user";
    document.getElementById("text").className = "non";
    document.getElementById("bg").className = "non";
};

function text_table() {
    document.getElementById("misc-table").style.display = "block";
    document.getElementById("bg-table").style.display = "none";
    document.getElementById("pictures-table").style.display = "none";
    document.getElementById("pictures").className = "non";
    document.getElementById("layout-table").style.display = "none";
    document.getElementById("text").className = "selected-user";
    document.getElementById("bg").className = "non";
};

function bg_table() {
    document.getElementById("misc-table").style.display = "none";
    document.getElementById("bg-table").style.display = "block";
    document.getElementById("layout-table").style.display = "none";
    document.getElementById("pictures-table").style.display = "none";
    document.getElementById("pictures").className = "non";
    document.getElementById("pictures").className = "non";
    document.getElementById("text").className = "non";
    document.getElementById("bg").className = "selected-user";
};

function layout_table() {
    document.getElementById("misc-table").style.display = "none";
    document.getElementById("bg-table").style.display = "none";
    document.getElementById("layout-table").style.display = "block";
    document.getElementById("pictures-table").style.display = "none";
    document.getElementById("pictures").className = "non";
    document.getElementById("pictures").className = "non";
    document.getElementById("text").className = "non";
    document.getElementById("bg").className = "selected-user";
};
</script>
<style>
    .example-parent {
    color: black;
    display: flex;
    font-family: sans-serif;
    font-weight: bold;
    }

    .example-origin {
    flex-basis: 100%;
    flex-grow: 1;
    padding: 10px;
    }

    .example-draggable {
        background-color: #f2f2f2;
    font-weight: normal;
    margin-bottom: 10px;
    margin-top: 10px;
    padding: 10px;
    }

    .example-dropzone {
    background-color: #6DB65B;
    flex-basis: 100%;
    flex-grow: 1;
    padding: 10px;
    }
</style>
<script>
function onDragStart(event) {
  event
    .dataTransfer
    .setData('text/plain', event.target.id);

  event
    .currentTarget
    .style
    .backgroundColor = '#f2f2f2;';
}

function onDrop(event) {
  const id = event
    .dataTransfer
    .getData('text');

    const draggableElement = document.getElementById(id);
    const dropzone = event.target;
    dropzone.appendChild(draggableElement);

    event
    .dataTransfer
    .clearData();
}

function onDragOver(event) {
  event.preventDefault();
}

function draggables2json() {
  draggables = $("#layout").children();
  outj = {};
  for (di=0; di < draggables.length; di++) {
    outj[di] = draggables[di].textContent.replace(/\s+$/, '');
  }
  return JSON.stringify(outj);
}
</script>
<div class="channel-customization-base" id="channel-customize" style="display: none;">
    <div class="user-header-bottom" style="margin: -5px;">
        <div class="www-header-list" style="margin-top: 0px;">
            <a class="www-header-item" href="#" onclick="pictures_table()">Main</a>
            <a class="www-header-item" href="#" onclick="bg_table()">Background</a>
            <a class="www-header-item" href="#" onclick="text_table()">Colors</a>
            <a class="www-header-item" href="#" onclick="layout_table()">Layout</a>
        </div>
    </div>

    <table id="pictures-table" style="width: 960px;padding: 10px;">
        <tr>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <td style="vertical-align: top;">
                <b>Avatar</b><br>
                <span style="font-size: 11px;" class="grey-text">Preview your avatar below. To upload a new avatar, choose a new file.</span><br>
                <?php if($_user['pfp'] != "default.png") { ?>
                    <a href="/get/remove_profile_pic">Remove Profile Picture</a><br>
                <?php } ?>
                <img src="/dynamic/pfp/<?php echo $_user['pfp']; ?>" style="width: 50px; height: 50px;margin-left: 146px;"><br>
                <form method="post" action="/post/channel_update_new" enctype="multipart/form-data">
                    <input type="file" name="fileToUpload" id="avatar-upload">
                    <!--<button class="yt-uix-button yt-uix-button-default" id="av-uplod">Select File</button>-->
                    <input class="yt-uix-button yt-uix-button-default" type="submit" value="Upload Image" name="pfpset">
                </form><br><br>
                <b>Channel Banner</b><br>
                <span style="font-size: 11px;" class="grey-text">Preview your banner that will be displayed on your channel page.</span><br>
                <br>
                <form method="post" action="/post/channel_update_new" enctype="multipart/form-data">
                    <?php if(!empty($_user['banner'])) { ?>
                        <a href="/get/remove_channel_banner">Remove Channel Banner</a><br>
                    <?php } ?>
                    <input type="file" name="fileToUpload" id="avatar-upload">
                    <!--<button class="yt-uix-button yt-uix-button-default" id="av-uplod">Select File</button>-->
                    <input class="yt-uix-button yt-uix-button-default" type="submit" value="Upload Image" name="bannerset">
                </form><br><br>
                <b>Video Page Banner</b><br>
                <span style="font-size: 11px;" class="grey-text">Preview your banner that will be displayed on your videos.</span><br>
                <br>
                <form method="post" action="/post/channel_update_new" enctype="multipart/form-data">
                    <?php if(!empty($_user['subbutton'])) { ?>
                        <a href="/get/remove_watch_banner">Remove Watch Page Banner</a><br>
                    <?php } ?>
                    <input type="file" name="fileToUpload" id="avatar-upload">
                    <!--<button class="yt-uix-button yt-uix-button-default" id="av-uplod">Select File</button>-->
                    <input class="yt-uix-button yt-uix-button-default" type="submit" value="Upload Image" name="videopageset">
                </form><br><br>
            </td>
            <td style="vertical-align: top;     padding-left: 94px; width: 432px;">
                <b>Bio</b><br>
                <span style="font-size: 11px;" class="grey-text">Set text (Max 2000 characters)</span><br>
                <form method="post" action="/post/channel_update_new" enctype="multipart/form-data">
                    <textarea style="width: 345px;padding: 0px;background-color:white;border: 1px solid #d3d3d3;" id="biomd" placeholder="Bio" name="bio"><?php echo htmlspecialchars($_user['bio']); ?></textarea><br>
                    <input class="yt-uix-button yt-uix-button-default" name="bioset" type="submit" value="Set">
                </form><br><br>

                <b>Featured Video</b><br>
                <span style="font-size: 11px;" class="grey-text">Must not have extra whitespace/spaces at the end. Example: MTYwNTQ4ODc4Mja=24 is valid. DO NOT INCLUDE THE SITE DOMAIN, JUST THE ID</span><br>
                <form method="post" action="/post/channel_update_new" enctype="multipart/form-data">
                <input style="padding:5px;border-radius:5px;background-color:white;border: 1px solid #d3d3d3;width: 345px;" id="biomd" placeholder="Video ID" value="<?php echo htmlspecialchars($_user['featured']);?>" name="videoid">
                    <input class="yt-uix-button yt-uix-button-default" name="featuredset" type="submit" value="Set">
                </form><br><br>

                <b>Channel Layout</b><br>
                <span style="font-size: 11px;" class="grey-text">Users will be redirected to the layout that you choose.</span><br>
                <form method="post" action="/post/channel_update_new" enctype="multipart/form-data">
                    <select name="layout">
                        <option value="1">1.0</option>
                        <option value="2">2.0 (BETA)</option>
                    </select>
                    <input class="yt-uix-button yt-uix-button-default" name="layset" type="submit" value="Set">
                </form><br><br>
            </td>
        </tr>
    </table>

    <table id="misc-table" style="display: none;width: 960px;padding: 10px;">
        <tr>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <td style="vertical-align: top;">
                <b>Primary Color</b><br>
                <span style="font-size: 11px;" class="grey-text">This will change the background of your boxes' tabs"</span><br>
                <form method="post" action="/post/channel_update_new" enctype="multipart/form-data">
                    Choose a color: <input type="color" id="solidcolor" name="solidcolor" value="<?php echo htmlspecialchars($_user['primary_color']); ?>"><br>
                    <input class="yt-uix-button yt-uix-button-default" name="primary" type="submit" value="Set">
                </form><br><br>
                <b>Channel Box Color</b><br>
                <span style="font-size: 11px;" class="grey-text">This will change the infobox's background color.</span><br>
                <form method="post" action="/post/channel_update_new" enctype="multipart/form-data">
                    Choose a color: <input type="color" id="solidcolor" name="solidcolor" value="<?php echo htmlspecialchars($_user['secondary_color']); ?>"><br>
                    <input class="yt-uix-button yt-uix-button-default" name="secondary" type="submit" value="Set">
                </form><br><br>

            </td>
            <td style="vertical-align: top;     padding-left: 94px; width: 432px;">
            <b>Background Color</b><br>
                <span style="font-size: 11px;" class="grey-text">This will change the background of all the other boxes.</span><br>
                <form method="post" action="/post/channel_update_new" enctype="multipart/form-data">
                    Choose a color: <input type="color" id="solidcolor" name="solidcolor" value="<?php echo htmlspecialchars($_user['third_color']); ?>"><br>
                    <input class="yt-uix-button yt-uix-button-default" name="thirdb" type="submit" value="Set">
                </form><br><br>
                <b>Text Color</b><br>
                <span style="font-size: 11px;" class="grey-text">This will change the color of the box ribbons.</span><br>
                <form method="post" action="/post/channel_update_new" enctype="multipart/form-data">
                    Choose a color: <input type="color" id="solidcolor" name="solidcolor" value="<?php echo htmlspecialchars($_user['text_color']); ?>"><br>
                    <input class="yt-uix-button yt-uix-button-default" name="textcolor" type="submit" value="Set">
                </form><br><br>
                <b>Text Main Color</b><br>
                <span style="font-size: 11px;" class="grey-text">This will change the color of the text inside the bottom part of sections.</span><br>
                <form method="post" action="/post/channel_update_new" enctype="multipart/form-data">
                    Choose a color: <input type="color" id="solidcolor" name="solidcolor" value="<?php echo htmlspecialchars($_user['primary_color_text']); ?>"><br>
                    <input class="yt-uix-button yt-uix-button-default" name="textprimarycolor" type="submit" value="Set">
                </form><br><br>
            </td>
        </tr>
    </table>

    <table id="bg-table" style="display: none;width: 960px;padding: 10px;"> 
        <tr>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <td style="vertical-align: top;">
                <b>Background Options</b><br>
                <span style="font-size: 11px;" class="grey-text">Choose how your background will be displayed.</span><br>
                <form method="post" action="/post/channel_update_new" enctype="multipart/form-data">
                    <select name="bgoption" id="ifyouarereadingthisYOUSUCK">
                        <option value="repeaty">Repeat - Y</option>
                        <option value="repeatx">Repeat - X</option>
                        <option value="norepeat">No Repeat</option>
                        <option value="repeatxy">Repeat - X and Y</option>
                        <option value="stretch">Stretch</option>
                        <option value="solid">Solid</option>
                    </select><br>
                    Choose a color: <input type="color" id="solidcolor" name="solidcolor" value="<?php echo htmlspecialchars($_user['2009_bgcolor']); ?>"><br>
                    <input class="yt-uix-button yt-uix-button-default" name="bgoptionset" type="submit" value="Set">
                </form><br><br>
            </td>
            <td style="vertical-align: top;     padding-left: 94px; width: 432px;">
                <b>Background</b><br>
                <span style="font-size: 11px;" class="grey-text">Choose Image (Max file size: 10MB)</span><br>
                <form method="post" action="/post/channel_update_new" enctype="multipart/form-data">
                    <input type="file" name="fileToUpload" id="background-upload">
                    <!--<button class="yt-uix-button yt-uix-button-default" id="av-uplod">Select File</button>-->
                    <input class="yt-uix-button yt-uix-button-default" type="submit" value="Upload Background" name="bgset">
                </form><br><br>
            </td>
        </tr>
    </table>

    <table id="layout-table" style="display: none;width: 960px;padding: 10px;"> 
        <tr>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <td style="vertical-align: top;">
                <b>Left Side</b><br>
                <span style="font-size: 11px;" class="grey-text">Choose how your left part of your channel will be displayed.<br>MODULES: main, stat, subscription, recent, subscribers, friends, discussion</span><br>
                <form method="post" action="/post/channel_update_new" enctype="multipart/form-data">
                    <input style="padding:5px;border-radius:5px;background-color:white;border: 1px solid #d3d3d3;width: 345px;" id="biomd" placeholder="Modules" value="<?php echo htmlspecialchars($_user['s_2009_user_left']);?>" name="left">
                    <input class="yt-uix-button yt-uix-button-default" name="leftset" type="submit" value="Set"> 
                </form><br><br>
            </td>
            <td style="vertical-align: top;     padding-left: 94px; width: 432px;">
                <b>Right Side</b><br>
                <span style="font-size: 11px;" class="grey-text">Choose how your right part of your channel will be displayed.<br>MODULES: main, stat, subscription, recent, subscribers, friends, discussion</span><br>
                <form method="post" action="/post/channel_update_new" enctype="multipart/form-data">
                    <input style="padding:5px;border-radius:5px;background-color:white;border: 1px solid #d3d3d3;width: 345px;" id="biomd" placeholder="Modules" value="<?php echo htmlspecialchars($_user['s_2009_user_right']);?>" name="right">
                    <input class="yt-uix-button yt-uix-button-default" name="rightset" type="submit" value="Set"> 
                </form><br><br>
            </td>
        </tr>
    </table>
</div>
<script>
    function getData() {
        var layout = draggables2json();

        $.ajax({
        url: 'set_customization.php',
        method: 'POST',
        dataType: 'text',
        data: {
            layout: layout,
        },
        success: function(response) {
            alert("Successfully updated your channel layout.");
        }
        });
    };
</script>