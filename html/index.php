<!DOCTYPE HTML>
<!-- 
 The main page the MK14keys suite
 It displays all the .hex files available
 Allows the addition of new files
 and has links to other pages

 part of the MK14keys webpages
 David Allday December 2021
 version 1
-->
<html>
<head>
<title>MK14 Keys</title>
<meta name='viewport' content='width=device-width, initial-scale=1' />

<style>
table, th, td { border: 1px solid black;}
table.center {margin-left: auto; margin-right: auto;}
td {padding: 5px;text-align: left;}p {text-align: left;}
.button { width: 100px; font-family:'Courier New';
          background-color: white;
          border: 3;   border-radius: 10px;
          color: black;   padding: 15px 10px;
          text-align: center;
          text-decoration: none;
          display: inline-block;
          font-size: 16px;
          margin: 4px 2px;
          cursor: pointer;
          width: 100; }
</style>

</head>

<body onload=getosver()>

<script>

// this will load the program contents into the element called filecontents on the web page
// it uses AJAX to update the page without a refresh
function loadDoc (name){
    document.getElementById ('filecontents').innerHTML='Loading file contents';
    try {
        const xhttp=new XMLHttpRequest ();
        xhttp.onload=function (){
            document.getElementById ('filecontents').innerHTML ='<pre>'+this.responseText+'</pre>';
            }
        xhttp.open ('GET', name );
        xhttp.send ();
    }
    catch(err) {
        document.getElementById ('filecontents').innerHTML = "Problem getting file contents<br>" + err.message;
    }
}

// get the version of the MK14 os as stored on the webserver
// relies on file mk14/MK14OS1 on the server
// it uses AJAX to update the page without a refresh
function getosver (){
    document.getElementById ('os').innerHTML = 'getting os';
    try {
        const xhttpsk = new XMLHttpRequest ();
        xhttpsk.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                if (this.responseText=='1'){
                    document.getElementById ('os').innerHTML = 'version 1 of monitor ---- --';
                    document.getElementById ("1").checked = true;
                }else{
                    document.getElementById ('os').innerHTML = 'version 2 of monitor 0000 00';
                    document.getElementById ("2").checked = true;
                }
            }
        };
        xhttpsk.open ('GET', 'getosver.php' , true);
        xhttpsk.send ();
    }
    catch(err) {
        document.getElementById ('os').innerHTML = err.message;
    }

}

// it uses AJAX to update the details on the webserver without a refresh
function setosver (MK14os){
    document.getElementById ('os').innerHTML='changing os version ' + MK14os ;
    try {
        const xhttpsk = new XMLHttpRequest ();
        xhttpsk.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById ('os').innerHTML = this.responseText;
            }
        };
        xhttpsk.open ('GET', 'setosver.php?os=' + MK14os , true);
        xhttpsk.send ();
    }
    catch (err) {
        document.getElementById ('os').innerHTML = err.message;
    }

}

</script>


<p>&nbsp;</p>
<form action="/action.php">
<table>
<tr>
<td style='text-align: center;' colspan='2'>Programs</td>
</tr>

<?php

// Get a list of file paths using the glob function.
// list all files as can have .HEX and .hex type files
$deletedfiles=0; // set to 1 if any deleted files found ( type.old )
$directory="mk14/";
$fileList = glob ("$directory*.*");

// Loop through the array that glob returned.
foreach($fileList as $filename){
    // check if correct type
    $fileext = strtolower (pathinfo ($filename,PATHINFO_EXTENSION));
    // echo "File $filename ext $fileext <br>";
    if ( $fileext == "old"){
        $deletedfiles = $deletedfiles + 1;
    }
    elseif ( $fileext == "hex"){

        // print them out onto the screen
        $hexname=substr ($filename,strlen ($directory));
        echo "<tr><td><input type=radio id='$filename' ";
        echo "name='program' value='$hexname' " ;
        echo "onclick=loadDoc('$filename') > ";
        // just want the end name

        echo "<label for='$filename'>$hexname</label> <br>";

        $commentfile="$filename" . ".txt";
        $comment="no comment";
        if ( file_exists ($commentfile) ) {
            if ( ($fp = fopen ($commentfile, "r"))!==false ) {
                $comment=fgets ($fp);
                fclose($fp);
            }
            else {
                $comment="file $commentfile open error";
            }
        }
        else {
            $comment="";
        }

        echo "</td><td>$comment</td></tr>";
    }
}

?>

<tr>
<td colspan='2'>
<input type='submit' name='action' value='Program MK14' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type='submit' name='action' value='Delete File' >
<br>
<input type=radio id='1' name='os' value='1' onclick='setosver ("1")' >
<label for='1'>version 1 of monitor reset to ---- --</label> <br>
<input type=radio id='2' name='os' value='2'  onclick='setosver ("2")' checked="checked">
<label for='2'>version 2 of monitor reset to 0000 00</label> <br>

</form>
</td>
</tr>
</table>

<p>
<?php
  // display the message if present
  if (array_key_exists ("message",$_GET)){
    echo $_GET["message"];
  }
?>
</p>
<?php
  // display the emessage if present
  if (array_key_exists ("emessage",$_GET)){
    $errormessage=$_GET["emessage"];
    if ( (strlen ($errormessage))  > "0" ){
      echo "<p style='color: red;'> ";
      echo $errormessage ;
      echo "</p>";
    }
  }
?>

<p id='os'>&nbsp;</p>

<p></p>
<table> <tr><td>File contents</td></tr><tr><td><div id='filecontents'>file contents will appear here</div></td></tr></table>

<br><br>
<a href="/inputchars.php">Send keystrokes to MK14</a><br><br><br>
<?php
    if ($deletedfiles > 0){
        $numfile="is $deletedfiles file";
        if ($deletedfiles > 1 ){
            $numfile="are $deletedfiles files";
        }
	    echo "<a href='/restorefiles.php'>There $numfile that can be restored</a><br>";
    }
?>
<a href="/upload.php">upload a new file</a><br>
<a href="/format.php">format the file store</a><br>
<!-- <a href="/setssid.php">Set SSID and password</a><br> -->
</body>
</html>
