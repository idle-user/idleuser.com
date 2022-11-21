<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <a class="navbar-brand" href="/" title="Home">
        <picture>
            <source srcset="/assets/images/favicon-32x32.webp" type="image/webp" alt="idleuser.com Logo" class="img-fluid" width="32" height="32">
            <img src="/assets/images/favicon-32x32.png" alt="idleuser.com Logo" class="img-fluid" width="32" height="32" loading="lazy">
        </picture>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNavbar"
            aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainNavbar">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="/">Home<span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/#education">Experience<span class="sr-only"></span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/#services">Services<span class="sr-only"></span></a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="/#education" id="projectDropdown" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false" ignore-smoothscroll>Projects</a>
                <div class="dropdown-menu" aria-labelledby="projectDropdown">
                    <a class="dropdown-item" href="/projects/matches/" target="_blank">Matches</a>
                    <a class="dropdown-item" href="/projects/create-a-poll/" target="_blank">Create-a-Poll</a>
                </div>
            </li>
        </ul>

        <ul class="navbar-nav ml-md-auto mr-3">
            <li class="nav-item">
                <a class="nav-link" href="https://www.twitch.tv/idle_user" target="_blank" title="Twitch">
                    <i class="fab fa-twitch"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="https://twitter.com/an_idle_user" target="_blank" title="Twitter">
                    <i class="fab fa-twitter"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="https://discord.gg/U5wDzWP8yD" target="_blank" title="Discord">
                    <i class="fab fa-discord"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="https://github.com/idle-user" target="_blank" title="GitHub">
                    <i class="fab fa-github"></i>
                </a>
            </li>
        </ul>

        <?php if ($_SESSION['loggedin']) { ?>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="accountDropdown" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION['profile']['username'] ?></a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="accountDropdown">
                        <?php if ($_SESSION['profile']['access'] > 1) { ?>
                            <a class="dropdown-item" href="/admin/">Admin</a>
                        <?php } ?>
                        <a class="dropdown-item" href="/account/">Settings</a>
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">Logout</a>
                    </div>
                </li>
            </ul>
        <?php } else { ?>
            <a href="/login" class="btn btn-secondary my-2 my-sm-0">Login</a>
        <?php } ?>
    </div>
</nav>

<?php if ($_SESSION['loggedin']) { ?>
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Logout of
                        account: <?php echo $_SESSION['profile']['username'] ?>?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to logout?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <a href="/logout" class="btn btn-primary">Logout</a>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
