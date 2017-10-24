<!doctype html>
<html>
<head>
	<title>Steganografi & Kompresi</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
</head>
<body>
	<div class="header">Kompresi Shannon-Fano</div>
	<div class="content">
		<form action="finish.php" method="post" enctype="multipart/form-data">
			<label>Pilih File :</label>
			<p><input class="btn btn-success" type="file" name="fileToUpload" id="fileToUpload"></p>
			<p><input class="btn btn-success" type="submit" value="Upload File" name="submit"></p>
			<div style="border: 2px solid #1b5e20;margin: 10px;padding: 5px;text-align: left;">
				Nama : <br>
				Format : <br>
				Ukuran : <br>
			</div>
			<a href="#back" class="btn btn-success" onclick="goBack()">Kembali</a>
			<script>
				function goBack() {
					window.history.back();
				}
			</script>
			<input class="btn btn-success" type="submit" value="Proses">
		</form>
	</div>
	<div class="footer"></div>


	<!--<h1>Program steganografi & Kompresi</h1>
	<h2>Silahkan pilih fitur</h2>
	<div class=opt>Steganografi Whitespace</div>
	<br>
	<div class=opt>Kompresi Shannon-Fano</div>-->
</body>