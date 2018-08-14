<?php
include 'DBconfig.php';  // Db config file
require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

// routes
$app->post('/products/:id/:ids','getProducts');
$app->put('/books/:id', function ($id){

echo json_encode("{'id':$id}");
});
$app->post('/newproduct', 'addProduct');


$app->run();
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////   Functions  ////////////////////////////////////////////





//////////////////////////// get All cars function //////////////////////////////////////////


function getProducts($idss,$ff) {
	$sql =  "SELECT * FROM `products` ";
	try {
        $db = getDB();
		$stmt = $db->query($sql);  
		$products = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($products);
	} catch(PDOException $e) {
	    echo "Error !".$e; 
	}
}






///////////////////////////////// Add car ////////////////////////////////////////////////
function addProduct() {
		global $app;
    $req = $app->request(); 
    
    $manufacturer = $req->params('manufacturer'); 
    $color = $req->params('color'); 
    
 
  $sql = "INSERT INTO car (`manufacturer`,`color`) VALUES (:manufacturer, :color);";
  
  
  try {
    $db = getDB();
    $stmt = $db->prepare($sql);
    
      $stmt->bindParam("manufacturer", $manufacturer);
      $stmt->bindParam("color",$color);
 
      $stmt->execute();
      $db = null;
  } catch(PDOException $e) {
      echo "Error !";
  }
}


?>
