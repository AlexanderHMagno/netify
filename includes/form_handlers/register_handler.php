
<?php
// Declaring variables

$fname = '';
$lname = '';
$email = '';
$email2 = '';
$password = '';
$password2 = '';
$date ='';
$message_array =[];

if (isset($_POST['reg_button'])) {

	//registration form 
	$fname = ucfirst(strtolower(strip_tags($_POST['reg_fname']))); //remove html tags
	$_SESSION['reg_fname'] = $fname;
	$lname = ucfirst(strtolower(strip_tags($_POST['reg_lname'])));
	$_SESSION['reg_lname'] = $lname;
	$email = strip_tags($_POST['reg_email']);
	$_SESSION['reg_email'] = $email;
	$email2 = strip_tags($_POST['reg_email2']);
	$_SESSION['reg_email2'] = $email2;
	$password = strip_tags($_POST['reg_password']);
	$password2 = strip_tags($_POST['reg_password2']);
	$date = date("Y-m-d");//this gets the current date


	//checking email 
	if ($email == $email2) { 
		//Check if the email haws the proper format or not. 
		if (filter_var($email,FILTER_VALIDATE_EMAIL)){
			
			$email = filter_var($email,FILTER_VALIDATE_EMAIL);
			$email_already_created = mysqli_query($con,"SELECT * FROM users WHERE email='$email'");
			//Count number of rows return 
			$emails_db = mysqli_num_rows($email_already_created);
			if ($emails_db>0){
				$message_array['email'] = 'Email already create in the data base';
			}
		} else {
			$message_array['email'] = 'Email doesn\'t have the proper format';
		}
	} else {
		$message_array['email'] = 'Emails don\'t match';
	} 

	//checkin first name
	if (strlen($fname) < 2 || strlen($fname) > 25) {
		$message_array['name'] = 'Name must be between 2 and 25 characters long';
	}

	//checking last name
	if (strlen($lname) < 2 || strlen($lname) > 25) {
		$message_array['last_name'] = 'Last name must be between 2 and 25 characters long';
	}
	//checking password
	if ($password != $password2) {
		$message_array['password'] = 'Passwords Don\'t match';
	} else {
		if (strlen($password) < 2) {
			$message_array['password'] = 'Password must have at least 2 characteres';
			
		} else {
			if (preg_match('/[^A-Za-z0-9]/', $password)){
				$message_array['password'] = 'Your Passwor only can contain letters or numbers';
			}
		}
	}

	//chequing errors

	if (empty($message_array)){

		$password = hash('sha256', $password); // encrypt password

		
		//1) Check if the user name is not been already created 
		$i = 1;
		$new_username = 0;  
		$username = $fname."_".$lname;

		while ($new_username == 0) { 
			$user_name_created = mysqli_query($con,
				 "SELECT * FROM `users` WHERE `username`= '$username'");

			if (mysqli_num_rows($user_name_created)==0) {
				$new_username++;
			} else { 
				$username = $fname."_".$lname.$i;
				$i++;
			}

		}


		$image_profile = '../assets/images/avatars/user.png';
			

		mysqli_query($con,
			"INSERT INTO `users` 
			(`first_name`, `last_name`, `username`, `email`, `password`, `sign_up_date`,`Profile_pic`) 
			VALUES ('$fname', '$lname', '$username', '$email', '$password', '$date','$image_profile');");
		session_unset();
		$message_array['user_created'] = 'The user was created'; 
			}
}

?>