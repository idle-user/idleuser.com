<?php require_once('assets/php/admin.php'); ?>
<!DOCTYPE html>
<html>
<head>
	<title>Admin: FJBot Commands</title>
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
	<div class="container">
		<h1 class="text-center">FJBot Chat Commands</h1>
		<p class="text-center small">Discord Responses</p>
	</div>
	<div id="placeholder" class="container">
		<h2>Loading commands ...</h2>
	</div>
	<div id="content" class="container" hidden="true">
		<h2>Commands</h2>
		<div>
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<th>ID</th>
						<th>Command</th>
						<th>Response</th>
						<th>Add/Update</th>
					</tr>
				</thead>
				<tbody id="command_table"></tbody>
			</table>
		</div>
	</div>
	<script type="text/javascript">
		var commandTable = document.getElementById('command_table');
		function updateTables(){
			document.querySelector("#placeholder").hidden = false;
			document.querySelector("#content").hidden = true;
			$.ajax({
				type: 'POST',
				url: 'assets/php/discord-data.php',
				dataType: 'json',
				success: function (data) {
					allData = data;
					if(data['commands'])
						displayCommandTable(data['commands']);
					addNewEntryRows();
					addClickAction();
					document.querySelector("#placeholder").hidden = true;
					document.querySelector("#content").hidden = false;
				}
			});
		}
		function displayCommandTable(data){
			commandTable.innerHTML = '';
			for(var i in data){
				commandTable.innerHTML +=
					'<tr contenteditable><td data-title="ID" contenteditable="false">' + data[i]['id'] +
					'</td><td style="white-space:nowrap;" data-title="Command">' + data[i]['command'] +
					'</td><td data-title="Response"><pre>' + data[i]['response'] + '</pre>' +
					'</td><td data-title="Add/Update" align="center" contenteditable="false"><button type="button" class="update-button">Update</button>' +
					'</td></tr>';
			}
		}
		function addNewEntryRows(){
			commandTable.innerHTML +=
				'<tr contenteditable><td data-title="ID" contenteditable="false">' +
				'</td><td data-title="Command">' +
				'</td><td data-title="Response">' +
				'</td><td data-title="Add/Update" align="center" contenteditable="false"><button type="button" class="add-button">Add</button>' +
				'</td></tr>';
		}
		function addClickAction(){
			$('button').click(function(){
				var className = $(this).attr('class');
				var row = $(this).closest('tr');
				var postData = {};
				postData.table = row.parent().attr('id');
				postData.id = row.find('td')[0].innerText;
				postData.command = row.find('td')[1].innerText;
				postData.response = row.find('td')[2].innerText;
				postData.command = '!' + postData.command.replace(/[^0-9a-z]/gi, "").toLowerCase();

				console.log(postData);

				$.ajax({
					url: 'assets/php/discord-update.php',
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
