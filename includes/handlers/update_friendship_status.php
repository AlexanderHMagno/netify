<?php 

include('../../config/config.php');
include_once('../classes/User.php');


if(isset($_POST['status'])){

	$query = '';
	$userLoggedIn = $_POST['userLoggedIn'];
	$userTo = $_POST['userTo'];
	$check_friendship = new User($con,$userLoggedIn);
	switch ($_POST['status']){

		case "request":

			if(!$check_friendship->isFriend_invitation($userLoggedIn,$userTo)){
				$query = "INSERT INTO `friends`(`user_id`, `friend_id`) VALUES ($userLoggedIn,$userTo)";
			}
			break;

		case "update":
				if($_POST['action']=='accept'){
					$query = "
						UPDATE `friends` 
						SET `status`='confirmed'
						WHERE `user_id` IN ($userLoggedIn,$userTo) 
						AND `friend_id` IN ($userLoggedIn,$userTo)";
				} else {
					$query = "
					 DELETE FROM `friends` 
					 WHERE `user_id` IN ($userLoggedIn,$userTo) 
					 AND `friend_id` IN ($userLoggedIn,$userTo)";		
				}
				
			break;

		case "remove":
			 $query = "
				 DELETE FROM `friends` 
				 WHERE `user_id` IN ($userLoggedIn,$userTo) 
				 AND `friend_id` IN ($userLoggedIn,$userTo)";
			break;
		default:
			echo $_POST['status'];	
			break;
	}


	if ($query != '') {
		mysqli_query($con,$query);
	}
} 

return false;


?>