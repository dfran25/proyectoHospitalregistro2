document.addEventListener('DOMContentLoaded', function () {
    const video = document.getElementById('video');
    const captureButton = document.getElementById('capture');
    const searchButton = document.getElementById('search');
    const photoPreview = document.getElementById('photoPreview');
    const canvas = document.getElementById('canvas');
    const photoBase64Input = document.getElementById('foto_base64');
    const photo = document.getElementById('photo');

    // Solicitar acceso a la cámara
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => {
            video.srcObject = stream;
            video.play();
        })
        .catch(error => {
            console.error('Error al acceder a la cámara: ', error);
            alert('No se pudo acceder a la cámara.');
        });

    // Capturar la foto
    captureButton.addEventListener('click', function () {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

        const dataURL = canvas.toDataURL('image/jpeg');
        photo.src = dataURL;
        photoBase64Input.value = dataURL.replace(/^data:image\/(png|jpeg);base64,/, '');

        photoPreview.style.display = 'block';
    });

    // Buscar visitante en la base de datos
    if (searchButton) {
        searchButton.addEventListener('click', function (event) {
            event.preventDefault();
            if (!photoBase64Input.value) {
                alert("Primero debes capturar una foto.");
                return;
            }

            // Enviar la imagen al servidor Flask
            fetch('http://localhost:5000/process_image', {
                method: 'POST',
                body: JSON.stringify({ image: photoBase64Input.value }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log("Respuesta del servidor:", data);
                if (data.mensaje === "Coincidencia encontrada") {
                    const urlParams = new URLSearchParams({
                        nombre: data.nombre,
                        identificacion: data.identificacion,
                        habitacion_id: data.habitacion_id,
                        hora: data.hora_actual
                    }).toString();
                    
                    window.location.href = `/visitantes/ingreso_exitoso?nombre=${encodeURIComponent(data.nombre)}&identificacion=${encodeURIComponent(data.identificacion)}&habitacion_id=${encodeURIComponent(data.habitacion_id)}&hora=${encodeURIComponent(data.hora_actual)}`;
                }
                 else {
                    // Mostrar mensaje si no se encuentra coincidencia y quedarse en la misma página
                    alert(data.mensaje);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al enviar la imagen al servidor Flask.');
            });
        });
    }
});
