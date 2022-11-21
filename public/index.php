<?php require_once getenv('APP_PATH') . '/src/session.php';
set_last_page();
?>
<!doctype html>
<html lang="en">
<head>
    <title>Jesse's Website</title>
    <meta name="google-site-verification" content="W6CApNdGsK6IEjel5CeMTOACliRAZOFeptIX9ABJfqs"/>
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/favicon-180x180.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon-16x16.png">
    <link rel="shortcut icon" href="/assets/images/favicon.ico">
    <link rel="manifest" href="/assets/images/site.webmanifest">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
          integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto&display=swap" type='text/css'>

    <?php
    $meta = [
        'og:title' => "Jesse's Personal Website",
        'og:description' =>
            "I am a developer, programmer, and project manager. When I'm not working, I spend my time tinkering with personal side projects.",
    ];
    echo page_meta($meta);
    ?>

    <style>
        body {
            font-family: 'Roboto', sans-serif !important;
            background-image: linear-gradient(to bottom, white, lightsteelblue, white);
        }
    </style>
</head>
<body>

<?php include 'includes/nav.php'; ?>

<main role="main">

    <div id="about" class="jumbotron text-center bg-transparent">
        <div class="container">
            <h1 class="display-4">Hello &amp; Welcome</h1>
            <picture>
                <source class="rounded-circle" srcset="/assets/images/red.webp" type="image/webp" alt="Jesse" width="180" height="180">
                <img class="rounded-circle" src="/assets/images/red.png" alt="Jesse" width="180" height="180" loading="lazy">
            </picture>
            <h2>I'm Jesse.</h2>
            <p>
                I am a developer, programmer, and project manager.
                <br/>When I'm not working, I spend my time tinkering with personal side projects.
            </p>
            <p><a class="btn btn-secondary shadow" href="#education" role="button">Learn more about me</a></p>
        </div>
    </div>

    <div id="education" class="container rounded text-center p-5 bg-transparent">
        <h2 class="text-center pb-3">Education &amp; Experience</h2>
        <div class="row">
            <div class="col-lg-4 p-2">
                <div class="card p-4 shadow">
                    <div style="display:inline-block; height:100px">
                        <p class="display-3"><i class="fas fa-graduation-cap"></i></p>
                    </div>
                    <h3 class="card-title">Education</h3>
                    <p class="card-text"><i>Bachelor of Science</i>, Computer Science<br/>2016</p>
                    <br/><br/>
                </div>
            </div>
            <div class="col-lg-4 p-2">
                <div class="card p-4 shadow">
                    <div style="display:inline-block; height:100px">
                        <p class="display-3"><i class="fas fa-briefcase"></i></p>
                    </div>
                    <h3 class="card-title">Experience</h3>
                    <p class="card-text">
                        <?php $currentYear = (int)date("Y"); ?>
                        <?php echo $currentYear - 2015 ?> years of web development<br/>
                        <?php echo $currentYear - 2016 ?> years of software development<br/>
                        <?php echo $currentYear - 2016 ?> years of database management<br/>
                        4 years of project management<br/>
                        7 years of customer service<br/>
                    </p>
                </div>
            </div>
            <div class="col-lg-4 p-2">
                <div class="card p-4 shadow">
                    <div style="display:inline-block; height:100px">
                        <p class="display-3"><i class="fas fa-cubes"></i></p>
                    </div>
                    <h3 class="card-title">Skills</h3>
                    <p class="card-text">Python, Java, C, SQL, PHP, Javascript, Linux, Bash, Web DevOps</p>
                    <br/><br/>
                </div>
            </div>
        </div>
    </div>

    <div id="projects" class="container rounded text-center p-5">
        <h2 class="text-center pb-3">Some of my Projects</h2>
        <div class="row">
            <div class="col-lg-4">
                <div style="display:inline-block; height:100px">
                    <p class="display-3"><i class="fas fa-trophy"></i>
                </div>
                <h2>Matches</h2>
                <p>Wager points against others on upcoming wrestling matches. Rank up on the leaderboard and rate your
                    favorite matches.</p>
                <p>
                    <a class="btn btn-secondary shadow" href="/projects/matches/" target="_blank" role="button">
                        Visit Page
                    </a>
                </p>
            </div>
            <div class="col-lg-4">
                <div style="display:inline-block; height:100px">
                    <p class="display-3"><i class="fas fa-poll-h"></i>
                </div>
                <h2>Create-a-Poll</h2>
                <p>Pineapple on pizza? Peanut butter on burgers? Quickly create and share a poll topic with others.</p>
                <p>
                    <a class="btn btn-secondary shadow" href="/projects/create-a-poll/" target="_blank" role="button">
                        Visit Page
                    </a>
                </p>
            </div>
            <div class="col-lg-4">
                <div style="display:inline-block; height:100px">
                    <p class="display-3"><i class="fas fa-comments"></i>
                </div>
                <h2>IdleBot (Chat Bot)</h2>
                <p>A customized chatbot used across Discord, Chatango, and Twitter. Used in conjunction with
                    <a href="/projects/matches/">Matches</a>.<i> (deprecated)</i>
                </p>
                <p>
                    <a class="btn btn-secondary shadow" href="/projects/fjbot/" target="_blank" role="button">
                        Visit Documentation
                    </a>
                </p>
            </div>
            <p class="container pt-3"><a class="btn btn-secondary btn-lg shadow" href="https://github.com/idle-user"
                                         target="_blank" role="button">Other Projects</a></p>

        </div>
    </div>

    <div id="services" class="container rounded text-center p-5 mb-5 bg-light shadow">
        <h2 class="text-center pb-3">My Services</h2>
        <div class="row">
            <div class="col-lg-4">
                <h2>Consultant</h2>
                <p>Whether you're creating your first website or troubleshooting a software bug, I can be there to help
                    at any step of the way. I have <strong>years of experience</strong> in database management,
                    well-versed in many programming languages, and have personally designed, developed and hosted
                    websites from scratch.</p>
            </div>
            <div class="col-lg-4">
                <h2>Web Applications</h2>
                <p>Do you have mundane or repetitive tasks that you know could be automated? Tell me about it, and I
                    will create a web application to meet your requirements.</p>
            </div>
            <div class="col-lg-4">
                <h2>Discord Bot</h2>
                <p>Discord chat is on the rise. Start your own Discord server and include your own <strong>custom
                        bot</strong> to fit your needs - whatever they may be. User mangement, customized greetings,
                    channel chat logs, scheduled alerts, and much more can be offered.</p>
            </div>
            <p class="container pt-3">
                <a class="btn btn-secondary btn-lg shadow" href="/contact" role="button">Contact Me</a>
            </p>
        </div>
    </div>

</main>

<?php include 'includes/footer.php'; ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
</body>
</html>
