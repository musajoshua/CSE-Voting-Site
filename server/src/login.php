<?php
$app->post('/login', function($request, $response){
	$data = json_decode($request->getBody());
	$handler = new IOhandler;
	$username = $data->username;
	$password = $data->password;
	$orderbyparam = "_id";
	$sth = $handler->login('users', 'imm', 'magnitudea', $userparam, $orderbyparam);
	return $this->response->withJson($sth);
});
?>