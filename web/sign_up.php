<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/important/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/lib/new/base.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/lib/new/fetch.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/lib/new/insert.php"); ?>
<?php
    $_user_fetch_utils = new user_fetch_utils();
    $_user_insert_utils = new user_insert_utils();
    $_video_fetch_utils = new video_fetch_utils();
    $_base_utils = new config_setup();
    
    $_base_utils->initialize_db_var($conn);
    $_video_fetch_utils->initialize_db_var($conn);
    $_user_insert_utils->initialize_db_var($conn);
    $_user_fetch_utils->initialize_db_var($conn);

    $_base_utils->initialize_page_compass("Sign Up");
?>
<!DOCTYPE html>
<html>
    <head>
        <title>SubRocks - <?php echo $_base_utils->return_current_page(); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/static/css/new/www-core.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script type="text/javascript" src="/dist/pwstrength-bootstrap.min.js"></script>
        <script src='https://www.google.com/recaptcha/api.js' async defer></script>
        <style>
            .progress-bar, .password-verdict {
                background: aliceblue;
                margin-top: 10px;
            }
        </style>
        <script>function onLogin(token){ document.getElementById('submitform').submit(); }</script>
    </head>
    <body>
        <div class="www-core-container">
            <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/header.php"); ?>
            <div class="sign-in-outer-box">
                <div class="sign-in-form-box">
                <form action="" method="post" id="submitform">
                    <span style="color: red; font-size: 12px;" id="pwwarnings"></span><span style="color: red; font-size: 12px;" id="specialchars"></span>
                    <?php  
                        if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['password'] && $_POST['username']) {
                            $email = htmlspecialchars(@$_POST['email']);
                            $username = trim(htmlspecialchars(@$_POST['username']));
                            $password = @$_POST['password'];
                            $passwordhash = password_hash(@$password, PASSWORD_DEFAULT);
                            $error = array();
            
                            if(strlen($username) > 21) { $error['message'] = "Your username must be shorter than 21 characters."; $error['status'] = true; }
                            if(strlen($password) < 8) { $error['message'] = "Your password must at least be 8 characters long."; $error['status'] = true; }
                            if(!preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/', $password)) { $error['message'] = "Include numbers and letters in your password!"; $error['status'] = true; }
                            if(preg_match('/[\'^£$.\/%&*()}{@#~?><>,|=_+¬-]/', $username)) { $error['message'] = "Your username cannot contain any special characters!"; $error['status'] = true; }
                            if(empty($username)) { $error['message'] = "Your username cannot be empty!"; $error['status'] = true; }
                            if(!isset($_POST['g-recaptcha-response'])){ $error['message'] = "captcha validation failed"; $error['status'] = true; }
                            if(!$_user_insert_utils->validateCaptcha($config['recaptcha_secret'], $_POST['g-recaptcha-response'])) { $error['message'] = "captcha validation failed"; $error['status'] = true; }
                            
                            if(!isset($error['message'])) {
                                $stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
                                $stmt->bind_param("s", $username);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if($result->num_rows) { $error['message'] = "There's already a user with that same username!"; $error['status'] = true; }
                                
                                if(!isset($error['message'])) {
                                    if($_user_insert_utils->register($username, $email, $passwordhash)) {
                                        $_SESSION['siteusername'] = htmlspecialchars($username);
                                        echo "<script>
                                        window.location = '/';
                                    </script>";
                                    } else {
                                        $error['message'] = "There was an unknown error making your account.";
                                    }
                                }
                            }
                        }
                    ?>
                    <?php 
                        if(isset($error['message'])) { 
                            echo $error['message'] . "<br>";
                        } 
                    ?>
                    <table>
                        <tbody>
                            <tr class="username">
                                <td class="label"><label for="username"> Username :</label></td>
                            <td class="input"><input style="border: 1px solid #a0a0a0; padding: 3px;" name="username" type="text" required id="username"></td>
                            </tr>
                            <tr class="email">
                                <td class="label"><label for="email"> E-Mail: </label></td>
                                <td class="input"><input style="border: 1px solid #a0a0a0; padding: 3px;" type="email" name="email" required id="email"></td>
                            </tr>
                            <tr class="password">
                                <td class="label"><label for="password"> Password: </label></td>
                                <td class="input"><input style="border: 1px solid #a0a0a0; padding: 3px;" name="password" type="password" required id="password"></td>
                            </tr>
                            <tr class="remember">


                            <script>
                                var pwwarnings = document.getElementById("pwwarnings");
                                var specialchars = document.getElementById("specialchars");

                                document.getElementById("username").onkeyup = () => {
                                    /*
                                    if (/\s/.test(document.getElementById("username").value)) {
                                        pwwarnings.innerHTML = "Your username cannot contain spaces.<br>";
                                        console.log("!");
                                    } else {
                                        pwwarnings.innerHTML = "";
                                    }
                                    */
                                    

                                    if (/[~`!@#$%\^&*+=\-\[\]\\';,/{}|\\":<>\?]/g.test(document.getElementById("username").value)) {
                                        specialchars.innerHTML = "Your username cannot contain special characters.<br>";
                                    } else {
                                        specialchars.innerHTML = "";
                                    }
                                };
                            </script>
                            </tr>
                                <tr class="buttons">
                                <td colspan="2"><br><input type="submit" id="search-button" value="Create Account" class="g-recaptcha" data-sitekey="<?php echo $config['recaptcha_sitekey']; ?>" data-callback="onLogin">
                                </td>
                            </tr>
                            <tr class="forgot">
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <b>Join the rockiest video-sharing community!</b><br>
            Sign up now to get full access with your SubRocks account:
            <ul>
                <li>Comment, rate, and make video responses to your favorite videos</li>
                <li>Upload and share your videos with millions of other users</li>
                <li>Save your favorite videos to watch and share later</li>
                <li>Enter your videos into contests for fame and prizes</li>
            </ul>
        </div>
        <div class="www-core-container">
        <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/footer.php"); ?>
        </div>

        <script>
            $('#password').pwstrength({
                ui: { showVerdictsInsideProgressBar: true }
            });
        </script>
    </body>
</html>