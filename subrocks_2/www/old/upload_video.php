<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/important/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/lib/new/base.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/lib/new/fetch.php"); ?>
<?php
  $_user_fetch_utils = new user_fetch_utils();
  $_video_fetch_utils = new video_fetch_utils();
  $_base_utils = new config_setup();
 
  $_base_utils->initialize_db_var($conn);
  $_video_fetch_utils->initialize_db_var($conn);
  $_user_fetch_utils->initialize_db_var($conn);

  $_base_utils->initialize_page_compass("Upload");
?>
<!DOCTYPE html>
<html>
    <head>
        <title>FulpTube - <?php echo $_base_utils->return_current_page(); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" href="/static/css/www-core.css">
    </head>
    <body>
        <div class="www-core-container">
            <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/header.php"); ?>
            Having problems with the new upload design or not feeling ready for it just yet? Watch our <a href="#">video tutorial</a> or ask questions on our Discord server.<br><br>
    
    <div id="upload_page_intro">
                <div style="color: #555;
width: 198px;
padding-top: 30px;
float: right;
padding-right: 77px;">
                    <b>Coming soon</b>
                    <ul class="upload-tips">
                        <li>
                            How to upload a video tutorial
                        </li>
                        <li>
                            Q&A for Uploading
                        </li>
                        <li>
                            Segmented uploading
                        </li>
                    </ul>
                    <b>Tip</b>
                    <ul class="upload-tips">
                        <li>
                            You can upload multiple files at once by opening multiple tabs.
                        </li>
                        <li>
                            You can compress videos under 100MB using a tool named <a href="https://handbrake.fr">Handbrake.</a>
                        </li>
                    </ul>
                </div>
                <div style="width: 680px;">
                <div class="title-bar-upload-s">
                    <b>Upload Video </b>
                    </div>
                    <div class="upload-video-stage-1">
                        <img id="upload_button" src="/static/img/UploadArrowHover.png" style="cursor: pointer;"
                        onclick="this.display = 'none'; document.getElementById"
                        ><br><br>
                        <span style="color: #555;font-size: 14px;font-weight: bold;">Click on the upload icon to start uploading your videos.</span><br><br>
                    </div>
                    <div style="text-align: center;">
                        <span style="color: #555;">Upload HD videos in various formats up to 100MB.</span><br><br>
                        <span style="color: #555;">You must own the copyright or have the necessary rights for any content you upload.</span>
                    </div>
                </div>
            </div>
            <div class="guideline-videos" id="guidelines_video">
                    <b>Guidelines</b>
                    <ul style="list-style: inside; line-height: 15.5px;">
                        <li>
                            Don't reupload videos that aren't yours.
                        </li>
                        <li>
                            All videos are converted to 720p@1000kbps H264/AAC by FFMPEG, allowing a wide variety of formats.
                        </li>
                        <li>
                            Videos must be smaller than 100MB. (because of Cloudflare)
                        </li>
                        <li>
                            No copyrighted content that will get us DMCA'd.
                        </li>
                        <li>
                            Videos are manually approved.
                        </li>
                    </ul>
                </div>
                <div style="display: none;" id="main_upload">
                    <?php if(!isset($_SESSION['siteusername'])) { echo("You arent logged in by the way! If you arent logged in, this won't work!"); } ?>
                    <form method="post" enctype="multipart/form-data" id="submitform">
                        <?php if(isset($fileerror)) { echo $fileerror . "<br>"; } ?>
                        <div class="title-bar-upload">
                            <b><?php if(!isset($cLang)) { ?> Upload Video <?php } else { echo $cLang['actualUploadVideo']; } ?> </b>
                        </div>
                        <div class="upload-main-s">
                            <b><?php if(!isset($cLang)) { ?> Title <?php } else { echo $cLang['uTitle']; } ?> </b> <br><input class="upload-inputs" type="text" name="title" id="upltx" required="required" row="20"><br><br>
                            <b><?php if(!isset($cLang)) { ?> Description <?php } else { echo $cLang['description']; } ?> </b><br>
                            <textarea onkeyup="textCounter(this,'counter',500);"  style="resize: none;width: 345px;padding:5px;border-radius:5px;background-color:white;border: 1px solid #d3d3d3;" id="upltx2" name="comment"></textarea><br><br>
                            <input disabled style="" maxlength="3" size="3" value="500" id="counter" class="characters-remaining"> <?php if(!isset($cLang)) { ?> characters remaining <?php } else { echo $cLang['charremaining']; } ?> 
                            <br><br>
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
                            <input id="tags" class="upload-inputs" placeholder="Seperate tags with commas" type="text" name="tags" required="required" row="20"><br><br>
                        
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
                            <div style="position:relative;left: 390px; top: -302px;">
                                <b>Category</b><br>
                                <select id="category" name="category" style="padding: 3px;border-radius:5px; border: 1px solid #d3d3d3;">  
                                    <?php $categories = ["None", "Film & Animation", "Autos & Vehicles", "Music", "Pets & Animals", "Sports", "Travel & Events", "Gaming", "People & Blogs", "Comedy", "Entertainment", "News & Politics", "Howto & Style", "Education", "Science & Technology", "Nonprofits & Activism"]; ?>
                                    <?php foreach($categories as $categoryTag) { ?>
                                        <option value="<?php echo $categoryTag; ?>"><?php echo $categoryTag; ?></option>
                                    <?php } ?>
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
                        <script src="/js/commd.js"></script>
                        
                        <!-- class="g-recaptcha" data-sitekey="<?php // echo $config['recaptcha_sitekey']; ?>" data-callback="onLogin" -->
                    </form>
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
                        window.location = "video_manager?uploaded";
                    }
                });
            });

            }); // defer
        </script>
    </body>
</html>