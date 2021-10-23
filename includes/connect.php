<?php 



$connect = mysqli_connect(
	$config['db']['server'],
	$config['db']['username'],
	$config['db']['password'],
	$config['db']['name']
);

if (!$connect) {

	die('Error connect to database!');


}





 ?>