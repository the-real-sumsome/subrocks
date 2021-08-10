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
    document.getElementById("layout-table").style.display = "none";
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
                <h1>Pictures</h1>
                <b>Avatar</b><br>
                <span style="font-size: 11px;" class="grey-text">Preview your avatar below. To upload a new avatar, choose a new file.</span><br>
                <?php if($_user['pfp'] != "default.png") { ?>
                    <a href="/get/remove_profile_pic">Remove Profile Picture</a><br>
                <?php } ?>
                <img src="/dynamic/pfp/<?php echo $_user['pfp']; ?>" style="width: 50px; height: 50px;margin-left: 146px;"><br>
                <form method="post" id="pfp" action="/post/channel_update_new" enctype="multipart/form-data">
                    <input type="file" name="fileToUpload" id="avatar-upload">
                    <!--<button class="www-button www-button-grey" id="av-uplod">Select File</button>-->
                    <input class="www-button www-button-grey" type="submit" value="Upload Image" name="pfpset">
                </form><br><br><hr class="thin-line">
                <b>Channel Banner</b><br>
                <span style="font-size: 11px;" class="grey-text">Preview your banner that will be displayed on your channel page.</span><br>
                <br>
                <form method="post" id="banner" action="/post/channel_update_new" enctype="multipart/form-data">
                    <?php if(!empty($_user['banner'])) { ?>
                        <a href="/get/remove_channel_banner">Remove Channel Banner</a><br>
                    <?php } ?>
                    <input type="file" name="fileToUpload" id="avatar-upload">
                    <!--<button class="www-button www-button-grey" id="av-uplod">Select File</button>-->
                    <input class="www-button www-button-grey" type="submit" value="Upload Image" name="bannerset">
                </form><br><br><hr class="thin-line">
                <b>Video Page Banner</b><br>
                <span style="font-size: 11px;" class="grey-text">Preview your banner that will be displayed on your videos.</span><br>
                <br>
                <form method="post" id="watchbanner" action="/post/channel_update_new" enctype="multipart/form-data">
                    <?php if(!empty($_user['subbutton'])) { ?>
                        <a href="/get/remove_watch_banner">Remove Watch Page Banner</a><br>
                    <?php } ?>
                    <input type="file" name="fileToUpload" id="avatar-upload">
                    <!--<button class="www-button www-button-grey" id="av-uplod">Select File</button>-->
                    <input class="www-button www-button-grey" type="submit" value="Upload Image" name="videopageset">
                </form><br><br><hr class="thin-line">
            </td>
            <td style="vertical-align: top;     padding-left: 94px; width: 432px;">
                <h1>Profile</h1>
                <b>Bio</b><br>
                <span style="font-size: 11px;" class="grey-text">Set text (Max 2000 characters)</span><br>
                <form method="post" id="bio" action="/post/channel_update_new" enctype="multipart/form-data">
                    <textarea style="width: 345px;padding: 0px;background-color:white;border: 1px solid #d3d3d3;" id="biomd" placeholder="Bio" name="bio"><?php echo htmlspecialchars($_user['bio']); ?></textarea><br><br>
                    <input class="www-button www-button-grey" style="margin: 0px;" name="bioset" type="submit" value="Set">
                </form><br><br><hr class="thin-line">

                <b>Featured Video</b><br>
                <span style="font-size: 11px;" class="grey-text">Must not have extra whitespace/spaces at the end. Example: MTYwNTQ4ODc4Mja=24 is valid. DO NOT INCLUDE THE SITE DOMAIN, JUST THE ID</span><br>
                <form method="post" id="featuredvid" action="/post/channel_update_new" enctype="multipart/form-data">
                <input style="padding: 5px;background-color: white;border: 1px solid #999;width: 345px;height: 12px;" id="biomd" placeholder="Video ID" value="<?php echo htmlspecialchars($_user['featured']);?>" name="videoid">
                    <input class="www-button www-button-grey" name="featuredset" type="submit" value="Set">
                </form><br><br><hr class="thin-line">

                <b>Website</b><br>
                <span style="font-size: 11px;" class="grey-text">Include the HTTP/HTTPS part.</span><br>
                <form method="post" id="featuredvid" action="/post/channel_update_new" enctype="multipart/form-data">
                <input style="padding: 5px;background-color: white;border: 1px solid #999;width: 345px;height: 12px;" id="biomd" placeholder="Website URL" value="<?php echo htmlspecialchars($_user['website']);?>" name="website">
                    <input class="www-button www-button-grey" name="websiteset" type="submit" value="Set">
                </form><br><hr class="thin-line">

                <?php $categories = ["None", "Director", "Musician", "Comedian", "Guru", "Nonprofit"]; ?>
                <b>Channel Layout</b><br>
                <span style="font-size: 11px;" class="grey-text">Users will be redirected to the layout that you choose.</span><br>
                <form method="post" id="channellayout" action="/post/channel_update_new" enctype="multipart/form-data">
                    <select style="background: transparent url(/static/img/spritesheet_main.png) repeat-x scroll 0 -800px;border: 1px solid #333;"  name="layout">
                        <option value="1">1.0</option>
                        <option value="2">2.0 (BETA)</option>
                    </select>
                    <input class="www-button www-button-grey" name="layset" type="submit" value="Set">
                </form><br><hr class="thin-line">

                <b>Channel Genre</b><br>
                <span style="font-size: 11px;" class="grey-text">This will show what type of channel you are to other users.</span><br>
                <form method="post" id="channellayout" action="/post/channel_update_new" enctype="multipart/form-data">
                    <select style="background: transparent url(/static/img/spritesheet_main.png) repeat-x scroll 0 -800px;border: 1px solid #333;"  name="genre">
                        <?php foreach($categories as $category) { ?>
                            <option value="<?php echo $category; ?>"><?php echo $category; ?></option>
                        <?php } ?>
                    </select>
                    <input class="www-button www-button-grey" name="genreset" type="submit" value="Set">
                </form><br><hr class="thin-line">

                <b>Transparency</b><br>
                <span style="font-size: 11px;" class="grey-text">This will decide the visibility of ALL of your modules.</span><br>
                <form method="post" id="channellayout" action="/post/channel_update_new" enctype="multipart/form-data">
                    <select style="background: transparent url(/static/img/spritesheet_main.png) repeat-x scroll 0 -800px;border: 1px solid #333;"  name="transparency">
                        <option value="1.0">100% (Visible)</option>
                        <option value="0.9">90%</option>
                        <option value="0.8">80%</option>
                        <option value="0.7">70%</option>
                        <option value="0.6">60%</option>
                        <option value="0.5">50%</option>
                        <option value="0.4">40%</option>
                        <option value="0.3">30%</option>
                        <option value="0.2">20%</option>
                        <option value="0.1">10%</option>
                    </select>
                    <input class="www-button www-button-grey" name="transparencyset" type="submit" value="Set">
                </form><br><hr class="thin-line">

                <b>Country</b><br>
                <span style="font-size: 11px;" class="grey-text">Users will be able to see what country you're from.</span><br>
                <form method="post" id="channellayout" action="/post/channel_update_new" enctype="multipart/form-data">
                    <select style="background: transparent url(/static/img/spritesheet_main.png) repeat-x scroll 0 -800px;border: 1px solid #333;" id="country" name="country">
                    <option value="Afganistan">Afghanistan</option>
                    <option value="Albania">Albania</option>
                    <option value="Algeria">Algeria</option>
                    <option value="American Samoa">American Samoa</option>
                    <option value="Andorra">Andorra</option>
                    <option value="Angola">Angola</option>
                    <option value="Anguilla">Anguilla</option>
                    <option value="Antigua & Barbuda">Antigua & Barbuda</option>
                    <option value="Argentina">Argentina</option>
                    <option value="Armenia">Armenia</option>
                    <option value="Aruba">Aruba</option>
                    <option value="Australia">Australia</option>
                    <option value="Austria">Austria</option>
                    <option value="Azerbaijan">Azerbaijan</option>
                    <option value="Bahamas">Bahamas</option>
                    <option value="Bahrain">Bahrain</option>
                    <option value="Bangladesh">Bangladesh</option>
                    <option value="Barbados">Barbados</option>
                    <option value="Belarus">Belarus</option>
                    <option value="Belgium">Belgium</option>
                    <option value="Belize">Belize</option>
                    <option value="Benin">Benin</option>
                    <option value="Bermuda">Bermuda</option>
                    <option value="Bhutan">Bhutan</option>
                    <option value="Bolivia">Bolivia</option>
                    <option value="Bonaire">Bonaire</option>
                    <option value="Bosnia & Herzegovina">Bosnia & Herzegovina</option>
                    <option value="Botswana">Botswana</option>
                    <option value="Brazil">Brazil</option>
                    <option value="British Indian Ocean Ter">British Indian Ocean Ter</option>
                    <option value="Brunei">Brunei</option>
                    <option value="Bulgaria">Bulgaria</option>
                    <option value="Burkina Faso">Burkina Faso</option>
                    <option value="Burundi">Burundi</option>
                    <option value="Cambodia">Cambodia</option>
                    <option value="Cameroon">Cameroon</option>
                    <option value="Canada">Canada</option>
                    <option value="Canary Islands">Canary Islands</option>
                    <option value="Cape Verde">Cape Verde</option>
                    <option value="Cayman Islands">Cayman Islands</option>
                    <option value="Central African Republic">Central African Republic</option>
                    <option value="Chad">Chad</option>
                    <option value="Channel Islands">Channel Islands</option>
                    <option value="Chile">Chile</option>
                    <option value="China">China</option>
                    <option value="Christmas Island">Christmas Island</option>
                    <option value="Cocos Island">Cocos Island</option>
                    <option value="Colombia">Colombia</option>
                    <option value="Comoros">Comoros</option>
                    <option value="Congo">Congo</option>
                    <option value="Cook Islands">Cook Islands</option>
                    <option value="Costa Rica">Costa Rica</option>
                    <option value="Cote DIvoire">Cote DIvoire</option>
                    <option value="Croatia">Croatia</option>
                    <option value="Cuba">Cuba</option>
                    <option value="Curaco">Curacao</option>
                    <option value="Cyprus">Cyprus</option>
                    <option value="Czech Republic">Czech Republic</option>
                    <option value="Denmark">Denmark</option>
                    <option value="Djibouti">Djibouti</option>
                    <option value="Dominica">Dominica</option>
                    <option value="Dominican Republic">Dominican Republic</option>
                    <option value="East Timor">East Timor</option>
                    <option value="Ecuador">Ecuador</option>
                    <option value="Egypt">Egypt</option>
                    <option value="El Salvador">El Salvador</option>
                    <option value="Equatorial Guinea">Equatorial Guinea</option>
                    <option value="Eritrea">Eritrea</option>
                    <option value="Estonia">Estonia</option>
                    <option value="Ethiopia">Ethiopia</option>
                    <option value="Falkland Islands">Falkland Islands</option>
                    <option value="Faroe Islands">Faroe Islands</option>
                    <option value="Fiji">Fiji</option>
                    <option value="Finland">Finland</option>
                    <option value="France">France</option>
                    <option value="French Guiana">French Guiana</option>
                    <option value="French Polynesia">French Polynesia</option>
                    <option value="French Southern Ter">French Southern Ter</option>
                    <option value="Gabon">Gabon</option>
                    <option value="Gambia">Gambia</option>
                    <option value="Georgia">Georgia</option>
                    <option value="Germany">Germany</option>
                    <option value="Ghana">Ghana</option>
                    <option value="Gibraltar">Gibraltar</option>
                    <option value="Great Britain">Great Britain</option>
                    <option value="Greece">Greece</option>
                    <option value="Greenland">Greenland</option>
                    <option value="Grenada">Grenada</option>
                    <option value="Guadeloupe">Guadeloupe</option>
                    <option value="Guam">Guam</option>
                    <option value="Guatemala">Guatemala</option>
                    <option value="Guinea">Guinea</option>
                    <option value="Guyana">Guyana</option>
                    <option value="Haiti">Haiti</option>
                    <option value="Hawaii">Hawaii</option>
                    <option value="Honduras">Honduras</option>
                    <option value="Hong Kong">Hong Kong</option>
                    <option value="Hungary">Hungary</option>
                    <option value="Iceland">Iceland</option>
                    <option value="Indonesia">Indonesia</option>
                    <option value="India">India</option>
                    <option value="Iran">Iran</option>
                    <option value="Iraq">Iraq</option>
                    <option value="Ireland">Ireland</option>
                    <option value="Isle of Man">Isle of Man</option>
                    <option value="Israel">Israel</option>
                    <option value="Italy">Italy</option>
                    <option value="Jamaica">Jamaica</option>
                    <option value="Japan">Japan</option>
                    <option value="Jordan">Jordan</option>
                    <option value="Kazakhstan">Kazakhstan</option>
                    <option value="Kenya">Kenya</option>
                    <option value="Kiribati">Kiribati</option>
                    <option value="Korea North">Korea North</option>
                    <option value="Korea Sout">Korea South</option>
                    <option value="Kuwait">Kuwait</option>
                    <option value="Kyrgyzstan">Kyrgyzstan</option>
                    <option value="Laos">Laos</option>
                    <option value="Latvia">Latvia</option>
                    <option value="Lebanon">Lebanon</option>
                    <option value="Lesotho">Lesotho</option>
                    <option value="Liberia">Liberia</option>
                    <option value="Libya">Libya</option>
                    <option value="Liechtenstein">Liechtenstein</option>
                    <option value="Lithuania">Lithuania</option>
                    <option value="Luxembourg">Luxembourg</option>
                    <option value="Macau">Macau</option>
                    <option value="Macedonia">Macedonia</option>
                    <option value="Madagascar">Madagascar</option>
                    <option value="Malaysia">Malaysia</option>
                    <option value="Malawi">Malawi</option>
                    <option value="Maldives">Maldives</option>
                    <option value="Mali">Mali</option>
                    <option value="Malta">Malta</option>
                    <option value="Marshall Islands">Marshall Islands</option>
                    <option value="Martinique">Martinique</option>
                    <option value="Mauritania">Mauritania</option>
                    <option value="Mauritius">Mauritius</option>
                    <option value="Mayotte">Mayotte</option>
                    <option value="Mexico">Mexico</option>
                    <option value="Midway Islands">Midway Islands</option>
                    <option value="Moldova">Moldova</option>
                    <option value="Monaco">Monaco</option>
                    <option value="Mongolia">Mongolia</option>
                    <option value="Montserrat">Montserrat</option>
                    <option value="Morocco">Morocco</option>
                    <option value="Mozambique">Mozambique</option>
                    <option value="Myanmar">Myanmar</option>
                    <option value="Nambia">Nambia</option>
                    <option value="Nauru">Nauru</option>
                    <option value="Nepal">Nepal</option>
                    <option value="Netherland Antilles">Netherland Antilles</option>
                    <option value="Netherlands">Netherlands (Holland, Europe)</option>
                    <option value="Nevis">Nevis</option>
                    <option value="New Caledonia">New Caledonia</option>
                    <option value="New Zealand">New Zealand</option>
                    <option value="Nicaragua">Nicaragua</option>
                    <option value="Niger">Niger</option>
                    <option value="Nigeria">Nigeria</option>
                    <option value="Niue">Niue</option>
                    <option value="Norfolk Island">Norfolk Island</option>
                    <option value="Norway">Norway</option>
                    <option value="Oman">Oman</option>
                    <option value="Pakistan">Pakistan</option>
                    <option value="Palau Island">Palau Island</option>
                    <option value="Palestine">Palestine</option>
                    <option value="Panama">Panama</option>
                    <option value="Papua New Guinea">Papua New Guinea</option>
                    <option value="Paraguay">Paraguay</option>
                    <option value="Peru">Peru</option>
                    <option value="Phillipines">Philippines</option>
                    <option value="Pitcairn Island">Pitcairn Island</option>
                    <option value="Poland">Poland</option>
                    <option value="Portugal">Portugal</option>
                    <option value="Puerto Rico">Puerto Rico</option>
                    <option value="Qatar">Qatar</option>
                    <option value="Republic of Montenegro">Republic of Montenegro</option>
                    <option value="Republic of Serbia">Republic of Serbia</option>
                    <option value="Reunion">Reunion</option>
                    <option value="Romania">Romania</option>
                    <option value="Russia">Russia</option>
                    <option value="Rwanda">Rwanda</option>
                    <option value="St Barthelemy">St Barthelemy</option>
                    <option value="St Eustatius">St Eustatius</option>
                    <option value="St Helena">St Helena</option>
                    <option value="St Kitts-Nevis">St Kitts-Nevis</option>
                    <option value="St Lucia">St Lucia</option>
                    <option value="St Maarten">St Maarten</option>
                    <option value="St Pierre & Miquelon">St Pierre & Miquelon</option>
                    <option value="St Vincent & Grenadines">St Vincent & Grenadines</option>
                    <option value="Saipan">Saipan</option>
                    <option value="Samoa">Samoa</option>
                    <option value="Samoa American">Samoa American</option>
                    <option value="San Marino">San Marino</option>
                    <option value="Sao Tome & Principe">Sao Tome & Principe</option>
                    <option value="Saudi Arabia">Saudi Arabia</option>
                    <option value="Senegal">Senegal</option>
                    <option value="Seychelles">Seychelles</option>
                    <option value="Sierra Leone">Sierra Leone</option>
                    <option value="Singapore">Singapore</option>
                    <option value="Slovakia">Slovakia</option>
                    <option value="Slovenia">Slovenia</option>
                    <option value="Solomon Islands">Solomon Islands</option>
                    <option value="Somalia">Somalia</option>
                    <option value="South Africa">South Africa</option>
                    <option value="Spain">Spain</option>
                    <option value="Sri Lanka">Sri Lanka</option>
                    <option value="Sudan">Sudan</option>
                    <option value="Suriname">Suriname</option>
                    <option value="Swaziland">Swaziland</option>
                    <option value="Sweden">Sweden</option>
                    <option value="Switzerland">Switzerland</option>
                    <option value="Syria">Syria</option>
                    <option value="Tahiti">Tahiti</option>
                    <option value="Taiwan">Taiwan</option>
                    <option value="Tajikistan">Tajikistan</option>
                    <option value="Tanzania">Tanzania</option>
                    <option value="Thailand">Thailand</option>
                    <option value="Togo">Togo</option>
                    <option value="Tokelau">Tokelau</option>
                    <option value="Tonga">Tonga</option>
                    <option value="Trinidad & Tobago">Trinidad & Tobago</option>
                    <option value="Tunisia">Tunisia</option>
                    <option value="Turkey">Turkey</option>
                    <option value="Turkmenistan">Turkmenistan</option>
                    <option value="Turks & Caicos Is">Turks & Caicos Is</option>
                    <option value="Tuvalu">Tuvalu</option>
                    <option value="Uganda">Uganda</option>
                    <option value="United Kingdom">United Kingdom</option>
                    <option value="Ukraine">Ukraine</option>
                    <option value="United Arab Erimates">United Arab Emirates</option>
                    <option value="United States of America">United States of America</option>
                    <option value="Uraguay">Uruguay</option>
                    <option value="Uzbekistan">Uzbekistan</option>
                    <option value="Vanuatu">Vanuatu</option>
                    <option value="Vatican City State">Vatican City State</option>
                    <option value="Venezuela">Venezuela</option>
                    <option value="Vietnam">Vietnam</option>
                    <option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
                    <option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
                    <option value="Wake Island">Wake Island</option>
                    <option value="Wallis & Futana Is">Wallis & Futana Is</option>
                    <option value="Yemen">Yemen</option>
                    <option value="Zaire">Zaire</option>
                    <option value="Zambia">Zambia</option>
                    <option value="Zimbabwe">Zimbabwe</option>
                    </select>
                    <input class="www-button www-button-grey" name="countryset" type="submit" value="Set">
                </form><br><hr class="thin-line">
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
                <h1>Color Pallete</h1>
                <b>Primary Color</b><br>
                <span style="font-size: 11px;display: inline-block;width: 256px;" class="grey-text">This will change the text color of your channel ribbon.</span>
                <form method="post" id="primarycolor" style="float: right;position: relative;bottom: 15px;" action="/post/channel_update_new" enctype="multipart/form-data">
                    <input type="color" id="solidcolor" name="solidcolor" value="<?php echo htmlspecialchars($_user['primary_color']); ?>">
                    <input class="www-button www-button-grey" name="primary" type="submit" value="Set">
                </form><br><br><hr class="thin-line">
                <b>Channel Box Color</b><br>
                <span style="font-size: 11px;display: inline-block;width: 256px;" class="grey-text">This will change the background color of the channel info box and the channel ribbon at top.</span><br>
                <form method="post" id="channelboxcolor" style="float: right;position: relative;bottom: 30px;" action="/post/channel_update_new" enctype="multipart/form-data">
                    <input type="color" id="solidcolor" name="solidcolor" value="<?php echo htmlspecialchars($_user['secondary_color']); ?>">
                    <input class="www-button www-button-grey" name="secondary" type="submit" value="Set">
                </form><br><br><hr class="thin-line">
                <b>Border Color</b><br>
                <span style="font-size: 11px;display: inline-block;width: 256px;" class="grey-text">This will change the border color of all the elements.</span><br>
                <form method="post" id="bordercolor" style="float: right;position: relative;bottom: 30px;" action="/post/channel_update_new" enctype="multipart/form-data">
                    <input type="color" id="bordercolor" name="bordercolor" value="<?php echo htmlspecialchars($_user['border_color']); ?>">
                    <input class="www-button www-button-grey" name="border" type="submit" value="Set">
                </form><br><br><hr class="thin-line">
            </td>
            <td style="vertical-align: top;     padding-left: 94px; width: 432px;position: relative;bottom: 2px;">
                <br><br>
                <b>Background Color</b><br>
                <span style="font-size: 11px;display: inline-block;width: 256px;" class="grey-text">This will change the background of all the other boxes including the top featured area.</span><br>
                <form method="post" id="boxbackgroundcolor" style="float: right;position: relative;bottom: 30px;" action="/post/channel_update_new" enctype="multipart/form-data">
                    <input type="color" id="solidcolor" name="solidcolor" value="<?php echo htmlspecialchars($_user['third_color']); ?>">
                    <input class="www-button www-button-grey" name="thirdb" type="submit" value="Set">
                </form><br><br><hr class="thin-line">
                <!--
                <b>Text Color</b><br>
                <span style="font-size: 11px;" class="grey-text">This will change the color of the box ribbons.</span><br>
                <form method="post" action="/post/channel_update_new" enctype="multipart/form-data">
                    Choose a color: <input type="color" id="solidcolor" name="solidcolor" value="<?php echo htmlspecialchars($_user['text_color']); ?>"><br>
                    <input class="www-button www-button-grey" name="textcolor" type="submit" value="Set">
                </form><br><br>
                -->
                <b>Text Main Color</b><br>
                <span style="font-size: 11px;display: inline-block;width: 256px;" class="grey-text">This will change the color of the text for boxes.</span><br>
                <form method="post" id="textmaincolor" style="float: right;position: relative;bottom: 30px;" action="/post/channel_update_new" enctype="multipart/form-data">
                    <input type="color" id="solidcolor" name="solidcolor" value="<?php echo htmlspecialchars($_user['primary_color_text']); ?>">
                    <input class="www-button www-button-grey" name="textprimarycolor" type="submit" value="Set">
                </form><br><br><hr class="thin-line">
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
                <h1>Background</h1>
                <b>Background Options</b> <br>
                <span style="font-size: 11px;" class="grey-text">Choose how your background will be displayed.</span><br>
                <form id="backgroundoptions" method="post" action="/post/channel_update_new" enctype="multipart/form-data">
                    <select style="position: relative;bottom: -3px;background: transparent url(/static/img/spritesheet_main.png) repeat-x scroll 0 -800px;border: 1px solid #333;"  name="bgoption" id="ifyouarereadingthisYOUSUCK">
                        <option value="repeaty">Repeat - Y</option>
                        <option value="repeatx">Repeat - X</option>
                        <option value="norepeat">No Repeat</option>
                        <option value="repeatxy">Repeat - X and Y</option>
                        <option value="stretch">Stretch</option>
                        <option value="solid">Solid</option>
                    </select>
                    <div style="float: right;">
                        <input style="position: relative;bottom: 3px;" type="color" id="solidcolor" name="solidcolor" value="<?php echo htmlspecialchars($_user['2009_bgcolor']); ?>">
                        <input style="position: relative;bottom: 4px;" class="www-button www-button-grey" name="bgoptionset" type="submit" value="Set">
                    </div>
                </form><br><br>

                <hr class="thin-line">
            </td>
            <td style="vertical-align: top;     padding-left: 94px; width: 432px;">
                <h1>&nbsp;</h1>
                <b>Background</b> <br>
                <span style="font-size: 11px;" class="grey-text">Choose Image (Max file size: 10MB)</span><br>
                <form id="backgroundimage" method="post" action="/post/channel_update_new" enctype="multipart/form-data">
                    <input type="file" name="fileToUpload" id="background-upload">
                    <!--<button class="www-button www-button-grey" id="av-uplod">Select File</button>-->
                    <input class="www-button www-button-grey" type="submit" value="Upload Background" name="bgset">
                </form><br><br> 

                <hr class="thin-line">
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
                <h1>Layout</h1>
                <b>Left Side</b><br>
                <span style="font-size: 11px;" class="grey-text">Choose how your left part of your channel will be displayed.<br>MODULES: main, stat, subscription, recent, subscribers, friends, discussion, channels, custom</span><br>
                <form id="leftlayoutset" method="post" action="/post/channel_update_new" enctype="multipart/form-data">
                    <input style="padding: 5px;background-color: white;border: 1px solid #999;width: 345px;height: 12px;" id="biomd" placeholder="Modules" value="<?php echo htmlspecialchars($_user['s_2009_user_left']);?>" name="left">
                    <input class="www-button www-button-grey" name="leftset" type="submit" value="Set"> 
                </form><br><br>
                <hr class="thin-line"> 

                <b>Custom Header</b><br>
                <span style="font-size: 11px;" class="grey-text">This will set the header in the custom module named "custom"</span><br>
                <form method="post" id="featuredvid" action="/post/channel_update_new" enctype="multipart/form-data">
                <input style="padding: 5px;background-color: white;border: 1px solid #999;width: 345px;height: 12px;" id="biomd" placeholder="Custom Header" value="<?php echo htmlspecialchars($_user['custom_header']);?>" name="header">
                    <input class="www-button www-button-grey" name="headerset" type="submit" value="Set">
                </form><br><hr class="thin-line">

                <b>Featured Channels</b><br>
                <span style="font-size: 11px;" class="grey-text">This will set the list of featured channels in the custom module named "channels"<br>SEPERATE BY COMMAS, ex: bhief,SubRocks,SubHelp</span><br>
                <form method="post" id="featuredvid" action="/post/channel_update_new" enctype="multipart/form-data">
                <input style="padding: 5px;background-color: white;border: 1px solid #999;width: 345px;height: 12px;" id="biomd" placeholder="Channels" value="<?php echo htmlspecialchars($_user['featured_channels']);?>" name="channels">
                    <input class="www-button www-button-grey" name="channelsset" type="submit" value="Set">
                </form><br><hr class="thin-line">
            </td>
            <td style="vertical-align: top;     padding-left: 94px; width: 432px;">
                <h1>&nbsp;</h1>
                <b>Right Side</b><br>
                <span style="font-size: 11px;" class="grey-text">Choose how your right part of your channel will be displayed.<br>MODULES: main, stat, subscription, recent, subscribers, friends, discussion, channels, custom</span><br>
                <form id="rightlayoutset" method="post" action="/post/channel_update_new" enctype="multipart/form-data">
                    <input style="padding: 5px;background-color: white;border: 1px solid #999;width: 345px;height: 12px;" id="biomd" placeholder="Modules" value="<?php echo htmlspecialchars($_user['s_2009_user_right']);?>" name="right">
                    <input class="www-button www-button-grey" name="rightset" type="submit" value="Set"> 
                </form><br><br>
                <hr class="thin-line"> 

                <b>Custom Text</b><br>
                <span style="font-size: 11px;" class="grey-text">This will set the text in the custom module named "custom"</span><br>
                <form method="post" id="bio" action="/post/channel_update_new" enctype="multipart/form-data">
                    <textarea style="width: 345px;padding: 0px;background-color:white;border: 1px solid #d3d3d3;" id="biomd" placeholder="Custom Text" name="customtext"><?php echo htmlspecialchars($_user['custom_text']); ?></textarea><br><br>
                    <input class="www-button www-button-grey" style="margin: 0px;" name="customtextset" type="submit" value="Set">
                </form><br><br><hr class="thin-line">
            </td>
        </tr>
    </table>
</div>
<script>
var alerts = 0; 
$('#leftlayoutset' ).submit(
    function( e ) {
        var data = new FormData(this);
        jQuery.each(jQuery('#fileToUpload')[0].files, function(i, file) {
            data.append('file-'+i, file);
        });

        $.ajax( {
            url: '/post/channel_update_new',
            type: 'POST',
            data: data,
            cache: false,
            processData: false,
            contentType: false,
            success: function(result){
                alerts++;
                addAlert("editsuccess_" + alerts, "Successfully updated your channel!");
                showAlert("#editsuccess_" + alerts);
                console.log("DEBUG: " + result);
            }
        } );
        e.preventDefault();
    } 
);
</script>
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