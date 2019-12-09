<?php 

include('../../config/config.php');
include('../classes/User.php');
include('../classes/Comment.php');
include('../classes/Notification.php');

if(isset($_POST)){
$comments_obj = new Comment($con,$_POST["post_id"]);

	if (isset($_POST['userLogIn'])) {
		$Notification_obj = new Notification($con,$_POST["userLogIn"]);
// post_comment 
		$body = $_POST['body'];
		$body = strip_tags($body);
		$body = mysqli_real_escape_string($con, $body);
		$body = str_replace('\r\n','\n', $body);
		$body = nl2br($body);
		$userLogIn = $_POST['userLogIn'];
		$userTo = $_POST['user_to'];
		$post_id = $_POST['post_id'];
		$date = date("Y-m-d H:i:s");
		$writer = $Notification_obj->get_original_writer($post_id);

		$comments_obj->post_comment($body, $userLogIn, $userTo, $date);

		if ($userLogIn != $userTo) {
			$Notification_obj->insert_notification(
					$post_id,$userTo,'profile_comment');
		}

		if ($writer['added_by'] != $userLogIn ) {
			$Notification_obj->insert_notification(
				$post_id,$writer['added_by'],'comment');
		}

		$commenters = $comments_obj->who_has_commented();

		foreach ($commenters as $key => $value) {

			if ($value != $writer['added_by'] AND $value != $writer['user_to'] AND
					$value != $userLogIn ) {

				$Notification_obj->insert_notification(
						$post_id,$value,'comment_non_owner');
			}
		}


		
	} else {
//append_comments_form
		$form = '
		<div class="comment_group" id="comment_group'.$_POST["post_id"].'">
			<div class="comment_general_group_actions">
				<textarea id="body'.$_POST['post_id'].'" placeholder="What would you like to comment"></textarea>
				<div class="comment_actions">
					<a class="btn btn-primary btn-comment js-scroll-trigger" onClick=post_comment('.$_POST["post_id"].','.$_POST["user_id"].')> Comment </a>
					<a class="btn btn-dark btn-comment js-scroll-trigger" onClick=close_comment('.$_POST["post_id"].')> Close </a>
				</div>
			</div>';

		$form .= $comments_obj->get_comments();
		$form .= '</div>';
		echo $form;
	}
	
} else {
	echo 1;
}

?>