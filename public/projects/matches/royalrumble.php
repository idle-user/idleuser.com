<?php
	require_once $_SERVER['DOCUMENT_ROOT'] . '/../src/session.php';
	$all_entries = $db->all_royalrumble_entries();

	$meta = [
		"keywords" => "Royal Rumble, random number, enter royal rumble, watchwrestling, WWE, AEW, NJPW, ROH, IMPACT, wrestling, bet, points, fjbot, chatroom, streams, watch online, wrestling discord, discord",
		"og:title" => "WatchWrestling - Royal Rumble ",
		"og:description" => "Join the Royal Rumble! Enter to get a random entry number and watch to see if your entry wins. Registered users win point prizes."
	];
	include 'header.php';
?>
<header class="main">
	<h1>Royal Rumble Pool</h1>
	<p>Test your luck in this year's Royal Rumble<br/>Get an entry number and watch to see if your entrant is the winner!</p>
</header>
<hr class="major"/>
<div class="table-wrapper">
	<h2>Enter the Rumble!</h2>
	<p>
		Enter a display name, leave a comment for everyone to see, and enter the Royal Rumble Pool!
		<br/>Signed-in users will have their username prefilled and have a chance to win points per event!
	</p>
	<p class="small font-italic">Points earned are based off current leaderboard.</p>
	<form id="entryForm" method="post">
		<div class="row uniform">
			<div class="12u row">
				<div class="4u">
					<label for="entry_username">Display Name</label>
					<input type="text" name="display_name" id="entry_username" maxlength="20" placeholder="required" <?php if($_SESSION['username']){ echo 'value="'.$_SESSION['username'].'" readonly="readonly"'; } ?> required />
				</div>
				<div class="6u">
					<label for="entry_comment">Comment</label>
					<input type="text" name="comment" id="entry_comment" maxlength="50" placeholder="optional" />
				</div>
			</div>
			<div class="12u row">
				<div class="6u">
					<label for="entry_event">Event</label>
					<select name="royalrumble_id" id="entry_event" required>
						<option value="" disabled selected>Select Event ...</option>
					<?php
						foreach($all_entries as $entries){
							if($entries[0]['entry_won']) continue;
							echo '<option value="'.$entries[0]['royalrumble_id'].'">'.$entries[0]['event_name'];if($entries[0]['description']) echo ' ('.$entries[0]['description'].')'; echo '</option>';
						}
					?>
					</select>
				</div>
				<div class="2u">
					<label for="entry_button">Enter the Rumble</label>
					<input type="submit" id="entry_button" name="royalrumble-entry-add" value="Enter Now" />
				</div>
			</div>
		</div>
	</form>
	<i>Note:
		<ul>
			<li>Registered users will receive a unique entry number.</li>
			<li>Once all registered entry numbers are filled, duplicates will occur.</li>
			<li>Unregistered user entries may not receive a unique entry number.</li>
			<li>Only registered users may win the point prize.</li>
		</ul>
	</i>
	<br/>
	<div id="entryAck" class="h3 m-4 text-center"></div>
	<audio id="audioControl" class="d-none" style="margin: 0 auto; display: block;" controls>
		<source src="audio/royalrumble_countdown.mp3" type="audio/mpeg">
	</audio>
</div>
<hr class="major"/>
<div class="inner">
	<h2>Current Entries</h2>
	<?php
		foreach($all_entries as $entries){
			if($entries[0]['entry_won']) continue;
			echo '<h3 title="'.$entries[0]['event_dt'].'">'.$entries[0]['event_name'].'</h3>';
			if($entries[0]['description']) echo '<i>'.$entries[0]['description'].'</i>';
	?>
	<div class="table-wrapper">
		<table id="entryTable_<?php echo $entries[0]['royalrumble_id']; ?>" class="alt">
			<thead>
				<tr>
					<th>Display Name</th>
					<th>Comment</th>
					<th>Entry Number</th>
					<th>Entered</th>
				</tr>
			</thead>
			<tbody>
			<?php
				$cnt = 0;
				foreach($entries as $entrant) {
					if(!$entrant['display_name']) continue;
					$cnt = $cnt+1;
			?>
				<tr>
				<?php if($entrant['user_id']){ ?>
					<td><a href="/projects/matches/user?user_id=<?php echo $entrant['user_id']; ?>"><?php echo $entrant['display_name']; ?></a></td>
				<?php } else{ ?>
					<td><?php echo $entrant['display_name']; ?></td>
				<?php } ?>
					<td><?php echo $entrant['comment']; ?></td>
					<td><?php echo $entrant['entry']; ?></td>
					<td class="w-25"><?php echo $entrant['entered']; ?></td>
				</tr>
			<?php
				}
				if($cnt==0) {
			?>
				<tr>
					<td>Be the first to enter!</td>
					<td>Be the first to enter!</td>
					<td>Be the first to enter!</td>
					<td>Be the first to enter!</td>
				</tr>
			<?php
				}
			?>
			</tbody>
		</table>
	</div>
	<?php
		}
	?>
