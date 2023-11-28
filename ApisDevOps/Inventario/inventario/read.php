<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/Database.php';
include_once '../class/Product.php';

$database = new Database();
$db = $database->getConnection();
 
$product = new Product($db);

$product->id = (isset($_GET['id']) && $_GET['id']) ? $_GET['id'] : '0';

$result = $product->read();

if($result->num_rows > 0){    
    $itemRecords=array();
    $itemRecords["Product"]=array(); 
	while ($item = $result->fetch_assoc()) { 	
        extract($item); 
        $itemDetails=array(
            "id" => $id,
            "proname" => $proname,
            "amount" => $amount,
			"time" => $time,

        ); 
       array_push($itemRecords["Product"], $itemDetails);
    }    
    http_response_code(200);     
    echo json_encode($itemRecords);
}else{     
    http_response_code(404);     
    echo json_encode(
        array("message" => "No item found.")
    );
} 