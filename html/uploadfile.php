<?php
// this handles the setting of the MK14 os version
//
// If the get os parameter is 1 then create  mk14/MK14OS1
//   else remove the file
//
//  part of the MK14keys webpages
//  David Allday December 2021
//  version 1

try {
  
    $errormessage="";
    $message="";
    $target_dir = "mk14/";
    $target_file = $target_dir . basename ($_FILES["data"]["name"]);
    // ensure the final file is all lower case
    // will think about this
    // $target_file = strtolower ($target_file);
    $uploadOk = 1;
    $uploadFileType = strtolower (pathinfo ($target_file,PATHINFO_EXTENSION));
    // echo "Filename " .  $_FILES["data"]["name"] . "<br>";
    // echo "target_file " . $target_file . "<br>";

    // Print the cities array
    //Print_r($_FILES);
    //echo "<hr>";
    //var_dump($_FILES);

    // Allow certain file formats
    if($uploadFileType != "hex" ) {
      $errormessage= "Sorry, only .hex files are allowed not .$uploadFileType.";
      $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
      $errormessage= "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        $comment = $_POST ["comment"];
        //  echo "Comment:$comment <br>";
        //  echo "copying " .  $_FILES["data"]["tmp_name"] . " to " . $target_file; 
        // thing this checks for valid file to be copied ???
        if (move_uploaded_file($_FILES["data"]["tmp_name"], $target_file)) {
            //   if (copy ($_FILES["data"]["tmp_name"], $target_file)) {
            $message = "The file ". htmlspecialchars( basename( $_FILES["data"]["name"])). " has been uploaded.";
            // check for old versions of the file and delete it if present
            $oldfilename = $target_file . ".old";
            if (file_exists($oldfilename) ){
                unlink($oldfilename);
            }
            // only update comment file if new comment supplied
            if (strlen ($comment) > 0){
                $commentfilename=$target_file . ".txt";
                //echo "Commentfilename:$commentfilename ";
                $commentfile = fopen ($commentfilename, "w");
                fwrite ($commentfile,$comment);
                fclose ($commentfile);
            }

        } else {
            $errormessage= "Sorry, there was an error uploading your file.";
        }
    }

    header ("Location:index.php?message=$message&emessage=$errormessage");
}
  catch (Exception $e) {
     header ("Location:index.php?emessage=" . "ERROR" . $e->getMessage ());
}

?>
