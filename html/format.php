<!DOCTYPE HTML>
<!-- 
 Asks if you really want to format the file system 
 aka delete all the files

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
.button  {  width: 100px; font-family:'Courier New';
            background-color: white;   border: 3;   border-radius: 10px;
            color: black;   padding: 15px 10px;   text-align: center;
            text-decoration: none;   display: inline-block;
            font-size: 16px;   margin: 4px 2px;   cursor: pointer;   width: 100; 
}

</style>
</head>
<body>
<script>
function loadmess(){
    document.getElementById('message').innerHTML='Processing please wait';
}
</script>
<h1>Format File System</h1>
<div id=message>
<h2> Are You Sure ?</h2>
<p>Formatting the file system cannot be undone</p>
<a onclick='loadmess()' href="/formatyes.php">YES</a>
&nbsp;&nbsp;&nbsp;
<a href="/?message=format operation cancelled">NO</a>
<br/>
<br/>
<a href="/">return to home</a>
<br>
</div>
</body>
</html>
