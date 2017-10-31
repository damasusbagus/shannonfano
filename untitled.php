<?php
$fp = fopen('data.txt', 'w');
fwrite($fp, '1');
fwrite($fp, '23');
fclose($fp);

// the content of 'data.txt' is now 123 and not 23!
?>