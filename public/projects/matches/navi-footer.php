				</section>
			</div>
		</div>
		<div id="sidebar">
			<div class="inner">
				<section>
					<div id="user-container">
						<?php if(!$_SESSION['loggedin']){ ?>
							<header class="major">
								<h2>Welcome</h2>
							</header>
							<strong id="notifier">Please register and login to get access to more features.</strong>
							<br/><br/>
							<input type="button" value="Login" onclick="location.href='/login?<?php echo get_direct_to(); ?>';" />
							<input type="button" value="Register" onclick="location.href='/register?<?php echo get_direct_to(); ?>';" />
						<?php }  else {
							echo "<header class='major'><h2>Welcome, {$_SESSION['username']}</h2></header>";
							echo "<h3>Available Points: {$pointsAvailable}<br/>Total Points: {$pointsTotal}</h3></hr>";
							echo '<input type="button" value="Logout" onclick="location.href=`/logout`"' . get_direct_to() . '`" />';
							echo '<a href="/account/" class="m-2 float-right">Account Settings</a>';
						}
						 ?>
					</div>
				</section>
				<nav id="menu">
					<header class="major">
						<h2>Menu</h2>
					</header>
					<ul>
						<li><a href="/projects/matches/">Home</a></li>
						<?php if(count($matches_bets_open)) echo "<li class='font-weight-bold'><a href='/projects/matches/matches?type=bets_open'>Matches (Bets Open)</a></li>"; ?>
						<?php if(count($royalrumbles_open)) echo "<li><a href='/projects/matches/royalrumble'>Royal Rumble</a></li>"; ?>
						<li>
							<span class="opener">Matches</span>
							<ul>
								<a href="/projects/matches/matches?season=5">Season 5</a>
								<a href="/projects/matches/matches?season=4">Season 4</a>
								<a href="/projects/matches/matches?season=3">Season 3</a>
								<a href="/projects/matches/matches?season=2">Season 2</a>
								<a href="/projects/matches/matches?season=1">Season 1</a>
							</ul>
						</li>
						<li>
							<span class="opener">Roster</span>
							<ul>
								<?php foreach($db->all_brands() as $brand){
									echo '<li><a href="/projects/matches/roster?brand_id='.$brand['id'].'">'.$brand['name'].'</a></li>';
								}?>
							</ul>
						</li>
						<li>
							<span class="opener">Leaderboard</span>
							<ul>
								<a href="/projects/matches/leaderboard?season=5">Season 5</a>
								<a href="/projects/matches/leaderboard?season=4">Season 4</a>
								<a href="/projects/matches/leaderboard?season=3">Season 3</a>
								<a href="/projects/matches/leaderboard?season=2">Season 2</a>
								<a href="/projects/matches/leaderboard?season=1">Season 1</a>
							</ul>
						</li>
						<li><a href="/projects/matches/FAQs">FAQs</a></li>
					</ul>
				</nav>
				<section>
					<header class="major">
						<h2>Join our Discord!</h2>
					</header>
					<div>
						<iframe src="https://discordapp.com/widget?id=361689774723170304&theme=dark&username=<?php echo $_SESSION['username']; ?>" width="225" height="400" allowtransparency="true" frameborder="0"></iframe>
					</div>
				</section>
				<footer id="footer">
					<p class="copyright">
						<ul class="alt">
							<li>&copy; 2017-2021 Jesus Andrade</li>
							<li><a href="https://freedns.afraid.org/">Free DNS</a> | <a href="/privacy-policy">Privacy Policy</a></li>
							<li>Design: <a href="https://html5up.net">HTML5 UP</a></li>
						</ul>
					</p>
				</footer>
			</div>
		</div>
	</div>
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/js/skel.min.js"></script>
	<script src="assets/js/util.js"></script>
	<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
	<script src="assets/js/main.js"></script>
	<script type="text/javascript">
		var cf = $('#contactForm');
		cf.submit(function (ev) {
			$.ajax({
				type: cf.attr('method'),
				url: cf.attr('action'),
				data: cf.serialize(),
				success: function (data) {
					$('#contactForm :input').prop('readonly', true);
					$('input[type="submit"]').prop('disable', false);
					$('#contactForm').hide();
					$('#messageAck').show();
				}
			});
			ev.preventDefault();
		});
		function updateFavorite(superstarID){
			$.post('scripts/updateFavorite', {'superstarID':superstarID},
				function(response){
					response = JSON.parse(response);
					alert(response.message);
				}
			);
			return false;
		}
		function updateMatchRating(match_id, rating){
			$.post('scripts/updateRating', {'match_id':match_id, 'rating':rating},
				function(response){
					response = JSON.parse(response);
					alert(response.message);
					if(response.success)
						location.reload();
				}
			);
			return false;
		}
		function placeBet(match_id){
			var superstar = $('#form_match_' + match_id).find(":selected").text();
			var amount = $('#form_match_' + match_id).find("input[name=bet_amount]").val();
			amount = amount.replace(/\,/g,'');
			if(superstar!='Superstar' && amount>0){
				$.post('scripts/userBet', {'match_id':match_id,'superstar':superstar,'bet':amount},
					function(response){
						response = JSON.parse(response);
						alert(response.message);
						if(response.success){
							location.reload();
						}
					}
				);
			} else {
				alert('Invalid Bet');
			}
			return false;
		}
		function prettyURI(toRemove, toReplace){
			toReplace = toReplace.replace(/[^a-zA-Z0-9]/g,'-');
			var newURI = window.location.href.replace(toRemove, "/"+toReplace);
			console.log(newURI);
			window.history.replaceState("", "", newURI);
		}
	</script>
</body>
</html>
