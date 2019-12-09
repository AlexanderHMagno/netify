<?php 

include_once('./config/config.php');
include('./includes/header.php');


if (isset($_GET['u'])) {
	$user_profile = $_GET['u'];
	$user_to_info = new User($con,'',$_GET['u']);
    $user_full_info = $user_to_info->get_all_info();
	$user_to = $user_to_info->get_user_id();
} else {
	$user_to = $info_user['id'];
    $user_to_info = new User($con,$user_to);
    $user_full_info = $user_to_info->get_all_info();
}


?>

<header class="masthead">
 <!-- Masthead -->
  <div class="container h-100 flex_divider">
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
        	<div class="open_conversations">
        		<?php 
        			$Message_obj = new Message($con,$info_user['id']);
        			$data = $Message_obj->get_list_conversations();
                    $open_mess_body = [];
        			while ($row = mysqli_fetch_array($data)) {
                        if (stripos($row['body'], 'www.youtube')) {
                            $row['body'] = 'video';
                        }
                        if (strlen($row['body'])>20) {
                            $row['body'] = substr($row['body'],0,20).'...';
                        };
                        if ($row['user_from'] == $row['user_to']){
                                array_unshift($open_mess_body, 
                                ['id'=>$row['user_to'],
                                'body'=>$row['body'],
                                'writer'=>$row['user_from']]);
                        } else {
                            if ($row['user_from'] == $info_user['id']) {
                                array_push($open_mess_body, 
                                ['id'=>$row['user_to'],
                                'body'=>$row['body'],
                                'writer'=>$row['user_from']]);
                            } else {
                                array_push($open_mess_body, 
                                ['id'=>$row['user_from'],
                                'body'=>$row['body'],
                                'writer'=>$row['user_from']]);
                            }    
                        }
        			}

        			foreach ($open_mess_body as $key => $value) {
        				$subject = new User($con,$value['id']);
                        if ($subject->check_account_open()) {
                            $s_info = $subject->get_all_info();
                            $message_name = $subject->get_user_full_name();
                            $you = $value['writer'] == $info_user['id'];
                            $writer = '('.$s_info['first_name'].')&nbsp';
                            if ($you) {
                                $message_name = $s_info['first_name'].' (you)';
                                $writer = '(you)&nbsp';
                            }
                            $writer = '<span class="last_short_writer">'.$writer
                                    .'</span>';
                            if ($user_full_info['username'] == $s_info['username']){
                                $output_m = '<div class="short_message_body selected">';
                            } else {
                                $output_m = '<div class="short_message_body">';    
                            }
                            
                            $output_m .= '<a href=messages.php?u='.$s_info['username'].'>'.
                            '<img class="individual_comment_image" alt="user Image" src="'.$s_info['Profile_pic'].'">'. $message_name.'</a>';
                            $output_m .='<p>'.$writer.$value['body'].'</p>';
                            $output_m .= '</div>';
                            echo $output_m;
                        }
        			}
        			
        		?>
        	</div>
        </div>
      </div>
        <div class="w-100 col-lg-10 align-self-baseline">

            <div class="message_group_area messages_area">

            </div>

            <div class="message_group_actions">
            	<textarea id="message_body_post" form="Message_form" placeholder="Write a message"required></textarea>
            	<a class="btn btn-primary btn-comment js-scroll-trigger" onclick="append_message(<?php echo $info_user['id'].','.$user_to;?>)">send</a>
           	</div>
        </div>
    </div>
  </header>
  <input type="hidden" class="fmess" value="<?php echo $info_user['id'] ?>">
<input type="hidden" class="tmess" value="<?php echo $user_to ?>">

<?php include('./includes/components/footer.php'); ?>



</html>
