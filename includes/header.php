
<?php 

include('classes/Index.php');
// include('classes/Notification.php');
// include('classes/Trending_words.php');
// include('classes/Post.php');
// include('classes/Message.php');
// include('classes/Arc.php');


if (isset($_SESSION['username'])) {
  $username = $_SESSION['username'];
  $query_user = "SELECT * FROM `users` WHERE username = '$username'";
  $data_user = mysqli_query($con,$query_user);
  $info_user = mysqli_fetch_assoc($data_user); 
  $_SESSION['global_user'] = $info_user;
  // with this variable we can access to the info of the user anywhere in our application. This variable will live only in the server side and Wont be exposed to the front side. It means it wont be a leak of info.
  $user_request = $_SERVER['QUERY_STRING'];
  $user_request = explode('=',$user_request);
  $user_request = $user_request[count($user_request)-1];
  $user_own_profile =  $info_user['username'] == $user_request;
  

  $current_path = $_SERVER['SCRIPT_FILENAME'];
  $page_path = explode('/', $current_path);
  $page_path = $page_path[count($page_path)-1]; 


} else {
  // session_destroy();
  header('location: ../register.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Social Network</title>

  <!-- Font Awesome Icons -->
  <link href="/socialNetwork/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:400,700" rel="stylesheet">
  <link href='https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic' rel='stylesheet' type='text/css'>

  <!-- Plugin CSS -->
  <link href="/socialNetwork/assets/vendor/magnific-popup/magnific-popup.css" rel="stylesheet">

  <!-- Theme CSS - Includes Bootstrap -->
  <link href="/socialNetwork/assets/css/vendor/vendor.css" rel="stylesheet">
  <link href="/socialNetwork/assets/css/main.css" rel="stylesheet">
  <!-- CROP CSS -->
    <link href="/socialNetwork/assets/css/vendor/jquery.Jcrop.css" rel="stylesheet">


  

</head>

<body id="page-top">
 <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
    <div class="container">
      <a class="navbar-brand js-scroll-trigger" href="#page-top">Netify</a>
      <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto my-2 my-lg-0">
          <li class="nav-item">
            <a class="message_toggle nav-link js-scroll-trigger <?php if($page_path == 'messages.php') echo 'selected';?>">
              Messages
            </a>
            <div class='message_resume'>
              <div class="go_to_message_center">
                <a href="/socialNetwork/messages.php">
                  <p>Go To Message Center</p>
                </a>
              </div>
              <div class="append_messages_pre_view">  
              </div>
            </div>
          </li>
          <!-- href="/socialNetwork/messages.php" -->
        <!--  <li class="nav-item">
            <a class="nav-link js-scroll-trigger" href="#services">Services</a>
          </li>
          <li class="nav-item">
            <a class="nav-link js-scroll-trigger" href="#portfolio">Portfolio</a>
          </li> -->
          <!-- <li class="nav-item">
            <a class="nav-link js-scroll-trigger" href="#contact">Contact</a>
          </li> -->
          <li class="nav-item">
            <a class="nav-link <?php if($page_path == 'friends.php') echo 'selected';?>" href="/socialNetwork/user_section/friends.php">Friends</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php if($page_path == 'welcome.php') echo 'selected';?>" href="/socialNetwork/user_section/welcome.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php if($page_path == 'profile.php' && $user_own_profile) echo 'selected';?>" href="/socialNetwork/<?php echo $username;?>">My Profile</a>
          </li>
          <li class="nav-item">
            <a class="nav-link alert_toggle" href="JavaScript:void(0);">
              <img class ="logo_icon_mini" src="/socialNetwork/assets/images/actions/alarm.png"></a>
              <div class='alert_resume'>
                <div class="append_alert_pre_view">  
                </div>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/socialNetwork/user_section/settings.php">
              <img class ="logo_icon_mini" src="/socialNetwork/assets/images/actions/gear.png"></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" onClick="closeSession()" href="/socialNetwork/includes/logout.php">Log Out</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>