<?php include('header.php'); ?>
<?php
	$brands = $db->all_brands();
	$titles = $db->all_titles();
	$header = 'All Matches';
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
			$matches = $matches_bets_open;
			$header = 'Open Bet Matches';
		}
		elseif($_GET['type']=='today'){
			$matches = $matches_today;
			$header = "Today's Matches";
		}
	}
	if(empty($matches)){
		$season_id = 0;
	        if(isset($_GET['season_id']) && !empty($_GET['season_id'])){
	                $season_id = htmlspecialchars($_GET['season_id']);
	        }
	        if($season_id == 1){
	                $matches = $db->s1_matches();
			$header = 'Matches (Season 1)';
	        } else if($season_id == 2){
	                $matches = $db->s2_matches();
			$header = 'Matches (Season 2)';
	        } else if($season_id == 3){
	                $matches = $db->s3_matches();
			$header = 'Matches (Season 3)';
	        } else if($season_id == 4 || !$season_id){
	                $matches = $db->s4_matches();
			$header = 'Matches (Season 4)';
	        } else {
			$matches = $db->all_matches();
			$header = 'All Matches';
		}
	}
	$header = '<h1>'.$header.'</h1>';
	include('matchlist.php');
?>
<?php include('navi-footer.php'); ?>
