<?php 
include("head.php");
include("nav.php");
?>
<section class="main wrap">
 <h3> Artists</h3>
<?php 
include 'dbFunctions.php';

if (isset($_POST['add']) && $_SESSION['pastData'] != $_POST)  { 
	$name = $_POST['name'];
	if (!empty($name)) {
	    addArtist($name);
	    $_SESSION['pastData'] = $_POST;
?>
<script>  
   $(document).ready(function () { 
       success();
   });
</script>

<?php

} }

if(isset($_POST['delete'])  && $_SESSION['pastData'] != $_POST ) { 
	$id = $_POST['id']; 
    if (!empty($id)) { 
	   deleteArtist($id);
	   $_SESSION['pastData'] = $_POST;
	?>
<script>  
   $(document).ready(function () { 
       deleted(<?php echo $id; ?>);
   });
</script>

<?php
}} 

if (isset($_POST['update'])  && $_SESSION['action']=="Y") { 
	$name = $_POST['newName']; 
	$id = $_POST['id']; 
	if (!empty($name) && !empty($id)) {
	    updateArtist($id, $name);
	    $_SESSION['pastData'] = $_POST;
	?>
<script>  
   $(document).ready(function () { 
       updated(<?php echo $id; ?>);
   });
</script>

<?php	  
} }

 $artists = getArtists(); ?>
 <div id="messages">
 
</div>

 <table border="1"> 
 <thead>
    <tr>
      <th> Artist ID </th></th>
      <th> Artist Name</th>
      <th> Action</th>
    </tr>
  </thead>
 
 <?php
 foreach ($artists as $row) {
     echo '<tr>';
     echo '<td>' . $row['id'] . '</td> <td>'. $row['name'] . '</td>' . '<td>' .  '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">
           <input type="hidden" value="' . $rand . '" name="randcheck" />
           <input type="hidden" name="id" value="'. $row['id'] .'"> 
           <button type="submit" name="delete"><i class="fi-x medium"></i></button>' . '</form></td>';
	 echo '</tr>';
 }

?> 
</table>
<h3> Add a new Artist</h3>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<?php 

?>
  <input type="text" name="name" required> 
  <input type="hidden" value="<?php echo $rand; ?>" name="randcheck" />
  <input type="submit" name="add" value="Add Artist">
</form>

<h3> Change an artist Name</h3>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<?php 
$_SESSION['action']="Y";
?>
  <input type="text" name="id"> 
  <input type="text" name="newName" required> 
  <input type="hidden" value="<?php echo $rand; ?>" name="randcheck" />
  <input type="submit" name="update" value="Update Artist name">
</form>
</section>
<script>
    
    function success() {
         var message = document.getElementById("messages");
    message.className = "callout success";
    message.innerHTML = "<b>New Artist Inserted</b>";
     setTimeout(function() {  
         message.className = "";
         message.innerHTML='';},2000);
         
  
    }
    
     function deleted(id) {
     var message = document.getElementById("messages");
    message.className = "callout warning";
    message.innerHTML = "<b>Artist " + id + " Deleted</b>";
    setTimeout(function() { 
        message.className = "";
        message.innerHTML='';},2000);
      
    }
    
    function updated(id) {
     var message = document.getElementById("messages");
    message.className = "callout sucess";
    message.innerHTML = "<b>Artist " + id + " updated</b>";
    setTimeout(function() { 
        message.className = "";
        message.innerHTML='';},2000);
        
   
    }
    
  
</script>
<?php 
$action='N';
include("footer.php");
include("tail.php");

?>