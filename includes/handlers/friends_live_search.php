<?php

include('../../config/config.php');
include_once('../classes/User.php');

if (isset($_POST['search'])) {
	$user = $_SESSION['global_user'];
	$user_obj = new User($con,$user['id']);

	$search = $_POST['search'];
	$search = strip_tags($search);
	$search = mysqli_real_escape_string($con, $search);
	$search = str_replace(' ', '_', $search);
	$search = '%'.$search.'%';
	$query = "
	SELECT * FROM `users` 
	WHERE (username LIKE '$search' 
		OR email LIKE '$search') AND `user_close`= 'NO' 
	LIMIT 10";

	$data = mysqli_query($con,$query);

	$friends = '';
	$no_friends = '';

	while ($row = mysqli_fetch_array($data)) {
		$is_friend = $user_obj->isFriend($row['id'],$user['id']);
		$frienship = '<span class="frienship_friend_card">'.($is_friend?'(Friend)':'(no friend)').'</span>';
		$people = '';
		if ($row['id'] != $user['id']){
			$people .= '<div class="friend_card">';
			$people .= '<div class="friend_card_image">';
			$people .= '<a href="../'.$row['username'].'"><img alt="User Image" src="'.$row['Profile_pic'].'" class="userImage col-lg-4"></a>';
			$people .= '</div>'; 
			$people.= '<a href="../'.$row['username'].'"><p>'.$row['first_name'].'</br> '.$row['last_name'].'</p></a>';
			$people .= $frienship;
			$people .='</div>';
		}
		if ($is_friend){
			$friends .= $people; 
		} else {
			$no_friends .= $people;
		}
	}	
	$friends = $friends.$no_friends;

	print_r($friends);
}

?>