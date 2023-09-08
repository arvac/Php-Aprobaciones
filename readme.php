<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .logo {
            width: 100px; /* Ajusta el tamaño del logo según tus necesidades */
            height: auto;
        }

        h1 {
            color: #333;
            font-size: 24px;
        }

        #mensaje {
            font-size: 18px;
            color: #0077cc;
            margin-top: 10px;
        }

        #video {
            width: 240px; /* Ajusta el ancho del video */
            max-width: 100%;
            height: auto;
            border: 1px solid #ccc; /* Añade un borde */
            margin-top: 10px;
        }

        #capture {
            margin-top: 10px;
            padding: 10px 20px;
            font-size: 18px;
            background-color: #0077cc;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        #capture:hover {
            background-color: #005599;
        }

        #canvas {
            display: none;
        }

        #photo {
            width: 240px; /* Ajusta el ancho de la foto */
            max-width: 100%;
            height: auto;
            margin-top: 20px;
            border: 1px solid #ccc; /* Añade un borde */
        }
    </style>
</head>
<body>
    <img src="https://www.favoritafruitcompany.com/es/images/logo-nuevo.png" alt="Logo" class="logo">
    <h1>Muchas gracias por la aprobación</h1>
    <p id="mensaje"></p>
    <button id="capture">Capturar Fotografía</button>
    <video id="video" width="240" height="280" autoplay></video>
    <canvas id="canvas" width="240" height="280" style="display:none;"></canvas>
    <img id="photo" src="" alt="Tu fotografía">
    <!-- Tu código JavaScript aquí  <img id="photo" src="" alt="Tu fotografía">-->

    
    <script>
        const video = document.getElementById('video');
        const captureButton = document.getElementById('capture');
        
        // Obtener el valor del parámetro de la URL
        const urlParams = new URLSearchParams(window.location.search);
        const parametro = urlParams.get('parametro');
        
        // Mostrar el mensaje de agradecimiento con el valor del parámetro
        document.getElementById('mensaje').innerText = `Gracias estimado ${parametro}`;
        
        navigator.mediaDevices.getUserMedia({ video: true })
        .then(function (stream) {
            video.srcObject = stream;

            // Capturar la foto automáticamente cuando el stream esté disponible
            captureButton.addEventListener('click', function () {
                const canvas = document.getElementById('canvas');
                const context = canvas.getContext('2d');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                const imageDataURL = canvas.toDataURL('image/jpeg');
                
                // Enviar la imagen al servidor PHP utilizando una solicitud AJAX
                fetch('upload.php', {
                    method: 'POST',
                    body: JSON.stringify({ image: imageDataURL, parametro: parametro }),
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Respuesta del servidor: ', data);
                    if (data.success) {
                        document.getElementById('photo').src = 'uploads/' + parametro + '.jpg';
                    }
                })
                .catch(error => {
                    console.error('Error al enviar la imagen al servidor: ', error);
                });
            });
        })
        .catch(function (error) {
            console.log('Error al acceder a la cámara: ', error);
        });
    </script>
</body>
</html>
