<?php 



$message_login =[];

if (isset($_POST['log_button'])) { 


	$email = filter_var(strip_tags($_POST['login_email']),FILTER_SANITIZE_EMAIL);	
	$_SESSION['log_email'] = $email;
	$password = strip_tags($_POST['login_password']);
	// $password = md5($password); 
	$password = hash("sha256", $password);


	$query = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
	$user_exists = mysqli_query($con, $query);

	$row = mysqli_fetch_assoc($user_exists);// if you need to use the name. 

	if (mysqli_num_rows($user_exists)>0){
		$message_login['welcome'] = 'Welcome '.$row['first_name']; 
		$_SESSION['username'] = $row['username'];
		$_SESSION['name'] = $row['first_name'];

		//updating an a closed account 
		$reopen_query = "UPDATE users SET user_close = 'NO' WHERE email='$email'";
		mysqli_query($con,$reopen_query);


		header("location: user_section/welcome.php");
		exit();
	} else {
		$message_login['thief'] = 'Please check your username or password';
	}

}


?>