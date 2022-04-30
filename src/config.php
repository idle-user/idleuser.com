<?php
$configs = array(

    'TIMEZONE' => $_ENV['TZ'] ?? date_default_timezone_get(),

    'DOMAIN'         => $_ENV['DOMAIN'],
    'WEBSITE'        => $_ENV['WEBSITE'],
    'ADMIN_EMAIL'    => $_ENV['ADMIN_EMAIL'],
    'NO-REPLY_EMAIL' => $_ENV['NOREPLY_EMAIL'],

    'API_URL' =>  $_ENV['API_URL'],

    'DB_HOST'   => $_ENV['MYSQL_HOST'],
    'DB_NAME'   => $_ENV['MYSQL_DATABASE'],
    'DB_USER'   => $_ENV['MYSQL_USER'],
    'DB_SECRET' => $_ENV['MYSQL_PASSWORD'],

    # Google reCaptcha
    'RECAPTCHA_V2_SITEKEY' => $_ENV['RECAPTCHA_V2_SITEKEY'],
    'RECAPTCHA_V2_SECRET'  => $_ENV['RECAPTCHA_V2_SECRET'],
    'RECAPTCHA_V3_SITEKEY' => $_ENV['RECAPTCHA_V3_SITEKEY'],
    'RECAPTCHA_V3_SECRET'  => $_ENV['RECAPTCHA_V3_SECRET'],

);
?>
