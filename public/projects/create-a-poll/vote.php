<?php
  require_once getenv('APP_PATH') . '/src/session.php';

  $topic_id = isset($_GET['id'])?$_GET['id']:false;
  $poll = $db->poll_info($topic_id);

  if(!$poll){
    redirect();
  }

  if(!isset($_SESSION['poll_votes'])){
    $_SESSION['poll_votes'] = [];
  }
  if($_SESSION['loggedin']){
    $_SESSION['poll_votes'] = [];
    $polls_voted_on = $db->polls_user_votes($_SESSION['user_id']);
    foreach($polls_voted_on as $vote){
      $_SESSION['poll_votes'][$vote['topic_id']][] = $vote['item_id'];
    }
  }

  $item_value_list = isset($_POST['item'])?$_POST['item']:false;
  $recaptcha_check = $_SESSION['loggedin'] || (isset($_POST['g-recaptcha-response']) && validate_recaptchaV2());
  $vote_attempt = $item_value_list && count($item_value_list)>0 && $recaptcha_check;

  $already_voted = isset($_SESSION['poll_votes'][$topic_id])?$_SESSION['poll_votes'][$topic_id]:false;
  $poll_expired = strtotime($poll['expire_dt']) < strtotime(date("Y-m-d H:i:s"));

  if($vote_attempt && !$already_voted && !$poll_expired){
    foreach($item_value_list as $item){
      $vote_id = $db->add_poll_vote($topic_id, $item, $_SESSION['user_id']);
      if($vote_id){
        $_SESSION['poll_votes'][$topic_id][] = $item;
        $already_voted = true;
      }
    }
  }

  $display_votes = !$poll['hide_votes'] || $poll_expired || $already_voted;

  $poll['items'] = $db->poll_items($topic_id);

  $chart_labels = [];
  $chart_label_values = [];
  if($display_votes){
    foreach($poll['items'] as $item){
      $chart_labels[] = $item['content'];
      $chart_label_values[] = $item['votes'];
    }
  }
  $chart_labels = json_encode($chart_labels);
  $chart_label_values = json_encode($chart_label_values);
?>
<!doctype html>
<html lang="en">
<head>
  <title>Create a Poll - <?php echo $poll['content'] ?></title>
	<link rel="apple-touch-icon" sizes="180x180" href="/assets/images/favicon-180x180.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon-16x16.png">
	<link rel="shortcut icon" href="/assets/images/favicon.ico">
  <link rel="manifest" href="/assets/images/site.webmanifest">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
  <link rel="stylesheet" href="assets/custom.css">

  <?php
    $topic_tokens = array_filter( explode(' ', $poll['content']), function($v){ return strlen($v) > 3; });
    $topic_keywords = implode(', ', $topic_tokens);
    $meta = [
      "keywords" => $topic_keywords.", questionair, straw poll, poll, poll online, ask online, create poll",
      "og:title" => $poll['content'],
      "og:description" => "Answer this poll or create your own to see what others are thinking!"
    ];
    echo page_meta($meta);
  ?>

  <style>
    body {
      background-image: linear-gradient(to bottom, white, <?php echo random_hex_color() ?>, white);
    }
  </style>
