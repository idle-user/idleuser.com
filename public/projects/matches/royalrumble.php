<?php include 'header.php';

$all_entries = $db->all_royalrumble_entries();

$response = maybe_process_form();

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
		<br/>Signed-in users will have their username prefilled and have a chance at up to <u><b>50 billion</b></u> points per event!
	</p>
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
						<option value="0" disabled selected>Select Event ...</option>
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
		</ul>
	</i>
	<br/>
	<h2 id="entryAck" />
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
					<td><a href="/projects/matches/user.php?user_id=<?php echo $entrant['user_id']; ?>"><?php echo $entrant['display_name']; ?></a></td>
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
					<td><a href="/projects/matches/user.php?user_id=<?php echo $entrant['user_id']; ?>"><?php echo $entrant['display_name']; ?></a></td>
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
					<td><a href="/projects/matches/user.php?user_id=<?php echo $entrant['user_id']; ?>"><?php echo $entrant['display_name']; ?></a></td>
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


<!-- TODO: update to API response instead-->
<script type="text/javascript">
	var ef = $('#entryForm');
	var audio = document.createElement('audio');
	audio.setAttribute('src', 'audio/royalrumble_countdown.mp3');
	ef.submit(function (ev) {
		$.ajax({
			type: ef.attr('method'),
			url: ef.attr('action'),
			dataType: 'json',
			data: ef.serialize(),
			success: function (data) {
				if(data['success']){
					$('#entryForm :input').prop('readonly', true);
					$('input[type="submit"]').prop('disable', true);
					$('#entryForm').hide();
					$('#entryForm').addClass("d-none");
					function countdown() {
						if (audio.readyState) {
							audio.play();
							audio.volume = 0.15;
						}
						var count = 10;
						$('#entryAck').empty();
						$('#entryAck').append('<h1>' + count-- + '</h1>');
						var interval = window.setInterval(function () {
							$('#entryAck').empty();
							if(count == 0) {
								window.clearInterval(interval);
								$('#entryAck').append(data['data']);
								//$('#entryTable').append(newRow);
								$('#entryForm :input').prop('readonly', false);
								$('input[type="submit"]').prop('disable', false);
								$('#entryForm').show(1000);
							} else {
								$('#entryAck').append('<h1>' + count-- + '</h1>');
							}
						} , 1000);
					}
					countdown();
				} else {
					$('#entryAck').empty();
					$('#entryAck').append(data['data']);
				}
				$('#entryAck').show();
			}
		});
		ev.preventDefault();
	});
</script>
