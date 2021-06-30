<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/important/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/lib/new/base.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/lib/new/fetch.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/lib/new/insert.php"); ?>
<?php
    require __DIR__ . '/vendor/autoload.php';

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
        <style>
            .progress-bar, .password-verdict {
                background: aliceblue;
                margin-top: 10px;
            }
            </style>
    </head>
    <body>
        <div class="www-core-container">
            <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/header.php"); ?>
            <div class="sign-in-outer-box">
                <div class="sign-in-form-box">
                <form action="" method="post" id="submitform">
                    <span style="color: red; font-size: 12px;" id="pwwarnings"></span><span style="color: red; font-size: 12px;" id="specialchars"></span>
                    <?php  
                        $gump = new GUMP();

                        if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['password'] && $_POST['username']) {
                            $email = htmlspecialchars(@$_POST['email']);
                            $username = htmlspecialchars(@$_POST['username']);
                            $password = @$_POST['password'];
                            $passwordhash = password_hash(@$password, PASSWORD_DEFAULT);

                            // 1st array is rules definition, 2nd is field-rule specific error messages (optional)
                            $is_valid = GUMP::is_valid(array_merge($_POST, $_FILES), [
                                'username' => ['required', 'alpha_numeric'],
                                'password' => ['required', 'between_len' => [6, 100]],
                                'email'    => ['required', 'valid_email']
                            ], [
                                'username' => ['required' => 'Fill the Username field please.'],
                                'password' => ['between_len' => '{field} must be between {param[0]} and {param[1]} characters.'],
                                'avatar'   => ['extension' => 'Valid extensions for avatar are: {param}'] // "png, jpg"
                            ]);

                            if ($is_valid === true) {                        
                                $stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
                                $stmt->bind_param("s", $username);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if($result->num_rows) { $error = "there's already a user with that same name!"; goto skip; }
                        
                                if($_user_insert_utils->register($username, $email, $passwordhash)) {
                                    $_SESSION['siteusername'] = htmlspecialchars($username);
                                    echo "<script>
                                        window.location = '/';
                                    </script>";
                                } else {
                                    $error = array("There was an unknown error making your account.");
                                }
                            } else {
                                $error = $is_valid; // array of error messages
                            }
                        }
                        skip:
                    ?>
                    <?php 
                        if(isset($error)) { 
                            foreach($error as $errorMessage) {
                                echo $errorMessage . "<br>";
                            }
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