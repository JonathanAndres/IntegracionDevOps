<?php

function dataApi(){
    // Dirección URL de la API de consumos
    $url = "http://localhost:8080/ApisDevOps/Inventario/inventario/read";

    // Inicializar cURL
    $curl = curl_init($url);

    // Establecer opciones de cURL
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    // Ejecutar la solicitud HTTP
    $response = curl_exec($curl);

    // Cerrar cURL
    curl_close($curl);

    // Convertir la respuesta JSON en un objeto PHP
    $consumos = json_decode($response, true);

    //echo $response;

    

    return $consumos['Product'];
}

//echo dataApi();

// Mostrar los consumos
/*foreach (dataApi() as $consumo) {
    echo "Product ID: " . $consumo['id'] . "<br>";
    echo "Product Name: " . $consumo['proname'] . "<br>";
    echo "Product Amount: " . $consumo['amount'] . "<br>";
    echo "Product Time: " . $consumo['time'] . "<br>";
    echo "---------------------------------------<br>";
}*/

?>