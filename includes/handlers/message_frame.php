<?php 

include('../../config/config.php');
include('../classes/User.php');
include('../classes/Message.php');
	

	if (isset($_POST['last'])) {
		$from = $_POST['from'];
		$to = $_POST['to'];
		$body = $_POST['body'];
		$body = strip_tags($body);
		$body = mysqli_real_escape_string($con, $body);
		$date = date("Y-m-d H:i:s");
		$Message_obj = new Message($con,$from);

		$last_id = $Message_obj->append_message($to,$body,$date);
		$body_inserted =$Message_obj->last_message($last_id);

		if ($last_id > 0  ) {
			$user_data = new User($con,$from);
			$user_from = $user_data->get_all_info();
			$output = null; 
			$output .= 
				'<div class="individual_comment_area_right">
					<div>
		                <a href=/socialNetwork/'.$user_from['username'].'><img class="individual_comment_image" alt="user Image" src="'.$user_from['Profile_pic'].'"></a>
		            </div>
		          	<div class="individual_comment_body">
		                <p class="individual_comment_body_message">'.
		                $body_inserted['body'].'</p>
		          	</div>
		      	</div>';

			echo $output;
		}
		
		echo false; 
	}

	if(isset($_POST['full'])){

		$output = null;
		$from = $_POST['from'];
		$to = $_POST['to'];
		$obj_from = new User($con,$from);
		$obj_to = new User($con,$to);
		$info_from = $obj_from->get_all_info();
		$info_to = $obj_to->get_all_info();
		$date = date("Y-m-d H:i:s");
		$Message_obj = new Message($con, $from);
		if ($from == $to) {
			$conversations = $Message_obj->get_diary();	
			$output .= '<div class="message_title">';
			$output .= '<p>This is your diary</p>';
			$output .= '</div>';

		} else {
			$conversations = $Message_obj->get_full_conversations_with($to);
			$output .= '<div class="message_title">';
			$output .= '<div>
		                <a href=/socialNetwork/'.$info_to['username'].'><img class="individual_comment_image" alt="user Image" src="'.$info_to['Profile_pic'].'"></a>
		                <a href=/socialNetwork/'.$info_to['username'].'>'.$obj_to->get_user_full_name().'</a> 
		            </div>';
			$output .= '<span> and </span>';
			$output .=  '<div>
		               <a href=/socialNetwork/'.$info_from['username'].'><img class="individual_comment_image" alt="user Image" src="'.$info_from['Profile_pic'].'"></a>
		               <a href=/socialNetwork/'.$info_from['username'].'>'.$obj_from->get_user_full_name().'</a>   
		            </div>';
			$output .= '</div>';
		}
		
		$output .= '<div class="message_body_component">';            
		while ($row = mysqli_fetch_array($conversations)){
			$Message_obj->message_opened($row['pk_id']);

			if ($row['user_from'] == $from){
				$output .=
				'<div class="individual_comment_area_right">
					<div>
		                <a href=/socialNetwork/'.$info_from['username'].'><img class="individual_comment_image" alt="user Image" src="'.$info_from['Profile_pic'].'"></a>  
		            </div>
		          	<div class="individual_comment_body">
		                <p class="individual_comment_body_message">'.
		                $row['body'].'</p>
		          	</div>
		      	</div>';
			} else {

				$output .=
				'<div class="invidual_comment_area">
					<div>
		                <a href=/socialNetwork/'.$info_to['username'].'><img class="individual_comment_image" alt="user Image" src="'.$info_to['Profile_pic'].'"></a>
		            </div>
		          	<div class="individual_comment_body">
		                <div>
		                  <a href=/socialNetwork/'.$info_to['username'].'>'.$obj_to->get_user_full_name().'</a> 
		                </div>
		                <p class="individual_comment_body_message">'.
		                $row['body'].'</p>
		          	</div>
		      	</div>';

			}
		}
		$output .= '</div>';
		echo $output;
	}

?> 