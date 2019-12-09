<?php 
require('../config/config.php');
include('../includes/header.php');

?>

<input type="hidden" class="user_id" value="<?php echo $info_user['id'] ?>">
<input type="hidden" class="post_unique_id" value="<?php echo $info_user['id'] ?>">

<header class="masthead">

 <!-- Masthead -->
  <div class="container h-100<?php if(!$first_initial) echo " flex_divider" ;?>">
    <div class="h-100 w-30 userImageSticky">
      <div>
        <a alt="Change your image" href="/socialNetwork/upload.php">
          <img alt="User Image" src="<?php if (isset($info_user)) echo $info_user['Profile_pic'];?>" class="userImage col-lg-4">
        </a>
      </div>
      <div class="text_align_center">
          <h4 class="text-uppercase text-white font-weight-bold profile_user_name"> <?php echo $info_user['first_name'];?> 
            </h4>
      </div>
    </div>
    <div class="w-100 col-lg-10 align-self-baseline settings_group">
    
      <div class="settings_drawer" id="settings_name">
        <div>
          <label for="first">First Name</label>
          <input name="first" id="settings_first_name" value="<?php echo $info_user['first_name']?>">
        </div>
        <div>
          <label for="second">Last Name</label>
          <input name="second" id="settings_last_name" value="<?php echo $info_user['last_name']?>">
        </div>
        <a class="update_settings btn btn-primary js-scroll-trigger" id="settings_name_general">Update</a>
      </div>
      <div class="settings_drawer" id="settings_email">
        <div>
          <label for="email">Email</label>
          <input name="email" id="new_email" value="<?php echo $info_user['email']?>">
        </div>
        <a class="update_settings btn btn-primary js-scroll-trigger" id="settings_email_general">Update</a>
      </div>
      <div class="settings_drawer" id="settings_password">
        <div>
          <label for="oldpassword1">Old Password</label>
          <input type="password" name="oldpassword1" id="oldpassword1"  value="">
        </div>
        <div>
          <label for="newpassword1">New Password</label>
          <input type="password" name="newpassword1" id="newpassword1" value="">
        </div>
        <div>
          <label for="newpassword2">New Password (confirm)</label>
          <input type="password" name="newpassword2" id="newpassword2"  value="">
        </div>
        <a class="update_settings btn btn-primary js-scroll-trigger" id="settings_password_general">Update</a>
      </div>
      <div class="settings_drawer" id="settings_close">
        <a class="update_settings btn btn-secondary js-scroll-trigger" id="settings_close_account">Close Account</a>
      </div>
    </div>    
  </div>
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
  <script type="text/javascript" src="../assets/js/settings.js"></script>
  <!-- TODO HOW TO PASS A VARIABLE TO JS FROM THE BEGGINING -->


</body>

</html>
