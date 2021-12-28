
<?php
// this handles the restoration of a .hex file
// it actually just renames it from the .old file
//
//  part of the MK14keys webpages
//  David Allday December 2021
//  version 1

  $errormessage="";
  $message="";
  $program="";

  if (array_key_exists ("program",$_GET)){
      $program=$_GET["program"];
  }
  
  if ( strlen($program) == 0 ){
    header("Location:/?emessage=no program specified to recover");
    exit ();
  }
  $directory="mk14/";
  $filename=$directory . $program;
  $oldfilename=$filename . ".old";
  if ( rename ($oldfilename, $filename) ){
    $message="file $program restored";
  }
  else {
    $errormessage="problem restoring $program";
  }

  header("Location:/?message=$message&emessage=$errormessage");
  

?>
