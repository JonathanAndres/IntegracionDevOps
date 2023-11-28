<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
include_once '../config/Database.php';
include_once '../class/agenda.php';
 
$database = new Database();
$db = $database->getConnection();
 
$agenda = new Agenda($db);
 
$data = json_decode(file_get_contents("php://input"));

if(!empty($data->id)) {
	$agenda->id = $data->id;
	if($agenda->delete()){    
		http_response_code(200); 
		echo json_encode(array("message" => "CITA ELIMINADA"));
	} else {    
		http_response_code(503);   
		echo json_encode(array("message" => "NO ES POSIBLE ELIMINAR LA CITA"));
	}
} else {
	http_response_code(400);    
    echo json_encode(array("message" => "DATOS INCOMPLETOS, NO ES POSIBLE REGISTRAR LA CITA"));
}
?>