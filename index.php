<?php
session_start();
require 'mailing.php';
require 'hashing.php';
require_once('connect.php');
function clean_input($in) {
	// $res = mysqli_escape_string($in);
	$res = stripslashes($in);
	$res = trim($res);
	return $res;
}
$db = new dbConnect();
$conn = $db->connect();



if(isset($_POST['regno'])){
		unset($_SESSION['message']);
		unset($_SESSION['messages']);
		$send_verify = new Mailing();
		
		function clean($text){
			$res = trim($text);
			$res = stripslashes($text);
			return $res;
		}

		$regno = clean($_POST['regno']);

		function check($regno){
			$db = new dbConnect();
			$conn = $db->connect();
			$query = $conn->prepare("SELECT * FROM allstud WHERE regno = :regno LIMIT 1");
			$query->execute(array(':regno' => $regno));
			$res = $query->fetch(PDO::FETCH_ASSOC);
			if($query->rowCount() > 0){
			   $mail = $res['email'];
			   $que = $conn->prepare("SELECT * FROM users WHERE regno = :regno LIMIT 1");
			   $que->execute(array(':regno'=>$regno));
			   $res = $que->fetch(PDO::FETCH_ASSOC);
			   if($que->rowCount() > 0){
			   		$mess = "info";
	            	return $mess;
			   }else{
			   		$mess = $mail;
			   		return $mess;
			   }
			}else{
				$mess = NULL;
			    return $mess;
			}
		}

		function random_char(){
			// where char stands for the string u want to randomize
			$char = 'abcdefghijklmnopqrstuvwxyz1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$char_length = 5;
			$cl = strlen($char);
			$randomize = '';
			for($i = 0; $i < $char_length; $i++ ){
				$randomize .= $char[rand(0, $cl - 1)]; 
			}
			return $randomize;
		}

		$token = random_char();
		// echo check($regno);

		if(check($regno) == NULL){
			$_SESSION['message'] = "User Does Not exist";
			$_SESSION['messageType'] ="alert alert-danger";
		}elseif(check($regno)== "info"){
	        $_SESSION['message'] = "User Already exists";
	        $_SESSION['messageType'] ="alert alert-danger";
		}else{
			if($send_verify->mail_verification(check($regno), $token)){
				$stmt = $conn->prepare("INSERT INTO users (regno, email, password) VALUES (:regno, :email, :password)");
				if($stmt->execute(array(':regno' => $regno, ':email' => check($regno), ':password' => passwordHash::hash($token)))){
					$_SESSION['message'] = "Registered Successfully Please Check your Mail For Your Password";
					$_SESSION['messageType'] ="alert alert-success";
					header('location: index.php');
				}
			}else{
				$_SESSION['message'] = "Baba, Park Well and Try Again";
				$_SESSION['messageType'] ="alert alert-danger";
				header('location: index.php');
			}
		}
	}

if(isset($_POST['logUser']) && isset($_POST['logPass'])) {
	unset($_SESSION['message']);
	unset($_SESSION['messages']);
	// clean input\
	$regno = clean_input($_POST['logUser']);
	$password = clean_input($_POST['logPass']);
	try {
		$stmt = $conn->prepare("SELECT _id, regno, email,password FROM users WHERE (regno=:regno)"); 
	    $stmt->bindParam(':regno', $regno);
		$stmt->execute();
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
    	if ( $stmt->rowCount() > 0 ) {
    		if(passwordHash::check_password($res['password'], $password)){
				$_SESSION['user_id'] = $res['_id'];
	    		$_SESSION['email'] = $res['email'];
	    		header('location:index.php');
    		}else{
    			$_SESSION['messages'] = "Wrong Password";
    			$_SESSION['messageType'] = "alert alert-danger";
	    		header('location:index.php');
    		}
    	} else {
    		$_SESSION['messages'] = "Please Register First";
    		$_SESSION['messageType'] = "alert alert-danger";
	    	header('location:index.php');
    	}
	} catch (PDOException $ex) {
		header('location:index.php');
	}
	
}



