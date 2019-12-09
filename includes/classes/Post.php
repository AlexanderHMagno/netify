<?php 
// include_once('Comment.php');
// include_once('Notification.php');
include_once('Index.php');

class Post {

	//{contains all the information of the user }
	private $user_obj;
	//{contains the user id}
	private $user_id;
	//{contains the conection to the db}
	private $con;
	//{contains the notification obj}
	private $notification_obj;
	//{check if there are references in the post}
	private $references_in_post;
	//{holds the id of the user that have been referenced in this post}
	private $users_referenced;
	//{holds the trending_words_object}
	private $trending_obj;
	//{holds the Arc Functions}
	private $Arc;


	public function __construct(&$con,$user_id){

		$this->con = $con; 
		$this->user_obj = new User($this->con,$user_id);
		$this->user_id = $user_id;
		$this->notification_obj = new Notification($this->con,$user_id);
		$this->trending_obj = new Trending_words($this->con);
		$this->arc = new Arc($this->con);	
	}

	// This functions allow us to not create a reprocess everytime when we want to run a query
	//TODO add the information to the query with ? and pass the parameters in a array 
	private function run_query ($query) { 

		$row = mysqli_query($this->con,$query);
		return $row;
	}

	// Create a new post and add it to the database
	public function submit_post ($body,$user_to= "") {

		$body = $this->arc->purge_value($body);
		$body = str_replace('\r\n', '\n', $body);
		$body = nl2br($body);
		$isempty = preg_replace('/\s+/', '', $body); // checking if it's not only spaces.


		if($isempty != "") {

			// check for youtube videos 

			$words_body = preg_split("/\s+/", $body);
			foreach ($words_body as $key => $value) {
				if (strpos($value, 'www.youtube.com/watch?v=')) {
					$link = preg_split("!&!", $value);
					$value = str_replace("watch?v=", "embed/", $link[0]);
					$value = str_replace("https://", '', $value);
					$www_position = stripos($value,'www');
					$text_before = substr($value, 0, $www_position);
					$text_after = 'https://'.substr($value, $www_position);
					$value = $text_before;
					$value .= "<br><iframe width=\'95%\' height=\'315\' src=\'".$text_after."\' class=\'youtube_embed\'></iframe><br>";
					$words_body[$key] = $value;
				} else {
					if (strlen($value)) {
						//adding the trending obj
						$this->trending_obj->add_trending_group($value,$this->user_id,$body);
					} 
				}

				if (strpos($value,'|') > -1) {
					$words_body[$key] = preg_replace('/\|/', '', $value);
					$user_reference = new User($this->con,1,$words_body[$key]);

					if ($user_reference) {
						$user_reference_info = $user_reference->get_all_info();

						$words_body[$key] = '<a href="/socialNetwork/'.$words_body[$key].'"><span class="user_reference">'.$user_reference->get_user_full_name().'</span></a>';
						$this->references_in_post = true;
						$this->users_referenced[] = $user_reference->get_user_id();
					}
				}
			}

			$body = implode(" ", $words_body);

			// $body = str_replace('~', '<b>', $body);

			// check for references 


			//current date and time 
			$date_added = date("Y-m-d H:i:s");
			$added_by = $this->user_obj->get_user_id();
			$added_to = $user_to == "" ? null : $user_to;

			$query = $this->run_query("INSERT INTO posts (`body`,`added_by`, `user_to`, `date_added`) 
				VALUES ('$body','$added_by','$added_to','$date_added')");
			$returned_id= mysqli_insert_id($this->con);

			//Insert notification
			//commented in your profile
			if ($returned_id > 0 AND $added_to != null) {
				$this->notification_obj->insert_notification($returned_id,$added_to,'profile');
			}

			//reference in the post
			if ($this->references_in_post){
				foreach ($this->users_referenced as $key => $value) {
					$this->notification_obj->insert_notification($returned_id,$value,'friend_reference');
				}
			}
			//update post count for user 
			$this->user_obj->set_num_post();
		}
	}

	//* Obtain all the information from the post and the user who post it. 
	//@param {obj} data - information coming from the Ajax number of page, and username
	//@param {int} limit - number of post to be sent
	public function get_post ($data, $limit) {
		$user_logged_in = $_SESSION['global_user']['id'];
		$page = $data['page'];
		$userLogIn = $data['userLogIn'];
		$security_friends = $this->user_obj->isFriend($userLogIn,$user_logged_in);
		$same_user = $userLogIn == $_SESSION['global_user']['id'];
		$post_unique_id = null;
		if ( isset($data['post_id'])) {
			$post_unique_id = $data['post_id'];
		}

		$final_post = '';
		$information_group = [];
		$query = "SELECT *,posts.id as post_id, users.id as user_id FROM posts JOIN users ON added_by = users.id WHERE deleted='NO' AND `users`.user_close = 'NO' ";
		if ($post_unique_id > 0 ) {
			$query .= "AND posts.id ='$post_unique_id'";
		}
		$query .= "ORDER BY posts.id DESC";

		$query = $this->run_query($query);
		while ($row = mysqli_fetch_array($query)){
			$row['time_since_post'] = $this->get_time_difference($row['date_added']);
			array_push($information_group, $row);
		};

		$number_of_interactions = 0 ;
		$count = 1; 

		$start = 0;

		if ($page > 1){
			$start = (($page - 1) * $limit)+1;
		}
		
		foreach ($information_group as $key => $value) {
//steps to follow check that the counter is entering. 
			if ($count>$limit) break;
			if ($number_of_interactions++ < $start) continue;
// trigger_error($count);
			//here will be check if the user's friends or themself have posted. 

			$user_are_friends = $this->user_obj->isFriend($userLogIn,$value['user_id']);
			$user_same = $userLogIn == $value['user_id'];
			// check number of comments
			$comments = new Comment($this->con,$value['post_id']);
			$number_comments =  $comments->get_number_comments_post();

//TODO CREATE a like class to handle this in a simmilar way we create comments
			$like_Tag = 'Like';
			$like_class = '';
			$post_id = $value["post_id"];
			$like_query = "SELECT id 
				FROM `likes` 
				WHERE `user_id`= $user_logged_in
				AND `post_id`= $post_id";
			$verify_like = mysqli_query($this->con,$like_query);

			if(mysqli_num_rows($verify_like)>0) {
				$like_Tag = 'Unlike';
				$like_class = 'redColor';
			}

			$number_of_likes = 0;
			$num_likes_query = "SELECT `likes`.`id` FROM `likes` JOIN `users` ON `user_id` = `users`.`id` WHERE `post_id`= $post_id AND `users`.`user_close` = 'NO'";
			$num_likes_counter = mysqli_query($this->con, $num_likes_query);
			$number_of_likes = mysqli_num_rows($num_likes_counter);

		// $user_are_friends = $this->user_obj->isFriend($userLogIn,$value['user_id']);
			$current = $_SERVER['HTTP_REFERER'];
			$current_page = explode('/',$current);
			$current_page = $current_page[count($current_page)-1];
	
			if ($current_page == 'welcome.php') {
	          	if ( $value['user_close'] == 'NO' 
	          			&& ($user_are_friends || $user_same) ) {

	          		if ($value['user_to']!= ''){
	          			$user_to_friends = $this->user_obj->isFriend($userLogIn,$value['user_to']);
	          			if ($user_to_friends || $user_same)
	          			$final_post .= $this->get_requeste_post($value,$userLogIn,
	              		$number_comments,$like_class,$like_Tag, $number_of_likes);
	                 
	          		} else {
	          			$final_post .= $this->get_requeste_post($value,$userLogIn,
	              		$number_comments,$like_class,$like_Tag, $number_of_likes);
	          		}
	              	$count++;
	     	 	} 
			} elseif ($user_same||$user_are_friends) {
				
				
	          	if ($security_friends || $same_user) {
      				if ($value['user_close'] == 'NO' 
          				&& (
          				($value['user_to'] == '' 
      					&& $value['added_by'] == $userLogIn) 
      					|| ($value['user_to'] == $userLogIn) &&
              		 	$value['user_to'] == $userLogIn
              		)) {
		              	$final_post .= $this->get_requeste_post($value,$userLogIn,
		              		$number_comments,$like_class,$like_Tag, $number_of_likes,'private');
		                 $count++;
	     	 		} 
	          	}	          
			}
		}

		$page = $page+1;
		if ($count>$limit) {
			$final_post .= '<input type="hidden" class="next_page" value="'. $page .'">
						<input type="hidden" class="no_more_post" value="false">';	
		} else { 
			$final_post.='<input type="hidden" class="no_more_post" value="true">';
		}
		if(!$security_friends && !$same_user) {
			$final_post = '<p class="security_friend_no_access">You don\'t have access because the user is not your friend</p>';
		}
		return $final_post;
	}

	private function get_requeste_post ($value, $userLogIn, $number_comments,
		$like_class, $like_Tag, $number_of_likes,$type='') { 
		$usuario_to = new User($this->con, $value['user_to']);
			$final_post = 
                  		'<div class="individual_post_area" id="parent_comment'.$value['post_id'].'">
                          <div>
                            <img class="individual_post_image" alt="user Image" src="'.$value['Profile_pic'].'"> 
                      	</div>
                      	<div class="individual_post_body">
                        	<div class="header_individual_post">
                            	<div>
                              		<a href="/socialNetwork/'.$value['username'].'">'.$value['first_name'].' '.$value['last_name'].'</a>';
                  if ($value['user_to'] && 
                  		($value['user_to'] != $value['added_by'] && $type != 'private') ) { 
                  	

                  	$final_post .='<span class="post_message_to"> to </span>
                              <a href="/socialNetwork/'.$usuario_to->get_username().'">'.$usuario_to->get_user_full_name().'</a>'
                  	;}

                $final_post .= '</div>';

              	if ($_SESSION['global_user']['id'] == $value['id']){
              		$final_post .= '<p class="delete_post_action" onClick=delete_post('.$value['post_id'].')>x</p>';
              	} 
              	
                $final_post .= '</div>
                            <span class="individual_post_body_time"> '. $value['time_since_post'] .'</span>
                            <p class="individual_post_body_message">'.$value['body'].'</p>
                            <div>
	                            <a class="individual_post_comment" onClick=append_comments_form('.
	                            $value['post_id'].",".$_SESSION['global_user']['id'].')>Comments (<span id="CommentNumber'.$value['post_id'].'">'.$number_comments.'</span>)</a>
	                            <a class="individual_post_comment likesGroup '.$like_class.'"
	                             onClick=update_like_post('.$value['post_id'].')>
	                             	<span id="likes'.$value['post_id'].'">'.$like_Tag.'</span> (<span
	                             	id="likesNumber'.$value['post_id'].'">'.$number_of_likes.'</span>)
	                             	</a>';
	            $final_post .= '</div> 
                          </div>
                    </div>';

            return $final_post;
	}

	//Get the time difference between two times expressed as Y-m-d H:i:s
	// @param{time} $time_to
	private function get_time_difference ($time_to) {

		$current_time = date("Y-m-d H:i:s");
		$start_time = new DateTime($time_to);
		$end_time = new DateTime($current_time);
		$interval = $start_time->diff($end_time);
		$time_passed = '';

		if ($interval->y >=1) {
			if ($interval->y == 1) { 
				$time_passed = $interval->y . " year ago";
			} else { 
				$time_passed = $interval->y . " years ago";
			}
		} else if ($interval->m >=1) {
			if ($interval->m == 1) {
				$time_passed = $interval->m . " month ago";
			} else {
				$time_passed = $interval->m . " months ago";
			}
		} else if ($interval->d >= 1) {
			if ($interval->d == 1) {
				$time_passed = $interval->d . " day ago"; 
			} else {
				$time_passed = $interval->d . " days ago";
			}
		} else if ($interval->h >=1 ){

			if ($interval->h == 1) {
				$time_passed = $interval->h . " hour ago";
			} else {
				$time_passed = $interval->h . " hours ago";
			}
		} else if ($interval->i >= 1) {
			if ($interval->i == 1) {
				$time_passed = $interval->i . " minute ago";
			} else {
				$time_passed = $interval->i . " minutes ago";
			}
		} else {
			$time_passed = 'seconds ago';
		}

		return $time_passed;

	}
}
?>