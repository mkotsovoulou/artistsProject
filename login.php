<?php
/**
 * Created by PhpStorm.
 * User: f-mkotsovoulou
 * Date: 3/12/2018
 * Time: 2:38 PM
 */
include("head.php");
include("nav.php");
include("dbFunctions.php");
?>

<section class="main">
 <div class="grid-container fluid">
      <div class="grid-x grid-margin-x">
      <div class="cell small-4"></div>
      <div class="cell small-4">
      <h4 class="text-center">Log in with you email account</h4>
      </div>
     </div>
      <div class="grid-x grid-margin-x">
      <div class="cell small-4"></div>
      <div class="cell small-4">
      <form class="log-in-form" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
   
          <label> Email 
              <input type="text" name="email" placeholder="email">
         </label> 
         <label> Password
                   <input type="text" name="password" placeholder="password">
        </label>
          <input class="button expanded" type="submit" class="button" value="Login" name="submitBTN"/> 
         <p class="text-center"><a href="#">Forgot your password?</a>  &nbsp;&nbsp;New User? <a href="signup.php">Signup here &raquo</a</p>
            <div id="message"></div>
      </form>
   </div>
    </div>
 
</section>
<?php
if ($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['submitBTN'])) {

    $email = htmlentities($_POST['email']);
    $pass = htmlentities($_POST['password']);
    if ($email == '' || $pass == '')
        echo 'Please enter email and password!';
    else {
          if (!login($email, $pass)) {
              $message='Invalid username or password!';
              ?>
              <script>
                   document.getElementById("message").className = "callout alert";
                  document.getElementById("message").innerHTML="<?php echo $message; ?>"; </script>
              <?php }
          else {
              $message='Login Successfull.. Redirecting';
                ?>
              <script>
                 document.getElementById("message").className = "callout success";
                  document.getElementById("message").innerHTML="<?php echo $message; ?>"; </script>
         <meta http-equiv="refresh" content="2;url=http://mairak.students.acg.edu/index.php" />
  <?php } }

    }

include("footer.php");
include("tail.php");
?>