<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/important/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/lib/new/base.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/lib/new/fetch.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/lib/new/insert.php"); ?>
<?php
    $_user_fetch_utils = new user_fetch_utils();
    $_video_fetch_utils = new video_fetch_utils();
    $_video_insert_utils = new video_insert_utils();
    $_user_insert_utils = new user_insert_utils();
    $_base_utils = new config_setup();
    
    $_base_utils->initialize_db_var($conn);
    $_video_fetch_utils->initialize_db_var($conn);
    $_video_insert_utils->initialize_db_var($conn);
    $_user_fetch_utils->initialize_db_var($conn);
    $_user_insert_utils->initialize_db_var($conn);

    $_base_utils->initialize_page_compass("Blog");
?>
<!DOCTYPE html>
<html>
    <head>
        <title>SubRocks - <?php echo $_base_utils->return_current_page(); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/static/css/new/www-core.css">
    </head>
    <body>
        <div class="www-core-container">
            <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/header.php"); ?>
            <div style="min-height: 300px;">
            <h1>YTPMV/YTP/SRP/SRPMV!!!</h1> <small>(Fri, 9 Jul)</small><hr class="thin-line"><br>
                MAKE A COOL ASS YTP/YTPMV.... YTPMV PREFERRED BUT IF YOU DO MAKE A YTPMV DONT MAKE IT SOUND LIKE ABSOLUTE SHIT!!!<br>
                And most importantly, have fun!<br><br>

                (Make sure to suffix the title of your submission(s) with "[CONTEST]" (with no quotes) or I won't put them into the rating bin)<br><br>
                <a href="/search_query?q=[CONTEST]">View All Contest Submissions</a>

                (ends @ august 1st)<br><br><hr class="thin-line">
                <h2>Spanish Version</h2>
                HAZ UN YTP/YTPMV GENIAL.... YTPMV PREFERIDO PERO SI HACES UN YTPMV NO HAGAS QUE SEA UNA MIERDA ABSOLUTA!!!<br>
                Y lo más importante, ¡diviértete! <br><br>

                (Asegúrate de poner como sufijo en el título de tu(s) envío(s) "[CONCURSO]" (sin comillas) o no lo(s) pondré en el cajón de la clasificación)<br><br>
                <a href="/search_query?q=[CONTEST]">Ver todas las presentaciones del concurso</a>

                (termina el 1 de agosto)
            </div>
            <i>...and then there was nothing...</i>
        </div>
        <div class="www-core-container">
        <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/module/footer.php"); ?>
        </div>

    </body>
</html>