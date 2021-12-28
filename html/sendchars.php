<?php

//send keys to MK14 and then return the keys sent 
//
//  part of the MK14keys webpages
//  David Allday December 2021
//  version 1

//  get the characters from the html get parameter chars

$chars="";
if (array_key_exists ("chars",$_GET)){
    $chars=$_GET["chars"];
}

// check characters are valid ??


$lastline= exec ("/var/www/html/scripts/send14_string.py $chars 2>&1", $output);

// this should display all lines returned by the script
// hopefully just the keys sent 
// but any errors may be more than 1 line
// ignoring the lastline result returned !
//        debug lines :)
//        echo "Last line returned:$lastline<br>";
//        echo "Output:<br>";
//        var_dump($output);
$breakchar="";
foreach ($output as $line)  {
    echo "$line $breakchar";
    $breakchar="<br>";
}
//echo "End of returned data<br>";

?>

