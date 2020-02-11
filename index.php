<!DOCTYPE html>
<html>
<head>
	<title>Upload File With AJAX</title>
	<meta charset="UTF-8"/>
	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"/>
</head>
<body>
	<h1 class="text-primary text-center">Upload File With AJAX AND PHP.</h1>
	<!--Split the form data with the enctype attribute-->
	<form id="file" enctype="multipart/form-data" method="POST">
		<div class="form-group col-md-8">
			<label for="name">File Name:(*)</label>
			<input type="text" name="name" id="name" class="form-control" placeholder="Enter the file Name" autofocus required/>
			<label for="name">Select File:(*)</label>
			<input type="file" name="file" id="file" class="form-control" required/>
			<br/>
			<button type="submit" class="btn btn-success pull-right">Upload File</button>
			<i><b>(*)Required:</b> All fields are required.</i>
		</div>
	</form>
	<!--DIV to display  the progress par during loading-->
	<div id="loading" class="col-md-8"></div>
	<!--DIV to display error messages -->
	<div id="error" class="col-md-8"></div>
	<!--DIV to display message success-->
	<div id="success" class="col-md-8"></div>
	<script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
	<script type="text/javascript" src="js/script.js"></script>
</body>
</html>
