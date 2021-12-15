<?php
$configs = array(

    # TIMEZONE
    'TIMEZONE' => isset($_ENV['TZ']) ? $_ENV['TZ'] : date_default_timezone_get(),

    # Domain
    'DOMAIN' => 'example.com',

    # Website
    'WEBSITE' => "https://example.com/",

    # Admin Email
    'ADMIN_EMAIL' => 'admin@example.com',

    # No-Reply Email
    'NO-REPLY_EMAIL' => 'no-reply@example.com',

    # API
    'API_URL' => '',

    # MySQL
    'DB_HOST' =>   '',
    'DB_USER' =>   '',
    'DB_SECRET' => '',
    'DB_NAME' =>   '',

    # reCaptcha
    'RECAPTCHA_V2_SITEKEY' =>  '',
    'RECAPTCHA_V2_SECRET' =>   '',
    'RECAPTCHA_V3_SITEKEY' =>  '',
    'RECAPTCHA_V3_SECRET' =>   '',
);
?>
