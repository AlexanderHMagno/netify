<?php
class Notification {
	
	private $user_obj;
	private $con;
	private $user_info;
	private $user_id;
	private $global;

	public function __construct(&$con, $user){
		$this->global = $user;
		$this->con = $con;
		$this->user_obj = new User($this->con,$user);
		$this->user_info = $this->user_obj->get_all_info();
		$this->user_id = $this->user_obj->get_user_id();
	}

	public function get_unread_notification () {
		$id = $this->user_id;
		$query = "
			SELECT * FROM `notifications` JOIN `users` ON `user_from` = `users`.`id`
			WHERE `opened` = '0' 
				AND `user_to`='$id' AND `user_from` != '$id' AND `users`.`user_close` = 'NO' 
			ORDER BY pk_id DESC";
		$data = mysqli_query($this->con,$query);

		return $data;
	}

	public function insert_notification ($post_id,$user_to,$type) {
		$id = $this->user_id;
		$full_name = $this->user_obj->get_user_full_name();
		$message = '';
		$date = date("Y-m-d H:i:s");

		switch ($type) {
			case 'comment':
				$message = $full_name.' commented on your post';
				break;
			case 'like':
				$message = $full_name.' liked your post';
				break;			
			case 'profile':
				$message = $full_name.' has commented in your profile';
				break;
			case 'comment_non_owner':
				$message = $full_name.' commented on a post you commented on';
				break;
			case 'profile_comment':
				$message = $full_name.' commented on your profile post';
				break;
			case 'friend_request':
				$message = $full_name.' wants to be your friend';
				break;
			case 'friend_reference':
				$message = $full_name.' has referenced you in a post';
				break;
		}

		$link = "post.php?id=".$post_id;

		$query = "INSERT INTO `notifications` (`user_from`, `user_to`, `message`, `link`,`post_id`, `datetime`,`type`) VALUES ('$id','$user_to','$message','$link','$post_id','$date','$type')";
		$data = mysqli_query($this->con, $query); 
	}

	public function get_original_writer ($post_id) {

		$query = "SELECT added_by, user_to FROM posts WHERE id = '$post_id'";
		$data = mysqli_query($this->con,$query);
		$writer_id = mysqli_fetch_array($data);
		return $writer_id;
	}

	public function get_post_full_info ($post_id) {
		$query = "SELECT * FROM posts WHERE id = '$post_id'";
		$data = mysqli_query($this->con,$query);
		$full = mysqli_fetch_array($data);
		return $full;
	}

	public function update_opened_notification ($post_id) {
		$id = $this->user_id;
		$query = "
		UPDATE `notifications` SET `opened`= '1' 
		WHERE `post_id`='$post_id' 
			AND `user_to` = '$id'";
		$update = mysqli_query($this->con,$query);
	}

	public function remove_notification ($post_id, $user_to, $type) {
		$id = $this->user_id;
		$query = "
		DELETE FROM `notifications` 
		WHERE user_from = '$id' 
			AND user_to = '$user_to' 
			AND `type` = '$type'";
		$data = mysqli_query($this->con,$query);
	}
}
?>