</div>
<hr class="major"/>
<div class="inner">
	<h2>Previous Winners</h2>
	<?php
		foreach($all_entries as $entries){
			if(!$entries[0]['entry_won']) continue;
			echo '<h3 title="'.$entries[0]['event_dt'].'">'.$entries[0]['event_name'].'</h3>';
			if($entries[0]['description']) echo '<i>'.$entries[0]['description'].'</i>';
	?>
	<div class="table-wrapper">
		<table id="winnerTable_<?php echo $entries[0]['royalrumble_id']; ?>" class="alt">
			<thead>
				<tr>
					<th>Username</th>
					<th>Comment</th>
					<th>Entry Number</th>
					<th>Entered</th>
				</tr>
			</thead>
			<tbody>
			<?php
				$cnt=0;
				foreach($entries as $entrant) {
					if(!$entrant['winner']) continue;
					$cnt = $cnt+1;
			?>
				<tr>
				<?php if($entrant['user_id']){ ?>
					<td><a href="/projects/matches/user?user_id=<?php echo $entrant['user_id']; ?>"><?php echo $entrant['display_name']; ?></a></td>
				<?php } else{ ?>
					<td><?php echo $entrant['display_name']; ?></td>
				<?php } ?>
					<td><?php echo $entrant['comment']; ?></td>
					<td><?php echo $entrant['entry']; ?></td>
					<td class="w-25"><?php echo $entrant['entered']; ?></td>
				</tr>
			<?php
				}
				if($cnt==0) {
			?>
				<tr>
					<td></td>
					<td></td>
					<td><?php echo $entries[0]['entry_won']; ?></td>
					<td></td>
				</tr>
			<?php
				}
			?>
			</tbody>
		</table>
	</div>
	<?php
		}
	?>
</div>
<hr class="major"/>
<div class="inner">
	<h2>Previous Entrants</h2>
	<?php
		foreach($all_entries as $entries){
			if(!$entries[0]['entry_won']) continue;
			echo '<h3 title="'.$entries[0]['event_dt'].'">'.$entries[0]['event_name'].'</h3>';
			if($entries[0]['description']) echo '<i>'.$entries[0]['description'].'</i>';
	?>
	<div class="table-wrapper table-sm">
		<table id="entryTable_<?php echo $entries[0]['royalrumble_id']; ?>" class="alt">
			<thead>
				<tr>
					<th>Display Name</th>
					<th>Comment</th>
					<th>Entry Number</th>
					<th>Entered</th>
				</tr>
			</thead>
			<tbody>
			<?php
				foreach($entries as $entrant) {
					if($entrant['winner']) continue;
			?>
				<tr>
				<?php if($entrant['user_id']){ ?>
					<td><a href="/projects/matches/user?user_id=<?php echo $entrant['user_id']; ?>"><?php echo $entrant['display_name']; ?></a></td>
				<?php } else{ ?>
					<td><?php echo $entrant['display_name']; ?></td>
				<?php } ?>
					<td><?php echo $entrant['comment']; ?></td>
					<td><?php echo $entrant['entry']; ?></td>
					<td class="w-25"><?php echo $entrant['entered']; ?></td>
				</tr>
			<?php
				}
			?>
			</tbody>
		</table>
	</div>
	<?php
		}
	?>
</div>
<?php include 'navi-footer.php'; ?>
<script type="text/javascript">
	var ef = $('#entryForm');
	var audio = document.getElementById("audioControl");
	var response = <?php echo json_encode($response); ?>;
	if(response){
		if(response.statusCode == 200){
			$('#entryForm :input').prop('readonly', true);
			$('input[type="submit"]').prop('disable', true);
			$('#entryForm').hide();
			$('#entryForm').addClass("d-none");
			$('#audioControl').removeClass("d-none");
			function countdown() {
				audio.play();
				audio.volume = 0.15;
				var count = 10;
				$('#entryAck').empty();
				$('#entryAck').append('<h1>' + count-- + '</h1>');
				var interval = window.setInterval(function () {
					$('#entryAck').empty();
					if(count == 0) {
						window.clearInterval(interval);
						$('#entryAck').append('<h1>#' + response.data.entry + '</h1>');
						$('#entryForm :input').prop('readonly', false);
						$('input[type="submit"]').prop('disable', false);
						$('#entryForm').removeClass("d-none");
						$('#entryForm').show(1000);
					} else {
						$('#entryAck').append('<h1>' + count-- + '</h1>');
					}
				} , 1000);
			}
			countdown();
		} else {
			$('#entryAck').empty();
			$('#entryAck').append(response.error.description);
		}

		$('#entryAck').show();
	}

</script>
