<?php
 
require('Shannon.php');
 
$instance = new Shannon();
 
$text = "More ending in death, but this time it sounds like a ";
$text.= "solace after life. I lingered round them, under that ";
$text.= "benign sky; watched the moths fluttering among the ";
$text.= "heath, and hare-bells; listened to the soft wind ";
$text.= "breathing through the grass; and wondered how any one ";
$text.= "could ever imagine unquiet slumbers ";
$text.= "for the sleepers in that quiet earth.";
 
echo "text len=".strlen($text)." characters\n";
 
$enc_ar = $instance -> compressText($text);
 
echo "encoded len=".count($enc_ar)." bytes\n";
 
$org_text = $instance -> expandText($enc_ar);
 
if(strcmp($org_text,$text)==0)
{
    echo "decoded text matches\n";
}
else
{
    echo "decoded text DOES NOT match\n";
}
 
?>