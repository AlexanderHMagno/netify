<?php 


class User {

	private  $user;
	private  $user_id;
	private  $con;

	public  function __construct (&$con, $user_id, $user_name = false){

		$this->con = $con;
		if ($user_name == false) {
			$user_details_query = mysqli_query($con,"SELECT * FROM users WHERE id = '$user_id'");
		} else {
			$user_details_query = mysqli_query($con,"SELECT * FROM users WHERE username = '$user_name'");
		}
		$this->user = mysqli_fetch_array($user_details_query);

		$this->user_id = $this->user['id'];

	}

	private function run_query ($query) {
		$query = mysqli_query($this->con,$query);
		return $query;
	}

	public function get_user_full_name () { 
		return $this->user['first_name']." ". $this->user['last_name'];
	}

	public function get_username () {
		return $this->user['username'];
	}

	public function get_user_id () {
		return $this->user['id'];
	}

	public function get_all_info () {
		return $this->user;
	}

	public function get_num_post () {
		$user_info = $this->user_id;
		$query = "SELECT 'num_post' FROM users WHERE id = '$user_info'";
		$row = $this->get_information($query);
		return $row['num_post'];
	}

	public function check_account_open () {
		$user_info = $this->user_id;
		$query = "SELECT `user_close` FROM `users` WHERE id = '$user_info'";
		$row = $this->get_information($query);
		if ($row['user_close'] == 'YES') return false;
		return true;
	}

	public function set_num_post () {
		$current = $this->get_num_post();
		$current++;
		$this->set_information('num_post',$current);

	}

	public function isFriend ($friendId, $friendTo) {


		$query = $this->run_query("
			SELECT pk_id 
			FROM friends 
			WHERE user_id = '$friendId' 
			AND friend_id = '$friendTo'
			AND status = 'confirmed'");
		
		if (mysqli_num_rows($query) == 0 ) {
			$new_query = $this->run_query("
			SELECT pk_id 
			FROM friends 
			WHERE user_id = '$friendTo' 
			AND friend_id = '$friendId'
			AND status = 'confirmed'");

			if (mysqli_num_rows($new_query) == 0) {
				return false;
			}
		}

		return true;

	}

	public function list_of_friends(){
		$id = $this->user_id;
		$query = "
			SELECT user_id, friend_id 
			FROM (
				SELECT * FROM `friends` 
				WHERE user_id = '$id' 
					AND status = 'confirmed' 
				UNION 
				SELECT * FROM `friends` 
				WHERE friend_id = '$id' 
					AND status = 'confirmed') 
			AS global_friend 
			GROUP BY pk_id";

		$data = $this->run_query($query);

		$list_of_friends = [];

		while ($row = mysqli_fetch_array($data)){
			if($row['user_id']== $id){
				$list_of_friends[] = $row['friend_id'];
			} else {
				$list_of_friends[] = $row['user_id'];
			}
		}

		$check_not_closed_accounts = implode("','",$list_of_friends);
		//check if friends account is close
		$query = "SELECT `id` 
			FROM `users` 
			WHERE `user_close`='NO' 
				AND `id` IN ('".$check_not_closed_accounts."')";

		$data = $this->run_query($query);

		$list_of_friends = []; 
		while ($row = mysqli_fetch_array($data) ) {
			$list_of_friends[] = $row['id'];
		}
		
		return $list_of_friends;
	}

	public function isFriend_invitation ($friendId, $friendTo) {


		$query = $this->run_query("
			SELECT pk_id 
			FROM friends 
			WHERE user_id = '$friendId' 
			AND friend_id = '$friendTo'");
		
		if (mysqli_num_rows($query) == 0 ) {
			$new_query = $this->run_query("
			SELECT pk_id 
			FROM friends 
			WHERE user_id = '$friendTo' 
			AND friend_id = '$friendId'");

			if (mysqli_num_rows($new_query) == 0) {
				return false;
			}
		}

		return true;

	}

	public function isFriend_invitation_pending ($friendId, $friendTo) {


		$query = $this->run_query("
			SELECT pk_id 
			FROM friends 
			WHERE user_id = '$friendId' 
			AND friend_id = '$friendTo'
			AND status = 'pending'");
		
		if (mysqli_num_rows($query) == 0 ) {
			$new_query = $this->run_query("
			SELECT pk_id 
			FROM friends 
			WHERE user_id = '$friendTo' 
			AND friend_id = '$friendId'
			AND status = 'pending'");

			if (mysqli_num_rows($new_query) == 0) {
				return false;
			}
		}

		return true;

	}

	public function friend_pending_invitations ($user_id) {

		$pending = 'no';
		$query = $this->run_query(
			"SELECT * FROM `friends` 
			WHERE `friend_id` = '$user_id' 
			AND `status`='pending'");

		if (mysqli_num_rows($query)>0) {
			$pending = mysqli_num_rows($query);
		}
		return $pending;
	}

	public function user_list_pending_invitation ($user_id) {
		$friends_array = [];
		$query = $this->run_query(
			"SELECT * FROM `friends`
			JOIN `users` ON `friends`.user_id = `users`.id
			WHERE `friend_id` = '$user_id' 
			AND `status`='pending'");
		while ($row = mysqli_fetch_array($query)) {
			array_push($friends_array, $row);
		}
		
		return $friends_array;
	}
	

	public function set_information ($column,$value){
		$user_info = $this->user_id;
		$query = "UPDATE users SET $column ='$value' WHERE id = '$user_info';";
		$query = mysqli_query($this->con,$query);
		if ($query == 1) {
			return true;
		} else {
			false;
		}
	}

	public function check_unique_email ($email) {
		$query = "SELECT * FROM `users` WHERE `email` = '$email' LIMIT 1;";
		$email = $this->get_information($query);

		if (isset($email) AND count($email) > 0) return false;
		return true;
	}

	private function get_information ($query) {

		$query = mysqli_query($this->con,$query);

		if ($query) {
			return mysqli_fetch_array($query);
		}

		return false;
		
	}


}



?>