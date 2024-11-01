// Acceso a los elementos del DOM
const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const captureButton = document.getElementById('capture');
const photoPreview = document.getElementById('photoPreview');
const photo = document.getElementById('photo');
const fotoInput = document.getElementById('foto_base64'); // Cambiamos aquí a "foto_base64"

console.log("camera.js cargado correctamente");

// Función para acceder a la cámara web
function startCamera() {
    console.log("Intentando acceder a la cámara...");
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(function(stream) {
            video.srcObject = stream;
            console.log("Acceso a la cámara concedido");
        })
        .catch(function(err) {
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

    // Convertir la imagen a base64
    const dataURL = canvas.toDataURL('image/png');
    fotoInput.value = dataURL; // Almacenar la imagen base64 en el input oculto "foto_base64"

    // Mostrar la imagen capturada en la vista previa
    photo.src = dataURL;
    photoPreview.style.display = 'block';
}

// Event listener para el botón de captura
if (captureButton) {
    captureButton.addEventListener('click', capturePhoto);
    console.log("Listener de captura añadido al botón");
}

// Iniciar la cámara cuando la página esté cargada
if (video) {
    startCamera();
}
