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
						<?php if(!$_SESSION['user_id']){ ?>
						<strong id="notifier">Please register and login to get access to more features.</strong>
						<br/>
						<input placeholder="username" type="text" id="username" />
						<input placeholder="password" type="password" id="password" />
						<input placeholder="verify password" type="password" id="password-verify" style="display:none;" />
						<br/>
						<input type="button" id="login-button" value="Login" onclick="login()" />
						<input type="button" id="register-button" value="Register" onclick="register()" />
						<?php } ?>
					</div>
				</section>
				<nav id="menu">
					<header class="major">
						<h2>Menu</h2>
					</header>
					<ul>
						<li><a href="/projects/matches">Homepage</a></li>
						<li><a href="/projects/matches/event.php">Events</a></li>
						<!--li><a href="/projects/matches/champions.php">Champions</a></li-->
						<!--li><a href="/projects/matches/stables.php">Stables</a></li-->
						<!--li><a href="/projects/matches/superstars.php">Superstars</a></li-->
						<li>
							<span class="opener">Rosters</span>
							<ul>
							<?php foreach($_SESSION['data']['brand'] as $brand){
								echo '<li><a href="/projects/matches/brand.php?brand_id='.$brand['id'].'">'.$brand['name'].'</a></li>';
							}?>
							</ul>
						</li>
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
				<section>
					<header class="major">
						<h2>Get in touch</h2>
					</header>
					<p>Feel free to drop me any feedback, requests, or questions you may have.</p>
					<form id="contactForm" method="post" action="/scripts/post_feedback.php">
						<input type="hidden" name="subject" id="subject" value="Matches"/>
						<div class="row uniform">
							<div class="6u 12u$(xsmall)">
								<input type="text" name="name" id="name" value="" placeholder="Name" required/>
							</div>
							<div class="6u$ 12u$(xsmall)">
								<input type="email" name="email" id="email" value="" placeholder="Email" required/>
							</div>
							<div class="12u$">
								<textarea name="message" id="message" placeholder="Enter your message" rows="6" required></textarea>
							</div>
							<div class="12u$">
								<ul class="actions">
									<li><input type="submit" value="Send Message" class="special"/></li>
									<li><input type="reset" value="Reset"/></li>
								</ul>
							</div>
						</div>
					</form>
					<h2 id="messageAck" hidden>Message Sent. Thanks!</h2>
				</section>
				<footer id="footer">
					<p class="copyright">
						<ul class="alt">
							<li>© 2017 Jesus Andrade</li>
							<li>Design: <a href="https://html5up.net">HTML5 UP</a></li>
							<li>DNS: <a href="https://freedns.afraid.org/">Free DNS</a></li>
							<li>Page Last Updated: <?php echo date("Y.m.d H:i:s", getlastmod()); ?></i>
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
							"<input type='button' id='logout-button' value='Logout' onclick='logout()' />" +
							"<a href='/scripts/passwordchange.php?project=matches' id='changepw-link' class='m-2 float-right'>Change Password</a>"
						);
					}
					document.querySelector("#sidebar > a").text = ""; // yeah, idk why it puts "Toggle"
				}
			);
		}
		function verify(){
			var username = document.getElementById('username').value.trim();
			var secret = document.getElementById('password').value.trim();
			if(username==''){
				notifier.innerHTML="Invalid username.";
				return false;
			}
			if(secret==''){
				notifier.innerHTML="Invalid password.";
				return false;
			}
			return true;
		}
		function login(){
			if(verify()){
				var username = document.getElementById('username').value.trim();
				var secret = document.getElementById('password').value.trim();
				$.post('/scripts/login.php', {'username':username, 'secret':secret},
			function(data){
				if(data!=0){
					location = "/projects/matches/";
				} else {
					notifier.innerHTML="Invalid username or password.";
				}
			});
			} else {
				return false;
			}
		}
		function register(){
			if(verify()){
				var username = document.getElementById('username').value.trim();
				var secret = document.getElementById('password').value.trim();
				var secret_verify = document.getElementById('password-verify').value.trim();
				if(secret_verify==''){
					notifier.innerHTML="Please re-enter your password to register.";
					document.getElementById('password-verify').style.display="inline";
					return false;
				}
				if(secret!=secret_verify){
					notifier.innerHTML="Passwords do not match.";
					return false;
				}
				$.post('/scripts/register.php', {'username':username, 'secret':secret, 'secret_verify':secret_verify},
					function(data){
						if(data!=0){
							location = "/projects/matches/";
						} else {
							notifier.innerHTML="Failed to register. Username might be taken or invalid.";
						}
					}
				);
			} else {
				return false;
			}
		}
		function logout(){
			document.location.href="/scripts/logout.php";
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
