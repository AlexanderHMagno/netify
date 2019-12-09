<?php 
include('../../config/config.php');
include('../classes/Index.php');
// include('../classes/User.php');

if (isset($_POST['action'])){

	$action = $_POST['action'];
	$Arc_functions = new Arc($con);
	$user_obj = new User($con,$_POST['id']);
	if ($action == 'settings_name_general') {

		$new_first_name = $Arc_functions->purge_value($_POST['first']);
		$new_last_name = $Arc_functions->purge_value($_POST['second']);
		
		$respond = null;
		if($_POST['first']){
			$respond = $user_obj->set_information('first_name',$new_first_name);
		}
		if($_POST['second']){
			$respond = $user_obj->set_information('last_name',$new_last_name);
		}
		echo json_encode(['respond' => $respond,'data' => $_POST]);

	} else if ($action == 'settings_email_general') {
		$respond = null;
		if($_POST['email']){
			$new_email = $Arc_functions->purge_value($_POST['email']);
			$new_email = filter_var($new_email,FILTER_VALIDATE_EMAIL);

			if (empty($new_email)) {
				$respond = 'Please add a correct Email';
			} elseif ($user_obj->check_unique_email($new_email)) {
				$respond = $user_obj->set_information('email',$new_email);
			} else {
				$respond = 'Email already taken';
			}
			
		}
		echo json_encode(['respond' => $respond,'data' => $_POST]);

	} else if ($action == 'settings_password_general') {
		$user_info = $user_obj->get_all_info();
		$respond = null;
		$confirm = $user_info['password'] == hash('sha256', $_POST['oldpassword1']);
		if (!$confirm) {
			echo json_encode(['respond' => 'Password doesn\'t match','data' => $_POST]);
			return;
		};
		if($_POST['newpassword1']) {
			$new_password = $Arc_functions->purge_value($_POST['newpassword1']);
			$respond = $user_obj->set_information('password',hash('sha256', $new_password));
		}
		echo json_encode(['respond' => $respond,'data' => $_POST]);
	} else if ($action == 'settings_close_account') {

		$user_obj->set_information('user_close','YES');
		session_destroy();
		$respond = 'close_account';
		echo json_encode(['respond' => $respond,'data' => $_POST]);
	}





}

?>