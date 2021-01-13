<?php set_last_page(); ?>
<header>
  <div class="collapse bg-dark" id="navbarHeader">
    <div class="container">
      <div class="row">
        <div class="col-sm-8 col-md-7 py-4">
          <h4 class="text-white">About</h4>
          <p class="text-muted">Create and share a poll with others. Register and sign-in to track your poll history. Unregistered polls will automatically close after 24 hours.</p>
        </div>
        <div class="col-sm-4 offset-md-1 py-4">
          <h4 class="text-white">Contact</h4>
          <ul class="list-unstyled">
            <li><a href="https://discord.gg/U5wDzWP8yD" target="_blank" class="text-white"><i class="fab fa-discord mr-2"></i>Find me on Discord</a></li>
            <li><a href="https://twitter.com/an_idle_user" target="_blank" class="text-white"><i class="fab fa-twitter mr-2"></i>Follow me on Twitter</a></li>
            <li><a href="/" class="text-white"><i class="fas fa-home mr-2"></i>Learn more about me</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <div class="navbar navbar-dark bg-dark shadow-sm">
    <div class="container d-flex justify-content-between">
      <a href="./" class="navbar-brand d-flex align-items-center">
        <svg width="1.5em" height="1em" viewBox="0 0 16 16" class="bi bi-bar-chart-line-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" d="M11 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h1V7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7h1V2z"/>
        </svg>
        <strong>Create a Poll</strong>
      </a>

      <div class="row">
        <?php if($_SESSION['loggedin']){ ?>
          <button type="button" class="btn btn-secondary float-right mr-4" data-toggle="modal" data-target="#logoutModal">Logout</button>
          <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="logoutModalLabel">Logout of account: <?php echo $_SESSION['username'] ?>?</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  Are you sure you want to logout?
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <a href="/logout?<?php echo get_direct_to(); ?>" class="btn btn-primary">Logout</a>
                </div>
              </div>
            </div>
          </div>
        <?php } else { ?>
          <a href="/login?<?php echo get_direct_to(); ?>" class="btn btn-secondary float-right mr-4">Login</a>
        <?php } ?>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>

    </div>
  </div>
</header>
