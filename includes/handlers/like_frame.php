<?php

include('../../config/config.php');
include('../classes/User.php');
include('../classes/Notification.php');

if (isset($_POST['post_id'])) { 

	$respond =[];
	$user_id = $_POST["userLogIn"];
	$post_id = $_POST["post_id"];
	$Notification_obj = new Notification($con,$user_id);
	$writer = $Notification_obj->get_original_writer($post_id);
	$writer = $writer['added_by'];


	if($_POST['type'] === 'Like') {

		$verify_query = "SELECT id FROM `likes` WHERE `user_id`='$user_id' AND `post_id`= '$post_id'";
		$verify_row = mysqli_query($con,$verify_query);
		if(mysqli_num_rows($verify_row) == 0) {
			$respond['trigger'] = 'Unlike';
			$query = "
				INSERT INTO `likes`(`user_id`, `post_id`) 
				VALUES ('$user_id','$post_id')";
			mysqli_query($con,$query);
			$inserted_like = mysqli_insert_id($con);
			if ( $inserted_like > 0) {
				if($writer != $user_id) {
					$Notification_obj->insert_notification($post_id,$writer,'like');
				}
			}

		} 
		
	} else {
		$respond['trigger'] = 'Like';
		$query = "
		DELETE FROM `likes` WHERE `user_id`='$user_id' AND `post_id`= '$post_id'";
		$row = mysqli_query($con,$query);
		$Notification_obj->remove_notification($post_id,$writer,'like');
	}	

	echo json_encode($respond) ;
}
?>