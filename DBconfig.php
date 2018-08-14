<?php
function getDB() {
	$dbhost="localhost:3306";
	$dbuser="root";
	$dbpass="root";
	$dbname="javafx";
	$dbConnection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbConnection;
}
?>
