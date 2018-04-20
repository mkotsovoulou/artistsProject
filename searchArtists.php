<?php
include 'dbFunctions.php';

if (isset($_GET['artistname'])) {
$artistname = $_GET['artistname'];
$artists = findArtist($artistname); 

echo '<table style="width:100%">';
echo ' <tr>' ;
echo ' <th>id</th>';
echo ' <th>name</th> ';
echo ' </tr> ';

 foreach ($artists as $row) {
  echo '<tr>';
   echo ' <td>'.$row['id'].'</td>';
   echo ' <td>'.$row['name'].'</td> ';

 echo ' </tr>';
}
echo '</table>';
}
?>
<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="text" name="artistname"/>
    <input type="submit" name="search" value="search"/>
    
</form>