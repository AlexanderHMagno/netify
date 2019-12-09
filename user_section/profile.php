
<?php 
require('../config/config.php');
include('../includes/header.php');

$first_initial = !isset($_SESSION['welcome_screen'])? true : false;
$post = new Post($con,$info_user['id']);
$userLoggedIn = $info_user['id'];



if (isset($_GET['profile_username'])) {
  $user_class = new User($con,'',$_GET['profile_username']);
  $profile_user_id = $user_class->get_user_id();
  $info_user = $user_class->get_all_info();
  
}

if (isset($_POST['post_button'])) {
  $post->submit_post($_POST['post_comment'],$profile_user_id);
  // header('location: welcome.php');
}

//proper user 
$my_profile = false;
if ( $info_user['id'] == $userLoggedIn) {
  $my_profile = true;
}

//are friends 
$pending_invitation = $user_class->isFriend_invitation_pending($info_user['id'],$userLoggedIn);
$friends = $user_class->isFriend($info_user['id'],$userLoggedIn); 

//
$number_of_pending_invitations = $user_class->friend_pending_invitations($userLoggedIn);
$invitations_action = $user_class->user_list_pending_invitation($userLoggedIn);


?>

<input type="hidden" class="user_id" value="<?php echo $info_user['id'] ?>">
<input type="hidden" class="user_Logged_In" value="<?php echo $userLoggedIn ?>">


<header class="masthead">


<!-- Modal -->
<div id="friendsModal" class="modal fade" role="document">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Pending Friend Request</h4>
      </div>
      <div class="modal-body">
<?php 
      foreach ($invitations_action as $key => $value) {
        echo 

        '<div class="individual_friend_request">
                          <div>
                            <img class="individual_post_image" alt="user Image" src="'.$value['Profile_pic'].'"> 
                          </div>
                          <div class="individual_post_body">
                            <div>
                              <a href="/socialNetwork/'.$value['username'].'">'.$value['first_name'].' '.$value['last_name'].'</a> 
                            </div>
                            <div>
                              <a class="btn friends_request_button individual_friend_action" onClick=action_friendship('.$value["user_id"].','.$value["friend_id"].',"accept",this)>Accept</a>
                              <a class="btn friends_request_button individual_friend_action" onClick=action_friendship('.$value["user_id"].','.$value["friend_id"].',"ignore",this)>Ignore</a>
                            </div>
                            
                          </div>

        </div>';
      }
?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


 <!-- Masthead -->
  <div class="container h-100<?php if(!$first_initial) echo " flex_divider" ;?>">
      <div class="h-100 w-50 userImageSticky">
        <div>
          <img alt="User Image" src="<?php if (isset($info_user)) echo $info_user['Profile_pic'];?>" class="userImage col-lg-4">
        </div>
        <div class="text_align_center">
          <h4 class="text-uppercase text-white font-weight-bold"> 
            <?php echo $info_user['first_name'];?> 
          </h4>
        </div>
        <div class="profile_buttons_area">
          <?php if (!$my_profile) {
              if ($friends) {
                echo "<a id='friendship_status' onClick='remove_friendship(".$userLoggedIn.",".$info_user['id'].")'><img 
                    class='logo_icon' src='/socialNetwork/assets/images/friendship/friend.png' alt='Remove as a friend' title='Remove as a friend'></a>";
              } else {
                if ($pending_invitation){
                  echo "<img id='friendship_status'class='logo_icon' src='/socialNetwork/assets/images/friendship/pending.png' alt='pending Friendship' title='Pending Friendship'>";
                } else {
                  echo "<a id='friendship_status' onClick='request_friendship(".$userLoggedIn.",".$info_user['id'].")'><img 
                    class='logo_icon' src='/socialNetwork/assets/images/friendship/unfriend.png' alt='Add as a friend' title='Add as a friend'></a>";
                }
                
              }
          ?>

              <a href='messages.php?u=<?php echo $info_user['username'];?>'><img 
                    class='logo_icon' src='/socialNetwork/assets/images/actions/chat.png' alt='Send a message' title='Send a message'></a>
          <?php   
            } else {
              if($number_of_pending_invitations != 'no') {
                 echo "<img data-toggle='modal' data-target='#friendsModal' class='logo_icon' src='/socialNetwork/assets/images/friendship/request.png' alt='You have pending friend requests' title='pending friend requests'>";
              }
            }
          ?>

        </div>
      </div>
        <div class="w-100 col-lg-10 align-self-baseline">
          <form id="Post_form" action="<?php echo $info_user['username'];?>" method="POST" id class="netify_post_area col-lg-8 align-self-baseline w-80">
            <textarea name="post_comment" id="post_comment" form="Post_form" placeholder="What would you like to <?php if($my_profile) echo 'post today' ;else echo'say to '.$info_user['first_name'];?>"required></textarea>
              <label for="image_uploads" class="likesGroup"><img src="/socialNetwork/assets/images/actions/add.png" class="logo_icon_mini"></label>
              <input type="file" style="display: none;" id="image_uploads" name="image_uploads" accept="image/png, image/jpeg">
            <fieldset>
              <input type="submit" form="Post_form" class="btn btn-primary js-scroll-trigger" name="post_button"> 
            </fieldset>
            
          </form>
            <div class="post_group_area">
            </div>
            <img src="/socialNetwork/assets/images/Loader.gif" class="loading_element" id="loading">';
        </div>
    </div>
  </header>

<?php include('../includes/components/footer.php'); ?>



</html>