<?php

include_once('Index.php');

class Message { 

	protected $user_id; 
	protected $con;
	protected $arc;
	
	
	public function __construct(&$con, $user){
		$this->user_id = $user;
		$this->con = $con;
		$this->arc = new Arc($con);
	}

	public function append_message($to, $body, $date) {
		$from = $this->user_id;

		$body = $this->arc->purge_value($body);
		$youtube = preg_split("/\s+/", $body);
			foreach ($youtube as $key => $value) {
				if (strpos($value, 'www.youtube.com/watch?v=')) {
					$link = preg_split("!&!", $value);
					$value = str_replace("watch?v=", "embed/", $link[0]);
					$value ="<br><iframe width=\'95%\' height=\'315\' src=\'".$value."\' class=\'youtube_embed\'></iframe><br>";
					$youtube[$key] = $value;
				}
			}
		$body = implode(" ", $youtube);

		$query = "INSERT INTO `messages`(`user_from`, `user_to`, `body`, `date`) 
		VALUES ('$from','$to','$body','$date')";

		$data = mysqli_query($this->con,$query);
		$last_id = mysqli_insert_id($this->con);

		return $last_id;
	}

	public function last_message($last_id) {

		$body_inserted = "SELECT `body` FROM `messages` WHERE `pk_id`='$last_id'";
		$body_inserted = mysqli_query($this->con,$body_inserted);
		$body_inserted = mysqli_fetch_array($body_inserted);
		return $body_inserted;

	}

	// get the whole conversation with a person
	public function get_full_conversations_with ($to) {
		$from = $this->user_id;
		$query = "
			SELECT * FROM `messages` 
			WHERE `user_from` IN ('$from','$to') 
			AND `user_to` IN ('$from','$to')  
			AND `user_from` != `user_to`";

		$conversations = mysqli_query($this->con,$query);
		return $conversations;
	}

	// get your own diary
	public function get_diary () {
		$from = $this->user_id;
		$query = "
			SELECT * FROM `messages` 
			WHERE `user_from`='$from' 
			AND `user_to`='$from'";

		$conversations = mysqli_query($this->con,$query);
		return $conversations;

	}

	public function get_list_conversations () {
		$from = $this->user_id;
		$output = null;
		$query = "
		SELECT * FROM `messages` JOIN `users` ON `users`.`id` = `user_from`
		WHERE 
			pk_id IN (
				SELECT id from (
					SELECT max(pk_id) as id,concat(least(user_from,user_to),greatest(user_from,user_to)) AS high 
						FROM `messages` 
						WHERE ( user_from = '$from' OR user_to = '$from' ) 
						GROUP BY high) 
				as moon)
			AND `users`.`user_close` = 'NO'
		ORDER BY pk_id DESC";
		$list = mysqli_query($this->con,$query);

		return $list;
	}

	//check if the user has opened a message sent it to them
	public function message_opened ($id_message) {
		$from = $this->user_id;

		$query = "
			UPDATE `messages` 
			SET `opened`=1 
			WHERE `pk_id`='$id_message' 
				AND `user_to`='$from'";
		$messages = mysqli_query($this->con, $query);

		if ($messages > 0) {
			return true;
		} else {
			return false;
		}
	}
}
?>