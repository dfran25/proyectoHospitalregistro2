import cv2 # type: ignore
import mediapipe as mp # type: ignore

# Inicializa MediaPipe Face Detection
mp_face_detection = mp.solutions.face_detection
mp_drawing = mp.solutions.drawing_utils

# Inicializa la captura de video
cap = cv2.VideoCapture(0)  # Usa la cámara web (cambia a 1 o el índice adecuado si tienes múltiples cámaras)

with mp_face_detection.FaceDetection(model_selection=1, min_detection_confidence=0.5) as face_detection:
    while cap.isOpened():
        ret, frame = cap.read()
        if not ret:
            print("Error al capturar el frame.")
            break

        # Convierte la imagen de BGR a RGB
        frame_rgb = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
        
        # Realiza la detección de rostros
        results = face_detection.process(frame_rgb)

        # Dibuja los resultados en la imagen
        if results.detections:
            for detection in results.detections:
                mp_drawing.draw_detection(frame, detection)

        # Muestra el frame
        cv2.imshow('Prueba de Mediapipe y OpenCV', frame)

        # Sale del bucle al presionar 'q'
        if cv2.waitKey(1) & 0xFF == ord('q'):
            break

# Libera la cámara y cierra las ventanas
cap.release()
cv2.destroyAllWindows()
