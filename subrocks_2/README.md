# ~~FulpTube~~ SubRocks Dockerized

copy `env.template` to `.env` and edit it to specify the mysql root password,

copy `www/static/important/config.sample.php` to `www/static/important/config.inc.php` and edit the contents inside

then just run `docker-compose up --build` to start up a brand new subrox instance!!1

unfortunately, php, phpmyadmin, AND the queuer all may start before mysql finishes initializing.

i can't do much about php and phpmyadmin (the connections are done per-page so it doesn't matter anyway, the user will just have to refresh for a bit), but I have added extra handling in a new version of the queuer packaged with this dockerized version of fulptube to help with this.

this seems to ALMOST work? do NOT put this in production as of now.

