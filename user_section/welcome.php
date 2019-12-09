<?php 
require('../config/config.php');
include('../includes/header.php');

$first_initial = !isset($_SESSION['welcome_screen'])? true : false;
$post = new Post($con,$info_user['id']);

if (isset($_POST['post_button'])) {
  $post->submit_post($_POST['post_comment'],"");
  header('location: welcome.php');
}

?>

<input type="hidden" class="user_id" value="<?php echo $info_user['id'] ?>">

<header class="masthead">

 <!-- Masthead -->
  <div class="container h-100<?php if(!$first_initial) echo " flex_divider" ;?>">
      <div class="h-100 <?php if($first_initial) {
        echo 'align-items-center justify-content-center text-center row';
        } else {
          echo 'w-50 userImageSticky';
        } 
       ?>">
        <div>
          <img alt="User Image" src="<?php if (isset($info_user)) echo $info_user['Profile_pic'];?>" class="userImage col-lg-4">
        </div>
        <div class="<?php if($first_initial) {echo "col-lg-10";} else {echo "text_align_center";}?>">
          <<?php echo $first_initial?'h2':'h4';?> class="text-uppercase text-white font-weight-bold"> <?php if(!isset($_SESSION['welcome_screen'])) echo "Welcome back "; echo $info_user['first_name'];?> 
            </<?php echo $first_initial?'h2':'h4';?>>
        </div>
  <!--       <div class="col-lg-8 align-self-baseline">
           -->
        <?php if(!$first_initial) echo '</div>';?>
        <div class="w-100 col-lg-10 align-self-baseline">
          <form id="Post_form" action="welcome.php" method="POST" id class="netify_post_area col-lg-8 align-self-baseline w-80">
            <textarea name="post_comment" id="post_comment" form="Post_form" placeholder="What would you like to post today" <?php if(!$first_initial) echo'required'?>></textarea>
            <fieldset>
              <input type="submit" form="Post_form" class="btn btn-primary js-scroll-trigger" name="post_button"> 

              <?php if($first_initial) echo 
              '<button type="submit" class="btn btn-secondary  js-scroll-trigger" name="no_post_button" formaction="welcome.php"> Later </button>'; ;?>
            </fieldset>
            
          </form>

          <?php if(!$first_initial) echo ' 
                <div class="post_group_area">
                </div>
                <img src="/socialNetwork/assets/images/Loader.gif" class="loading_element" id="loading">';
          ?>
         
        </div>
          <!-- <a class="btn btn-primary btn-xl js-scroll-trigger" href="#about">Post</a> -->
        <!-- </div> -->


        <?php if($first_initial) echo '</div>';?>
     
    </div>
<?php  $_SESSION['welcome_screen'] = true; 
  ?>

  </header>


  <!-- Footer -->
  <footer class="bg-light py-5">
    <div class="container">
      <div class="small text-center text-muted">Copyright &copy; 2019 - Start Bootstrap</div>
      <div class="small text-center text-muted">Icons made by <a href="https://www.flaticon.com/authors/freepik" title="Freepik">Freepik</a> from <a href="https://www.flaticon.com/"     title="Flaticon">www.flaticon.com</a></div>
    </div>

  
  </footer>


  <script src="../assets/vendor/jquery/jquery.min.js"></script>
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../assets/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
  <script src="../assets/vendor/js/creative.min.js"></script>
  <script type="text/javascript" src="../assets/js/infinitive_scroll.js"></script>
  <script type="text/javascript" src="../assets/js/comments.js"></script>
  <script type="text/javascript" src="../assets/js/message.js"></script>
  <script type="text/javascript" src="../assets/js/alert.js"></script>
  <!-- TODO HOW TO PASS A VARIABLE TO JS FROM THE BEGGINING -->


</body>

</html>
