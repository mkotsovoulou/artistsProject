<?php 
include("head.php");
include("nav.php");

$id= $_GET['id'];
?>
<section class="main wrap">
 <h3> Paintings</h3>
 
<?php 
include 'dbFunctions.php';
 $paintings = getPaintingBlob($id); 
?>
 <table border="1"> 
 <thead>
    <tr>
      <th>Painting Title</th>
      <th>Painting</th>
    </tr>
  </thead>
 
<?php
 foreach ($paintings as $row) {
     echo '<tr>';
     echo '<td>' . $row['title'] . '</td> <td> <img src="data:image/jpg;base64,'. base64_encode($row['image']). '"/></td>';
	 echo '</tr>';
	 echo '<tr><td>' . $row['artist'] . '</td> <td>' . $row['price'] . '</td> </tr>';
 }

?> 
</table>

</section>
<?php 

include("footer.php");
include("tail.php");

?>