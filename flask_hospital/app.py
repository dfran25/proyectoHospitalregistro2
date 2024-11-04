from flask import Flask, request, jsonify # type: ignore
import cv2 # type: ignore
import numpy as np # type: ignore
import base64
from flask_cors import CORS # type: ignore
import mediapipe as mp # type: ignore

app = Flask(__name__)
CORS(app)

# Configuración de MediaPipe para la detección de rostros
mp_face_detection = mp.solutions.face_detection
mp_drawing = mp.solutions.drawing_utils

@app.route('/process_image', methods=['POST'])
def process_image():
    try:
        print("Recibiendo la solicitud en /process_image")
        
        # Obtener la imagen en base64 desde el cuerpo de la solicitud
        data = request.get_json()
        if not data:
            print("Error: No se recibieron datos JSON")
            return jsonify({"error": "No se recibieron datos JSON"}), 400
        
        image_data = data.get('image')
        if not image_data:
            print("Error: No se encontró la clave 'image' en los datos")
            return jsonify({"error": "No se encontró la clave 'image'"}), 400
        
        # Imprimir parte del base64 recibido (truncado para no llenar la consola)
        print("Parte del base64 recibido:", image_data[:100])
        
        # Remover la parte del encabezado 'data:image/jpeg;base64,' si está presente
        if 'base64,' in image_data:
            image_data = image_data.split('base64,')[1]
            print("Encabezado 'data:image/jpeg;base64,' removido")

        # Decodificar la imagen y convertirla en un array de NumPy
        try:
            nparr = np.frombuffer(base64.b64decode(image_data), np.uint8)
            img = cv2.imdecode(nparr, cv2.IMREAD_COLOR)
            print("Imagen decodificada correctamente")
        except Exception as decode_error:
            print("Error al decodificar la imagen:", str(decode_error))
            return jsonify({"error": "Error al decodificar la imagen"}), 400

        # Procesar la imagen con MediaPipe para detectar rostros
        with mp_face_detection.FaceDetection(model_selection=1, min_detection_confidence=0.5) as face_detection:
            img_rgb = cv2.cvtColor(img, cv2.COLOR_BGR2RGB)
            results = face_detection.process(img_rgb)

            if results.detections:
                print("Rostro detectado en la imagen")
                return jsonify({"mensaje": "Rostro detectado en la imagen"})
            else:
                print("No se detectaron rostros")
                return jsonify({"mensaje": "No se detectaron rostros"})

    except Exception as e:
        print("Error general:", str(e))
        return jsonify({"error": f"Error procesando la imagen: {str(e)}"})

if __name__ == '__main__':
    app.run(debug=True)
