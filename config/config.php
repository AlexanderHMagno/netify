<?php

ob_start();
$timezone = date_default_timezone_set('America/Vancouver');
// Start the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// This is creating the connection between php and mysql
$con = mysqli_connect("localhost","root","","social_network");
//If the connection fails this will hold the information. 
if ( mysqli_connect_errno()) {
	echo "Failed to connect" . mysqli_connect_errno(); 
}
?>