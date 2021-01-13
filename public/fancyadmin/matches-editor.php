<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/../src/session.php'; set_last_page(); requires_admin(); ?>
<?php
    $is_success = false;
    $alert_message = false;
    $update_attempt = false;
    $new_attempt = false;
    $match_info = false;

    function get_match_info($match_id){
        global $db;
        $match_info = $db->match($match_id);
        if($match_info){
            $match_info['contestant_info'] = [];
            $contestants_info = $db->match_contestants($match_info['id']);
            foreach($contestants_info as $ci){
                $match_info['contestant_info'][$ci['team']][] = $ci;
            }
            ksort($match_info['contestant_info']);
            return $match_info;
        }
        return false;
    }

    if(isset($_GET['search']) && $_GET['search'] && empty($_POST)){
        $match_info = get_match_info($_GET['search']);
        if($match_info){
            $is_success = true;
            $alert_message = "Match info loaded.";
        } else {
            $is_success = false;
            $alert_message = "Unable to find match.";
        }
    } elseif(isset($_POST['match_id']) && $_POST['match_id']!=0){
        $match_info = get_match_info($_POST['match_id']);
        if(!$match_info){
            $is_success = false;
            $alert_message = "Unable to find match.";
        }
    }

    $new_attempt = !$match_info && isset($_POST['match_id']) && $_POST['match_id']==0;
    $update_attempt = $match_info && isset($_POST['match_id']) && $_POST['match_id']==$match_info['id'];
    if($new_attempt || $update_attempt){

        $match_id = $_POST['match_id']?:0;
        $event_id = $_POST['match_event']?:0;
        $title_id = $_POST['match_title']?:0;
        $match_type_id = $_POST['match_type']?:0;
        $match_note = $_POST['match_note']?:'';
        $team_won = $_POST['result']?:0;
        $winner_note = $_POST['result_note']?:'';
        $bet_open = isset($_POST['bet_open'])?1:0;
        $last_updated = $_POST['last_updated']?:0;

        if(isset($_POST['contestants']) && count($_POST['contestants'])>0){
            if($new_attempt){
                $match_id = $db->add_match($event_id, $title_id, $match_type_id, $match_note, $team_won, $winner_note, $bet_open, $_SESSION['user_id']);
                $is_success = $match_id?true:false;
                if(!$is_success){
                    $alert_message = "Failed to add the match. Please contact admin.";
                }
            } elseif($update_attempt){
                if($last_updated == $match_info['info_last_updated']){
                    $is_success = $db->update_match($match_id, $event_id, $title_id, $match_type_id, $match_note, $team_won, $winner_note, $bet_open, $_SESSION['user_id']);
                    if(!$is_success){
                        $alert_message = "Update failed. Please contact admin.";
                    }
                } else {
                    $is_success = false;
                    $alert_message = "Failed to update. Editing an older version of the match.<br/>Latest info loaded.";
                }
            } else{
                $is_success = false;
            }

            if($is_success){
                $db->remove_all_match_contestants($match_id);
                $contestants_added = 0;
                foreach($_POST['contestants'] as $team_id => $team_data) {
                    foreach($team_data as $key => $val) {
                        if(is_numeric($key)){
                            if($val == 0) continue;
                            $superstar_id = $val;
                            $db->add_match_contestant($match_id, $superstar_id, $team_id, $team_data['multiplier']);
                            $contestants_added++;
                        }
                    }
                }
                if($contestants_added==0){
                    $db->add_match_contestant($match_id, 1, 1, 1);
                }
            }

        } else {
            $alert_message = "Match must contain at least 1 contestant.";
        }
    }

    if(!$alert_message){
        if($new_attempt){
            if($is_success){
                $alert_message = "Match created.";
            } else {
                $alert_message = "Match creation failed.";
            }
        } elseif($update_attempt){
            if($is_success){
                $alert_message = "Match updated.";
            } else {
                $alert_message = "Match update failed.";
            }
        }
    }

    $event_list = $db->all_events();
    $open_matches = $db->open_matches();
    $season_matches = $db->s4_matches();
    $superstar_list = $db->all_superstars();
    $matches_base_data = $db->matches_base_data();
    usort($matches_base_data['matches_title'], function($a, $b) {return strcmp($a['name'], $b['name']);});
    usort($matches_base_data['matches_match_type'], function($a, $b) {return strcmp($a['name'], $b['name']);});
    $title_list = $matches_base_data['matches_title'];
    $match_type_list = $matches_base_data['matches_match_type'];

    if($is_success && ($new_attempt || $update_attempt)){
        $match_info = get_match_info($match_id);
    }
