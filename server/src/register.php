<?php
$app->post('/register', function($request, $response){
	$data = json_decode($request->getBody());
	$handler = new IOhandler;
	$username = $data->username;
	$password = $data->password;
	$email = $data->email;
	$mobile_number = $data->mobile_number;
	$about_me = $data->about_me;
	$d_o_b = $data->date_of_birth;
	$date_created = date("Y-m-d h:i:sa");
	$sth = $handler->insert('users', array('username', 'password', 'email', 'mobile_num', 'about_me', 'date_of_birth','date_registered'), array($username, $password, $email, $mobile_number, $about_me, $d_o_b, $date_created));
	return $this->response->withJson($sth);
});