<?php include '../includes/nav.php'; ?>

<div class="nav-scroller bg-white shadow-sm">
    <ul class="nav nav-underline mr-auto">
        <li class="nav-item">
            <a class="nav-link dropdown-toggle" id="settingsDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Settings</a>
            <div class="dropdown-menu" aria-labelledby="settingsDropdown">
                <a class="nav-link" href="/account/">Account</a>
                <a class="nav-link" href="connections">Connections</a>
                <a class="nav-link" href="api">API</a>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link dropdown-toggle" id="matchesDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Matches</a>
            <div class="dropdown-menu" aria-labelledby="matchesDropdown">
                <a class="nav-link" href="matches?season=1">Season 1</a>
                <a class="nav-link" href="matches?season=2">Season 2</a>
                <a class="nav-link" href="matches?season=3">Season 3</a>
                <a class="nav-link" href="matches?season=4">Season 4</a>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link dropdown-toggle" id="pollDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Create-a-Poll</a>
            <div class="dropdown-menu" aria-labelledby="pollDropdown">
                <a class="nav-link" href="create-a-poll">History</a>
            </div>
        </li>
    </ul>
</div>



<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="sidebar-sticky pt-3">

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Settings</span>
            <a class="d-flex align-items-center text-muted" href="#"></a>
        </h6>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="/account/">My Account</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="connections">Connections</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="api">API</a>
            </li>
        </ul>

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Matches</span>
            <a class="d-flex align-items-center text-muted" href="#"></a>
        </h6>
        <ul class="nav flex-column mb-2">
            <li class="nav-item">
                <a class="nav-link" href="matches?season=1">Season 1</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="matches?season=2">Season 2</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="matches?season=3">Season 3</a>
            </li>
           <li class="nav-item">
                <a class="nav-link" href="matches?season=4">Season 4</a>
            </li>
        </ul>

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Create-a-Poll</span>
            <a class="d-flex align-items-center text-muted" href="#"></a>
        </h6>
        <ul class="nav flex-column mb-2">
            <li class="nav-item">
                <a class="nav-link" href="create-a-poll">History</a>
            </li>
        </ul>

    </div>
</nav>

