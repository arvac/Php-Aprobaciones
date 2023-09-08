<?php
$data = json_decode(file_get_contents("php://input"));

// Obtener la imagen y el correo
$imageData = $data->image;
$correo = $data->correo;

// Guardar la imagen en el servidor
$decodedImage = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData));
$fileName = 'uploads/' . $correo . '.jpg'; // Directorio 'uploads' y el nombre será el correo + '.jpg'
if (file_put_contents($fileName, $decodedImage)) {
    // Responder con un mensaje de éxito
    $response = array("success" => true, "message" => "Imagen guardada con éxito.");
} else {
    // Responder con un mensaje de error si la imagen no se pudo guardar
    $response = array("success" => false, "message" => "Hubo un error al procesar la imagen.");
}

// Devolver la respuesta JSON
echo json_encode($response);
?>
