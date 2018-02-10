<?php 
	class IOhandler{	
		public function __construct(){
	 		include('dbconfig.php');
	 		$db = new connect();
        	$this->DBcon = $db->startConn();
		}

		public function getAll($table){
			$SQL = "SELECT * from $table";
			$q = $this->DBcon->query($SQL) or die("Failed");
			while ($r = $q->fetch(PDO::FETCH_ASSOC)) {
				$data[] = $r;
			}
			return $data;
		}

		public function logincheck($username) {
		    $stmt = $this->DBcon->prepare("SELECT _email, _username FROM _users WHERE _email = :email or _username = :username");
		    $stmt->execute(array(':email' => $username, ':username' => $username));
		    if($stmt->rowCount() > 0){
		        return 'exist';
		    } else {
		        return 'notexist';
		    }
		}

		public function getById($email, $ref, $table){
			$SQL = "SELECT * from $table where '_email' = :email and '_act_code' = :ref";
			$q = $this->DBcon->prepare($SQL);
			$q->execute(array(':email' => $email, ':ref' => $ref));
			$data = $q->fetch(PDO::FETCH_ASSOC);
			return $data;
		}

		public function insert($table, array $fields, array $values) {
		    $numFields = count($fields);
		    $numValues = count($values);
		    if($numFields === 0 or $numValues === 0)
		        throw new Exception("At least one field and value is required.");
		    if($numFields !== $numValues)
		        throw new Exception("Mismatched number of field and value arguments.");

		    $fields = '`' . implode('`,`', $fields) . '`';
		    $values = "'" . implode("','", $values) . "'";
		    $sql = "INSERT INTO {$table} ($fields) VALUES($values)";
			
			if ($q=$this->DBcon->prepare ( $sql )) {
		       // echo json_encode($sql);
		        if ($q->execute()) {
		            return true;
		        }
		    }
		    return false;
		}
		
		public function update($table,$values=array(),$where){
            		$args=array();
			foreach($values as $field=>$value){
				$args[]=$field.'="'.$value.'"';
			}
			$spilt = implode(',',$args);
			$sql='UPDATE '.$table.' SET '.$spilt.' WHERE '.$where;
   			if($q=$this->DBcon->prepare($sql)){
   				if ($q->execute()) {
   					return true;
   				}
   			}
   			return false;
    		}

		public function deleteData($id, $table){
			$SQL = "DELETE from $table where _id = :id";
			$q = $this->DBcon->prepare($SQL);
			$q->execute(array(':id' => $id));
			return true;
		}

		public function startSession(){
			if (!isset($_SESSION['id'])) {
				session_start();
			}
			if (isset($_SESSION['id'])) {
				$sessid = $_SESSION['id'];
			}	
		}

		public function endSession($id){
			session_start();
	    	session_destroy();  
	    }

	    public function sendMail($values = array()){
	    	$values = '`' . implode ( '`,`', $values ) . '`';
		    $mail_status = mail($values);
		    if ($mail_status) { 
		        return true;    
		    }else{
		        return false;
		    }
	    }

	    public function mail($email, $header, $message, $sender){
	    	$mail = mail($email, $header, $message, $sender);
	    	if ($mail) {
	    		return true;
	    	}else{
	    		return false;
	    	}
	    }

	    public function checkTableExist($table){
	    	$sql = "'SHOW TABLES FROM '.$this->dbname.' LIKE '.$table.''";
	    	if($sql){
	        	if(mysql_num_rows($sql)==1){
	                return true;
	            }else{
	                return false;
	            }
	        }
	    }

	    public function validateInput($input){
			$input=preg_replace("#[^0-9a-z]#i","",$input);
	    }
	    
	    public function login($username, $password, $table, $dbtable, $orderparams){
		    $query = $DBcon->prepare("SELECT * FROM $table WHERE $dbtable='$username' ORDER BY $orderparams DESC limit 1");
		    $vee=$query->execute();
		    $row=$vee->fetch_array();
		    $count=$query->num_rows;
		    $getpw = $row['password'];
		    $verify = password_verify($password, $getpw);
		    if(($count)){
		        if ($verify) {
		           $_SESSION['userid'] = $row['_userid'];
		            echo "ok";
		        } else {
		            echo "incorrect password";
		        }
		    } else {
		         echo  "email not exist try logging in with your username";
		    }
			$DBcon->close();
		}
		public function GetClientMac(){
		    $macAddr=false;
		    $arp=`arp -n`;
		    $lines=explode("\n", $arp);

		    foreach($lines as $line){
		        $cols=preg_split('/\s+/', trim($line));

		        if ($cols[0]==$_SERVER['REMOTE_ADDR']){
		            $macAddr=$cols[2];
		        }
		    }
		    return $macAddr;
		}

		public function my_url(){
		    $url = (!empty($_SERVER['HTTPS'])) ?
		               "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] :
		               "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		    echo $url;
		}

		public function Search($queried){
			//where queried is the form name gotten from your class use
			$keys = explode(" ",$queried);
			$sql = "SELECT * FROM links WHERE name LIKE '%$queried%' ";
			foreach($keys as $k){
			    $sql .= " OR name LIKE '%$k%' ";
			}
			$result = mysql_query($sql);
			return $result;
		}

		public function uploadImg($table){
			$upload_image=$_FILES["myimage"][ "name" ];
			$folder="images/";
			move_uploaded_file($_FILES[" myimage "][" tmp_name "], "$folder".$_FILES[" myimage "][" name "]);
			$insert_path="INSERT INTO $table VALUES($folder,$upload_image)";
			$var=mysql_query($inser_path);
		}

		public function check_match($param1, $param2){
			if($param1 === $param2)
				return true;
			else
				return false;
		}

		public function trow_encode($message){
			echo json_encode($message);
		}

		public function push($code, $message){
			$response['status'] = $code;
			$response['message'] = $message;
			trow($response);
	     }

	     public function random_char($char){
			// where char stands for the string u want to randomize
			$char_length = 15;
			$cl = strlen($char);
			$randomize = '';
			for($i = 0; $i < $char_length; $i++ ){
				$randomize .= $char[rand(0, $cl - 1)]; 
			}
			return $randomize;
	     }

	

	    public function random_string($length, $ranges = array('0-9', 'a-z', 'A-Z')) {
			/*usage for randomize all small letters
				random_string($l, array('a-z'))
			*/
	        foreach ($ranges as $r) $s .= implode(range(array_shift($r = explode('-', $r)), $r[1]));
	        while (strlen($s) < $length) $s .= $s;
	        return substr(str_shuffle($s), 0, $length);
	    }

	    public function reverse_string($str){
	    	// lol php function for string revers is strrev("Hello world!"); it doess the exact same tin 
			for($i=1; $i <= strlen($str); $i++) {
			    return substr($str, $i*-1, 1);
			}
	    }


	    public function getOne($query){
		$final = $this->DBcon->prepare($query. ' LIMIT 1');
		$final->execute();
		$data = $final->fetch(PDO::FETCH_ASSOC);
		return $data;
	    }

	    public function run_query($q){
		if($this->DBcon->query($q)){
		     return true;
		} else {
		    return false;
		};

	    }

	    public function stringify($item){
		 return (string)$item;
	    }

	    public function isExist($id, $table, $item){
			$stmt = $this->DBcon->prepare("SELECT * FROM $table WHERE $item = :val");
		    $stmt->execute(array(':val' => $id));
		    if($stmt->rowCount() > 0){
		        return 'exist';
		    } else {
		        return 'notexist';
		    }
	    }

	    public function usernameCheck($email) {
		    $stmt = $this->DBcon->prepare("SELECT _email FROM _users WHERE _email = :email");
		    $stmt->execute(array(':email' => $email));
		    if($stmt->rowCount() > 0){
		        return 'exist';
		    } else {
		        return 'notexist';
		    }
		}

		public function validate($fields){
			// $fields = array('subject', 'email', 'phone', 'message');
			$error = false; 
			foreach($fields AS $fieldname) { 
			  if(!isset($_POST[$fieldname]) || empty($_POST[$fieldname])) {
			    echo 'Field '.$fieldname.' missing!<br />';
			    $error = true; //Yup there are errors
			  }else{
			  	$error = false;
			  }
			}
			return $error;
		}

		public function match($val1, $val2){
			if ($val1 === $val2) {
				return true;
			}else{
				return false;
			}
		}
	}		
?>
