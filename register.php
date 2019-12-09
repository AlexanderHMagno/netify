  <?php 

require('config/config.php');
require('includes/form_handlers/register_handler.php');
require('includes/form_handlers/login_handler.php');


?>

<!DOCTYPE html>
<html>
<head>
	<title>Welcome to your social media</title>
	<!-- Font Awesome Icons -->
  <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:400,700" rel="stylesheet">
  <link href='https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic' rel='stylesheet' type='text/css'>

  <!-- Plugin CSS -->
  <link href="assets/vendor/magnific-popup/magnific-popup.css" rel="stylesheet">

  <!-- Theme CSS - Includes Bootstrap -->
  <link href="assets/css/vendor/vendor.css" rel="stylesheet">
  <link href="assets/css/register.css" rel="stylesheet">

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
				<a class="nav-link js-scroll-trigger" href="#about">About</a>
			</li>
		<!-- 	<li class="nav-item">
				<a class="nav-link js-scroll-trigger" href="#services">Services</a>
			</li>
			<li class="nav-item">
				<a class="nav-link js-scroll-trigger" href="#portfolio">Portfolio</a>
			</li> -->
			<!-- <li class="nav-item">
				<a class="nav-link js-scroll-trigger" href="#contact">Contact</a>
			</li> -->
			<li class="nav-item">
				<a class="nav-link" onClick="toggleSignInForm()" href="#header">Sign In</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" onClick="toggleRegisterFrom()" href="#header">Sign Up</a>
			</li>
			<li class="nav-item signIn_form <?php if(!isset($_POST['log_button'])) echo 'netify_SignIn_form';?>">
		    <form action="register.php" method="POST" class="form-inline">
          <div class="form-group mb-2">
            <label for="inputEmail" class="sr-only">Email</label>
            <input type="email" name="login_email" class="form-control" id="inputEmail" value="
        					<?php if(isset($_SESSION['log_email'])) echo $_SESSION['log_email'];?>">
          </div>
        					<div class="form-group mx-sm-3 mb-2">
            <label for="inputPassword2" class="sr-only">Password</label>
            <input type="password" class="form-control" id="inputPassword2" name="login_password" placeholder="Password" required>
          </div>
					<button type="submit" name="log_button" class="btn btn-primary mb-2"> Sign In</button>
					<?php if(isset($message_login['welcome'])) echo "<br>".$message_login['welcome'];?>
					<?php if(isset($message_login['thief'])) echo "<br>".$message_login['thief'];?>
				</form>
			</li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Masthead -->
  <header class="masthead" id="header">
    <div class="container h-100">
      <div class="row h-100 align-items-center justify-content-center text-center">
        <div class="col-lg-10 align-self-end">
          <h1 class="text-uppercase text-white font-weight-bold"> Netify
            </h1>
          <hr class="divider my-4">
        </div>
        <div class="col-lg-8 align-self-baseline">
			<form action="register.php" method="POST" class="register_form <?php if (!isset($_POST['reg_button'])) echo "netify_register_form";?>">
				<input type="text" name="reg_fname" placeholder="First Name" value="<?php if(isset($_SESSION['reg_fname'])){echo $_SESSION['reg_fname']; };?>" required>
				<?php if(isset($message_array['name']))echo "<br>". $message_array['name']; ?>
				<br>
				<input type="text" name="reg_lname" placeholder="Last Name" value="<?php if(isset($_SESSION['reg_lname'])){echo $_SESSION['reg_lname'];}?>" required>
				<?php if(isset($message_array['last_name']))echo "<br>". $message_array['last_name']; ?>
				<br>
				<input type="email" name="reg_email" placeholder="Email" value="<?php if(isset($_SESSION['reg_email'])){echo $_SESSION['reg_email'];}?>"required>
				<br>
				<input type="email" name="reg_email2" placeholder="Confirm your Email" value="<?php if(isset($_SESSION['reg_email2'])){echo $_SESSION['reg_email2'];}?>" required>
				<?php if(isset($message_array['email']))echo "<br>". $message_array['email']; ?>
				<br>
				<input type="password" name="reg_password" placeholder="Password" required>
				<br>
				<input type="password" name="reg_password2" placeholder="Confirm your Password" required>
				<?php if(isset($message_array['password']))echo "<br>". $message_array['password']; ?>
				<br>
				<input type="submit" name="reg_button" value="register">
			</form>
      <?php if(isset($message_array['user_created'])) echo '<h1>User has been created</h1>';?>
        </div>
      </div>
    </div>
  </header>

  <!-- About Section -->
  <section class="page-section bg-primary" id="about">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
          <h2 class="text-white mt-0">We've got what you need!</h2>
          <hr class="divider light my-4">
          <p class="text-white-50 mb-4">Netify has everything that you need in a social network, and most important our moto is security first! </p>
          <a class="btn btn-light btn-xl js-scroll-trigger" href="#services">Get Started!</a>
        </div>
      </div>
    </div>
  </section>

  <!-- Services Section -->
  <section class="page-section" id="services">
    <div class="container">
      <h2 class="text-center mt-0">At Your Service</h2>
      <hr class="divider my-4">
      <div class="row">
        <div class="col-lg-3 col-md-6 text-center">
          <div class="mt-5">
            <i class="fas fa-4x fa-gem text-primary mb-4"></i>
            <h3 class="h4 mb-2">Security</h3>
            <p class="text-muted mb-0">You choose who can see your information</p>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 text-center">
          <div class="mt-5">
            <i class="fas fa-4x fa-laptop-code text-primary mb-4"></i>
            <h3 class="h4 mb-2">No tracking data</h3>
            <p class="text-muted mb-0">We dont need your data, we dont sell your data.</p>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 text-center">
          <div class="mt-5">
            <i class="fas fa-4x fa-globe text-primary mb-4"></i>
            <h3 class="h4 mb-2">Publish</h3>
            <p class="text-muted mb-0">Publish with confidence, Share with your friends</p>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 text-center">
          <div class="mt-5">
            <i class="fas fa-4x fa-heart text-primary mb-4"></i>
            <h3 class="h4 mb-2">Love</h3>
            <p class="text-muted mb-0">Just Love Netify</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Portfolio Section -->
  <section id="portfolio">
    <div class="container-fluid p-0">
      <div class="row no-gutters">
        <div class="col-lg-4 col-sm-6">
          <a class="portfolio-box" href="assets/vendor/img/portfolio/fullsize/1.jpg">
            <img class="img-fluid" src="assets/vendor/img/portfolio/thumbnails/1.jpg" alt="">
            <div class="portfolio-box-caption">
              <div class="project-category text-white-50">
                Category
              </div>
              <div class="project-name">
                Interest Name to pick
              </div>
            </div>
          </a>
        </div>
        <div class="col-lg-4 col-sm-6">
          <a class="portfolio-box" href="assets/vendor/img/portfolio/fullsize/2.jpg">
            <img class="img-fluid" src="assets/vendor/img/portfolio/thumbnails/2.jpg" alt="">
            <div class="portfolio-box-caption">
              <div class="project-category text-white-50">
                Category
              </div>
              <div class="project-name">
                Interest Name to pick
              </div>
            </div>
          </a>
        </div>
        <div class="col-lg-4 col-sm-6">
          <a class="portfolio-box" href="assets/vendor/img/portfolio/fullsize/3.jpg">
            <img class="img-fluid" src="assets/vendor/img/portfolio/thumbnails/3.jpg" alt="">
            <div class="portfolio-box-caption">
              <div class="project-category text-white-50">
                Category
              </div>
              <div class="project-name">
                Interest Name to pick
              </div>
            </div>
          </a>
        </div>
        <div class="col-lg-4 col-sm-6">
          <a class="portfolio-box" href="assets/vendor/img/portfolio/fullsize/4.jpg">
            <img class="img-fluid" src="assets/vendor/img/portfolio/thumbnails/4.jpg" alt="">
            <div class="portfolio-box-caption">
              <div class="project-category text-white-50">
                Category
              </div>
              <div class="project-name">
                Interest Name to pick
              </div>
            </div>
          </a>
        </div>
        <div class="col-lg-4 col-sm-6">
          <a class="portfolio-box" href="assets/vendor/img/portfolio/fullsize/5.jpg">
            <img class="img-fluid" src="assets/vendor/img/portfolio/thumbnails/5.jpg" alt="">
            <div class="portfolio-box-caption">
              <div class="project-category text-white-50">
                Category
              </div>
              <div class="project-name">
                Interest Name to pick
              </div>
            </div>
          </a>
        </div>
        <div class="col-lg-4 col-sm-6">
          <a class="portfolio-box" href="assets/vendor/img/portfolio/fullsize/6.jpg">
            <img class="img-fluid" src="assets/vendor/img/portfolio/thumbnails/6.jpg" alt="">
            <div class="portfolio-box-caption p-3">
              <div class="project-category text-white-50">
                Category
              </div>
              <div class="project-name">
                Interest Name to pick
              </div>
            </div>
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- Call to Action Section -->
  <section class="page-section bg-dark text-white">
    <div class="container text-center">
      <h2 class="mb-4">Free Download at Start Bootstrap!</h2>
      <a class="btn btn-light btn-xl" href="https://startbootstrap.com/themes/creative/">Download Now!</a>
    </div>
  </section>

  <!-- Contact Section -->
  <section class="page-section" id="contact">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
          <h2 class="mt-0">Let's Get In Touch!</h2>
          <hr class="divider my-4">
          <p class="text-muted mb-5">Ready to start your next project with us? Give us a call or send us an email and we will get back to you as soon as possible!</p>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-4 ml-auto text-center mb-5 mb-lg-0">
          <i class="fas fa-phone fa-3x mb-3 text-muted"></i>
          <div>+1 (202) 555-0149</div>
        </div>
        <div class="col-lg-4 mr-auto text-center">
          <i class="fas fa-envelope fa-3x mb-3 text-muted"></i>
          <!-- Make sure to change the email address in anchor text AND the link below! -->
          <a class="d-block" href="mailto:contact@yourwebsite.com">contact@yourwebsite.com</a>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-light py-5">
    <div class="container">
      <div class="small text-center text-muted">Copyright &copy; 2019 - Start Bootstrap</div>
    </div>
  </footer>

  <!-- Bootstrap core JavaScript -->
  <script src="assets/vendor/jquery/jquery.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Plugin JavaScript -->
  <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="assets/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
  <script type="text/javascript" src="main.js"></script>

  <!-- Custom scripts for this template -->
  <script src="assets/vendor/js/creative.min.js"></script>

</body>

</html>