<?php
include('../config/config.php');
include('../includes/header.php');
?>

<header class="masthead">
	<div class="">
		<ul class="option_friend_selector list-group list-group-horizontal">
		  <li class="list-group-item selected">Friend</li>
		  <li class="list-group-item">Search</li>
		</ul>
	</div>
	<div class="friends_confirmed">
	<?php
	if (isset($info_user)) {

		$loggedIn = new User($con,$info_user['id']);
		$list_friends = $loggedIn->list_of_friends();
		$generate_list = null;
		foreach ($list_friends as $key => $value) {
			$friend_obj = new User($con,$value);
			$friend_info = $friend_obj->get_all_info();
			$generate_list.= '';
			$generate_list .= '<div class="friend_card">';
			$generate_list .= '<div class="friend_card_image">';
			$generate_list .= '<a href="../'.$friend_info['username'].'"><img alt="User Image" src="'.$friend_info['Profile_pic'].'" class="userImage col-lg-4"></a>';
			$generate_list .= '</div>';
			$generate_list.= '<a href="../'.$friend_info['username'].'"><p>'.$friend_obj->get_user_full_name().'</p></a>';

			$generate_list .='</div>';

		}
		echo $generate_list;
	}
	?>
	</div>
	<div class="friends_search">
		<div class="input-group input-group-sm mb-3 search_bar">
		  <div class="input-group-prepend">
		    <span class="input-group-text" id="inputGroup-sizing-sm">Search</span>
		  </div>
		  <input type="text" id="friends_search" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
		</div>
		<div class="result_friend_search">
		</div>

	</div>

</header>
<?php
include('../includes/components/footer.php');
?>