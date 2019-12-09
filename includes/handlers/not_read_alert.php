<?php 
include('/opt/lampp/htdocs/socialNetwork/config/config.php');
include_once('/opt/lampp/htdocs/socialNetwork/includes/classes/Notification.php');
include_once('/opt/lampp/htdocs/socialNetwork/includes/classes/User.php');

$id = $_SESSION['global_user']['id'];
$Message = new Notification($con,$id);

$list_not_read = $Message->get_unread_notification();
$output_m = '';

while ($value = mysqli_fetch_array($list_not_read)){

	if ($value['opened'] == 0 AND $value['user_to'] == $id ) {
		$subject = new User($con,$value['user_from']);
		$s_info = $subject->get_all_info();
        $message_name = $subject->get_user_full_name();
        $writer = '('.$s_info['first_name'].')&nbsp';
        $writer = '<span class="last_short_writer">'.$writer.'</span>';
        $output_m .= '<div class="short_message_body short_alert_message">';    	        
        $output_m .= '<a  class="alert_short_message" href=/socialNetwork/'.$value['link'].'>'.
		'<img class="individual_comment_image_alert" alt="user Image" src="'.$s_info['Profile_pic'].'">';
        $output_m .='<p>'.$value['message'].'</p>';
        $output_m .= '</a></div>';
	}
}

if ($output_m == '') {
	$output_m .= '<div class="go_to_message_center">';
$output_m .= '<p class="no_new_messages">Not New Alert</p>';
$output_m .= '</div>';
}



echo $output_m;
?>