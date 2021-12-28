
<?php
// this handles the delete of a .hex file
// it actually just renames it adding .old so it can be recovered
//
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
    $errormessage= "no program specified to delete";
   
  }
  else {
      $directory="mk14/";
      $filename=$directory . $program;
      $newfilename=$filename . ".old";
      if ( rename ($filename, $newfilename) ){
        $message="file $program deleted";
      }
      else {
        $errormessage="problem deleting $program";
      }
  }
  // return to main page with messages 
  header("Location:/?message=$message&emessage=$errormessage");

?>
