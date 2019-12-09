<?php 

include_once('./config/config.php');
include('./includes/header.php');



if (isset($_GET['id'])) {
    $post_id = ($_GET['id']);
} else {
    $post_id = false; 
}

$select = mysqli_query($con,"SELECT `user_to` FROM posts WHERE id = '$post_id'");
$user_to_load = mysqli_fetch_array($select);
$post_user_id = $user_to_load['user_to'];   

$profile_location_obj = new User($con,$post_user_id);
$profile_location_info = $profile_location_obj->get_all_info();
$notification_obj = new Notification($con,$info_user['id']);
$notification_obj->update_opened_notification($post_id);

?>

<header class="masthead">
 <!-- Masthead -->
  <div class="container h-100 flex_divider">
        <div class="h-100 w-50 userImageSticky">
            <div>
              <img alt="User Image" src="<?php if (isset($profile_location_info)) 
                echo $profile_location_info['Profile_pic'];?>" class="userImage col-lg-4">
            </div>
            <div class="text_align_center">
              <h4 class="text-uppercase text-white font-weight-bold"> 
                <?php echo $profile_location_info['first_name'];?> 
              </h4>
            </div>
        </div>
        <div class="w-100 col-lg-10 align-self-baseline centralized">
            <div class="post_group_area">
            </div>
        </div>
    </div>
  </header>
<input type="hidden" class="user_id" value="<?php echo $info_user['id'] ?>">
<input type="hidden" class="user_Logged_In" value="<?php echo $post_user_id ?>">
<input type="hidden" class="post_unique_id" value="<?php echo $post_id ?>">

<?php include('./includes/components/footer.php'); ?>



</html>
