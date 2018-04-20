<?php 
include("head.php");
include("nav.php");
?>
<section class="main wrap">
 <h3> Paintings</h3>
 
<?php 
include 'dbFunctions.php';
 $paintings = getPaintings(); 
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
     echo '<td>' . '<a href="searchPaintings.php?id=' . $row['id'] . '">'. $row['title'] . '</a></td> <td> <img src="img/'. $row['filename']. '"/></td>';
	 echo '</tr>';
 }

?> 
</table>

</section>
<?php 

include("footer.php");
include("tail.php");

?>
