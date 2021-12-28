<?php
// this handles the action from the main page
// which can be program or delete
// the header function redirects the page to the new one 
// without displaying anything in the brower
//
//  part of the MK14keys webpages
//  David Allday December 2021
//  version 1

  $action="";
  $program="";
  $os="";
  if (array_key_exists ("action",$_GET)){
      $action=$_GET["action"];
  }
  if (array_key_exists ("program",$_GET)){
      $program=$_GET["program"];
  }
  if (array_key_exists ("os",$_GET)){
      $os=$_GET["os"];
  }

  // default report if nothing else happens
  $location="/?emessage=Invalid action $action";

  if ( ( strlen ($program)) == 0 ){
    $location="/?emessage=no program selected";
  }
  elseif ( ( strlen ($os)) == 0 ){
    $location="/?emessage=no Os version selected"; 
  }
  elseif ( $action == "Program MK14" ){
	$location="program.php?os=$os&program=$program";
  }
  elseif ( $action == "Delete File" ){
	$location="delete.php?program=$program";
  }
  header ("Location:$location");

  //echo "Action: $action<br>";
  //echo "program: $program<br>";

?>
