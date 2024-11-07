from flask import Flask, request, jsonify
import cv2
import numpy as np
import base64
import pymysql
from flask_cors import CORS
import mediapipe as mp
import os
from datetime import datetime

app = Flask(__name__)
CORS(app)

# Configuración de MediaPipe para la detección de rostros
mp_face_detection = mp.solutions.face_detection
mp_drawing = mp.solutions.drawing_utils

# Configuración de la conexión con la base de datos
db_connection = pymysql.connect(
    host='127.0.0.1',
    user='root',        # Usuario según tu .env
    password='',        # Sin contraseña
    database='registro'
)

# Directorio donde están guardadas las fotos
PHOTO_DIRECTORY = "C:/xampp/htdocs/Proyectofinal/hospital/public/storage"

@app.route('/process_image', methods=['POST'])
def process_image():
    try:
        print("Recibiendo la solicitud en /process_image")
        
        # Obtener la imagen en base64 desde el cuerpo de la solicitud
        data = request.get_json()
        if not data:
            return jsonify({"error": "No se recibieron datos JSON"}), 400
        
        image_data = data.get('image')
        if not image_data:
            return jsonify({"error": "No se encontró la clave 'image'"}), 400
        
        # Remover el encabezado 'data:image/jpeg;base64,' si está presente
        if 'base64,' in image_data:
            image_data = image_data.split('base64,')[1]

        # Decodificar la imagen y convertirla en un array de NumPy
        nparr = np.frombuffer(base64.b64decode(image_data), np.uint8)
        img = cv2.imdecode(nparr, cv2.IMREAD_COLOR)

        # Procesar la imagen para detectar rostros
        with mp_face_detection.FaceDetection(model_selection=1, min_detection_confidence=0.5) as face_detection:
            img_rgb = cv2.cvtColor(img, cv2.COLOR_BGR2RGB)
            results = face_detection.process(img_rgb)

            if results.detections:
                print("Rostro detectado en la imagen, buscando coincidencias en la base de datos")

                # Buscar coincidencias en la base de datos
                cursor = db_connection.cursor()
                cursor.execute("SELECT nombre, identificacion, foto, habitacion_id FROM visitantes")
                visitantes = cursor.fetchall()
                
                for visitante in visitantes:
                    nombre, identificacion, foto_filename, habitacion_id = visitante
                    photo_path = os.path.join(PHOTO_DIRECTORY, foto_filename)
                    
                    # Cargar la imagen de la base de datos y comparar
                    db_img = cv2.imread(photo_path)
                    db_img_rgb = cv2.cvtColor(db_img, cv2.COLOR_BGR2RGB)
                    db_results = face_detection.process(db_img_rgb)

                    if db_results.detections:
                        # Asumimos coincidencia en esta versión simplificada
                        current_time = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
                        print(f"Coincidencia encontrada: {nombre}, ID: {identificacion}, Habitación: {habitacion_id}")
                        return jsonify({
                            "mensaje": "Coincidencia encontrada",
                            "nombre": nombre,
                            "identificacion": identificacion,
                            "habitacion_id": habitacion_id,
                            "hora_actual": current_time
                        })

                # Si no se encontró coincidencia
                return jsonify({"mensaje": "No se encontraron coincidencias"})

            else:
                print("No se detectaron rostros en la imagen enviada")
                return jsonify({"mensaje": "No se detectaron rostros"})

    except Exception as e:
        print("Error procesando la imagen:", str(e))
        return jsonify({"error": f"Error procesando la imagen: {str(e)}"})

if __name__ == '__main__':
    app.run(debug=True)
