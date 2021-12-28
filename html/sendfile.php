<?php
// a php only page to just send a file to MK14
// it will echo the results to be picked up by the calling page
// see program.php
//
// part of the MK14keys webpages
// David Allday December 2021
//  version 1

  $program="";
  $osparam="";
  if (array_key_exists ("program",$_GET)){
      $program=$_GET["program"];
  }
  if (array_key_exists ("os",$_GET)){
      $osparam=$_GET["os"];
  }

  if ( strlen($program) == 0 ){
    echo "no program specified to process";
  }
  else {
      $directory="mk14/";
      $filename=$directory . $program;

      if (file_exists ($filename)){
          $os="2";
          if ($osparam== "1"){
              $os="1";
          }
          $sendfilepython = "/var/www/html/scripts/send14_file.py";
          // could use this output file and get an immediate response
          // but would need to check the file??
          //$outputfile = "mk14/runoutput.txt";
          // $message= exec ("$sendfilepython $filename $os 2>&1 1>$outputfile", $output);

          $lastline= exec ("$sendfilepython $filename $os 2>&1 ", $output);
          // the result from the exec is the last line sent to stdout
          // printed all the lines by using the $output variable
          //echo "Last line returned:$lastline<br>";
          //echo "Output:<br>";
          //var_dump($output)
          $break="";
          foreach ($output as $line)  {
              echo "$break$line";
              $break="<br>";
          }
          //echo "End of returned data<br>";
       }
       else {
           echo "program $program (filename $filename) not found";
       }

   }

?>


