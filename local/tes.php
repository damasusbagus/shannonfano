<!DOCTYPE html>
<html>
<body>

<?php
$section = file_get_contents('./whitespace.txt', true);
// var_dump($section);
// echo $section;
$array = str_split($section);

var_dump($array);print_r("<br/><br/>");

$arrayToString = implode('', $array);
// var_dump($arrayToString);


function biner($input){
	for ($i=0; $i < count($input); $i++) { 
		if ($input[$i]==" ") {
			$input[$i]="0";
		}
		else
		{
			$input[$i]="1";
		}
	}
	return $input;
}

function marker_check($input){
	for ($i=0; $i < count($input); $i++) { 
		if ($input[$i]=="0") {
			$i++;
			if ($input[$i]=="0") {
				$i++;
				if ($input[$i]=="1") {
					$i++;
					if ($input[$i]=="0") {
						$i++;
						if ($input[$i]=="1") {
							$i++;
							if ($input[$i]=="0") {
								$i++;
								if ($input[$i]=="1") {
									$i++;
									if ($input[$i]=="0") {
										$i++;
										return $i;
									}
								}
							}
						}
					}
				}
			}
		}
	}
}

// function join($input){
// 	for ($i=0; $i <= 10 ; $i++) {
// 			string .= input[$i}; 
// 	}
// }


$array_convert = biner($array);
// var_dump($array_convert);

// var_dump($array);

$markerend = marker_check($array_convert); // array akhir marker
$markerstart = $markerend - 8; // array awal marker

// var_dump($markerend);

// $test = implode('',$array_convert[0][10]);
// var_dump($test);

// $test2 = input($array_convert);
// var_dump($test2);

// $array_hasil = 

$array_hasil = array();

for ($i=$markerend; $i < count($array_convert); $i++) { 
	array_push($array_hasil, $array_convert[$i]);
}

var_dump($array_hasil);

print_r('<br/>');

$array_hasil_string = implode('', $array_hasil);
var_dump($array_hasil_string); '<br/>';

$binertostring = chr(bindec($array_hasil_string));
// var_dump($binertostring); '<br/>';

// var_dump($array_convert);'<br/>';

// var_dump(bindec($binertostring));

echo pack('H*', base_convert($array_hasil_string, 2, 16));


?>
<br/>###################### <br/>

<?php
//hitng jumlah per karakter
$data = $arrayToString; 
// print_r(count_chars($data,1));
$frekuensi = count_chars($data,1);

//mengurutkan array
$age = $frekuensi;
arsort($age);

// var_dump($age);

//string to binary
function strToBin3($input)
{
    if (!is_string($input))
        return false;
    $input = unpack('H*', $input);
    $chunks = str_split($input[1], 2);
    $ret = '';
    foreach ($chunks as $chunk)
    {
        $temp = base_convert($chunk, 16, 2);
        $ret .= str_repeat("0", 8 - strlen($temp)) . $temp;
    }
    return $ret;
}

// $x = textBinASCII("a");
for ($i=0; $i < count($array); $i++) { 
echo(strToBin3($array[$i]));
}
// var_dump($array[0]);

print_r("<br/>");


function ASCIIBinText($bin){
	$text = array();
	$bin = explode(" ", $bin);
	for($i=0; count($bin)>$i; $i++)
		$text[] = chr(bindec($bin[$i]));
		
	return implode($text);
}
$y = ASCIIBinText("01100001");
// var_dump($y);


?>



</body>
</html>