?>
<!doctype html>
<html lang="en">
<head>
    <?php
        $title = 'Admin - Matches Editor';
        include 'includes/head.php';
    ?>
</head>
<body>

    <?php include 'includes/nav.php'; ?>

    <main role="main" class="container">

        <?php include 'includes/banner.php'; ?>

        <?php if($alert_message){ ?>
        <!-- Notice Message -->
        <div class="alert text-center p-3 <?php echo $is_success?'alert-success':'alert-danger' ?>" role="alert">
            <h5><?php echo $alert_message ?></h5>
        </div>
        <?php } ?>

        <!-- Matches dropdown -->
        <div class="my-3 p-3 bg-white rounded shadow-sm">
            <form method="get">
                <label class="my-1 mr-2" for="selectMatchSelector">Match Selector</label>
                <select class="custom-select my-1 mr-sm-2" id="selectMatchSelector" id="match_selected" name="search">
                    <option value="0" selected>Select a Match ...</option>
                    <?php
                        foreach($season_matches as $sm){
                            $has_title = $sm['title']?'(c)':'';
                            $is_searched = ($match_info && $sm['id']==$match_info['id'])?'selected':'';
                            $sumamry = "[$sm[date]] $has_title $sm[match_type] - $sm[contestants]";
                            $classes = $sm['completed']?'text-danger':($sm['bet_open']?'text-success':'text-warning');
                            echo "<option class='$classes' value='$sm[id]' $is_searched>$sumamry</option>";
                        }
                    ?>
                </select>
                <button type="submit" class="btn btn-primary my-1">Search</button>
            </form>
        </div>

        <!-- Open matches and create -->
        <div class="my-3 p-3 bg-white rounded shadow-sm">
            <h6 class="border-bottom border-gray pb-2 mb-0">Open Matches</h6>
            <?php if(empty($open_matches)){ ?>
                <div class="media text-muted pt-3">
                    <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                        <small class="d-block float-right"></small>
                        <strong class="d-block text-gray-dark">No Open Matches found.</strong>
                    </p>
                </div>
            <?php } else {
                foreach($open_matches as $om){
            ?>
                <div class="media text-muted pt-3">
                    <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                        <a class="d-block float-right" href="matches-editor?search=<?php echo $om['id'] ?>"><?php echo $om['info_last_updated'] ?></a>
                        <strong class="d-block text-gray-dark"><?php echo $om['event'].' - '.$om['title'].' - '.$om['match_type'] ?></strong>
                        <?php echo $om['contestants'] ?><small class="float-right"><?php echo $om['info_last_updated_by_username'] ?></small>
                    </p>
                </div>
            <?php
                }
            }
             ?>
            <small class="d-block text-right mt-3">
                <a type="button" class="btn btn-primary" href="matches-editor">New Match</a>
            </small>
        </div>

        <div class="my-3 p-3 bg-white rounded shadow-sm">
            <h6 class="border-bottom border-gray pb-2 mb-0">Match Editor <?php if($match_info) echo  "<small class='float-right'>Last Updated: $match_info[info_last_updated]</small>"; ?></h6>
            <div class="media text-muted pt-3">

            <?php $dtToday = date('Y-m-d 0:0:0'); ?>
            <?php if($match_info){ ?>

                 <!-- Update Form -->
                <form class="container" method="post">
                    <input type="text" class="form-control d-none" name="match_id" value="<?php echo $match_info['id'] ?>">
                    <input type="text" class="form-control d-none" name="last_updated" value="<?php echo $match_info['info_last_updated'] ?>">
                    <div class="form-row text-center">
                        <div class="col-md-4 mb-3">
                            <h5 for="base_pot">Base Pot</h5>
                            <small id="base_pot"><?php echo number_format($match_info['base_pot']) ?></small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h5 for="base_pot">Total Pot</h5>
                            <small id="base_pot"><?php echo number_format($match_info['total_pot']) ?></small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h5 for="base_pot">Bet Multiplier</h5>
                            <small id="base_pot"><?php echo $match_info['completed']?$match_info['bet_multiplier']:'TBD' ?></small>
                        </div>
                    </div>
                    <div class="form-group row text-center">
                        <div class="col-sm-12">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="inputBetsOpen" name="bet_open" <?php if($match_info['bet_open']) echo 'checked'; ?>>
                                <label class="form-check-label" for="inputBetsOpen">Bets Open</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="selectMatchEvent">Event</label>
                            <select class="custom-select my-1 mr-sm-2" id="selectMatchEvent" name="match_event">
                                <option value="0" selected>Select an Event ...</option>
                                <?php
                                    foreach($event_list as $event){
                                        $is_selected = ($event['id']==$match_info['event_id'])?'selected':'';
                                        $event_date_only = explode(' ', $event['date_time'])[0];
                                        $classes = $event['date_time']<$dtToday?'text-danger':'text-success';
                                        echo "<option class='$classes' value='$event[id]' $is_selected>[$event_date_only] $event[name]</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="selectMatchType">Match Type</label>
                            <select class="custom-select my-1 mr-sm-2" id="selectMatchType" name="match_type">
                                <option value="0" selected>Select a Match Type ...</option>
                                <?php
                                    foreach($match_type_list as $match_type){
                                        $is_selected = ($match_type['id']==$match_info['match_type_id'])?'selected':'';
                                        echo "<option value='$match_type[id]' $is_selected>$match_type[name]</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="selectTitle">Title</label>
                            <select class="custom-select my-1 mr-sm-2" id="selectTitle" name="match_title">
                                <option value="0" selected>Select a Title ...</option>
                                <?php
                                    foreach($title_list as $title){
                                        $is_selected = ($title['id']==$match_info['title_id'])?'selected':'';
                                        echo "<option value='$title[id]' $is_selected>$title[name]</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-12 mb-3">
                            <label for="inputMatchNote">Match Note</label>
                            <input type="text" class="form-control" id="inputMatchNote" name="match_note" placeholder="Match Note" value="<?php echo $match_info['match_note'] ?>">
                        </div>
                    </div>
                    <div id="contestantsDiv">
                        <label for="contestantsDiv">Contestants</label>
                        <?php
                            foreach($match_info['contestant_info'] as $team) {
                                $team_number = $team[0]['team'];
                                $team_bet_multiplier = $team[0]['bet_multiplier'];
                        ?>
                            <div class="mb-3">
                                <label>Team <?php echo $team_number ?></label>
                                <select class="ml-4 rounded text-muted" name="contestants[<?php echo $team_number ?>][multiplier]">
                                <?php
                                    for($i=1; $i<=5; $i++){
                                        $is_selected = $i==$team_bet_multiplier?'selected':'';
                                        echo "<option value='$i' $is_selected>{$i}x</option>";
                                    }
                                ?>
                                </select>
                                <div class="form-row">
                                    <?php foreach($team as $member){ ?>
                                        <div class="col-md-4">
                                            <select team="<?php echo $team_number ?>" class="custom-select my-1 mr-sm-2" name="contestants[<?php echo $team_number ?>][]">
                                                <option value="0" selected>Select a Contestant ...</option>
                                                <?php
                                                    foreach($superstar_list as $superstar){
                                                        $is_selected = ($superstar['id']==$member['superstar_id'])?'selected':'';
                                                        echo "<option value='$superstar[id]' $is_selected>$superstar[name]</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="col-md-12 mb-3 float-right">
                        <a class="btn btn-primary btn-sm" type="submit" onclick="addTeam()">Add Team</a>
                        <a class="btn btn-primary btn-sm" type="submit" onclick="addMembers()">Add Member</a>
                    </div>
                    <div class="form row">
                        <div class="col-md-12 mb-3">
                            <fieldset class="form-group">
                                <div class="row">
                                    <legend class="col-form-label col-sm-2 pt-0">Result</legend>
                                    <div class="col-sm-10">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" id="radioTeamWon0" name="result" value="0" checked>
                                            <label class="form-check-label" for="radioTeamWon0">TBD</label>
                                        </div>
                                        <?php
                                            foreach($match_info['contestant_info'] as $team) {
                                                $team_number = $team[0]['team'];
                                                $team_won = $match_info['team_won'] == $team_number;
                                        ?>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" id="radioTeamWon<?php echo $team_number ?>" name="result" value="<?php echo $team_number ?>" <?php echo $team_won?'checked':'' ?>>
                                                <label class="form-check-label" for="radioTeamWon<?php echo $team_number ?>">Team <?php echo $team_number ?></label>
                                            </div>
                                        <?php } ?>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" id="radioTeamWon999" name="result" value="999" <?php echo $match_info['team_won']=='999'?'checked':'' ?>>
                                            <label class="form-check-label" for="radioTeamWon999">No Contest / DNF</label>
                                        </div>
                                    </div>
                                </div>
                                <label for="inputResult">Result Note</label>
                                <input type="text" class="form-control" id="inputResult" name="result_note" placeholder="Result Note" value="<?php echo $match_info['winner_note'] ?>">
                            </fieldset>
                        </div>
                    </div>
                    <button class="btn btn-primary float-right" type="submit">Update Match</button>
                </form>

            <?php } else { ?>

                <!-- Create Form -->
                <form class="container" method="post">
                    <input type="text" class="form-control d-none" name="match_id" value="0">
                    <input type="text" class="form-control d-none" name="result" value="0">
                    <input type="text" class="form-control d-none" name="result_note" value="0">
                    <input type="text" class="form-control d-none" name="last_updated" value="0">
                    <div class="form-row text-center">
                        <div class="col-md-4 mb-3">
                            <h5 for="base_pot">Base Pot</h5>
                            <small id="base_pot">N/A</small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h5 for="base_pot">Total Pot</h5>
                            <small id="base_pot">N/A</small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h5 for="base_pot">Bet Multiplier</h5>
                            <small id="base_pot">N/A</small>
                        </div>
                    </div>
                    <div class="form-group row text-center">
                        <div class="col-sm-12">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="inputBetsOpen" name="bet_open" checked>
                                <label class="form-check-label" for="inputBetsOpen">Bets Open</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="selectMatchEvent">Event</label>
                            <select class="custom-select my-1 mr-sm-2" id="selectMatchEvent" name="match_event">
                                <option value="0" selected>Select an Event ...</option>
                                <?php
                                    function isFutureEvent($event){
                                        global $dtToday;
                                        return $event['date_time']>$dtToday;
                                    }
                                    $filtered_event_list = array_filter($event_list, "isFutureEvent");
                                    $last_event = end($filtered_event_list);
                                    foreach($filtered_event_list as $event){
                                        $event_date_only = explode(' ', $event['date_time'])[0];
                                        $is_default = $event==$last_event?'selected':'';
                                        echo "<option class='text-success' value='$event[id]' $is_default>[$event_date_only] $event[name]</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="selectMatchType">Match Type</label>
                            <select class="custom-select my-1 mr-sm-2" id="selectMatchType" name="match_type">
                                <option value="0" selected>Select a Match Type ...</option>
                                <?php
                                    foreach($match_type_list as $match_type){
                                        echo "<option value='$match_type[id]'>$match_type[name]</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="selectTitle">Title</label>
                            <select class="custom-select my-1 mr-sm-2" id="selectTitle" name="match_title">
                                <option value="0" selected>Select a Title ...</option>
                                <?php
                                    foreach($title_list as $title){
                                        echo "<option value='$title[id]'>$title[name]</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-12 mb-3">
                            <label for="inputMatchNote">Match Note</label>
                            <input type="text" class="form-control" id="inputMatchNote" name="match_note" placeholder="Match Note">
                        </div>
                    </div>
                    <div id="contestantsDiv">
                        <label for="contestantsDiv">Contestants</label>
                        <div class="mb-3">
                            <label>Team 1</label>
                            <select class="ml-4 rounded text-muted" name="contestants[1][multiplier]">
                                <option value="1">1x</option>
                                <option value="2">2x</option>
                                <option value="3">3x</option>
                                <option value="4">4x</option>
                                <option value="5">5x</option>
                            </select>
                            <div class="form-row">
                                <div class="col-md-4">
                                    <select team="1" class="custom-select my-1 mr-sm-2" name="contestants[1][]">
                                        <option value="0" selected>Select a Contestant ...</option>
                                        <?php
                                            foreach($superstar_list as $superstar){
                                                echo "<option value='$superstar[id]'>$superstar[name]</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Team 2</label>
                            <select class="ml-4 rounded text-muted" name="contestants[2][multiplier]">
                                <option value="1">1x</option>
                                <option value="2">2x</option>
                                <option value="3">3x</option>
                                <option value="4">4x</option>
                                <option value="5">5x</option>
                            </select>
                            <div class="form-row">
                                <div class="col-md-4">
                                    <select team="2" class="custom-select my-1 mr-sm-2" name="contestants[2][]">
                                        <option value="0" selected>Select a Contestant ...</option>
                                        <?php
                                            foreach($superstar_list as $superstar){
                                                echo "<option value='$superstar[id]'>$superstar[name]</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3 float-right">
                        <a class="btn btn-primary btn-sm" type="submit" onclick="addTeam()">Add Team</a>
                        <a class="btn btn-primary btn-sm" type="submit" onclick="addMembers()">Add Member</a>
                    </div>
                    <button class="btn btn-primary float-right" type="submit">Submit Match</button>
                </form>
            <?php } ?>

            </div>
        </div>

    </main>

    <?php include '../includes/footer.php'; ?>
    <script type="text/javascript" src="assets/js/custom.js"></script>

</body>
</html>
