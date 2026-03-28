import os
import cv2
import numpy as np
import pickle

# ===== PATHS =====
BASE_DIR = os.path.dirname(os.path.abspath(__file__))

FACE_DATA_DIR = os.path.join(BASE_DIR, "face_data")
PROTO_PATH = os.path.join(BASE_DIR, "deploy.prototxt")
MODEL_PATH = os.path.join(BASE_DIR, "res10_300x300_ssd_iter_140000.caffemodel")
ENCODINGS_FILE = os.path.join(BASE_DIR, "encodings.pkl")

# ===== LOAD FACE DETECTOR =====
net = cv2.dnn.readNetFromCaffe(PROTO_PATH, MODEL_PATH)

known_encodings = []
known_ids = []

print("[INFO] Starting face encoding...")

# ===== LOOP THROUGH STUDENTS =====
for student_id in os.listdir(FACE_DATA_DIR):
    student_path = os.path.join(FACE_DATA_DIR, student_id)

    if not os.path.isdir(student_path):
        continue

    for img_name in os.listdir(student_path):
        img_path = os.path.join(student_path, img_name)
        image = cv2.imread(img_path)

        if image is None:
            continue

        (h, w) = image.shape[:2]

        blob = cv2.dnn.blobFromImage(
            cv2.resize(image, (300, 300)),
            1.0,
            (300, 300),
            (104.0, 177.0, 123.0)
        )

        net.setInput(blob)
        detections = net.forward()

        for i in range(detections.shape[2]):
            confidence = detections[0, 0, i, 2]

            if confidence > 0.6:
                box = detections[0, 0, i, 3:7] * np.array([w, h, w, h])
                (x1, y1, x2, y2) = box.astype("int")

                face = image[y1:y2, x1:x2]

                if face.size == 0:
                    continue

                face = cv2.resize(face, (100, 100))
                encoding = face.flatten()

                known_encodings.append(encoding)
                known_ids.append(student_id)

                print(f"[INFO] Encoded face for {student_id}")
                break   # only one face per image

# ===== SAVE ENCODINGS =====
with open(ENCODINGS_FILE, "wb") as f:
    pickle.dump((known_encodings, known_ids), f)

print(f"[SUCCESS] Encoding completed.")
print(f"[SUCCESS] Total faces encoded: {len(known_ids)}")
print(f"[SUCCESS] Saved to encodings.pkl")