<?php
// Content of database.php
 
$mysqli = new mysqli('localhost', 'Chloe', '860126', 'storysite');

if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}
?>