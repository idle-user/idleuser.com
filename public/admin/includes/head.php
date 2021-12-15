<title><?php echo $title; ?></title>
<link rel="apple-touch-icon" sizes="180x180" href="/assets/images/favicon-180x180.png">
<link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon-16x16.png">
<link rel="shortcut icon" href="/assets/images/favicon.ico">
<link rel="manifest" href="/assets/images/site.webmanifest">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto" type='text/css'>
<link rel="stylesheet" href="assets/css/custom.css">

<?php
    $meta = [
        "og:title" => $title,
        "og:description" => "Admin page for " . $configs['DOMAIN']
    ];
    echo page_meta($meta);
?>
