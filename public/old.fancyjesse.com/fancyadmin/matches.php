<?php require_once('assets/php/admin.php'); ?>
<!DOCTYPE html>
<html>
<head>
	<title>Admin - Matches</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="assets/favicon.ico">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="assets/js/custom.js"></script>
	<link rel="stylesheet" href="assets/css/custom.css">
</head>
<body>
<?php include('nav.php'); ?>
	<div align="center" class="container">
		<h1>Matches - Match Editor</h1>
	</div>
	<div id="placeholder" class="container">
		<h2>Loading matches ...</h2>
	</div>
	<div id="content" class="container" hidden="true">
		<div>
			<form>
				<select id="match_select" onchange="showMatch();">
					<option value="" disabled selected>Select Match ...</option>
				</select>
			</form>
		</div>
		<div id="match">
			<div id="stats"></div>
			<div id="event"></div>
			<div id="type"></div>
			<div id="title"></div>
			<div id="contestant"></div>
			<div id="winner"></div>
			<div id="bet"></div>
			<div id="action"></div>
		</div>
	</div>
	<br/>
	<div class="container" <?php if($_SESSION['user_id'] != 1) echo 'hidden'; ?>>
		<h1>User Bets</h2>
		<div>
			<table width="100%">
				<thead align="center">
					<tr>
						<th>Placed</th>
						<th>Match ID</th>
						<th>Username</th>
						<th>Team</th>
						<th>Points</th>
					</tr>
				</thead>
				<tbody id="user_bets" align="center"></tbody>
			</table>
		</div>
	</div>
	<script type="text/javascript">
		var user_bet_table = document.getElementById("user_bets");
		var now = new Date();
		var day = ("0" + now.getDate()).slice(-2);
		var month = ("0" + (now.getMonth() + 1)).slice(-2);
		var today = now.getFullYear()+"-"+(month)+"-"+(day);
		var events = [];
		var title = [];
		var superstar = {};
		var match = {};
		var bets = {};
		var match_type = [];
		var orderedEKeys = [];
		var orderedTKeys = [];
		var orderedSKeys = [];
		var orderedMKeys = [];
		function updateData(){
			document.querySelector("#placeholder").hidden = false;
			document.querySelector("#content").hidden = true;

			$.ajax({
				type: 'POST',
				url: 'assets/php/match-data.php',
				dataType: 'json',
				success: function (data) {
					events = data['event'];
					orderedEKeys = Object.keys(events).sort(function(a,b){return events[b].date_time.localeCompare(events[a].date_time);});

					title = data['title'];
					orderedTKeys = Object.keys(title).sort(function(a,b){return title[a].name.localeCompare(title[b].name);});

					superstar = data['superstar'];
					orderedSKeys = Object.keys(superstar).sort(function(a,b){return superstar[a].name.localeCompare(superstar[b].name);});

					match_type = data['match_type'];

					match = data['match'];
					orderedMKeys = Object.keys(match).sort(function(a,b){return (match[a].open+match[a].date+match[a].id).localeCompare(match[b].open+match[b].date+match[b].id);});

					bets = data['user_bets'];

					updateMatchList();
					checkParams();
					document.querySelector("#placeholder").hidden = true;
					document.querySelector("#content").hidden = false;
				}
			});
		}
		function updateMatchList(){
			var select = document.getElementById("match_select");
			var option = document.createElement("option");
			option.setAttribute("match_id", 0);
			option.text = "New Match";
			select.add(option);
			for(var i=0;i<orderedMKeys.length;i++){
				m = match[orderedMKeys[i]];
				option = document.createElement("option");
				var cs = [];
				for(var j in m.contestants){
					c = m.contestants[j];
					cs.push(superstar[c['superstar_id']]['name']);
				}
				var t = '';
				var tn = '';
				if(m.title_id>0){
					tn = '(c) '; //title[m.title_id].name + ' - ';
				}
				if(m.match_type_id!=0){
					t += match_type[m.match_type_id].name + ' - '
				}
				option.setAttribute("match_id", m.id);
				option.text = '[' + m.date + '] ' + tn + t + cs + ' (' + m.id + ')';
				if(m.bet_open==1){
					option.style.color="green";
				} else {
					if(m.team_won==0)
						option.style.color="orange";
					else
						option.style.color="red";
				}
				select.add(option, 2);
			}
		}
		function showMatch(){
			$("#stats").html("<h3>STATS</h3>");
			$("#event").html("<hr><h3>EVENT</h3>");
			$("#type").html("<hr><h3>MATCH TYPE</h3>");
			$("#title").html("<hr><h3>TITLE</h3>");
			$("#contestant").html("<hr><h3>CONTESTANTS</h3>");
			$("#winner").html("<hr><h3>WINNER</h3>");
			$("#action").html("<hr>");
			$("#user_bets").html("");

			// match info
			var options = document.getElementById("match_select");
			var selected = options.options[options.selectedIndex];
			var m = selected.getAttribute("match_id");
			m = match[m];
			if(!m){
				m = {};
				m.id = 0;
				m.date = today;
				m.event_id = 0;
				m.match_type_id = 0;
				m.team_won = 0;
				m.contestants = [];
				m.bet_open = 1;
				m.bet_multiplier= "N/A";
				m.base_pot= "N/A";
				m.total_pot = "N/A";
				m.user_rating_avg = "N/A";
				m.user_rating_cnt = 0;
				if(now.getDay() == 6 || now.getDay() == 0){
					m.bet_multiplier = 2;
				} else {
					m.bet_multiplier = 1;
				}
				m.match_note = "";
				m.winner_note = "";
			}

			// stats
			var bet_open_select = document.createElement("select");
			bet_open_select.label = "Open";
			var bet_open_option = document.createElement("option");
			bet_open_option.setAttribute("bet_open", 0);
			bet_open_option.text = 0;
			bet_open_select.add(bet_open_option);
			bet_open_option = document.createElement("option");
			bet_open_option.setAttribute("bet_open", 1);
			bet_open_option.text = 1;
			if(m.bet_open==1){
				bet_open_option.selected = true;
			}
			bet_open_select.add(bet_open_option);
			document.getElementById("stats").innerHTML += "Base Pot: "+m.base_pot+"<br/>Bet Multiplier: "+m.bet_multiplier+"<br/>Total Pot: "+m.total_pot+"<br/>Rating: "+m.user_rating_avg+" ("+m.user_rating_cnt+")<br/>Bet Open: ";
			document.getElementById("stats").appendChild(bet_open_select);

			// event
			var event_select = document.createElement("select");
			var event_option = document.createElement("option");
			event_option.setAttribute("match_type_id", 0);
			event_option.text = "Select Event";
			event_option.style.color="red";
			event_select.add(event_option);
			var event_selected = false;
			for(var i=0; i<orderedEKeys.length; i++){
				e = events[orderedEKeys[i]];
				e.date = e.date_time.split(" ")[0];
				event_option = document.createElement("option");
				event_option.setAttribute("event_id", e.id);
				event_option.text = '[' + e.date + '] ' + e.name + ' ('+e.id+')';
				if(e.date<today){
					event_option.style.color="red";
				} else {
					event_option.style.color="green";
				}
				if(e.id==m.event_id){
					event_option.selected = true;
					event_selected = true;
				} else if(!event_selected && e.date==today){
					event_option.selected = true;
					event_selected = true;
				} else if(!event_selected && e.date>today){
					event_option.selected = true;
				}
				event_select.add(event_option);
			}
			document.getElementById("event").appendChild(event_select);

			// match type
			var type_select = document.createElement("select");
			var type_option = document.createElement("option");
			type_option.setAttribute("match_type_id", 0);
			type_option.text = "N/A (Do Not Display)";
			type_option.style.color="red";
			type_select.add(type_option);
			for(var type in match_type){
				type = match_type[type];
				type_option = document.createElement("option");
				type_option.setAttribute("match_type_id", type.id);
				type_option.text = type.name + ' ('+type.id+')';;
				if(type.id==m.match_type_id){
					type_option.selected = true;
				}
				type_select.add(type_option);
			}
			document.getElementById("type").appendChild(type_select);
			var mnote = document.createElement("input");
			mnote.type = "text";
			mnote.placeholder = "Match Note";
			mnote.value = m.match_note;
			document.getElementById("type").appendChild(mnote);

			// title option
			var title_select = document.createElement("select");
			var title_option = document.createElement("option");
			title_option.setAttribute("title_id", 0);
			title_option.text = "None";
			title_select.add(title_option);
			for(var i=0; i<orderedTKeys.length; i++){
				t = title[orderedTKeys[i]];
				title_option = document.createElement("option");
				title_option.setAttribute("title_id", t.id);
				title_option.text = t.name + ' ('+t.id+')';
				if(t.id==m.title_id){
					title_option.selected = true;
				}
				title_select.add(title_option);
			}
			document.getElementById("title").appendChild(title_select);

			// superstar option
			var superstar_select = document.createElement("select");
			var superstar_option = document.createElement("option");
			superstar_option.setAttribute("superstar_id", 0);
			superstar_option.text = "Select Superstar ...";
			superstar_select.add(superstar_option);
			for(var i=0; i<orderedSKeys.length; i++){
				s = superstar[orderedSKeys[i]];
				superstar_option = document.createElement("option");
				superstar_option.setAttribute("superstar_id", s.id);
				superstar_option.text = s.name + ' ('+s.id+')';
				superstar_select.add(superstar_option);
			}

			// bet multiplier option
			var bet_multi_select = document.createElement("select");
			var bet_multi_option = document.createElement("option");
			for(var i=1; i<11; i++){
				bet_multi_option = document.createElement("option");
				bet_multi_option.setAttribute("bet_multi", i);
				bet_multi_option.text = i;
				if(bet_multi_option.text==m.bet_multiplier){
					bet_multi_option.selected = bet_multi_option.text==m.bet_multiplier;
				}
				bet_multi_select.add(bet_multi_option);
			}

			// arrange teams
			var team = {};
			for(var c in m.contestants){
				c = m.contestants[c];
				contestant_select = superstar_select.cloneNode(true);
				for(var i=0; i<contestant_select.options.length; i++){
					if(contestant_select.options[i].getAttribute("superstar_id")==c.superstar_id){
						contestant_select.options[i].selected = true;
						if(!team[c.team]){
							team[c.team] = [];
						}
						c.select_element = contestant_select;
						team[c.team].push(c);
						break;
					}
				}
			}

			// contestants and winner
			var winner_select = document.createElement("select");
			var winner_option = document.createElement("option");
			winner_option.setAttribute("team_won", 0);
			winner_option.text = 0;
			winner_select.add(winner_option);
			winner_option = document.createElement("option");
			winner_option.setAttribute("team_won", 999);
			winner_option.text = "NO CONTEST";
			if(m.team_won==winner_option.getAttribute("team_won")){
				winner_option.selected = true;
			}
			winner_select.add(winner_option);
			winner_option = document.createElement("option");
			winner_option.setAttribute("team_won", 998);
			winner_option.text = "UPDATING ...";
			if(m.team_won==winner_option.getAttribute("team_won")){
				winner_option.selected = true;
			}
			winner_select.add(winner_option);
			for(var t in team){
				var p = document.createElement("p");
				p.setAttribute("team", t);
				p.innerHTML = "<b>Team "+t+"</b><br/>Bet Multiplier: ";
				multiplier_select = bet_multi_select.cloneNode(true);
				multiplier_select.setAttribute("team", t);
				multiplier_select.options[team[t][0].bet_multiplier-1].selected = true;
				p.appendChild(multiplier_select);
				p.appendChild(document.createElement("br"));
				for(var c in team[t]){
					team[t][c].select_element.setAttribute("team", t);
					p.appendChild(team[t][c].select_element);
				}
				default_node = team[t][c].select_element.cloneNode(true);
				p.appendChild(default_node);
				document.getElementById("contestant").appendChild(p);
				winner_option = document.createElement("option");
				winner_option.setAttribute("team_won", t);
				winner_option.text = t;
				if(m.team_won==t){
					winner_option.selected = true;
				}
				winner_select.add(winner_option);
			}

			// new team button
			var b1 = document.createElement("input");
			b1.type = "button";
			b1.value = "Add Team ...";
			b1.onclick = function(){
				var team_cnt = Object.keys(team).length;
				var p = document.createElement("p");
				p.setAttribute("team", team_cnt+1);
				p.innerHTML = "<b>Team "+(team_cnt+1)+"</b><br/>Bet Multiplier: ";
				multiplier_select = bet_multi_select.cloneNode(true);
				multiplier_select.setAttribute("team", team_cnt+1);
				p.appendChild(multiplier_select);
				p.appendChild(document.createElement("br"));
				contestant_select = superstar_select.cloneNode(true);
				contestant_select.setAttribute("team", team_cnt+1);
				team[team_cnt+1] = [{'select_element': contestant_select}];
				p.appendChild(contestant_select);
				document.getElementById("contestant").insertBefore(p, this);
			};
			document.getElementById("contestant").appendChild(b1);

			// add contestants button
			var b2 = document.createElement("input");
			b2.type = "button";
			b2.value = "Add Contestants ...";
			b2.onclick = function(){
				var team_container = document.getElementById("contestant").getElementsByTagName("p");
				for(var i=0; i<team_container.length; i++){
					contestant_select = superstar_select.cloneNode(true);
					contestant_select.setAttribute("team", i+1);
					team_container[i].appendChild(contestant_select);
				}
			};
			document.getElementById("contestant").appendChild(b2);

			var h = document.createElement("h5");
			h.innerHTML = "Team: ";
			h.appendChild(winner_select);
			var wnote = document.createElement("input");
			wnote.type = "text";
			wnote.placeholder = "Winner Note";
			wnote.value = m.winner_note;
			h.appendChild(wnote);
			document.getElementById("winner").appendChild(h);

			var b2 = document.createElement("input");
			b2.type = "button";
			b2.value = "Add/Update";
			b2.onclick = submit;
			document.getElementById("action").appendChild(b2);

			// user bets
			if(m.id && bets[m.id]!=void 0){
				bet = bets[m.id];
				for(var i=0; i<bet.length; i++){
					u = bet[i];
					user_bet_table.innerHTML +=
						'<tr><td>' + u['dt_placed'] +
						'</td><td>' + u['match_id'] +
						'</td><td>' + u['username'] +
						'</td><td>' + u['team'] +
						'</td><td>' + u['points'] +
						'</td></tr>';
				}
			}
		}
		function submit(){
			var match_sel = document.getElementById("match_select");
			var bet_open_sel = document.getElementById("stats").getElementsByTagName("select")[0];
			var match_type_sel = document.getElementById("type").getElementsByTagName("select")[0];
			var event_sel = document.getElementById("event").getElementsByTagName("select")[0];
			var title_sel = document.getElementById("title").getElementsByTagName("select")[0];
			var team_won_sel = document.getElementById("winner").getElementsByTagName("select")[0];

			var match_id = match_sel.options[match_sel.selectedIndex].getAttribute("match_id");
			var event_id = event_sel.options[event_sel.selectedIndex].getAttribute("event_id");
			var match_type_id = match_type_sel.options[match_type_sel.selectedIndex].getAttribute("match_type_id");
			var match_note = document.getElementById("type").getElementsByTagName("input")[0].value.trim();
			var title_id = title_sel.options[title_sel.selectedIndex].getAttribute("title_id");
			var team_won = team_won_sel.options[team_won_sel.selectedIndex].getAttribute("team_won");
			var winner_note = document.getElementById("winner").getElementsByTagName("input")[0].value.trim();
			var bet_open = bet_open_sel.options[bet_open_sel.selectedIndex].getAttribute("bet_open");
			//var bet_multi = bet_multi_sel.options[bet_multi_sel.selectedIndex].getAttribute("bet_multi");

			var contestant = [];
			var team_p = document.getElementById("contestant").getElementsByTagName("p");
			for(var t=0; t<team_p.length; t++){
				var c_sel = team_p[t].getElementsByTagName("select");
				for(var i=1; i<c_sel.length; i++){
					c = c_sel[i].options[c_sel[i].selectedIndex];
					if(c.getAttribute("superstar_id")==0){
						continue;
					}
					contestant.push({
						"superstar_id" : c.getAttribute("superstar_id"),
						"team" : c_sel[i].getAttribute("team"),
						"bet_multiplier" : c_sel[0].options[c_sel[0].selectedIndex].getAttribute("bet_multi")
					});
				}
			}

			var data = {
				'id': match_id,
				'event_id': event_id,
				'title_id': title_id,
				'match_type_id': match_type_id,
				'match_note': match_note,
				'team_won': team_won,
				'winner_note': winner_note,
				'bet_open': bet_open,
				'contestant': contestant,
			};
			console.log(data);

			$.post('assets/php/match-update.php', {'data': data},
				function(data){
					data = JSON.parse(data);
					console.log(data);
					alert(data.message);
					if(data.success){
						var url = window.location.href.substr(0, window.location.href.indexOf('?'));
						url += '?match_id='+data.match_id;
						window.location.href = url;
					}
				}
			);

		}
		function checkParams(){
			var url = new URL(window.location.href);
			var mid = url.searchParams.get("match_id");
			if(mid){
				var matchList = document.getElementsByTagName("option");
				for(var i=0; i<matchList.length; i++){
					if(matchList[i].getAttribute("match_id")==mid){
						document.getElementById("match_select").value = matchList[i].value;
						showMatch();
						return;
					}
				}
			}
			displayUserBets();
		}
		function displayUserBets(){
			var ubs = [];
			for(var bet in bets){
				bet = bets[bet];
				for(var i=0; i<bet.length; i++){
					ubs.push(bet[i]);
				}
			}
			ubs.sort(function(a,b){return new Date(b.dt_placed)-new Date(a.dt_placed);});
			//for(var i=0; i<ubs.length; i++){
			for(var i=0; i<50; i++){
				u = ubs[i];
				user_bet_table.innerHTML +=
					'<tr><td>' + u['dt_placed'] +
					'</td><td>' + u['match_id'] +
					'</td><td>' + u['username'] +
					'</td><td>' + u['team'] +
					'</td><td>' + u['points'] +
					'</td></tr>';
			}

		}
		updateData();
	</script>
</body>
</html>

