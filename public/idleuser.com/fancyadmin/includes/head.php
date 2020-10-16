<title><?php echo $title; ?></title>
<link rel="apple-touch-icon" sizes="180x180" href="/assets/images/favicon-180x180.png">
<link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon-16x16.png">
<link rel="shortcut icon" href="/assets/images/favicon.ico">
<link rel="manifest" href="/assets/images/site.webmanifest">	
<link href="/assets/bootstrap-4.5.2-dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet" type='text/css'>
<link href="assets/css/custom.css" rel="stylesheet">

<?php 
    $meta = [
        "og:title" => $title,
        "og:description" => "Admin page for idleuser.com"
    ];
    echo page_meta($meta);
?>