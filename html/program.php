<!DOCTYPE HTML>
<!-- 
 Web page to initiate and display the results from sending a .hex file to the MK14.

 It using the page sendfile.php to call the python script send14_file.py

 part of the MK14keys webpages
 David Allday December 2021
  version 1
-->

<html>
<head>
<title>MK14 Keys</title>
<meta name='viewport' content='width=device-width, initial-scale=1' />
<style>
table, th, td {
  border: 0px solid black;
  border-collapse: collapse;
}
td {padding: 5px;text-align: left;}
p {text-align: left;}
.error { color: red; }
</style>
</head>
<body>
<script>
function callsendfile (program,os){
    try {
       var xhttpsk = new XMLHttpRequest ();
       document.getElementById ('title').innerHTML = 'processing ' + program + ' please wait';
       xhttpsk.onreadystatechange = function() {
           if (this.readyState == 4 && this.status == 200) {
               document.getElementById ('title').innerHTML = 'Result:';
               document.getElementById ('indent').innerHTML = '&nbsp;&nbsp;';
               document.getElementById ('result').innerHTML =  this.responseText;
               document.getElementById ('link').innerHTML =  "<a href='/'>back to main page</a>";

           }
       };
       xhttpsk.open ('GET', 'sendfile.php?os=' + os + '&program=' + program, true);
       xhttpsk.send ();

    }
    catch (err){
       document.getElementById ('title').innerHTML = 'error in processing';
   }
}

</script>

<h2>Sending hex file to the MK14</h2>

<table>
<tr><td id='title' colspan=2></td></tr>
<tr><td id='indent'></td><td id='result'></td></tr>
</table>
<p id='link'></p>
<?php
//send a file to MK14
  $errormessage="";
  $message="";
  $refresh=0;
  $program="";
  $os="";
  if (array_key_exists ("program",$_GET)){
      $program=$_GET["program"];
  }
  if (array_key_exists ("os",$_GET)){
      $os=$_GET["os"];
  }

  if ( strlen($program) == 0 ){
    $errormessage = "no program specified to process";
    echo "<p class=error>$errormessage</p>";
    $refresh=1;
  }
  else {
      $directory="mk14/";
      $filename=$directory . $program;

      if (file_exists ($filename)){
        echo "<script>";
        echo "callsendfile (\"$program\",\"$os\")";
        echo "</script>";

       }
       else {
           $errormessage = "program $program not found";
           echo "<p class=error>$errormessage</p>";
           $refresh=1;
       }

   }
   echo "</body>";

  // last little bit - add a second header block and refresh if required
  // sends error ones to the index page
  if ($refresh==1){
     echo "<head> <meta http-equiv='refresh' content='5; URL=/index.php?emessage=" . "$errormessage" . "' /></head>";
  }

?>
</html>

