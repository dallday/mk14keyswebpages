<!DOCTYPE HTML>
<!--
 The input keys page the MK14keys suite
 It allows you to enter keys on the MK14
 either by entering a string or by clicking on keys

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
.button  {
  width: 100px;
  font-family:'Courier New';
  background-color: white;
  border: 3;
  border-radius: 10px;
  color: black;
  padding: 15px 10px;
   text-align: center;
   text-decoration: none;
   display: inline-block;
   font-size: 16px;
   margin: 4px 2px;
   cursor: pointer;
   width: 100; }
</style>
</head>
<body onload=getosver(1);>
<h1>Send keystrokes to the MK14</h1>
<p> enter the keystrokes to send to the MK14 <br/>Use 0 to 9, A to F, <br/>
&nbsp;G=GO, M=MEM , T=TERM, Z=ABORT, R=Reset</p>
<input type='text' id='chars' name='chars'>&nbsp;&nbsp;&nbsp;
<button type='button' onclick="sendkeys(document.getElementById('chars').value);document.getElementById('chars').value='';">
Send keystrokes</button><p>
or click on the keys below</p>
<p><p id='reply'>&nbsp;</p><table border = 1><tr><td style='text-align: center;'>
<P style='text-align: center;'>Science of Cambridge</P>
<button onclick="sendkeys('g')" class='button' > go </button>
<button onclick="sendkeys('m')" class='button' > mem </button>
<button onclick="sendkeys('z')" class='button' > abort </button>
<button onclick="sendkeys('a')" class='button' > a </button>
<br>
<button onclick="sendkeys('7')" class='button' > 7 </button>
<button onclick="sendkeys('8')" class='button' > 8 </button>
<button onclick="sendkeys('9')" class='button' > 9 </button>
<button onclick="sendkeys('b')" class='button' > b </button>
<br>
<button onclick="sendkeys('4')" class='button' > 4 </button>
<button onclick="sendkeys('5')" class='button' > 5 </button>
<button onclick="sendkeys('6')" class='button' > 6 </button>
<button onclick="sendkeys('c')" class='button' > c </button>
<br>
<button onclick="sendkeys('1')" class='button' > 1 </button>
<button onclick="sendkeys('2')" class='button' > 2 </button>
<button onclick="sendkeys('3')" class='button' > 3 </button>
<button onclick="sendkeys('d')" class='button' > d </button>
<br>
<button onclick="sendkeys('t')" class='button' > Term </button>
<button onclick="sendkeys('0')" class='button' > 0 </button>
<button onclick="sendkeys('f')" class='button' > f </button>
<button onclick="sendkeys('e')" class='button' > e </button>
<br>
<button onclick="sendkeys('r')" class='button' > reset </button>
<P style='text-align: center;'>MK 14</P></td></tr></table>
</p>
<p id='os'>&nbsp;</p><p id='help'>&nbsp;</p>
<br>
<a href="/">return to main page</a><br>

<script>

// send the character string to the python script to operate the MK14 keys
// it checks that the string only contains correct characters
// it uses AJAX to update the page without a refresh
function sendkeys(keys){
    try {
        keycheck=keys.replace (/[^A-F0-9 gmtzr]/ig,'');
        if (keycheck == keys){
            var xhttpsk = new XMLHttpRequest();
            xhttpsk.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById('reply').innerHTML = 'Last keys sent:&nbsp;' + this.responseText;
                }
            };
            xhttpsk.open('GET', 'sendchars.php?chars=' + keys , true);
            xhttpsk.send();
        }else{
            document.getElementById('reply').innerHTML = "Invalid characters in " + keys ;
        }
    }
    catch (err) {
        document.getElementById ('filecontents').innerHTML = "Problem sending keys<br>" + err.message;
    }
}

// get the version of the MK14 os as stored on the webserver
// relies on file mk14/MK14OS1 on the server
// it uses AJAX to update the page without a refresh
function getosver(){
    document.getElementById ('os').innerHTML = 'getting os';
    try {
        const xhttpsk = new XMLHttpRequest ();
        xhttpsk.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                if (this.responseText=='1'){
                    showos(1);
                }else{
                    showos (2);
                }
            }
        };
        xhttpsk.open ('GET', 'getosver.php' , true);
        xhttpsk.send ();
    }
    catch (err) {
        document.getElementById ('os').innerHTML = err.message;
    }

}

// displays details of the OS selected on the webpage
// it does not reset the os selection on the index page
function showos(version){
    if (version=='1'){
        document.getElementById('os').innerHTML = 'version 1 of monitor resets to ---- -- &nbsp;&nbsp; <button onclick="showos(2)"> show version 2</button>';
        document.getElementById('help').innerHTML = '<pre>usage:<br/>Z M 0 F 2 0<br/> T C 4 T M T 0 7 T M T 0 7 T M T 3 F T <br/>Z G 0 F 2 0 T<br/></pre>';
    }else{
        document.getElementById('os').innerHTML = 'version 2 of monitor resets to 0000 00 &nbsp;&nbsp; <button onclick="showos(1)">show version 1</button>';
        document.getElementById('help').innerHTML = '<pre>usage:<br/>Z 0 F 2 0  M C 4 M 0 7 M 0 7 M 3 F Z 0 F 2 0 G</pre><br/>';
    }
}


</script>

<script>
// think this means touch screen key interaction are translated to a click event
input.addEventListener('keyup', function(event) {
    if (event.keyCode == 13) {
        event.preventDefault(); 
        document.getElementById('chars').click(); 
    }
});
</script>
</body></html>
