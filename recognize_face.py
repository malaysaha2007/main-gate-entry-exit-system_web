import cv2
import numpy as np
import pickle
import os

# ================= PATHS =================
BASE_DIR = os.path.dirname(os.path.abspath(__file__))
ENCODINGS_FILE = os.path.join(BASE_DIR, "encodings.pkl")
PROTO_PATH = os.path.join(BASE_DIR, "deploy.prototxt")
MODEL_PATH = os.path.join(BASE_DIR, "res10_300x300_ssd_iter_140000.caffemodel")

# ================= LOAD FACE DETECTOR =================
net = cv2.dnn.readNetFromCaffe(PROTO_PATH, MODEL_PATH)

# ================= LOAD ENCODINGS =================
with open(ENCODINGS_FILE, "rb") as f:
    known_encodings, known_ids = pickle.load(f)

known_encodings = np.array(known_encodings)

print("[INFO] System ready")

# ================= CAMERA =================
cap = cv2.VideoCapture(0)   # DroidCam / Laptop camera

THRESHOLD = 1.10   # relaxed for your encoding method

while True:
    ret, frame = cap.read()
    if not ret:
        break

    h, w = frame.shape[:2]

    # ===== FACE DETECTION =====
    blob = cv2.dnn.blobFromImage(
        cv2.resize(frame, (300, 300)),
        1.0,
        (300, 300),
        (104.0, 177.0, 123.0)
    )

    net.setInput(blob)
    detections = net.forward()

    for i in range(detections.shape[2]):
        confidence = detections[0, 0, i, 2]

        if confidence < 0.5:   # lowered so box always appears
            continue

        box = detections[0, 0, i, 3:7] * np.array([w, h, w, h])
        x1, y1, x2, y2 = box.astype(int)

        # 🛡️ keep box inside frame
        x1 = max(0, x1)
        y1 = max(0, y1)
        x2 = min(w, x2)
        y2 = min(h, y2)

        face = frame[y1:y2, x1:x2]
        if face.size == 0:
            continue

        # ===== ENCODING =====
        face = cv2.resize(face, (100, 100))
        face_encoding = face.flatten()

        distances = np.linalg.norm(known_encodings - face_encoding, axis=1)
        min_dist = np.min(distances)
        idx = np.argmin(distances)

        print("Min distance:", round(min_dist, 3))

        if min_dist < THRESHOLD:
            label = known_ids[idx]
            color = (0, 255, 0)
        else:
            label = "Unknown"
            color = (0, 0, 255)

        # ===== DRAW =====
        cv2.rectangle(frame, (x1, y1), (x2, y2), color, 2)
        cv2.putText(
            frame,
            label,
            (x1, y1 - 10),
            cv2.FONT_HERSHEY_SIMPLEX,
            0.8,
            color,
            2
        )

    # ===== SHRINK DISPLAY WINDOW ONLY =====
    display_frame = cv2.resize(frame, (640, 480))
    cv2.imshow("Face Recognition (Press Q to exit)", display_frame)

    if cv2.waitKey(1) & 0xFF == ord("q"):
        break

cap.release()
cv2.destroyAllWindows()
