				</section>
			</div>
		</div>
		<div id="sidebar">
			<div class="inner">
				<section>
					<header class="major">
						<h2 id="user-welcome">Welcome</h2>
					</header>
					<div id="user-container">
						<?php if(!$_SESSION['loggedin']){ ?>
							<strong id="notifier">Please register and login to get access to more features.</strong>
							<br/><br/>
							<input type="button" value="Login" onclick="location.href='/login.php?<?php echo get_direct_to(); ?>';" />
							<input type="button" value="Register" onclick="location.href='/register.php?<?php echo get_direct_to(); ?>';" />
						<?php } ?>
					</div>
				</section>
				<nav id="menu">
					<header class="major">
						<h2>Menu</h2>
					</header>
					<ul>
						<li><a href="/projects/matches">Homepage</a></li>
						<?php if(count($matches_bets_open)) echo "<li class='font-weight-bold'><a href='/projects/matches/matches.php?type=bets_open'>Matches (Bets Open)</a></li>"; ?>
						<!--li><a href="/projects/matches/royalrumble.php">Royal Rumble</a></li-->
						<li>
							<span class="opener">Matches</span>
							<ul>
								<a href="/projects/matches/matches.php?season_id=4">Season 4</a>
								<a href="/projects/matches/matches.php?season_id=3">Season 3</a>
								<a href="/projects/matches/matches.php?season_id=2">Season 2</a>
								<a href="/projects/matches/matches.php?season_id=1">Season 1</a>
							</ul>
						</li>
						<li>
							<span class="opener">Rosters</span>
							<ul>
								<?php foreach($db->all_brands() as $brand){
									echo '<li><a href="/projects/matches/brand.php?brand_id='.$brand['id'].'">'.$brand['name'].'</a></li>';
								}?>
							</ul>
						</li>
						<li>
							<span class="opener">Leaderboard</span>
							<ul>
								<a href="/projects/matches/leaderboard.php?season_id=4">Season 4</a>
								<a href="/projects/matches/leaderboard.php?season_id=3">Season 3</a>
								<a href="/projects/matches/leaderboard.php?season_id=2">Season 2</a>
								<a href="/projects/matches/leaderboard.php?season_id=1">Season 1</a>
							</ul>
						</li>
						<!--li><a href="/projects/matches/chatroom.php">Chatroom</a></li-->
						<!--li><a href="/projects/matches/shop.php">Shop</a></li-->
						<li><a href="/projects/matches/FAQs.php">FAQs</a></li>
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
							<li>© 2017 Jesus Andrade</li>
							<li><a href="https://freedns.afraid.org/">Free DNS</a> | <a href="/privacy-policy.php">Privacy Policy</a></li>
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
		function updateData(){
			$.post('scripts/userData.php', {},
				function(response){
					data = JSON.parse(response);
					if(data){
						$("#user-welcome").html("Welcome, "+data.username);
						$("#user-container").html(
							"<h3>Available Points: " + String(data.s4_available_points).replace(/\B(?=(\d{3})+(?!\d))/g, ",") +
							"<br/>Total Points: " + String(data.s4_total_points).replace(/\B(?=(\d{3})+(?!\d))/g, ",") +
							"</h3></hr>" +
							`<input type="button" value="Logout" onclick="location.href='/logout.php?<?php echo get_direct_to(); ?>'" />` +
							`<a href="/account.php?<?php echo get_direct_to(); ?>" class="m-2 float-right">Change Password</a>`
						);
					}
					document.querySelector("#sidebar > a").text = ""; // yeah, idk why it puts "Toggle"
				}
			);
		}
		function updateEmail(){
			var email = $('#email_row').find("td")[1].innerText;
			$.post('/scripts/email_link.php', {'email':email},
				function(response){
					response = JSON.parse(response);
					alert(response.message);
				}
			);
			return false;
		}
		function updateDiscordID(){
			var discordID = $('#discord_row').find("td")[1].innerText;
			$.post('/scripts/discord_link.php', {'discord_id':discordID},
				function(response){
					response = JSON.parse(response);
					alert(response.message);
				}
			);
			return false;
		}
		function updateChatangoID(){
			var chatangoID = $('#chatango_row').find("td")[1].innerText.toLowerCase();
			$.post('/scripts/chatango_link.php', {'chatango_id':chatangoID},
				function(response){
					response = JSON.parse(response);
					alert(response.message);
				}
			);
			return false;
		}
		function updateFavorite(superstarID){
			$.post('scripts/updateFavorite.php', {'superstarID':superstarID},
				function(response){
					response = JSON.parse(response);
					alert(response.message);
				}
			);
			return false;
		}
		function updateMatchRating(match_id, rating){
			$.post('scripts/updateRating.php', {'match_id':match_id, 'rating':rating},
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
				$.post('scripts/userBet.php', {'match_id':match_id,'superstar':superstar,'bet':amount},
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
		updateData();
	</script>
</body>
</html>
