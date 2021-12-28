<?php
// delete all the files in the mk14 directory 
//
//  part of the MK14keys webpages
//  David Allday December 2021
//  version 1

$fileList = glob('mk14/*');

//Loop through the array that glob returned.
// and delete (unlink) them
$errormessage="";
$message="";

foreach($fileList as $filename){

    if (!unlink ($filename)){
        $errormessage=$errormessage . "Failed to delete " . $filename . "<br>";
    }
}
if (strlen ($errormessage) == 0 ) {
    $message="File store cleared";
}

header ("Location:index.php?message=$message&emessage=$errormessage");

?>

