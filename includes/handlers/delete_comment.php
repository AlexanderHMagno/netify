<?php

include_once('../../config/config.php');

if (isset($_POST['delete'])) {

	if($_POST['delete']=='yes'){

		$post = $_POST['post_id'];
		$query = "UPDATE `posts` SET `deleted` = 'YES' WHERE `id`='$post'";
		$runquery = mysqli_query($con, $query);
		print_r($runquery);
	}
}


?>