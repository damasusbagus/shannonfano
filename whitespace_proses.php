<<?php 

// header('Content-disposition: attachment; filename=whitespace.txt');
// header('Content-type: text/plain');

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

for ($i=0; $i < count($array); $i++) { 
		# code...
	if ($array[$i]=="0") {
			# code...
			// echo "A<br>";
			// $tampungA = array("A");
			// array_replace($array[$i], $tampungA);
			// echo "A";
		$array[$i] = "A";
	}
	else
	{
		// echo "B";
		$array[$i] = "B";
		// echo "B<br>";
		// $tampungB = array("B");
		// array_replace($array[$i], $tampungB);
	}
}
echo "TEST";
print_r($_POST["teks"]);
print_r($array);
implode("", $array);
?>
