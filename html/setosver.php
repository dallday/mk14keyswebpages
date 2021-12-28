<?php
// this handles the setting of the MK14 os version
//
// If the get os parameter is 1 then create  mk14/MK14OS1
//   else remove the file
//
//  part of the MK14keys webpages
//  David Allday December 2021
//  version 1

  try {
    $osver="";
    if (array_key_exists ("os",$_GET)){
        $osver=$_GET["os"];
    }
    $filename = "mk14/MK14OS1";
    if ( $osver == "1" ){
        $osverfile = fopen ($filename,"w");
        fwrite ( $osverfile,"used to set os to version 1 ");
        fclose ( $osverfile);
	echo "OS set to version 1";
    }
    else {
       if (file_exists ($filename)){
           unlink ( $filename);
       }
       echo "OS set to version 2";
    }
  }
  catch (Exception $e) {
     echo "ERROR" . $e->getMessage ();
  }

?>