?>
<!DOCTYPE HTML>
<html>
	<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>CSE Week '18</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta name="author" content="" />

	<meta property="og:title" content=""/>
	<meta property="og:image" content=""/>
	<meta property="og:url" content=""/>
	<meta property="og:site_name" content=""/>
	<meta property="og:description" content=""/>
	<meta name="twitter:title" content="" />
	<meta name="twitter:image" content="" />
	<meta name="twitter:url" content="" />
	<meta name="twitter:card" content="" />
	<link rel="shortcut icon" href="images/fav.png">

	<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700" rel="stylesheet">
	
	<link rel="stylesheet" type="text/css" href="js/anime/documentation/assets/css/anime.css">
	<!-- Animate.css -->
	<link rel="stylesheet" href="css/animate.css">
	<!-- Icomoon Icon Fonts-->
	<link rel="stylesheet" href="css/icomoon.css">
	<!-- Themify Icons-->
	<link rel="stylesheet" href="css/themify-icons.css">
	<!-- Bootstrap  -->
	<link rel="stylesheet" href="css/bootstrap.css">

	<!-- Magnific Popup -->
	<link rel="stylesheet" href="css/magnific-popup.css">

	<!-- Owl Carousel  -->
	<link rel="stylesheet" href="css/owl.carousel.min.css">
	<link rel="stylesheet" href="css/owl.theme.default.min.css">

	<!-- Theme style  -->
	<link rel="stylesheet" href="css/style.css">

	<!-- Modernizr JS -->
	<script src="js/modernizr-2.6.2.min.js"></script>
	<!-- FOR IE9 below -->
	<!--[if lt IE 9]>
	<script src="js/respond.min.js"></script>
	<![endif]-->

	</head>
	<body>


		
	<!-- <div class="gtco-loader"></div> -->
	<!-- 
	<div id="page"> -->

	
	<!-- <div class="page-inner"> -->
	<nav class="gtco-nav" role="navigation">
			<div class="gtco-container">
				
				<div class="row">
					<div class="col-sm-4 col-xs-12">
						<div id="gtco-logo"><a href="http://lmu.edu.ng"><img src="images/cse.png" class="animated flipInY infinite"></a></div>
					</div>

					<div class="col-xs-8 text-right menu-1">
						<ul>
							<li class="btn-cta"><a href="./cat.php" data-tab="login"><span>Start Voting Now !</span></a></li>
						</ul>
					</div>
				</div>
				
			</div>
		</nav>
	
	<header id="gtco-header" class="gtco-cover" role="banner" style="background-color: #2B292C">
		<div class="overlay"></div>
		<div class="gtco-container">
			<div class="row">
				<div class="col-md-12 col-md-offset-0 text-left">
					

					<div class="row row-mt-15em">
						<!-- <div class="col-md-7 mt-text animate-box" data-animate-effect="fadeInUp">
							<span class="intro-text-small">Welcome to College of Science and Engineering</span>
							<h1>CSE Week '18 Voting Platform</h1>	
						</div> -->
						<div class="col-md-7 mt-text animate-box">
							<div id="lineDrawing">
							 <svg viewBox="0 0 280 100">
						      <g fill="none" fill-rule="evenodd" stroke-width="4" stroke="#FB155A" stroke-width="1" class="lines">
						        <path d="M60,10 C-10,0 -10,100 60,90" />
						        <path d="M75,10 c-60,0 0,50 50,50 s10,0 -50,90" />
							<!-- 	<path d="M75 30 A20 20 0 1 0 50 50 A20 20 0 1 1 30 70" /> -->
						        <path d="M210,10 l-60,0 0,80 60,0 M150,50 l50,0" />
						      </g>
    						 </svg>
    						</div>
    						<span class="intro-text-small animated slideInLeft">Welcome to College of Science and Engineering</span>
    						<h1 id="Timercount"></h1>
						</div>
						<div class="col-md-4 col-md-push-1 animate-box" data-animate-effect="fadeInRight">
							<div class="form-wrap">
								<?php
													if(isset($_SESSION['messages'])) {
														echo '<div class="'.$_SESSION['messageType'].' alert-dismissible">
							  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
															  '.$_SESSION['messages'].'
													</div>';
													}

													?>
											<?php
						if(isset($_SESSION['message'])) {
							echo '<div class="'.$_SESSION['messageType'].' alert-dismissible">
									<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
								  '.$_SESSION['message'].'
						</div>';
						}

						?>
							<?php 
								if(!isset($_SESSION['user_id'])){
							?>
								<div class="tab">
									<ul class="tab-menu">
										<li class="gtco-first"><a href="#" data-tab="signup">Sign up</a></li>
										<li class="active gtco-second"><a href="#" data-tab="login">Login</a></li>
									</ul>
									<div class="tab-content">
										
										<div class="tab-content-inner" data-content="signup">
											<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
												
												<div class="row form-group">
													<div class="col-md-12">
														<label for="user.email">Reg No</label>
														<input type="text" class="form-control" name="regno">
													</div>
												</div>
												<div class="row form-group">
													<div class="col-md-12">
														<input type="submit"  class="btn btn-primary" value="Sign up">
													</div>
												</div>
											</form>	
										</div>
										
										<div class="tab-content-inner active" data-content="login">
											<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
															
												<div class="row form-group">
													<div class="col-md-12">
														<label for="username">Reg No</label>
														<input type="text" class="form-control" name="logUser">
													</div>
												</div>
												<div class="row form-group">
													<div class="col-md-12">
														<label for="password">Password</label>
														<input type="password" class="form-control" name="logPass">
													</div>
												</div>

												<div class="row form-group">
													<div class="col-md-12">
														<input type="submit" class="btn btn-primary" value="Login">
													</div>
												</div>
											</form>	
											
										</div>


									</div>

								</div>
								<?php 
									}else{
								?>
									<div class="row form-group">
										<div class="col-md-12">
											<h2>We apologize for the inconvieniences in the CSE Voting. Please be assured of dedication to serving you. Thank you.</h2>
											<h2>Please also note that pathfinders would be able to vote 5 hours before voting closes. Thank you.</h2>
											<a href="logout.php"><button type="button" class="btn btn-primary">Logout</button></a>
											<a href="cat.php"><button type="button" class="btn btn-primary">Categories</button></a>
										</div>
									</div>
								<?php 
									}
								?>
							</div>
						</div>
					</div>
							
					
				</div>
			</div>
		</div>
	</header>

	<script src="js/anime/anime.min.js"></script>

	<script type="text/javascript">
		var lineDrawing = anime({
		  targets: '#lineDrawing .lines path',
		  strokeDashoffset: [anime.setDashoffset, 0],
		  easing: 'easeInOutSine',
		  duration: 1500,
		  delay: function(el, i) { return i * 250 },
		  direction: 'alternate',
		  loop: true
		});
	</script>

	<!-- jQuery -->
	<script src="js/jquery.min.js"></script>
	<!-- jQuery Easing -->
	<script src="js/jquery.easing.1.3.js"></script>
	<!-- Bootstrap -->
	<script src="js/bootstrap.min.js"></script>
	<!-- Waypoints -->
	<script src="js/jquery.waypoints.min.js"></script>
	<!-- Carousel -->
	<script src="js/owl.carousel.min.js"></script>
	<!-- countTo -->
	<script src="js/jquery.countTo.js"></script>
	<!-- Magnific Popup -->
	<script src="js/jquery.magnific-popup.min.js"></script>
	<script src="js/magnific-popup-options.js"></script>
	<!-- Main -->
	<script src="js/main.js"></script>
	<script>
                // Set the date we're counting down to
	    var countDownDate = new Date("April 11, 2018 23:59:59").getTime();

	    // Update the count down every 1 second
	    var x = setInterval(function() {

	      // Get todays date and time
	      var now = new Date().getTime();

	      // Find the distance between now an the count down date
	      var distance = countDownDate - now;

	      // Time calculations for days, hours, minutes and seconds
	      var days = Math.floor(distance / (1000 * 60 * 60 * 24));
	      var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
	      var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
	      var seconds = Math.floor((distance % (1000 * 60)) / 1000);

	      // Display the result in the element with id="demo"
	      document.getElementById("Timercount").innerHTML = days + "d " + hours + "h "
	      + minutes + "m " + seconds + "s ";

	      // If the count down is finished, write some text
	      if (distance < 0) {
	        clearInterval(x);
	        document.getElementById("Timercount").innerHTML = "EXPIRED";
	      }
	    }, 1000);

	</script>

	</body>
</html>

