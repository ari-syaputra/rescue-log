import joblib
import os
import onnx
from skl2onnx import convert_sklearn
from skl2onnx.common.data_types import FloatTensorType, StringTensorType

# 1. Load Model PKL
model_path = "model_logistik_rf_komprehensif.pkl"
model = joblib.load(model_path)

# 2. Definisi 12 Tipe Input Fitur Sesuai Dataset & Pipeline Model
initial_types = [
    ('total_pengungsi', FloatTensorType([None, 1])),
    ('anak_balita', FloatTensorType([None, 1])),
    ('dewasa', FloatTensorType([None, 1])),
    ('ibu_hamil', FloatTensorType([None, 1])),
    ('lansia', FloatTensorType([None, 1])),
    ('disabilitas', FloatTensorType([None, 1])),
    ('tipe_tempat', StringTensorType([None, 1])),
    ('akses_air', StringTensorType([None, 1])),
    ('suhu_celcius', FloatTensorType([None, 1])),
    ('cuaca', StringTensorType([None, 1])),
    ('akses_jalan', StringTensorType([None, 1])),
    ('lama_pengungsian_hari', FloatTensorType([None, 1]))
]

try:
    # 3. Konversi ke Format ONNX
    onnx_model = convert_sklearn(model, initial_types=initial_types)
    
    # 4. Tentukan Path Target (Folder Public Laravel)
    output_dir = os.path.join("..", "backend", "public", "models")
    os.makedirs(output_dir, exist_ok=True)
    output_path = os.path.join(output_dir, "model_logistik.onnx")

    # 5. Simpan menggunakan modul bawaan ONNX (Bebas Error Serialize)
    onnx.save_model(onnx_model, output_path)

    print(f"✅ SUCCESS: Model ONNX berhasil disimpan di '{output_path}'!")
except Exception as e:
    print(f"❌ ERROR Konversi: {e}")