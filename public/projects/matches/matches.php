<?php
	require_once $_SERVER['DOCUMENT_ROOT'] . '/../src/session.php';

	$brands = $db->all_brands();
	$titles = $db->all_titles();
	$header = '';
	$matches = [];
	if(isset($_GET['match_id']) && !empty($_GET['match_id'])){
		$match_id = htmlspecialchars($_GET['match_id']);
		$matches[] = $db->match($match_id);
		$header =  'Single Match View';
	}
	elseif(isset($_GET['title_id']) && !empty($_GET['title_id'])){
		$title_id = htmlspecialchars($_GET['title_id']);
		$matches = $db->title_matches($title_id);
		$header = $titles[$title_id]['name'].' Matches';
	}
	elseif(isset($_GET['brand_id']) && !empty($_GET['brand_id'])){
		$brand_id = htmlspecialchars($_GET['brand_id']);
		$matches = $db->brand_matches($brand_id);
		$header = $brands[$brand_id]['name'].' Matches';
	}
	elseif(isset($_GET['type'])){
		if($_GET['type']=='bets_open'){
			$header = 'Open Bet Matches';
		}
		elseif($_GET['type']=='today'){
			$header = "Today's Matches";
		}
	}
	if(empty($header)){
		if(isset($_GET['season']) && !empty($_GET['season'])){
			$season = htmlspecialchars($_GET['season']);
		} else {
			$season = 0;
		}
		if($season == 1){
			$matches = $db->s1_matches();
			$header = 'Matches (Season 1)';
		} else if($season == 2){
			$matches = $db->s2_matches();
			$header = 'Matches (Season 2)';
		} else if($season == 3){
			$matches = $db->s3_matches();
			$header = 'Matches (Season 3)';
		} else if($season == 4){
			$matches = $db->s4_matches();
			$header = 'Matches (Season 4)';
		} else if($season == 5 || !$season){
			$matches = $db->s5_matches();
			$header = 'Matches (Season 5)';
		} else {
			$matches = $db->all_matches();
			$header = 'All Matches';
		}
	}

	$meta = [
		"keywords" => "{$header}, watchwrestling, WWE, AEW, NJPW, ROH, IMPACT, wrestling, bet, points, fjbot, chatroom, streams, watch online, wrestling discord, discord",
		"og:title" => "WatchWrestling - {$header}",
	];
	include 'header.php';

	if(isset($_GET['type'])){
		if($header == "Open Bet Matches"){
			$matches = $matches_bets_open;
		}
		elseif($header == "Today's Matches"){
			$matches = $matches_today;
		}
	}

	$header = '<h1>'.$header.'</h1>';
	include 'matchlist.php';
?>
<?php include 'navi-footer.php'; ?>
