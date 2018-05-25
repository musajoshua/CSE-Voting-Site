<?php
session_start();
	require_once('connect.php');
	$db = new dbConnect();
	$conn = $db->connect();
	function clean_input($in) {
		// $res = mysqli_escape_string($in);
		$res = stripslashes($in);
		$res = trim($res);
		return $res;
	}
	function get_cat_name($cat_id) {
		$db = new dbConnect();
		$conn = $db->connect();
		try {
			$stmt = $conn->prepare("SELECT * FROM category WHERE _id = :category_id");
			$stmt->execute(array(':category_id' => $cat_id));
			$res = $stmt->fetch(PDO::FETCH_ASSOC);
			if($stmt->rowCount() > 0){
			    return $res['cat_name'];
			}else{
			    header('location:cat.php');
			}
		} catch(PDOException $ex) {
			return NULL;
		}
	}
		
	function getcount($constId, $catId){
		$db = new dbConnect();
		$conn = $db->connect();
		try {
			$stmt = $conn->prepare("SELECT * FROM vote WHERE contestant_id = :contestant_id AND category_id = :category_id");
			$stmt->execute(array(':contestant_id' => $constId, ':category_id' => $catId));
			$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $stmt->rowCount();
		} catch(PDOException $ex) {
			return NULL;
		}
	}
	
	//clean get data
	if(!isset($_GET['cat'])) {echo 'GGG';	
		$_SESSION['message'] = "Stop trying to mess around";
		$_SESSION['messageType'] = "alert alert-danger";
		header('location:cat.php');
	}else{
		$cat_id = clean_input($_GET['cat']);
		try{
			$stmt = $conn->prepare("SELECT * FROM contestant WHERE category_id=:category_id");
			$res = $stmt->execute(array(':category_id' => $cat_id));
			if($res) {
				if($stmt->rowCount() > 0){
					$cont = $stmt->fetchAll(PDO::FETCH_ASSOC);
				}
			}
		}catch(PDOException $ex) {
			return NULL;
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

	<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700" rel="stylesheet">
	
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
	<style type="text/css">
		@media only screen and (max-width: 770px) {
			.space {
				margin-top: 70px !important;
				size:5em !important;
			}
			.img .img-responsive {
				width: 100%;
			}
			.btn-vote {
				width: 8em !important;
				size:3em;
			}
		}
		.btn-vote {
			background-color: transparent;
			border-style:thick !important;
			border:0;
			box-shadow: inset 0 0 0 2px #F34E57;
			/*border-color:#891434;*/
			color: #F34E57;
			width: 10em;
		}
		.btn-vote:hover {
			background-color: transparent;
			transition: 1s;
			box-shadow: inset 0 0 0 2px #891434;
			box-shadow: 5 5;
		}
		.btn-vote:click {
			outline: none;
			background-color: rgba(243,78,87,1);
		}
		.btn-vote:focus {
		    outline: none;
		}
	</style>
	<body>
		
	<div class="gtco-loader"></div>
	
	<div id="page">

	
	<div class="page-inner">
	<nav class="gtco-nav" role="navigation" style="background-color: rgb(43,41,44);">
			<div class="gtco-container">
				
				<div class="row">
					<div class="col-sm-4 col-xs-12">
						<div id="gtco-logo"><a href=""><img src="images/cse.png" class="animated flipInY infinite"></a></div>
					</div>
					<div class="col-xs-8 text-right menu-1">
						<ul>
							<li class="active btn-cta"><a href="./cat.php" ><span>Categories</span></a></li>
							<?php  
								if (isset($_SESSION['user_id'])){
							?>
							<li class="btn-cta"><a href="logout.php" data-tab="login"><span>Logout</span></a></li>
							<?php 
								}
							?>
							
						</ul>
					</div>
				</div>
				
			</div>
		</nav>

	
	<div class="space gtco-section">
		<div class="gtco-container">
			<div class="row">
				<div class="col-md-8 col-md-offset-2 text-center gtco-heading">
					<h2><?php echo get_cat_name($cat_id); ?></h2>
					<?php if($cat_id > 1){
						echo '<a href="contestant.php?cat='.($cat_id-1).'" style="float: left;" class="btn-vote">Prev</a>';
					} ?>
					<?php if($cat_id < 18){
						echo '<a href="contestant.php?cat='.($cat_id+1).'" style="float: right;" class="btn-vote">Next</a>';
					} ?>
					
				</div>
			</div>
			<div class="row">
				<div class="col-md-8 col-md-offset-2 text-center gtco-heading">
					<?php
						if(isset($_SESSION['message'])) {
							echo '<div class="'.$_SESSION['messageType'].' alert-dismissible">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
								  '.$_SESSION['message'].'
						</div>';
						}

						unset($_SESSION['message']);

						?>
				</div>
			</div>
			<div class="row">
					<?php
						
						foreach ($cont as $row) {
							$numVote = getcount($row['_id'], $cat_id);
							echo '<div class="col-lg-4 col-md-4 col-sm-6">
							<div class="fh5co-project-item">
						<a href="images/'.$row['image'].'" class="img img-responsive image-popup" style="background-color:lightgray;">
							<figure>
								<div class="overlay"><i class="ti-plus"></i></div>
								<img src="images/'.$row['image'].'" alt="Image" class="img-responsive">
							</figure>
						</a>
						<div class="fh5co-text">
							<h2>'.$row['contestant_name'].'</h2>
							<p>'.$row['contestant_dept'].' '.$row['contestant_level'].'</p>
							<a href="process.php?cat='.$cat_id.'&cont='.$row['_id'].'" /><button class="btn-vote">VOTE ('.$numVote.')</button></a>
						</div>
					</div>
					</div>';
						}
					?>
			</div>
		</div>
	</div>
	</div>

	<div class="gototop js-top">
		<a href="#" class="js-gotop"><i class="icon-arrow-up"></i></a>
	</div>
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

	</body>
</html>

