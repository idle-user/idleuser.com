<?php require_once('assets/php/admin.php'); ?>
<!DOCTYPE html>
<html>
<head>
	<title>Admin - Matches Roster</title>
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
		<h1 class="text-center">Matches - Roster Editor</h1>
	</div>
	<div id="placeholder" class="container">
		<h2>Loading roster ...</h2>
	</div>
	<div id="content" class="container-fluid small" hidden="true">
		<h2>Superstars</h2>
		<div>
			<table class="table table-striped table-bordered">
				<thead>
					<tr>
						<th>ID</th>
						<th>Name</th>
						<th>Brand_ID</th>
						<th>Height</th>
						<th>Weight</th>
						<th>Hometown</th>
						<th>DOB</th>
						<th>Signature Move</th>
						<th>Page_URL</th>
						<th>Image_URL</th>
						<th hidden>Bio</th>
						<th>Last_Updated</th>
						<th>Add/Update</th>
					</tr>
				</thead>
				<tbody id="superstar_table"></tbody>
			</table>
		</div>
	</div>
	<script type="text/javascript">
		var superstarTable = document.getElementById('superstar_table');
		function updateTables(){
			document.querySelector("#placeholder").hidden = false;
			document.querySelector("#content").hidden = true;
			$.ajax({
				type: 'POST',
				url: 'assets/php/data.php',
				dataType: 'json',
				success: function (data) {
					if(data['superstar'])
						displaySuperstarTable(data['superstar']);
					addNewEntryRows();
					addClickAction();
				document.querySelector("#placeholder").hidden = true;
				document.querySelector("#content").hidden = false;
				}
			});
		}
		function displaySuperstarTable(data){
			superstarTable.innerHTML = '';
			for(var i in data){
				superstarTable.innerHTML +=
					'<tr contenteditable><td data-title="ID" contenteditable="false">' + data[i]['id'] +
					'</td><td data-title="Name">' + data[i]['name'] +
					'</td><td data-title="Brand_ID">' + data[i]['brand_id'] +
					'</td><td data-title="Height">' + data[i]['height'] +
					'</td><td data-title="Weight">' + data[i]['weight'] +
					'</td><td data-title="Hometown">' + data[i]['hometown'] +
					'</td><td data-title="DOB">' + data[i]['dob'] +
					'</td><td data-title="Signature Move">' + data[i]['signature_move'] +
					'</td><td data-title="Page_URL">' + data[i]['page_url'] +
					'</td><td data-title="Image_URL">' + data[i]['image_url'] +
					'</td><td data-title="Bio" hidden>' + data[i]['bio'] +
					'</td><td data-title="Last_Updated" contenteditable="false">' + data[i]['last_updated'] +
					'</td><td data-title="Add/Update" contenteditable="false"><button type="button" class="add-update">Update</button>' +
					'</td></tr>';
			}
		}
		function addNewEntryRows(){
			superstarTable.innerHTML +=
				'<tr contenteditable><td data-title="ID" contenteditable="false">' +
				'</td><td data-title="Name">' +
				'</td><td data-title="Brand_ID">' +
				'</td><td data-title="Height">' +
				'</td><td data-title="Weight">' +
				'</td><td data-title="Hometown">' +
				'</td><td data-title="DOB">0000-00-00' +
				'</td><td data-title="Signature Move" contenteditable="false">' +
				'</td><td data-title="Page_URL">' +
				'</td><td data-title="Image_URL" contenteditable="false">' +
				'</td><td data-title="Bio" contenteditable="false" hidden>' +
				'</td><td data-title="Last_Updated" contenteditable="false">' +
				'</td><td data-title="Add/Update" contenteditable="false"><button type="button" class="add-update">Add</button>' +
				'</td></tr>';
		}
		function addClickAction(){
			$('button.add-update').click(function(){
				// var className = $(this).attr('class');
				var row = $(this).closest('tr');
				var postData = {};
				postData.table = row.parent().attr('id');
				postData.id = row.find('td')[0].innerText.trim();
				switch(postData.table){
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
				}
				console.log(postData);
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

