<header class="main">
	<?php echo $header;?>
</header>
<?php
	$showPoints = false;
	if(empty($matches)) {
		echo 'NO MATCHES FOUND';
		return;
	}
	if(!isset($user)) {
		$showPoints = count($matches_bets_open);
		$user['user_id'] = $_SESSION['user_id'];
		$user['username'] = 'You';
	}
?>
<div class="table-wrapper">
<?php
	if($_SESSION['loggedin'] &&  $showPoints) {
		echo "<div class='float-right'><h3>Points Available: {$pointsAvailable}</h3></div>";
	}
?>
	<table class="alt">
		<thead>
			<tr>
				<th>Date</th>
				<th>Match</th>
				<th>Contestants</th>
				<th>Winner</th>
				<th class="text-center">Pot</th>
				<th class="text-center">Bet</th>
			</tr>
		</thead>
		<tbody>
<?php
	$limit = 10;
	$pages = ceil(count($matches) / $limit);
	$base_data = $db->matches_base_data();
	$current_page = (isset($_GET['page']) && !empty($_GET['page']) && $_GET['page'] <= $pages) ? $_GET['page'] : 1;
	$matches_slice = array_slice($matches, ($current_page*$limit)-($limit), $limit);
	echo '<p class="h6">Showing '.(($current_page*$limit)-($limit-1)).'-'.($current_page*$limit < count($matches) ? $current_page*$limit :  count($matches)).' out of '.count($matches).'</p>';
	foreach($matches_slice as $match){
		if(!$match['match_type_id']) continue;
		// group all match contestants and winners
		$all_contestants = array();
		$winner = array();
		foreach($db->match_contestants($match['id']) as $contestant){
			$match_superstar = $db->superstar($contestant['superstar_id']);
			$match_superstar['bet_multiplier'] = $contestant['bet_multiplier'];
			$all_contestants[$contestant['team']][] = $match_superstar;
			if($match['team_won']==$contestant['team'])
				$winner[$contestant['team']][] = $match_superstar;
		}
?>
			<tr>
				<td><?php
					echo '<b>'.$match['event'].'</b>';
					echo '<br/>'.$match['date'].'<br/><br/><span title="'.$match['user_rating_avg'].'">';
					for($star_cnt=1; $star_cnt<6; $star_cnt++){
						echo '<span class="fa fa-star'.($match['user_rating_avg']>=$star_cnt?'':'-o').'"></span>';
					}
					echo '</span>';
				?></td>
				<td><?php
					echo $match['title_id'] ? '<b>'.$base_data['matches_title'][$match['title_id']]['name'].'</b><br/><br/>':'';
					echo $match['match_type_id'] ? $base_data['matches_match_type'][$match['match_type_id']]['name']:'N/A';
					echo '<br/><br/><i>'.$match['match_note'].'</i>';
				?></td>
				<td><?php
				foreach($all_contestants as $team){
					echo ''.$team[0]['bet_multiplier'].'x<br/>';
					foreach($team as $superstar){
						echo '<a href="/projects/matches/superstar?superstar_id='.$superstar['id'].'">'.$superstar['name'].'</a><br/>';
					}
					echo '<br/>';
				}?></td>
				<td><?php
				$winner_listed = false;
				foreach($winner as $team){
					foreach($team as $superstar){
						echo '<a href="/projects/matches/superstar?superstar_id='.$superstar['id'].'">'.$superstar['name'].'</a><br/>';
						$winner_listed = true;
					}
				}
				if($match['team_won']==999)
					echo "<b><font color='grey'>NO CONTEST</font></b><br/>";
				else if($match['team_won']==998)
					echo "<font color='orange'>Updating ...<br/><br/><i>Please check back later</i></font><br/>";
				else if(!($winner_listed || $match['bet_open']))
					echo '<br/><i><font color="orange">Match in progress ...</font></i>';
				echo '<br/><i>'.$match['winner_note'].'</i>';
				?></td>
				<td><center><?php echo number_format($match['total_pot'], 0, '', ',');?></center></td>
				<td><?php
				$message = '';
				if($match['bet_open'] && $user['user_id']==$_SESSION['user_id'] && !$_SESSION['user_id']){
					$message = 'Requires Login';
				} else {
					if($user['user_id']){
						if(!isset($user_bets)){
							$user_bets = $db->user_bets($user['user_id']);
							$user_ratings = $db->user_match_ratings($user['user_id']);
						}
						$user_bet = array_key_exists($match['id'], $user_bets)?$user_bets[$match['id']]:False;
						if($user_bet){
							if($match['team_won']){
								if($user_bet['bet_won'])
									$message = "<font color='green'>WIN (+".number_format($user_bet['potential_cut_points'], 0, '', ',').")</font>";
								else if($match['team_won']==999)
									$message = "<font color='grey'>NO CONTEST</font>";
								else
									$message = "<font color='red'>LOSS (-".number_format($user_bet['points'], 0, '', ',').")</font>";
								$message = "<br/><b>".$message."</b>";
							}
							$message = $message."<br/>".($_SESSION['user_id']==$user['user_id']?'You':$user['username'])." placed a ".number_format($user_bet['points'], 0, '', ',')." point bet";
							if($user_bet['team']){
								$chosen_names = array();
								foreach($all_contestants[$user_bet['team']] as $t)
									$chosen_names[] = $t['name'];
								$message = $message.' on '.implode(', ', $chosen_names);
							}
						} else if($match['bet_open']){
							echo '<form id="form_match_'.$match['id'].'" action="javascript:placeBet('.$match['id'].')"><select> <option value="" disabled selected>Superstar</option>';
							foreach($all_contestants as $team){
								$team_names = array(); foreach($team as $s_name){$team_names[]=$s_name['name'];}
								echo '<option>'.implode(', ',$team_names).'</option>';
							}
							echo '</select><input type="float" name="bet_amount" placeholder="Bet Amount" class="m-2"/><input type="submit" class="m-2"></form>';
						}
					}
					if(!$match['bet_open']){
						$message = '<b><u>Closed</u></b><br/>'.$message;
						if($user['user_id']){
							$message = $message.'<br/><br/><font color="orange">'.($_SESSION['user_id']==$user['user_id']?'Your ':$user['username'].'\'s ').'Match Rating</font><br/>';
							$user_rating = array_key_exists($match['id'], $user_ratings)?$user_ratings[$match['id']]['rating']:0;
							for($star_cnt=1; $star_cnt<6; $star_cnt++){
								$message = $message.'<span class="fa fa-star'.($user_rating>=$star_cnt?'':'-o').'"'.($_SESSION['user_id']==$user['user_id']?' title="Rate '.$star_cnt.'*" onClick="return updateMatchRating('.$match['id'].','.$star_cnt.')"':'').'></span>';
							}
						}
					}
				}
				echo '<center>'.$message.'</center>';
				?></td>
			</tr>
<?php
	}
