<?php
 include 'dbFunctions.php';
 $artists = getArtists(); 
 
 echo '<select name="artist">';
 foreach ($artists as $row) {
   echo ' <option value="'. $row['id'] . '">'. $row['name'].'</option>';
 }

echo '</select>';



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
?>

