<?php
  require_once('/srv/http/src/session.php');
  set_last_page();

  $topic_value = isset($_POST['topic'])?$_POST['topic']:false;
  $item_value_list = isset($_POST['item'])?$_POST['item']:false;
  $allowMulti_value = isset($_POST['allowMulti'])?$_POST['allowMulti']:false;
  $hideVote_value = isset($_POST['hideVotes'])?$_POST['hideVotes']:false;
  $allowMulti_value = $allowMulti_value?1:0;
  $hideVote_value = $hideVote_value?1:0;

  $recaptcha_check = $_SESSION['loggedin'] || (isset($_POST['g-recaptcha-response']) && validate_recaptchaV2());

  $creation_attempt = $topic_value && $item_value_list && count($item_value_list)>1 && $recaptcha_check;
  if($creation_attempt){
    $creation_success = true;

    $now_dt = date("Y-m-d H:i:s");
    $now_dt = strtotime($now_dt);
    if($_SESSION['loggedin']){
      $now_dt = strtotime("+7 day", $now_dt);
    } else {
      $now_dt = strtotime("+1 day", $now_dt);  
    }
    $expire_value = date('Y-m-d H:i:s', $now_dt);

    $topic_id = $db->add_poll_topic($topic_value, $allowMulti_value, $hideVote_value, $_SESSION['user_id'], $expire_value);
    if($topic_id){
      foreach($item_value_list as $item){
        if(empty($item))
          continue;
        $item_id = $db->add_poll_item($topic_id, $item);
        if(!$item_id){
          $creation_success = false;
        }
      }
    } else {
      $creation_success = false;
    }
    if($creation_success){
      redirect(1, "vote.php?id=$topic_id");
    }

  }
  
?>
<!doctype html>
<html lang="en">
<head>
  <title>Create a Poll</title>
	<link rel="apple-touch-icon" sizes="180x180" href="/assets/images/favicon-180x180.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon-16x16.png">
	<link rel="shortcut icon" href="/assets/images/favicon.ico">
  <link rel="manifest" href="/assets/images/site.webmanifest">	
  <link href="/assets/bootstrap-4.5.2-dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/custom.css" rel="stylesheet">
  
  <?php 
    $meta = [
      "keywords" => "questionair, straw poll, poll, poll online, ask online, create poll",
      "og:title" => "Create-a-Poll",
      "og:description" => "Create and share any poll fast!"
    ];
    echo page_meta($meta);
  ?>
  
</head>
<body>
  <?php include('header.php'); ?>

  <main role="main">

  <?php if($creation_attempt && $creation_success){ ?>
    <section class="jumbotron text-center bg-light">
      <div class="container">
        <h1>Poll Created!</h1>
        <p class="lead text-muted">Redirecting you, please wait ...</p>
        <a href="vote.php?id=<?php echo $topic_id ?>" class="btn btn-secondary my-2">Taking too long? Click here</a>
      </div>
    </section>
  <?php } else { ?>
    <section class="jumbotron text-center">
      <div class="container">
        <h1>Creating a Poll</h1>
        <p class="lead text-muted">What is that you want to ask others?</p>
        <?php if(!$_SESSION['loggedin']){ ?>
          <div class="alert alert-warning">
            <p>
                Login to track your poll history<br/>
                Registered polls expire after 7 days.<br/>
                Your poll may be lost if you forget the URL.<br/>
                Unregistered polls will automatically <b>expire after 24-hours</b>.
            </p>
            <a href="/login.php?<?php echo get_direct_to();?>" class="btn btn-secondary my-2">Register / Login</a>
        </div>
        <?php } else { ?>
          <a href="./" class="btn btn-secondary my-2">Home</a>
          <a href="history.php" class="btn btn-secondary my-2">Your Polls</a>
        <?php } ?>
        </p>
      </div>
    </section>

    <div class="py-5 bg-light">
      <div class="container">
        
        <form method="post">

          <div class="form-group row">
            <label for="inputTopic" class="col-sm-2 col-form-label font-weight-bolder">Poll Topic</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="inputTopic" placeholder="Enter the Topic/Question" name="topic" maxlength="100" required>
            </div>
          </div>

          <div class="m-3" id="inputItemList">
            <div class="form-group row">
              <label for="inputItem" class="col-sm-2 col-form-label">Option 1</label>
              <div class="col-sm-10">
                <input type="text" class="form-control form-control-sm" id="inputTopic" placeholder="Enter option value" name="item[]" maxlength="50" required>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputItem" class="col-sm-2 col-form-label">Option 2</label>
              <div class="col-sm-10">
                <input type="text" class="form-control form-control-sm" id="inputTopic" placeholder="Enter option value" name="item[]" maxlength="50" required>
              </div>
            </div>
          </div>

          <div class="form-group row float-xl-right float-right">
            <div class="col-sm-10">
              <button type="button" class="btn-sm btn-secondary" id="additem">Add more options</button>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-sm-2"></div>
            <div class="col-sm-10">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="multiCheck" name="allowMulti">
                <label class="form-check-label" for="multiCheck">
                  Allow multiple options? <i class="fas fa-info-circle" title="Check to allow a poll vote to contain more than one option."></i>
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="hideVotes" name="hideVotes" checked>
                <label class="form-check-label" for="hideVotes">
                  Hide results until voted? <i class="fas fa-info-circle" title="Check to hide the poll votes and chart until after a vote is submitted."></i>
                </label>
              </div>
            </div>
          </div>

          <?php if(!$_SESSION['loggedin']) { ?>
            <div class="form-group row">
              <div class="col-sm-10">
                <div class="g-recaptcha" data-callback="recaptchaCallback" data-expired-callback="expiredRecaptchaCallback" data-sitekey="<?php echo get_recaptchav2_sitekey() ?>" id="recaptchaDiv"></div>
              </div>
            </div>
          <?php } ?>

          <div class="form-group row">
            <div class="col-sm-10 ml-3">
              <button type="submit" class="btn btn-primary" id="recaptchaSubmitBtn">Create it!</button>
            </div>
          </div>

        </form>

      </div>
    </div>
    <?php } ?>

  </main>

  <?php include('footer.php'); ?>

  <script src='https://www.google.com/recaptcha/api.js'></script>
  <script src='/assets/js/recaptcha.js'></script>
  <?php if(!$creation_attempt || !$creation_success){ ?>
    <script type="text/javascript">
      $(document).ready(function () {
        var counter = $('#inputItemList')[0].childElementCount;
        var optionLimit = 8;

        $("#additem").on("click", function () {
            counter++;
            if(counter<=optionLimit){
              var newOption = `
                <div class="form-group row">
                  <label for="inputItem" class="col-sm-2 col-form-label">Option ` + counter +`</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control form-control-sm" id="inputTopic" placeholder="Enter option value" name="item[]" maxlength="50" >
                  </div>
                </div>
              `;
              $("#inputItemList").append(newOption);
            } 
            if(counter>=optionLimit){
              $("#additem").hide();
            }
        });
      });
    </script>
  <?php } ?>
</body>
</html>
