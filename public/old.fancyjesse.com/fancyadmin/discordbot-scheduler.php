<?php require_once('assets/php/admin.php'); ?>
<!DOCTYPE html>
<html>
<head>
	<title>Admin: FJBot Scheduler</title>
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
		<h1 class="text-center">FJBot Scheduler</h1>
		<p class="text-center small">Weekly Alerts</p>
	</div>
	<div id="placeholder" class="container">
		<h2>Loading schedules ...</h2>
	</div>
	<div id="content" class="container" hidden="true">
		<h2>Schedules</h2>
		<div>
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<th>ID</th>
						<th>Name</th>
						<th>Description</th>
						<th>Message</th>
						<th>Tweet</th>
						<th>Schedule (PST)</th>
						<th>Active</th>
						<th>Add/Update</th>
					</tr>
				</thead>
				<tbody id="scheduler_table"></tbody>
			</table>
		</div>
	</div>
	<script type="text/javascript">
		var commandTable = document.getElementById('scheduler_table');
		function updateTables(){
			document.querySelector("#placeholder").hidden = false;
			document.querySelector("#content").hidden = true;
			$.ajax({
				type: 'POST',
				url: 'assets/php/discord-data.php',
				dataType: 'json',
				success: function (data) {
					allData = data;
					if(data['scheduler'])
						displayCommandTable(data['scheduler']);
					addNewEntryRows();
					addCheckMarks();
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
					'</td><td data-title="Name">' + data[i]['name'] +
					'</td><td data-title="Description">' + data[i]['description'] +
					'</td><td data-title="Message"><pre>' + data[i]['message'] +
					'</td><td data-title="Tweet"><pre>' + data[i]['tweet'] +
					'</td><td data-title="Schedule" contenteditable="false" style="white-space:nowrap;width:20%;"><div class="form-group-sm">' +
						'<div class="row"><div class="col-md-4"><label class="checkbox-inline"><input type="checkbox" value="' + data[i]['sunday_flag'] + '">Sun</label></div><div class="col-md-8"><input type="time" class="form-control" value="' + data[i]['sunday_time'] + '"></div></div>' +
						'<div class="row"><div class="col-md-4"><label class="checkbox-inline"><input type="checkbox" value="' + data[i]['monday_flag'] + '">Mon</label></div><div class="col-md-8"><input type="time" class="form-control" value="' + data[i]['monday_time'] + '"></div></div>' +
						'<div class="row"><div class="col-md-4"><label class="checkbox-inline"><input type="checkbox" value="' + data[i]['tuesday_flag'] + '">Tue</label></div><div class="col-md-8"><input type="time" class="form-control" value="' + data[i]['tuesday_time'] + '"></div></div>' +
						'<div class="row"><div class="col-md-4"><label class="checkbox-inline"><input type="checkbox" value="' + data[i]['wednesday_flag'] + '">Wed</label></div><div class="col-md-8"><input type="time" class="form-control" value="' + data[i]['wednesday_time'] + '"></div></div>' +
						'<div class="row"><div class="col-md-4"><label class="checkbox-inline"><input type="checkbox" value="' + data[i]['thursday_flag'] + '">Thu</label></div><div class="col-md-8"><input type="time" class="form-control" value="' + data[i]['thursday_time'] + '"></div></div>' +
						'<div class="row"><div class="col-md-4"><label class="checkbox-inline"><input type="checkbox" value="' + data[i]['friday_flag'] + '">Fri</label></div><div class="col-md-8"><input type="time" class="form-control" value="' + data[i]['friday_time'] + '"></div></div>' +
						'<div class="row"><div class="col-md-4"><label class="checkbox-inline"><input type="checkbox" value="' + data[i]['saturday_flag'] + '">Sat</label></div><div class="col-md-8"><input type="time" class="form-control" value="' + data[i]['saturday_time'] + '"></div></div>' +
					'</div>' +
					'</td><td data-title="Active" align="center" contenteditable="false"><input type="checkbox" value="' + data[i]['active'] + '">' +
					'</td><td data-title="Add/Update" align="center" contenteditable="false"><button type="button" class="update-button">Update</button>' +
					'</td></tr>';
			}
		}
		function addNewEntryRows(){
			commandTable.innerHTML +=
				'<tr contenteditable><td data-title="ID" contenteditable="false">' +
				'</td><td data-title="Name">' +
				'</td><td data-title="Description">' +
				'</td><td data-title="Message">' +
				'</td><td data-title="Tweet">' +
				'</td><td data-title="Schedule" contenteditable="false" style="white-space:nowrap;width:20%;"><div class="form-group-sm">' +
					'<div class="row"><div class="col-md-4"><label class="checkbox-inline"><input type="checkbox" value="0">Sun</label></div><div class="col-md-8"><input type="time" class="form-control" value="00:00:00" ></div></div>' +
					'<div class="row"><div class="col-md-4"><label class="checkbox-inline"><input type="checkbox" value="0">Mon</label></div><div class="col-md-8"><input type="time" class="form-control" value="00:00:00"></div></div>' +
					'<div class="row"><div class="col-md-4"><label class="checkbox-inline"><input type="checkbox" value="0">Tue</label></div><div class="col-md-8"><input type="time" class="form-control" value="00:00:00"></div></div>' +
					'<div class="row"><div class="col-md-4"><label class="checkbox-inline"><input type="checkbox" value="0">Wed</label></div><div class="col-md-8"><input type="time" class="form-control" value="00:00:00"></div></div>' +
					'<div class="row"><div class="col-md-4"><label class="checkbox-inline"><input type="checkbox" value="0">Thu</label></div><div class="col-md-8"><input type="time" class="form-control" value="00:00:00"></div></div>' +
					'<div class="row"><div class="col-md-4"><label class="checkbox-inline"><input type="checkbox" value="0">Fri</label></div><div class="col-md-8"><input type="time" class="form-control" value="00:00:00"></div></div>' +
					'<div class="row"><div class="col-md-4"><label class="checkbox-inline"><input type="checkbox" value="0">Sat</label></div><div class="col-md-8"><input type="time" class="form-control" value="00:00:00"></div></div>' +
					'</div>' +
				'</td><td data-title="Active" align="center" contenteditable="false"><input type="checkbox" value="0">' +
				'</td><td data-title="Add/Update" align="center" contenteditable="false"><button type="button" class="add-button">Add</button>' +
				'</td></tr>';
		}
		function addCheckMarks(){
			$("input[type='checkbox']").each(function(){
				if($(this).attr('value')==1){
					$(this).prop('checked', true);
				}
			});
		}
		function addClickAction(){
			$('button').click(function(){
				var className = $(this).attr('class');
				var row = $(this).closest('tr');
				var postData = {};
				postData.table = row.parent().attr('id');
				postData.id = row.find('td')[0].innerText;
				postData.name = row.find('td')[1].innerText;
				postData.description = row.find('td')[2].innerText;
				postData.message = row.find('td')[3].innerText;
				postData.tweet = row.find('td')[4].innerText;
				scheduleTd = row.find('td')[5];
				postData.sunFlag = scheduleTd.getElementsByTagName('input')[0].checked ? 1 : 0;
				postData.sunTime = scheduleTd.getElementsByTagName('input')[1].value;
				postData.monFlag = scheduleTd.getElementsByTagName('input')[2].checked ? 1 : 0;
				postData.monTime = scheduleTd.getElementsByTagName('input')[3].value;
				postData.tueFlag = scheduleTd.getElementsByTagName('input')[4].checked ? 1 : 0;
				postData.tueTime = scheduleTd.getElementsByTagName('input')[5].value;
				postData.wedFlag = scheduleTd.getElementsByTagName('input')[6].checked ? 1 : 0;
				postData.wedTime = scheduleTd.getElementsByTagName('input')[7].value;
				postData.thuFlag = scheduleTd.getElementsByTagName('input')[8].checked ? 1 : 0;
				postData.thuTime = scheduleTd.getElementsByTagName('input')[9].value;
				postData.friFlag = scheduleTd.getElementsByTagName('input')[10].checked ? 1 : 0;
				postData.friTime = scheduleTd.getElementsByTagName('input')[11].value;
				postData.satFlag = scheduleTd.getElementsByTagName('input')[12].checked ? 1 : 0;
				postData.satTime = scheduleTd.getElementsByTagName('input')[13].value;
				postData.active = row.find('td')[6].children[0].checked ? 1 : 0;

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
