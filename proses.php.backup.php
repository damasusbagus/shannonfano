<!-- 	Isi file anda adalah : <?php echo $_POST["teks"]; ?><br>
	Pesan rahasia anda adalah : <?php echo $_POST["rahasia"]; ?>
	<br>Binary dari pesan rahasia :
 -->	<?php
	//fungsi untuk ubah string ke binary

 	$myfile = fopen("teks.txt", "w") or die("Unable to open file!");
	fwrite($myfile, $_POST["teks"]);
	// for($x = 0; $x < count($array); $x++) {
	// fwrite($myfile, $array[$x]);
	// 	// echo $array[$x];
	// }
	fclose($myfile);

	function strToBin($input)
	{
		if (!is_string($input))
			return false;
		$ret = '';
		for ($i = 0; $i < strlen($input); $i++)
		{
			$temp = decbin(ord($input{$i}));
			$ret .= str_repeat("0", 8 - strlen($temp)) . $temp;
		}
		return $ret;
	}
	// printf("Binary dari pesan rahasia :")
	// convert string to array
	$array = str_split(strToBin($_POST["rahasia"]));
	// for($x = 0; $x < count($array); $x++) {
	// 	echo $array[$x];
	// }

	for ($i=0; $i < count($array); $i++) { 
		# code...
		if ($array[$i]=="0") {
			# code...
			// echo "A<br>";
			// $tampungA = array("A");
			// array_replace($array[$i], $tampungA);
			// echo "A";
			$array[$i] = " ";
		}
		else
		{
		// echo "B";
			$array[$i] = "\t";
		// echo "B<br>";
		// $tampungB = array("B");
		// array_replace($array[$i], $tampungB);
		}
	}
	// echo "<br>Array setelah di convert ke whitespace :";
	// for($x = 0; $x < count($array); $x++) {
	// 	echo $array[$x];
	// }
	// echo "<br>hasil whitespace : ";
	// echo $_POST["teks"];


	$myfile = fopen("whitespace.txt", "w") or die("Unable to open file!");
	fwrite($myfile, $_POST["teks"]);
	for($x = 0; $x < count($array); $x++) {
	fwrite($myfile, $array[$x]);
		// echo $array[$x];
	}
	fclose($myfile);


	?>


<?php
$file_url = 'whitespace.txt';
header('Content-Type: application/octet-stream');
header("Content-Transfer-Encoding: Binary"); 
header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\""); 
readfile($file_url); // do the double-download-dance (dirty but worky)
?>