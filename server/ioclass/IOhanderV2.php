<?php 
class IOhandler{	
	public function __construct(){
 		include('dbconfig.php');
 		$db = new connect();
    	$this->DBcon = $db->startConn();
	}

	public function validate($input){
		$input = preg_replace("#[^0-9a-z]#i","",$input);
		return $input;
	}

	public function login($table, $checker, array $param){
		$fields = '`' . implode('`,`', $fields) . '`';	//WHERE CHECKER is what u are chcking with and the firstelement of the array is the userinput
		$SQL = "SELECT $fields FROM $table WHERE $checker = :param";
		$stmt = $this->DBcon->prepare($SQL);
		$stmt->execute(array(':param' => $param[0]));
		$data = $stmt->fetch(PDO::FETCH_ASSOC);
		foreach ($data as $parameter => $value) {
			// $parameter = $data[$parameter];
			if (isset($data[$parameter])) {
				$parameter = $data[$parameter];
			}
		}
?>