<?php include '../includes/nav.php'; ?>


<nav class="nav nav-underline bg-white shadow-sm">
    <a class="nav-link active" href="index" title="Don't fuck shit up.">Admin Dashboard</a>

    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="matchesDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" ignore-smoothscroll>Matches</a>
        <div class="dropdown-menu" aria-labelledby="matchesDropdown">
            <a class="nav-link" href="matches-editor">Match Editor</a>
            <a class="nav-link" href="matches-roster">Roster</a>
            <a class="nav-link" href="matches-metadata">Metadata</a>
            <a class="nav-link" href="matches-royalrumble">Royal Rumble</a>
        </div>
    </li>

    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="discordbotDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" ignore-smoothscroll>DiscordBot</a>
        <div class="dropdown-menu" aria-labelledby="matchesDropdown">
            <a class="nav-link" href="discordbot-commands">IdleBot Commands</a>
            <a class="nav-link" href="discordbot-scheduler">IdleBot Scheduler</a>
        </div>
    </li>

    <a class="nav-link" href="analytics">Analytics</a>
</nav>