</head>
<body>
  <?php include 'header.php'; ?>

  <main role="main">

    <section class="jumbotron text-center bg-light">

      <div class="pb-4 small container col-md-4">
        <label class="form-control-label" for="share_poll">Share this poll</label>
        <div class="input-group shadow input-group-sm">
          <input class="form-control form-control-sm" id="share_poll" value="https://<?php echo getenv('DOMAIN').$_SERVER['REQUEST_URI']; ?>" type="text">
          <button class="btn btn-sm btn-secondary" data-clipboard-target="#share_poll" title="Copy URL" ><i class="fas fa-clipboard"></i></button>
        </div>
      </div>

      <div class="container-fluid col-md-7">

        <h1><?php echo $poll['content'] ?></h1>
        <p class="text-muted small"><?php if($poll['user_id']){ echo "Poll created by $poll[username]"; } ?></p>
          <div class="card-body">
            <?php if($display_votes) { ?>
              <canvas id="chart-line" class="chartjs-render-monitor"></canvas>
            <?php } ?>
            <?php if($poll_expired){?>
              <small class="text-muted">Poll Closed.</small>
            <?php } ?>
          </div>
        </p>


        <?php if(!$poll_expired){ ?>
          <form class="container pb-5" method="post">
            <fieldset  class="form-group form-row">
              <?php
                foreach($poll['items'] as $item){
                  $voted_on = $already_voted && in_array($item['id'], $_SESSION['poll_votes'][$topic_id]);
              ?>

                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="<?php echo $poll['allow_multi']?'checkbox':'radio' ?>" id="<?php echo $item['id'] ?>" name="item[]" value="<?php echo $item['id'] ?>" <?php echo $voted_on?'checked':'';?> <?php echo $already_voted?'disabled':'';?>>
                  <label class="form-check-label <?php echo $voted_on?'font-weight-bold':'' ?>" for="<?php echo $item['id'] ?>"><?php echo $item['content'] ?></label>
                </div>

              <?php } ?>
            </fieldset>

            <?php if(!$_SESSION['loggedin'] && !$already_voted) { ?>
            <div class="form-group row">
              <div class="col-sm-12">
                <div class="g-recaptcha" style="text-align: center; display: inline-block;" data-callback="recaptchaCallback" data-expired-callback="expiredRecaptchaCallback" data-sitekey="<?php echo get_recaptchav2_sitekey() ?>" id="recaptchaDiv"></div>
              </div>
            </div>
          <?php } ?>

            <button class="btn btn-primary" type="submit" <?php echo $already_voted?'disabled':'' ?> id="recaptchaSubmitBtn"><?php echo $already_voted?'Already Voted':'Submit Vote' ?></button>
            <div class="pt-2">
              <small class="text-muted"><text name="countdown" value="<?php echo $poll['ending_in'] ?>"></text></small>
            </div>
          </form>
        <?php } ?>


      <?php if(!$_SESSION['loggedin']){ ?>

            <div class="alert alert-warning">
              <p>
                  Login to track your poll history.<br/>
                  Registered polls close after 7 days.<br/>
                  Your poll may be lost if you forget the URL.<br/>
                  Unregistered polls will automatically <b>close after 24-hours</b>.
              </p>
              <a href="/login?<?php echo get_direct_to();?>" class="btn btn-secondary my-2">Register / Login</a>
            </div>
          </div>
      </section>
      <?php } ?>

      </div>
    </section>

    <div class="py-5">
      <div class="container bg-light shadow">
        <h2 class="text-center py-3">Recently Added</h2>
        <div class="row">

        <?php
          $poll_list_limit = 3;
          $poll_list = $db->polls_most_recent();
          $poll_list = array_slice($poll_list, 0, $poll_list_limit);
          if(empty($poll_list)){
              echo '<p class="card-body">No active polls found.</p>';
          } else {
            foreach($poll_list as $poll){
        ?>
            <div class="col-md-4">
              <div class="card mb-4 shadow-sm">
                <div class="card-header text-center m-2" style="height:225px;background-color: <?php echo random_hex_color(0x7f7f7f) ?>">
                  <h4 class="text-light"><?php echo $poll['content']?></h4>
                </div>
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="btn-group">
                      <a href="vote?id=<?php echo $poll['id'] ?>" type="button" class="btn btn-sm btn-outline-secondary">Vote</a>
                    </div>
                    <small class="text-muted"><text name="countdown" value="<?php echo $poll['ending_in'] ?>"></text></small>
                  </div>
                </div>
              </div>
            </div>
        <?php
            }
          }
        ?>
        </div>
      </div>
    </div>

    <section class="jumbotron text-center bg-light">
      <div class="container">
        <h1>Create a Poll</h1>
        <p class="lead text-muted">Pineapple on pizza? Peanut butter on burgers?<br/>Whatever it may be, quickly create and share a poll with others!</p>
        <p>
          <a href="create" class="btn btn-primary my-2">Create Your Own Poll</a>
          <?php if($_SESSION['loggedin']){ ?>
            <a href="history" class="btn btn-secondary my-2">Your Polls</a>
          <?php } ?>
        </p>
      </div>
    </section>

  </main>

  <?php include 'footer.php'; ?>

  <script src='https://www.google.com/recaptcha/api.js'></script>
  <script src='/assets/js/recaptcha.js'></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.bundle.min.js'></script>
  <script type="text/javascript">
    new ClipboardJS('.btn', {
      text: function(trigger) {
        return trigger.getAttribute('aria-label');
      }
    });
  </script>
  <?php if($display_votes) { ?>
    <script type="text/javascript">
        $(document).ready(function() {
          var ctx = $("#chart-line");
          var myLineChart = new Chart(ctx, {
              type: 'horizontalBar',
              data: {
                  labels: <?php echo $chart_labels ?>,
                  datasets: [{
                      data: <?php echo $chart_label_values ?>,
                      label: "Votes",
                      borderColor: "#43bac7",
                      backgroundColor: '#43bac7',
                      fill: false
                  }]
              },
              options: {
                  title: {
                      display: false,
                      text: ''
                  },
                  legend: {
                    display: false
                  },
                  layout: {
                    padding: {
                      left: 0
                    }
                  },
                  scales: {
                    xAxes: [{
                        ticks: {
                            beginAtZero: true,
                            fixedStepSize: 1
                        }
                    }]
                }
              }
          });
      });
    </script>
  <?php } ?>
</body>
</html>
