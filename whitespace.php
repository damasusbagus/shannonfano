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
	<div class="header">Steganografi Whitespace<br>Enkripsi</div>
	<div class="content">
		<form action="proses.php" method="post" class="form-group">
			<label>Masukkan Teks : </label>
			<textarea class="" name="teks" cols="30" rows="4"></textarea>
			<label>Masukkan Teks Rahasia :</label>
			<textarea class="" name="rahasia" cols="30" rows="4"></textarea>
			<div class="navigation">
			<a href="#back" class="btn btn-success" onclick="goBack()">Kembali</a>
				<script>
					function goBack() {
						window.history.back();
					}
				</script>
				<input class="btn btn-success" type="submit" value="Proses"></div>
			</form>
		</div>
		<div class="footer"></div>


	<!--<h1>Program steganografi & Kompresi</h1>
	<h2>Silahkan pilih fitur</h2>
	<div class=opt>Steganografi Whitespace</div>
	<br>
	<div class=opt>Kompresi Shannon-Fano</div>-->
</body>