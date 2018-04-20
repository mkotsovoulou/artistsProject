<?php
 include("dbFunctions.php");
 $artist = $_POST["artistname"]; //links with the FORM which posts to this page
 
 echo addArtist($artist);
