<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/important/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/lib/new/base.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/lib/new/fetch.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/lib/new/update.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/lib/new/insert.php"); ?>
<?php
    $_user_fetch_utils = new user_fetch_utils();
    $_video_fetch_utils = new video_fetch_utils();
    $_video_insert_utils = new video_insert_utils();
    $_user_insert_utils = new user_insert_utils();
    $_user_update_utils = new user_update_utils();
    $_base_utils = new config_setup();
    
    $_base_utils->initialize_db_var($conn);
    $_video_fetch_utils->initialize_db_var($conn);
    $_video_insert_utils->initialize_db_var($conn);
    $_user_fetch_utils->initialize_db_var($conn);
    $_user_insert_utils->initialize_db_var($conn);
    $_user_update_utils->initialize_db_var($conn);

  $_base_utils->initialize_page_compass("Upload");

  if(!isset($_SESSION['siteusername']))
    header("Location: /sign_in");

    if(isset($_SESSION['siteusername']) && !$_user_fetch_utils->user_exists(@$_SESSION['siteusername'])) 
        header("Location: /logout");
?>
<!DOCTYPE html>
<html>
    <head>
        <title>SubRocks - <?php echo $_base_utils->return_current_page(); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/static/css/new/www-core.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    </head>
    <body>
        <div class="www-core-container">
            <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/header.php"); ?>
            <?php if($_user_fetch_utils->if_upload_cooldown($_SESSION['siteusername'])) { ?>
                <div class="alert" id="videodoesntexist" style="background-color: #FFA3A3;">
                    You are currently under a cooldown. Wait 10 minutes then upload another video!
                </div>
            <?php } ?>
            Having problems with the new upload design or not feeling ready for it just yet? Watch our <a href="#">video tutorial</a> or ask questions on our Discord server.<br><br>
    
    <div id="upload_page_intro">
                <div class="upload-guidelines">
                    Important: Do not upload any TV shows, music videos, music concerts, or commercials without permission unless they consist entirely of content you created yourself.<br><br>

                    The Copyright Tips page and the Community Guidelines can help you determine whether your video infringes someone else's copyright.<br><br>

                    By clicking "Upload Video", you are representing that this video does not violate SubRocks's Terms of Use and that you own all copyrights in this video or have authorization to upload it.
                </div>
                <div style="width: 680px;">
                    <div class="upload-new-base">
                        <div class="upload-inner-box">
                        <button class="upload_button" id="upload_button" style="float: none;">Upload</button><br><br>
                        </div>
                        <div style="position: relative; left: 394px;">
                            <h4 style="position: relative;right: 391px;">Videos can be...</h4>
                            <ul style="list-style: disc inside none;width: 159px;padding: 0px;position: relative;bottom: 114px;">
                                <li>High Definition</li>
                                <li>Up to 100 MB in size.</li>
                                <li>Up to 100 hours in length.</li>
                                <li>A wide variety of formats</li>
                            </ul>
                        </div>
                    </div>
                    <div style="text-align: center;">
                        <span style="color: #555;">Upload HD videos in various formats up to 100MB.</span><br><br>
                        <span style="color: #555;">You must own the copyright or have the necessary rights for any content you upload.</span>
                    </div>
                </div>
            </div>
                <div style="display: none;" id="main_upload">
                    <div class="upload-guidelines" style="position: relative;left: 14px;">
                        Important: Do not upload any TV shows, music videos, music concerts, or commercials without permission unless they consist entirely of content you created yourself.<br><br>

                        The Copyright Tips page and the Community Guidelines can help you determine whether your video infringes someone else's copyright.<br><br>

                        By clicking "Upload Video", you are representing that this video does not violate SubRocks's Terms of Use and that you own all copyrights in this video or have authorization to upload it.
                    </div>
                    <div class="upload-new-base" style="height: 400px;width: 632px;">
                    <?php if(!isset($_SESSION['siteusername'])) { echo("You arent logged in by the way! If you arent logged in, this won't work!"); } ?>
                    <form method="post" enctype="multipart/form-data" id="submitform">
                        <?php if(isset($fileerror)) { echo $fileerror . "<br>"; } ?>
                        <div class="upload-main-s">
                            <b><?php if(!isset($cLang)) { ?> Title <?php } else { echo $cLang['uTitle']; } ?> </b> <br><input class="upload-inputs" type="text" name="title" id="upltx" required="required" row="20"><br><br>
                            <b><?php if(!isset($cLang)) { ?> Description <?php } else { echo $cLang['description']; } ?> </b><br>
                            <textarea onkeyup="textCounter(this,'counter',500);"  style="resize: none;width: 345px;padding:5px;border-radius:5px;background-color:white;border: 1px solid #d3d3d3;" id="upltx2" name="comment"></textarea><br><br>
                            <input disabled style="" maxlength="3" size="3" value="500" id="counter" class="characters-remaining"> <?php if(!isset($cLang)) { ?> characters remaining <?php } else { echo $cLang['charremaining']; } ?> 
                            <br><br>
                            <b>Category</b><br>
                            <select id="category" name="category" style="padding: 3px;border-radius:5px; border: 1px solid #d3d3d3;">  
                                <?php $categories = ["None", "Film & Animation", "Autos & Vehicles", "Music", "Pets & Animals", "Sports", "Travel & Events", "Gaming", "People & Blogs", "Comedy", "Entertainment", "News & Politics", "Howto & Style", "Education", "Science & Technology", "Nonprofits & Activism"]; ?>
                                <?php foreach($categories as $categoryTag) { ?>
                                    <option value="<?php echo $categoryTag; ?>"><?php echo $categoryTag; ?></option>
                                <?php } ?>
                            </select><br><br>
                            <script>
                                function textCounter(field,field2,maxlimit) {
                                    var countfield = document.getElementById(field2);
                                    if ( field.value.length > maxlimit ) {
                                        field.value = field.value.substring( 0, maxlimit );
                                        return false;
                                        } else {
                                        countfield.value = maxlimit - field.value.length;
                                        }
                                }
                            </script>
                            <b>Tags</b> <br>
                            <input id="tags" class="upload-inputs" placeholder="Seperate tags with commas" type="text" name="tags" row="20"><br><br>
                        
                            <b><?php if(!isset($cLang)) { ?> Recommended Tags <?php } else { echo $cLang['rectag']; } ?> </b><br><br>
                            <div style="width: 345px;">
                            <span class="upload-add-tag" onclick="addTag('game');">+ game</span>
                            <span class="upload-add-tag" onclick="addTag('funny');">+ funny</span>
                            <span class="upload-add-tag" onclick="addTag('education');">+ education</span>
                            <span class="upload-add-tag" onclick="addTag('blog');">+ blog</span>
                            <span class="upload-add-tag" onclick="addTag('travel');">+ travel</span>
                            <span class="upload-add-tag" onclick="addTag('science');">+ science</span>
                                    </div>
                            <script>
                                var tags = document.getElementById("tags");

                                function addTag(tag) {
                                    tags.value = tags.value + ", " + tag;
                                } 
                            </script><br>
                            <span style="display: none;">
                            <input type="file" name="fileToUpload" id="fileToUpload"><br>
                            <span id="fileSize">0</span>/100MB
                            </span>
                            <script>
                            function updateSize() {
                                let nBytes = 0,
                                    oFiles = this.files,
                                    nFiles = oFiles.length;
                                for (let nFileId = 0; nFileId < nFiles; nFileId++) {
                                nBytes += oFiles[nFileId].size;
                                }
                                let sOutput = nBytes + " bytes";
                                // optional code for multiples approximation
                                const aMultiples = ["KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"];
                                for (nMultiple = 0, nApprox = nBytes / 1024; nApprox > 1; nApprox /= 1024, nMultiple++) {
                                sOutput = nApprox.toFixed(3) + " " + aMultiples[nMultiple];
                                }
                                // end of optional code
                                document.getElementById("fileSize").innerHTML = sOutput;
                            }

                            document.getElementById("fileToUpload").addEventListener("change", updateSize, false);
                            </script>
                            <input class="yt-uix-button yt-uix-button-default" type="submit" value="Upload">
                            <div style="position:relative;left: 390px; top: -211px; display: none;">
                                <b><?php if(!isset($cLang)) { ?> Privacy Settings <?php } else { echo $cLang['privacySetting']; } ?> </b> <img src="/static/info.png" title="This is a way to make your video inaccessible to people." style="vertical-align: middle;"><br>
                                <select id="privacy" name="privacy" style="padding: 3px;border-radius:5px; border: 1px solid #d3d3d3;">
                                    <option value="pub">Public</option>
                                    <option value="lnk">Link Only</option>
                                    <option value="prv">Private</option>
                                </select>
                            </div>
                            <!--
                            <div id="retard" style="width: 450px; padding: 5px; border: 1px solid #c6c6c6; background-color: #ffffd4; position: relative; bottom: 84px;">
                            <small>
                                
                            <?php if(!isset($cLang)) { ?>
                            <b style="font-size: 18px;">Please, don't reupload videos that aren't yours.</b><br>
                            <br>
                            Thumbnails are auto generated. <br>
                            All videos are converted to 720p@1000kbps H264/AAC by FFMPEG, allowing a wide variety of formats.<br>
                            Videos must be smaller than 100MB. (because of Cloudflare)<br>
                            No copyrighted content that will get us DMCA'd.<br>
                            <b>Videos are manually approved.</b>
                            <?php } else { echo $cLang['donotreupload']; } ?>
                        </small>
                        </div><br>
                            -->
                        </div><br>
                        
                        <!-- class="g-recaptcha" data-sitekey="<?php // echo $config['recaptcha_sitekey']; ?>" data-callback="onLogin" -->
                    </form>
                </div>
                    <div class="progressbar" style="display: hidden;">
                        Your video is being uploaded. Please wait. [DO NOT LEAVE THIS PAGE EVEN IF IT IS AT 100%]<span class="timeRemaining"></span><br>
                        <div class="barbg">
                            <div class="bar"></div>
                        </div>
                    </div>
                    <script>
                        var i = 0;

                        function removeElement(parentDiv, childDiv){
                            if (childDiv == parentDiv){
                                alert("The parent div cannot be removed.");
                            }
                            else if (document.getElementById(childDiv)){
                                var child = document.getElementById(childDiv);
                                var parent = document.getElementById(parentDiv);
                                parent.removeChild(child);
                            }
                            else{
                                alert("Child div has already been removed or does not exist.");
                                return false;
                            }
                        }

                        function addAnno(){
                            i++;

                            var r = document.createElement('span');
                            var y = document.createElement("textarea");
                            var width = document.createElement("input");
                            var height = document.createElement("input");
                            var locX = document.createElement("input");
                            var locY = document.createElement("input");
                            var textcolor = document.createElement("input");
                            var rectcolor = document.createElement("input");
                            var start = document.createElement("input");
                            var end = document.createElement("input");
                            var g = document.createElement("img");

                            var breakline = document.createElement("br");
                            var divider = document.createElement("br");
                            var divider2 = document.createElement("br");

                            y.setAttribute("cols", "17");
                            y.setAttribute("placeholder", "Text");
                            y.setAttribute("name", "annotext_" + i);
                            y.setAttribute("style", "display: block;");

                            width.setAttribute("placeholder", "Width [%]");
                            width.setAttribute("type", "number");
                            width.setAttribute("name", "annowidth_" + i);
                            width.setAttribute("style", "width: 79.5px;");

                            height.setAttribute("placeholder", "Height [%]");
                            height.setAttribute("type", "number");
                            height.setAttribute("style", "width: 79.5px;");
                            height.setAttribute("name", "annoheight_" + i);

                            locX.setAttribute("placeholder", "X Offset [px]");
                            locX.setAttribute("style", "width: 79.5px;");
                            locX.setAttribute("name", "locx_" + i);

                            locY.setAttribute("placeholder", "Y Offset [px]");
                            locY.setAttribute("step", "any");
                            locY.setAttribute("min", "0");
                            locY.setAttribute("style", "width: 79.5px;");
                            locY.setAttribute("name", "locy_" + i);

                            textcolor.setAttribute("placeholder", "Text Color [Hex]");
                            textcolor.setAttribute("style", "width: 79.5px;");
                            textcolor.setAttribute("value", "#textcolor");
                            textcolor.setAttribute("name", "textcolor_" + i);

                            rectcolor.setAttribute("placeholder", "Rect Color [Hex]");
                            rectcolor.setAttribute("value", "#rectcolor");
                            rectcolor.setAttribute("style", "width: 79.5px;");
                            rectcolor.setAttribute("name", "rectcolor_" + i);

                            start.setAttribute("placeholder", "Start [sec]");
                            start.setAttribute("type", "number");
                            start.setAttribute("step", "any");
                            start.setAttribute("min", "0");
                            start.setAttribute("style", "width: 79.5px;");
                            start.setAttribute("name", "start_" + i);

                            end.setAttribute("placeholder", "End [sec]");
                            end.setAttribute("type", "number");
                            end.setAttribute("step", "any");
                            end.setAttribute("min", "0");
                            end.setAttribute("style", "width: 79.5px;");
                            end.setAttribute("name", "end_" + i);

                            g.setAttribute("src", "delete.png");
                            g.setAttribute("style", "vertical-align: middle;");
                            g.setAttribute("name", "textelement_" + i);

                            r.appendChild(y);
                            r.appendChild(width);
                            r.appendChild(height);
                            r.appendChild(breakline);
                            r.appendChild(locX);
                            r.appendChild(locY);
                            r.appendChild(divider);
                            r.appendChild(textcolor);
                            r.appendChild(rectcolor);
                            r.appendChild(divider2);
                            r.appendChild(start);
                            r.appendChild(end);

                            g.setAttribute("onclick", "removeElement('annoform','id_" + i + "')");
                            r.appendChild(g);
                            r.setAttribute("id", "id_" + i);
                            document.getElementById("annoform").appendChild(r);
                            document.getElementById("annoform").innerHTML = document.getElementById("annoform").innerHTML + "<br><br>";
                        }

                        $('#upload_button').on('click', function() {
                            $('#fileToUpload').trigger('click');
                        });

                        $(document).ready(function(){
                            $("#fileToUpload").click(function(){
                                $(this).val("");
                            });

                            $("#fileToUpload").change(function(){
                                document.getElementById('main_upload').style.display = 'block';
                                document.getElementById('upload_page_intro').style.display = 'none';
                                document.getElementById('guidelines_video').style.display = 'inline-block';

                                var path = $(this).val();
                                var filename = path.replace(/^.*\\/, "");
                                $('#upltx').val(filename);
                            });
                        });
                    </script>
                </div>
            </div>
            <script>
                $(document).ready(function(){
                    $("#fileToUpload").click(function(){
                        $(this).val("");
                    });

                    $("#fileToUpload").change(function(){
                        document.getElementById('upload_second_stage').style.display = 'block';
                        document.getElementById('upload_initial_stage').style.display = 'none';

                        var path = $(this).val();
                        var filename = path.replace(/^.*\\/, "");
                        $('#upltx').val(filename);
                    });
                });
            </script>
        </div>
        <div class="www-core-container">
        <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/footer.php"); ?>
        </div>

        <script type="text/javascript">
            $(()=>{ // defer

            var uploadform = $("#submitform");
            var progressbar = $(".progressbar");
            var bar = $(".bar");
            var postto = "/post/upload";

            progressbar.hide();

            // when you press submit
            uploadform.on('submit', (e) => {

                // i have to both of these for it to not redirect (i want it to redirect if JS is off)
                e.stopImmediatePropagation();
                e.preventDefault();
                
                // good luck understanding this shit. it's mostly copy pasted.
                $.ajax({
                    xhr: () => {
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", (evt) => {
                            if (evt.lengthComputable) {
                                var percentComplete = Math.floor((evt.loaded / evt.total) * 100);
                                bar.width(percentComplete + '%');
                                bar.text(percentComplete + '%');

                                var seconds_elapsed =   ( new Date().getTime() - started_at.getTime() )/1000;
                                var bytes_per_second =  seconds_elapsed ? loaded / seconds_elapsed : 0 ;
                                var Kbytes_per_second = bytes_per_second / 1000 ;
                                var remaining_bytes =   total - loaded;
                                var seconds_remaining = seconds_elapsed ? remaining_bytes / bytes_per_second : 'calculating' ;
                                jQuery( '.timeRemaining' ).html( '' );
                                jQuery( '.timeRemaining' ).append( seconds_remaining );
                            }
                        }, false);
                        return xhr;
                    },
                    // if you can't understand this part you shouldn't be reading this
                    type: 'POST',
                    url: postto,
                    // afaik this only works for POST. don't care enough to check.
                    data: new FormData(uploadform[0]),
                    // no idea why this shit is 'false'.
                    contentType: false,
                    cache: false,
                    processData: false,
                    // right before data starts to be sent
                    beforeSend: () => {
                        uploadform.hide();
                        progressbar.show();
                        bar.width('0%');
                    },
                    // when the form gets a non-200 code probably
                    error: () => {
                        alert("Fatal error or stopped uploading. Try again.");
                        window.location = "index";
                    },
                    // when the form succeeds. resp is a string of what the server sent back 
                    success: (resp) => {
                        //alert(resp);
                        window.location = "video_manager?uploaded";
                    }
                });
            });

            }); // defer
        </script>
    </body>
</html>