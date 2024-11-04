// Acceso a los elementos del DOM
const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const captureButton = document.getElementById('capture');
const searchButton = document.getElementById('search'); // Botón "Buscar Visitante"
const photoPreview = document.getElementById('photoPreview');
const photo = document.getElementById('photo');
const fotoInput = document.getElementById('foto_base64'); // Input oculto para almacenar la imagen base64

console.log("camera.js cargado correctamente");

// Función para acceder a la cámara web
function startCamera() {
    console.log("Intentando acceder a la cámara...");
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(function (stream) {
            video.srcObject = stream;
            console.log("Acceso a la cámara concedido");
        })
        .catch(function (err) {
            console.error("Error al acceder a la cámara: " + err);
        });
}

// Función para capturar la foto
function capturePhoto() {
    console.log("Capturando foto...");
    if (!canvas) {
        console.error("El elemento canvas no está disponible en el DOM.");
        return;
    }
    const context = canvas.getContext('2d');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    context.drawImage(video, 0, 0, canvas.width, canvas.height);

    // Convertir la imagen a base64 y quitar el prefijo
    const dataURL = canvas.toDataURL('image/png');
    const base64Data = dataURL.replace(/^data:image\/(png|jpeg);base64,/, ''); // Remueve el prefijo

    fotoInput.value = base64Data; // Almacenar la imagen base64 en el input oculto "foto_base64"

    // Mostrar la imagen capturada en la vista previa
    photo.src = dataURL;
    photoPreview.style.display = 'block';
}

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
        console.log(data);
        alert(data.mensaje || 'Proceso completado');
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al enviar la imagen al servidor Flask.');
    });
}

// Event listener para el botón de captura
if (captureButton) {
    captureButton.addEventListener('click', capturePhoto);
    console.log("Listener de captura añadido al botón");
}

// Event listener para el botón de búsqueda
if (searchButton) {
    searchButton.addEventListener('click', sendPhotoToServer);
    console.log("Listener de búsqueda añadido al botón");
}

// Iniciar la cámara cuando la página esté cargada
if (video) {
    startCamera();
}
