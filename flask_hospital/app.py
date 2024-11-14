from flask import Flask, request, jsonify
import cv2
import numpy as np
import base64
import pymysql
import torch
from flask_cors import CORS
import os
from datetime import datetime
from facenet_pytorch import MTCNN, InceptionResnetV1
from scipy.spatial.distance import cosine
from PIL import Image

app = Flask(__name__)
CORS(app)

# Configuración de facenet-pytorch
mtcnn = MTCNN(keep_all=False, min_face_size=40, thresholds=[0.6, 0.7, 0.7], device='cpu')
resnet = InceptionResnetV1(pretrained='vggface2').eval()

# Configuración de la conexión con la base de datos
db_connection = pymysql.connect(
    host='127.0.0.1',
    user='root',
    password='',
    database='registro'
)

# Directorio donde están guardadas las fotos
PHOTO_DIRECTORY = "C:/xampp/htdocs/Proyectofinal/hospital/public/storage"

# Función para calcular embeddings
def calcular_embedding(imagen):
    img_rgb = cv2.cvtColor(imagen, cv2.COLOR_BGR2RGB)
    img_pil = Image.fromarray(img_rgb)
    face = mtcnn(img_pil)  # Detectar y extraer el rostro
    if face is not None:
        embedding = resnet(face.unsqueeze(0)).detach().cpu().numpy()
        return embedding.flatten()
    return None

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

        # Calcular el embedding de la imagen capturada
        embedding = calcular_embedding(img)
        if embedding is None:
            return jsonify({"mensaje": "No se detectaron rostros en la imagen"})

        print("Rostro detectado en la imagen, buscando coincidencias en la base de datos")

        # Buscar coincidencias en la base de datos
        cursor = db_connection.cursor()
        cursor.execute("SELECT nombre, identificacion, foto, habitacion_id FROM visitantes")
        visitantes = cursor.fetchall()
        
        best_match = None
        best_score = 0.7  # Umbral de similitud (0.5 se usa aquí como mínimo de similitud)

        for visitante in visitantes:
            nombre, identificacion, foto_filename, habitacion_id = visitante
            photo_path = os.path.join(PHOTO_DIRECTORY, foto_filename)
            
            # Cargar la imagen de la base de datos y calcular su embedding
            db_img = cv2.imread(photo_path)
            db_embedding = calcular_embedding(db_img)

            if db_embedding is not None:
                score = 1 - cosine(embedding, db_embedding)
                if score > best_score:  # Si la similitud es mejor, actualiza el mejor match
                    best_match = {
                        "nombre": nombre,
                        "identificacion": identificacion,
                        "habitacion_id": habitacion_id,
                        "similaridad": score
                    }
                    best_score = score

        if best_match:
            current_time = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
            print(f"Coincidencia encontrada: {best_match['nombre']}, ID: {best_match['identificacion']}, Habitación: {best_match['habitacion_id']}")
            return jsonify({
                "mensaje": "Coincidencia encontrada",
                "nombre": best_match["nombre"],
                "identificacion": best_match["identificacion"],
                "habitacion_id": best_match["habitacion_id"],
                "hora_actual": current_time
            })
        
        # Si no se encontró coincidencia
        return jsonify({"mensaje": "No se encontraron coincidencias"})

    except Exception as e:
        print("Error procesando la imagen:", str(e))
        return jsonify({"error": f"Error procesando la imagen: {str(e)}"})

if __name__ == '__main__':
    app.run(debug=True)