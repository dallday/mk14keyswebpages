<?php
// this handles the setting of the MK14 os version
//
// If the mk14/MK14OS1 exists then set it to OS 1
//   else set to os 2
//
//  part of the MK14keys webpages
//  David Allday December 2021
//  version 1

  try {
    if (file_exists ("mk14/MK14OS1")){
        echo "1";
    }
    else {
        echo "2";
    }
  }
  catch (Exception $e) {
     echo "ERROR" . $e->getMessage ();
  }
  //echo "Action: $action<br>";
  //echo "program: $program<br>";

?>