?>
		</tbody>
	</table>
	<div class="align-center">
	<?php
		if ($current_page != 1) {
			$prevpage = $current_page - 1;
			$url = get_page_query_url($prevpage);
			echo "<a href='$url' class='m-2 btn' >Previous Page</a>";
		}

		/*
		for ($p = 1; $p <= $pages; $p++) {
			$query = $_GET;
			$query['page'] = $p;
			$query_result = http_build_query($query);
			if($p == $current_page)
				echo "<a href='{$_SERVER['PHP_SELF']}?{$query_result}' class='m-2 font-weight-bold h5'>".$p."</a>";
			else
				echo "<a href='{$_SERVER['PHP_SELF']}?{$query_result}' class='m-2 btn'>".$p."</a>";
		}
		*/

		if ($pages >= 1 && $current_page <= $pages) {
			$url = get_page_query_url(1);
			if($current_page==1)
				echo "<a href='{$url}' class='m-2 btn font-weight-bold h5'>1</a>";
			else
				echo "<a href='{$url}' class='m-2 btn'>1</a>";
			$i = max(2, $current_page - 5);
			if ($i > 2)
				echo " ... ";
			for (; $i < min($current_page + 6, $pages); $i++) {
				$url = get_page_query_url($i);
				if($current_page==$i)
					echo "<a href='{$url}' class='m-2 btn  font-weight-bold h5'>{$i}</a>";
				else
					echo "<a href='{$url}' class='m-2 btn'>{$i}</a>";
			}
			if ($i != $pages)
				echo " ... ";
			$url = get_page_query_url($pages);
			echo "<a href='{$url}' class='m-2 btn'>{$pages}</a>";
		}

		if ($current_page != $pages) {
			$nextpage = $current_page + 1;
			$url = get_page_query_url($nextpage);
			echo "<a href='$url' class='m-2 btn '>Next Page</a>";
		}

		function get_page_query_url($page_number){
			$query = $_GET;
			$query['page'] = $page_number;
			$query_result = http_build_query($query);
			return "{$_SERVER['PHP_SELF']}?{$query_result}";
		}

	?>
	</div>
</div>
