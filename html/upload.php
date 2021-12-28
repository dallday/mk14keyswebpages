<!DOCTYPE HTML>
<!-- 
 The upload page the MK14keys suite
 It asks for the name of the new file 
 and called uploadfile.php to do the actual upload

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
         padding: 15px 10px;
         text-align: center;
         text-decoration: none;
         display: inline-block;
         font-size: 16px;
         margin: 4px 2px;
         cursor: pointer;
         width: 100; 
}
</style>
</head>
<body>
<h1>Upload a new .hex file</h1>
<p>select the hex file to upload and enter a comment to be displayed with it</p>
<form action='/uploadfile.php' method='post'  enctype='multipart/form-data'>
<input type='file' name='data' accept='.hex'>
<br/><br/>
<input type='text' name='comment' value=''>
<button>Upload</button>
</form>
<br>
<a href="/">return to main page</a><br>
</body>
</html>
