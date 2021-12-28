<!DOCTYPE HTML>
<!--
 Displays all the files deleted by being renaming as .old
 and then gives you the option to restore the file

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
td {padding: 5px;text-align: left;}
p {text-align: left;}
.button  {
      width: 100px; font-family:'Courier New';
      background-color: white;   border: 3;
      border-radius: 10px;   color: black;
      padding: 15px 10px;   text-align: center;
      text-decoration: none;   display: inline-block;
      font-size: 16px;   margin: 4px 2px;
      cursor: pointer;   width: 100; 
      }
</style>
</head>
<body>
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

</script>


<p>&nbsp;</p><form action="/restorefile.php"><table> <tr>
<td style='text-align: center;' colspan='2'>Programs</td></tr>

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

        $origname=substr ($filename, 0, strrpos ($filename, "."));
        $hexname=substr ($origname,strlen ($directory));
        echo "<tr><td><input type=radio id='$filename' ";
        echo "name='program' value='$hexname' " ;
        echo "onclick=loadDoc('$filename') > ";
        // just want the end name

        echo "<label for='$filename'>$hexname</label> <br>";

        $commentfile="$origname" . ".txt";
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

<tr><td colspan='2'><input type='submit' name='action' value='Restore file'>

</form> </td></tr></table>

<p>
<?php 
echo $_GET["message"];
?>
</p>
<?php 
  $errormessage=$_GET["emessage"];
  if ( (strlen ($errormessage))  > "0" ){
    echo "<p style='color: red;'> ";
    echo $errormessage ;
    echo "</p>";
  }
?>
<p></p>
<table>
<tr><td>File contents</td></tr>
<tr><td><div id='filecontents'>file contents will appear here</div></td></tr>
</table>

<br><br>

<a href="/">return to main page</a><br>

</body>
</html>
