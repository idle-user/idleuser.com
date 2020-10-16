<?php require_once('assets/php/admin.php'); ?>
<!DOCTYPE html>
<html>
<head>
	<title>Admin - Matches Base</title>
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
		<h1>Matches - Base Data Editor</h1>
	</div>
    <div id="placeholder" class="container">
        <h2>Loading base data ...</h2>
    </div>
    <div id="content" class="container" hidden="true">
	<div>
		<h2>Titles</h2>
		<div>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>ID</th>
						<th>Name</th>
						<th>Last_Updated</th>
						<th>Add/Update</th>
					</tr>
				</thead>
				<tbody id="title_table"></tbody>
			</table>
		</div>
	</div>
	<div>
		<h2>Brand</h2>
		<div>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>ID</th>
						<th>Name</th>
						<th>Add/Update</th>
					</tr>
				</thead>
				<tbody id="brand_table"></tbody>
			</table>
		</div>
	</div>
	<div>
		<h2>Match Type</h2>
		<div>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>ID</th>
						<th>Name</th>
						<th>Add/Update</th>
					</tr>
				</thead>
				<tbody id="match_type_table"></tbody>
			</table>
		</div>
	</div>
	<div>
		<h2>Upcoming Events</h2>
		<div>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>ID</th>
						<th>DateTime (PT)</th>
						<th>Name</th>
						<th>PPV</th>
						<th>Add/Update</th>
					</tr>
				</thead>
				<tbody id="event_table"></tbody>
			</table>
		</div>
	</div>
	<div>
		<h2>Stables</h2>
		<div>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>ID</th>
						<th>Name</th>
						<th>Members</th>
						<th>Add/Update</th>
					</tr>
				</thead>
				<tbody id="stable_table"></tbody>
			</table>
		</div>
	</div>
	</div>
	<script type="text/javascript">
		var today = new Date();
		today = today.getFullYear() + '-' + (today.getMonth()+1) + '-' +today.getDate();
		var superstar = {};
		var orderedSKeys = [];
		var titleTable = document.getElementById('title_table');
		var brandTable = document.getElementById('brand_table');
		var matchTypeTable = document.getElementById('match_type_table');
		var eventTable = document.getElementById('event_table');
		var stableTable = document.getElementById('stable_table');
		//var matchTable = document.getElementById('match_table');
		//var contestantTable = document.getElementById('contestant_table');
		function updateTables(){
			document.querySelector("#placeholder").hidden = false;
			document.querySelector("#content").hidden = true;

			$.ajax({
				type: 'POST',
				url: 'assets/php/data.php',
				dataType: 'json',
				success: function (data) {
					// allData = data;
					superstar = data['superstar'];
					orderedSKeys = Object.keys(superstar).sort(function(a,b){return superstar[a].name.localeCompare(superstar[b].name);});
					if(data['title'])
						displayTitleTable(data['title']);
					if(data['brand'])
						displayBrandTable(data['brand']);
					if(data['match_type'])
						displayMatchTypeTable(data['match_type']);
					if(data['event'])
						displayEventTable(data['event']);
					if(data['stable'])
						displayStableTable(data['stable']);
					addNewEntryRows();
					addClickAction();
					document.querySelector("#placeholder").hidden = true;
					document.querySelector("#content").hidden = false;
				}
			});
		}
		function displayTitleTable(data){
			titleTable.innerHTML = '';
			for(var i in data){
				var s_select = superstar_dropdown(data[i]['superstar_id']);
				titleTable.innerHTML +=
					'<tr contenteditable><td data-title="ID" contenteditable="false">' + data[i]['id'] +
					'</td><td data-title="Name">' + data[i]['name'] +
					'</td><td data-title="Last_Updated" contenteditable="false">' + data[i]['last_updated'] +
					'</td><td data-title="Add/Update" contenteditable="false"><button type="button" class="add-update">Update</button>' +
					'</td></tr>';
			}
		}
		function displayBrandTable(data){
			brandTable.innerHTML = '';
			for(var i in data){
				brandTable.innerHTML +=
					'<tr contenteditable><td data-title="ID" contenteditable="false">' + data[i]['id'] +
					'</td><td data-title="Name">' + data[i]['name'] +
					'</td><td data-title="Add/Update" contenteditable="false"><button type="button" class="add-update">Update</button>' +
					'</td></tr>';
			}
		}
		function displayMatchTypeTable(data){
			matchTypeTable.innerHTML = '';
			for(var i in data){
				matchTypeTable.innerHTML +=
					'<tr contenteditable><td data-title="ID" contenteditable="false">' + data[i]['id'] +
					'</td><td data-title="Name">' + data[i]['name'] +
					'</td><td data-title="Add/Update" contenteditable="false"><button type="button" class="add-update">Update</button>' +
					'</td></tr>';
			}
		}
		function displayEventTable(data){
			eventTable.innerHTML = '';
			for(var i in data){
				eventTable.innerHTML +=
					'<tr contenteditable><td data-title="ID" contenteditable="false">' + data[i]['id'] +
					'</td><td data-title="DateTime">' + data[i]['date_time'] +
					'</td><td data-title="Name">' + data[i]['name'] +
					'</td><td data-title="PPV">' + data[i]['ppv'] +
					'</td><td data-title="Add/Update" contenteditable="false"><button type="button" class="add-update">Update</button>' +
					'</td></tr>';
			}
		}
		function displayStableTable(data){
			stableTable.innerHTML = '';
			for(var i in data){
				var row = document.createElement("tr");
				row.contentEditable = true;

				var td_id = document.createElement("td");
				td_id.setAttribute("data-title", "ID");
				td_id.innerHTML =  data[i]['id'];
				var td_name = document.createElement("td");
				td_name.setAttribute("data-title", "Name");
				td_name.innerHTML =  data[i]['name'];

				var td_members = document.createElement("td");
				td_members.setAttribute("data-title", "Members");
				for(var member_i in data[i]['members']){
					var s_id = data[i]['members'][member_i];
					var s_select = superstar_dropdown(s_id);
					td_members.appendChild(s_select);
				}
				// TODO: separate to function
				var b1 = document.createElement("button");
				b1.type = "button";
				b1.setAttribute("type", "button");
				b1.setAttribute("class", "add-member");
				b1.innerText = "Add Member ...";
				b1.contentEditable = false;
				td_members.appendChild(b1);

				// TODO: separate to function
				var td_button = document.createElement("td");
				td_button.contentEditable = false;
				td_button.setAttribute("data-title", "Add/Update");
				var b2 = document.createElement("Button");
				b2.setAttribute("type", "button");
				b2.setAttribute("class", "add-update");
				b2.innerText = 'UPDATE';
				td_button.appendChild(b2);

				row.appendChild(td_id);
				row.appendChild(td_name);
				row.appendChild(td_members);
				row.appendChild(td_button);
				stableTable.appendChild(row);

				/*
				stableTable.innerHTML +=
					'<tr contenteditable><td data-title="ID" contenteditable="false">' + data[i]['id'] +
					'</td><td data-title="Name">' + data[i]['name'] +
					'</td><td data-title="Members">' + 'Coming Soon' +
					'</td><td data-title="Add/Update" contenteditable="false"><button type="button" class="add-update">Update</button>' +
					'</td></tr>';
				*/
			}
		}
		function superstar_dropdown(superstar_id){
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
				if(s.id==superstar_id){
					superstar_option.selected = true;
					superstar_option.setAttribute("selected", true);
				}
				superstar_select.add(superstar_option);
			}
			return superstar_select;
		}
		function addSuperstarSelect(){
			var s_select = superstar_dropdown(0);
			$(this).appendChild(s_select);
			console.log("test");
		}
		function addNewEntryRows(){
			var s_select = superstar_dropdown(0);
			titleTable.innerHTML +=
				'<tr contenteditable><td data-title="ID" contenteditable="false">' + '' +
				'</td><td data-title="Name">' +
				'</td><td data-title="Last_Updated" contenteditable="false">' +
				'</td><td data-title="Add/Update" contenteditable="false"><button type="button" class="add-update">Add</button>' +
				'</td></tr>';
			brandTable.innerHTML +=
				'<tr contenteditable><td data-title="ID" contenteditable="false">' +
				'</td><td data-title="Name">' +
				'</td><td data-title="Add/Update" contenteditable="false"><button type="button" class="add-update">Add</button>' +
				'</td></tr>';
			matchTypeTable.innerHTML +=
				'<tr contenteditable><td data-title="ID" contenteditable="false">' +
				'</td><td data-title="Name">' +
				'</td><td data-title="Add/Update" contenteditable="false"><button type="button" class="add-update">Add</button>' +
				'</td></tr>';
			eventTable.innerHTML +=
				'<tr contenteditable><td data-title="ID" contenteditable="false">' +
				'</td><td data-title="DateTime">' +
				'</td><td data-title="Name">' +
				'</td><td data-title="PPV">' +
				'</td><td data-title="Add/Update" contenteditable="false"><button type="button" class="add-update">Add</button>' +
				'</td></tr>';
			stableTable.innerHTML +=
				'<tr contenteditable><td data-title="ID" contenteditable="false">' +
				'</td><td data-title="Name">' +
				'</td><td data-title="Members">' +
				'</td><td data-title="Add/Update" contenteditable="false"><button type="button" class="add-update">Add</button>' +
				'</td></tr>';
			/*
			matchTable.innerHTML +=
				'<tr contenteditable><td data-title="ID" contenteditable="false">' +
				'</td><td data-title="Date">' + today +
				'</td><td data-title="Title_ID">' +
				'</td><td data-title="Match_Type_ID">' +
				'</td><td data-title="Match_Note">' +
				'</td><td data-title="Team_Won">' +
				'</td><td data-title="Winner_Note">' +
				'</td><td data-title="Bet_Open">' +
				'</td><td data-title="Bet_Multiplier" >' + 2 +
				'</td><td data-title="Last_Updated" contenteditable="false">' +
				'</td><td data-title="Add/Update" contenteditable="false"><button class="add-update">Add</button>' +
				'</td></tr>';
			contestantTable.innerHTML +=
				'<tr contenteditable><td data-title="Match_ID">' +
				'</td><td data-title="Superstar_ID">' +
				'</td><td data-title="Team">' +
				'</td><td data-title="Add/Update" contenteditable="false"><button type="button" class="add-update">Add</button>' +
				'</td></tr>';
			*/
		}
		function addClickAction(){
			$('button.add-member').click(function(){
				var s_select = superstar_dropdown(0);
				var td = $(this).parent()[0];
				td.insertBefore(s_select, td.children[td.childElementCount-1]);
			});
			$('button.add-update').click(function(){
				// var className = $(this).attr('class');
				var row = $(this).closest('tr');
				var postData = {};
				postData.table = row.parent().attr('id');
				postData.id = row.find('td')[0].innerText.trim();
				switch(postData.table){
					case 'title_table':
						postData.name = row.find('td')[1].innerText.trim();
						var s_dd = row.find('select')[0];
						break;
					case 'brand_table':
						postData.name = row.find('td')[1].innerText.trim();
						break;
					case 'match_type_table':
						postData.name = row.find('td')[1].innerText.trim();
						break;
					case 'event_table':
						postData.date_time = row.find('td')[1].innerText.trim();
						postData.name = row.find('td')[2].innerText.trim();
						postData.ppv = row.find('td')[3].innerText.trim();
						break;
					case 'stable_table':
						postData.name = row.find('td')[1].innerText.trim();
						var memberTd = row.find('td')[2];
						postData.members = [];
						for(var i=0; i<memberTd.childElementCount-1; i++){
							var child_select = memberTd.children[i];
							var s_id = child_select.options[child_select.selectedIndex].getAttribute("superstar_id");
							if(s_id!=0){
								postData.members.push(s_id);
							}
						}
						break;
					/*
					case 'superstar_table':
						postData.name = row.find('td')[1].innerText.trim();
						postData.brand_id = row.find('td')[2].innerText.trim();
						postData.height = row.find('td')[3].innerText.trim();
						postData.weight = row.find('td')[4].innerText.trim();
						postData.hometown = row.find('td')[5].innerText.trim();
						postData.dob = row.find('td')[6].innerText.trim();
						postData.signature_move = row.find('td')[7].innerText.trim();
						postData.page_url = row.find('td')[8].innerText.trim();
						postData.image_url = row.find('td')[9].innerText.trim();
						postData.bio = row.find('td')[10].innerHTML.trim();
						break;
					*/
					/*
					case 'match_table':
						postData.date = row.find('td')[1].innerText.trim();
						postData.title_id = row.find('td')[2].innerText.trim();
						postData.match_type_id = row.find('td')[3].innerText.trim();
						postData.match_note = row.find('td')[4].innerText.trim();
						postData.team_won = row.find('td')[5].innerText.trim();
						postData.winner_note = row.find('td')[6].innerText.trim();
						postData.bet_open = row.find('td')[7].innerText.trim();
						postData.bet_multiplier = row.find('td')[8].innerText.trim();
						break;
					case 'contestant_table':
						postData.match_id = row.find('td')[1].innerText.trim();
						postData.superstar_id = row.find('td')[2].innerText.trim();
						postData.team = row.find('td')[3].innerText.trim();
						break;
					*/
				}
				$.ajax({
					url: 'assets/php/update.php',
					type: 'POST',
					dataType: 'json',
					data: {data: postData},
					success: function(data){
						console.log(data);
						if(data.success){
							updateTables();
						} else {
							console.log('Error - Please Verify Inputs');
						}
						alert(data.message);
					},
					error: function(data){
						console.log(data)
						alert('error');
					}
				});
			});
		}
		updateTables();
	</script>
</body>
</html>

