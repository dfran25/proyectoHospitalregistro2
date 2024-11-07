// Función para enviar la foto al servidor Flask
function sendPhotoToServer() {
    const base64Data = fotoInput.value;
    if (!base64Data) {
        alert("No hay una foto capturada para enviar.");
        return;
    }

    // Enviar la imagen al servidor Flask
    fetch('http://localhost:5000/process_image', {
        method: 'POST',
        body: JSON.stringify({ image: base64Data }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log("Respuesta del servidor:", data);

        if (data.mensaje === "Coincidencia encontrada") {
            // Mostrar la información del visitante en la vista
            document.getElementById('visitorName').textContent = data.nombre;
            document.getElementById('visitorId').textContent = data.identificacion;
            document.getElementById('visitorRoom').textContent = data.habitacion_id;
            document.getElementById('visitorTime').textContent = data.hora_actual;

            // Mostrar el contenedor de información
            document.getElementById('visitorInfo').style.display = 'block';
        } else {
            // Si no hay coincidencia, mostrar mensaje y ocultar la información
            alert(data.mensaje);
            document.getElementById('visitorInfo').style.display = 'none';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al enviar la imagen al servidor Flask.');
    });
}
