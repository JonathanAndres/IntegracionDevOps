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

if(!empty($data->fecha) && !empty($data->hora_ini)  && !empty($data->hora_fin) 
&& !empty($data->estado_agenda) && !empty($data->confirmacion) 
&& !empty($data->informacion_extra_agenda)){    

    $agenda->id = $data->id;
    $agenda->fecha = $data->fecha;
    $agenda->hora_ini = $data->hora_ini;
    $agenda->hora_fin = $data->hora_fin;
    $agenda->estado_agenda = $data->estado_agenda;	
    $agenda->confirmacion = $data->confirmacion; 
    $agenda->informacion_extra_agenda = $data->informacion_extra_agenda; 
    
    
    
    if($agenda->create()){         
        http_response_code(201);         
        echo json_encode(array("message" => "CITA AGENDADA"));
    } else{         
        http_response_code(503);        
        echo json_encode(array("message" => "YA EXISTE UNA CITA PARA ESA HORA"));
    }
}else{    
    http_response_code(400);    
    echo json_encode(array("message" => "NO ES POSIBLE CREAR CITA, DATOS INCOMPLETOS"));
}
?>