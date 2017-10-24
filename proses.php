<?php
	//fungsi untuk ubah string ke binary

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
	// $test = strToBin($_POST["rahasia"]);
	// for($x = 0; $x < count($array); $x++) {
	// 	echo $array[$x];
	// }

	var_dump(strToBin($_POST["rahasia"]));
	function whitespace($input)
	{
		for ($i=0; $i < count($input); $i++) { 
		# code...
			if ($input[$i]=="0") {
			# code...
			// echo "A<br>";
			// $tampungA = array("A");
			// array_replace($array[$i], $tampungA);
			// echo "A";
				$input[$i] = " ";
			}
			else
			{
		// echo "B";
				$input[$i] = "\t";
		// echo "B<br>";
		// $tampungB = array("B");
		// array_replace($array[$i], $tampungB);
			}
		}
		return $input;
	}

	// function writeText($input){
	// 	for ($i=0; $i < count($input); $i++) { 
	// 		fwrite($myfile, $input[$i]);
	// 	}
	// 	return $input;
	// }

	$whitespace = whitespace($array);
	$whitespaceString = implode('', $whitespace);
	// echo "<br>Array setelah di convert ke whitespace :";
	// for($x = 0; $x < count($array); $x++) {
	// 	echo $array[$x];
	// }
	// echo "<br>hasil whitespace : ";
	// echo $_POST["teks"];
	$arrayMarker = str_split(strToBin('*'));
	$marker = whitespace($arrayMarker);
	$markerString = implode('',$marker);
	var_dump($markerString);

	$myfile = fopen("local/whitespace.txt", "w") or die("Unable to open file!");
	fwrite($myfile, $_POST["teks"]);
	// fwrite($myfile, writeText($marker));
	fwrite($myfile, $markerString);
	fwrite($myfile, $whitespaceString);
	// for($x = 0; $x < count($whitespace); $x++) {
	// fwrite($myfile, $whitespace[$x]);
	// 	// echo $array[$x];
	// }
	fclose($myfile);

	$myfile = fopen("local/text.txt", "w") or die("Unable to open file!");
	fwrite($myfile, $_POST["teks"]);
	fwrite($myfile, $_POST["rahasia"]);
	fclose($myfile);