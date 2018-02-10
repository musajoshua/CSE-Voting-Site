<?php
$data =  array('username', 'password', 'email');
foreach ($data as $parameter => $value) {
 	$parameter = $data[$parameter].'<br>';
 	echo $parameter;
}