<?php

include('../../config/config.php');
include('../classes/Index.php');


if(isset($_POST)){

	$user_obj = new User($con,$_SESSION['global_user']['id']);
	$arc = new Arc($con);
	if (isset($_POST['at_replace'])){
		$searcher = $_POST['at_replace'];
		$searcher = explode(',', $searcher);
		$searcher = explode('#', $searcher[0]);
		$searcher = $searcher[1];
		$searcher = $arc->purge_value($searcher);

		$query = "SELECT * FROM `users` WHERE `first_name` LIKE '%".$searcher."' OR `last_name` LIKE '%".$searcher."%'";

		$info = mysqli_query($con, $query);

		$user = [];

		while ($row = mysqli_fetch_array($info)){
			if ($user_obj->isFriend($_SESSION['global_user']['id'],$row['id'])) {
				$user[] = ' |'.$row['username'].' ';	
			}
		}

		echo json_encode($user);
		exit;
	}

	echo 'nothing was found';
}

?>