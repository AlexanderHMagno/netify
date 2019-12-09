<?php

include_once('Arc.php');
class Comment {

	//con
	private $con;
	//{integer} id
	private $post_id;
	//{obj} comment object 
	private $comment_obj;
	//{obj} Arc object 
	private $arc;	
	

	public function __construct (&$con, $post_id) {

		$this->con = $con; 
		$this->post_id = $post_id;
		$this->arc = new Arc($con);
	}

	private function run_query ($query) {

		$row = mysqli_query($this->con, $query);
		$returned_id = mysqli_insert_id($this->con);
		return ['data'=>$row, 'id'=>$returned_id];
	}

	public function post_comment ($body, $userLogIn, $userTo, $date) {
		$post_id = $this->post_id;
		$body = $this->arc->purge_value($body);
		$sql_query = "INSERT INTO comments (`body`,`posted_by`,`posted_to`,`date_added`,`post_id`)";
		$sql_query .= "VALUES ('$body','$userLogIn','$userTo','$date','$post_id')";
		$query = $this->run_query($sql_query);
		$insert = $this->get_comments($query['id']);
		echo $insert;
	}

	public function get_comments ($comment_id = '') {

		$post_id = $this->post_id;
		$comments_information = [];
		$final_comments = '<div class="subcomments_group'.$post_id.'">';
		$query = '';
		if ( $comment_id == '') {
			$query = "SELECT * FROM `comments`JOIN users ON `users`.id = posted_by WHERE `post_id`='$post_id' AND `users`.user_close = 'NO' ORDER BY date_added DESC";
		} else {
			$query = "SELECT * FROM `comments`JOIN users ON `users`.id = posted_by WHERE `comments`.id='$comment_id' AND `users`.user_close = 'NO' ORDER BY date_added DESC";
		}
		
		$data = $this->run_query($query);

		while ($row = mysqli_fetch_array($data['data'])) {
			array_push($comments_information, $row);
		}

		foreach ($comments_information as $key => $value) {
			$final_comments .= 
						'<div class="invidual_comment_area">
							<div>
	                            <img class="individual_comment_image" alt="user Image" src="'.$value['Profile_pic'].'"> 
	                        </div>
	                      	<div class="individual_comment_body">
	                            <div>
	                              <a href=/socialNetwork/'.$value['username'].'>'.$value['first_name'].' '.$value['last_name'].'</a> 
	                            </div>
	                            <p class="individual_comment_body_message">'.$value['body'].'</p>
	                      	</div>
                      	</div>';
		}
		
		return $final_comments;
	}

	public function get_number_comments_post () {

		$post_id = $this->post_id;
		$query = "SELECT body FROM `comments` JOIN users ON `posted_by` = `users`.`id` WHERE `post_id` = '$post_id' AND `user_close` = 'NO'";
		
		$query = mysqli_query($this->con, $query);
		return mysqli_num_rows($query);

	}

	public function who_has_commented () {
		$post_id = $this->post_id;
		$query = "
			SELECT posted_by FROM comments 
			WHERE post_id = '$post_id' 
			GROUP by posted_by";

		$data = mysqli_query($this->con, $query);
		$info = [];
		while ($row = mysqli_fetch_array($data)) {
			$info[]= $row['posted_by'];
		}
		return $info;
	}


}
?>