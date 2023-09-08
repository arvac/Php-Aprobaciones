<!DOCTYPE html>
<html>
<head>
    <head>
    <meta charset="UTF-8">
    <title>Aprobacion</title>
</head>
     
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }
        header {
            background-color: #35424a;
            color: white;
            padding: 20px 0;
            text-align: center;
        }
        h1 {
            margin: 0;
            padding: 0;
        }
        .main {
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            margin-top: 20px;
        }
        .success {
            color: #008000;
            font-weight: bold;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Formulario de Solicitud</h1>
        </header>
        <div class="main">
            <h2>Muchas gracias por su aprobacion</h2>
 
                
<?php            
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

 require 'phpmailer/src/Exception.php'; 
 require 'phpmailer/src/PHPMailer.php'; 
 require 'phpmailer/src/SMTP.php'; 
 
session_start();
            if (!isset($_SESSION['ejecutado'])) {
$_SESSION['ejecutado'] = false;
                                // Establecer la conexión a la base de datos
$servername = "127.0.0.1:3306";
$username = "u738407338_aprobaciones";
$password = "Turion.1V";
$dbname = "u738407338_aprobaciones";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error al conectar a la base de datos: " . $conn->connect_error);
}

               $urlFragment = $_SERVER['REQUEST_URI'];
                    
                    // Obtener los segmentos de la URL
                    $segments = explode('/', $urlFragment);
                    
                    // Inicializar variables para correo y uname
                    $correo = '';
                    $uname = '';
                    
                    // Recorrer los segmentos y capturar valores
                    foreach ($segments as $segment) {
                        if (strpos($segment, '@') !== false) {
                            $correo = urldecode($segment);
                        } else {
                            $uname = urldecode($segment);
                        }
                    }
                    
                     
                    // Obtener la dirección IP del usuario
                   $ipAddress = $_SERVER['REMOTE_ADDR'];

                 // Obtener la hora actual en el formato deseado
                   $currentTime = date('H:i:s Y-m-d');
                    echo "<p>Correo: $correo</p>";
                    echo "<p>Uname: $uname</p>";
                    echo 'Los datos fueron enviados';
                    
                    
                    // Generate a new private key
$config = [
    "digest_alg" => "sha512",
    "private_key_bits" => 4096,
    "private_key_type" => OPENSSL_KEYTYPE_RSA,
];
$res = openssl_pkey_new($config);
openssl_pkey_export($res, $privKey);

// Extract the public key from the private key
$pubKey = openssl_pkey_get_details($res);
$pubKey = $pubKey["key"];

// Encrypt data with the public key
$data = $correo;
openssl_public_encrypt($data, $encrypted, $pubKey);

// Create a digital signature of the data
openssl_sign($data, $signature, $privKey, OPENSSL_ALGO_SHA512);

// Create an XML document to store the encrypted data and signature
$xml = new DOMDocument();
$xml->formatOutput = true;

$root = $xml->createElement("EncryptedData");
$xml->appendChild($root);

$encryptedElement = $xml->createElement("Data", base64_encode($encrypted));
$root->appendChild($encryptedElement);

$signatureElement = $xml->createElement("Signature", base64_encode($signature));
$root->appendChild($signatureElement);

// Save the XML document to a file using the data as the filename
$filename = preg_replace('/[^a-z0-9]+/', '_', strtolower($data)) . ".xml";
$xml->save($filename);

// Load the XML document from the file
$xml = new DOMDocument();
$xml->load($filename);

// Extract the encrypted data and signature from the XML document
$encrypted = base64_decode($xml->getElementsByTagName("Data")->item(0)->nodeValue);
$signature = base64_decode($xml->getElementsByTagName("Signature")->item(0)->nodeValue);

// Decrypt data with the private key
openssl_private_decrypt($encrypted, $decrypted, $privKey);
//echo $decrypted . "\n"; // Outputs: Salida
// Generar la firma y almacenarla en una variable

// Verify the digital signature
if (openssl_verify($data, $signature, $pubKey, OPENSSL_ALGO_SHA512)) {
    echo "Signature is valid\n";
    //echo nl2br($decrypted);
$signatureBase64 = base64_encode($signature);

// ... Tu código HTML ...

// Almacenar la firma en una variable antes de mostrarla en la etiqueta <pre>
$preFormattedSignature = "<h3>Código de la Firma Generada:</h3>\n" .
                         "<pre>" . htmlspecialchars($signatureBase64) . "</pre>";

// ... Continuación de tu código HTML ...

// Mostrar la firma en la etiqueta <pre>
echo $preFormattedSignature;

// Consulta SQL para insertar los valores en la tabla
$sql = "INSERT INTO aprobador (correo, firma, ip, hora) VALUES ('$correo', '$signatureBase64', '$ipAddress','$currentTime')";

if ($conn->query($sql) === TRUE) {
    echo "Valores almacenados correctamente en la tabla aprobador.";
} else {
    echo "Error al almacenar valores: " . $conn->error;
}



  // Mostrar la dirección IP y la hora en que se ejecutó el script
  echo '<img src="https://www.favoritafruitcompany.com/es/images/logo-nuevo.png" alt="Logo" class="logo">';
  echo "<p>Dirección IP: $ipAddress</p>";
  echo "<p>Hora de ejecución: $currentTime</p>";
} else {
    echo "Signature is invalid\n";
}
//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);
try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.hostinger.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'aprobado@favoritafc.es';      //Your Gmail Id aprobacion@favoritafc.es
    $mail->Password   = 'Turion.1V';      //Your App password
    $mail->SMTPSecure =  PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('aprobado@favoritafc.es', 'Aprobaciones');
    $mail->addAddress("$correo", " $uname");     //Add a recipient
  
    $mail->addReplyTo("$correo", "$uname");
    

    //Attachments
   // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
   // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Aprobaciones';
    
    //Create the HTML message body with inline CSS styles
    $htmlBody = <<<EOT
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .logo {
            width: 200px;
            display: block;
            margin: 0 auto;
        }
        .content {
            text-align: center;
        }
        .title {
            font-size: 24px;
            color: #333333;
            margin-bottom: 10px;
        }
        .message {
            font-size: 18px;
            color: #666666;
            margin-bottom: 20px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #0099ff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="https://www.favoritafruitcompany.com/es/images/logo-nuevo.png" alt="Logo" class="logo">
        <div class="content">
            <h1 class="title">Gracias estimado  $uname por su aprobación</h1>
            <p class="message">Estamos muy contentos de que haya aprobado nuestro servicio. Esperamos que tenga un excelente día.</p>
             
            <pre> $preFormattedSignature </pre>
            <a href="https://www.favoritafruitcompany.com" class="button">Muchas Gracias</a>
        </div>
    </div>
</body>
</html>
EOT;

     $mail->Body = $htmlBody; //Assign the HTML message body to the mail object
    
     //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
  
} catch (Exception $e) {
   echo ". Mailer Error: {$mail->ErrorInfo}";
}                   

    
//<meta http-equiv="refresh" content="2">
$mail1 = new PHPMailer(true);
try {
    //Server settings
    $mail1->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail1->isSMTP();                                            //Send using SMTP
    $mail1->Host       = 'smtp.hostinger.com';                     //Set the SMTP server to send through
    $mail1->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail1->Username   = 'email@cristianarmijo.com';      //Your Gmail Id aprobacion@favoritafc.es
    $mail1->Password   = 'Turion.1V';      //Your App password
    $mail1->SMTPSecure =  PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail1->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail1->setFrom('email@cristianarmijo.com', 'Aprobaciones');
    $mail1->addAddress('carmijo@favoritafc.com', "Cristian Armijo");     //Add a recipient
    $mail1->addCC('carmijo@favoritafc.com', 'Cristian Armijo');
    $mail1->addReplyTo('carmijo@favoritafc.com', "Cristian Armijo");
    

    //Content
                              
    $mail1->Subject = 'Aprobaciones';
    
    //Create the HTML message body with inline CSS styles
   
     $mail1->Body = $correo; //Assign the HTML message body to the mail object
    
     //$mail1->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail1->send();
  
} catch (Exception $e) {
   echo "Mailer Error: {$mail->ErrorInfo}";
}                   
// Cerrar la conexión
$conn->close();
echo '<script type="text/JavaScript">location.href = "https://favoritafc.es/readme.php";</script>';
            }else{
                 echo date('H:i:s Y-m-d');
                echo '<script type="text/JavaScript">location.href = "https://favoritafc.es/index.html";</script>';
                $_SESSION['ejecutado'] = true;
 
            }
?>
       
        </div>
    </div>
</body>
</html>
