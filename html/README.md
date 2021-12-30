These files need to be copied to the webservers main folder

The README.md files are not needed on the webserver but will do no actual harm.

These pages are a mixture of HTML, CSS, JAVASCRIPT and PHP. 
The PHP executes on the server where as the rest execute on the client's web browser.

They store the .hex files under a mk14 sub directory. This folder is also used to hold OS version - the presence of a file called MK14OS1 in the mk14 folder signifies to use the original version of the OS, the one that resets to ---- --, if not present it uses the newer version, which resets to 0000 00.
This is required when sending a .hex file as the "key strokes" needed are different for each OS.

Deleting a .hex file results in it being renamed as .hex.old, and it can therefore be restored if required.

The comment is stored in a file called .hex.txt, this file may not be present if no comment entered on the upload page.
If a previous comment was stored then leaving the comment blank on the upload page will leave the original comment file.

TODO:
At present the files must be stored on the webserver root directory, but by changing some of the links it should be possible for them to be used in a sub directory.

