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

if(isset($_SESSION['user_id'])){
	//clean get data
	if(!isset($_GET['cat']) || !isset($_GET['cont'])) {
		
		$_SESSION['message'] = "Stop trying to mess around";
		$_SESSION['messageType'] = "alert alert-danger";
		header('location:cat.php');
	}else{
		//clean get data
		$cat = clean_input($_GET['cat']);
		$cont = clean_input($_GET['cont']);
		$voter = $_SESSION['user_id'];
		try {
			$stmt = $conn->prepare("SELECT * FROM vote WHERE category_id=:category_id and user_id =:user_id");
			$stmt->execute(array(':category_id' => $cat, ':user_id' => $voter));
	        $res = $stmt->fetch(PDO::FETCH_ASSOC);
	        if($stmt->rowCount() > 0) {
	        	//nigga voted befor now
        		$_SESSION['message'] = "Like I said, voting has closed...but you can keep on trying";
	        	$_SESSION['messageType'] = "alert alert-success";
				header('location:contestant.php?cat='.$cat);
	        }else {
	        	$stmt = $conn->prepare("INSERT INTO vote (category_id, user_id, contestant_id) VALUES(:category_id, :user_id, :contestant_id)");
	        	$res = $stmt->execute(array(':category_id' => $cat, ':user_id' => $voter, ':contestant_id' => $cont));
	        	if($res){
	        		$_SESSION['message'] = "Voted! You tried waiting for the system Welldone King or Queen";
	        		$_SESSION['messageType'] = "alert alert-success";
					header('location:contestant.php?cat='.$cat);
	        	}else{
	        		$_SESSION['message'] = "No vex the thing no vote, try again!";
	        		$_SESSION['messageType'] = "alert alert-danger";
					header('location:contestant.php?cat='.$cat);
	        	}
	        }
		}catch(PDOExecption $e) {
			$_SESSION['message'] = "No vex the thing no vote, try again!";
			$_SESSION['messageType'] = "alert alert-danger";
			header('location:contestant.php?cat='.$cat);
		}
	}
}else{
	$_SESSION['message'] = "Daddy Yo! What are you doing here! Login";
	header('location:index.php');
}


?>