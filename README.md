# 🚨 SiGap RESCUE-LOG

> **Platform Manajemen Rantai Pasok Logistik Tanggap Darurat Bencana Terintegrasi Berbasis Progressive Web App (PWA), GIS, dan Machine Learning.**

---

## 📌 Ringkasan Platform

**SiGap RESCUE-LOG** menghadirkan solusi rantai pasok darurat pintar yang mengubah penanganan logistik bencana dari proses manual dan reaktif menjadi ekosistem digital yang terintegrasi, proaktif, transparan, serta berbasis data. 

Platform ini dirancang khusus untuk memenuhi standar **6T** (*Tepat Jenis, Tepat Jumlah, Tepat Kualitas, Tepat Sasaran, Tepat Waktu, dan Tepat Biaya*) guna meredam dampak *Bullwhip Effect* saat krisis bencana terjadi.

---

## 🏗️ Arsitektur Sistem (Microservices)

Sistem ini dibangun dengan arsitektur **4-Layer Microservices** yang ter-deploy secara terpisah dan terhubung secara publik:

1. **User Layer**: Dashboard Eksekutif berbasis Web Desktop untuk BNPB/BPBD & Mobile PWA *Offline-First* untuk petugas posko lapangan.
2. **Main Application Layer (Laravel 11 Core)**: Mengelola *5-tier Role-Based Access Control (RBAC)*, logika bisnis logistik, dan layanan GIS.
3. **AI/ML Predictive Service (Python FastAPI)**: Engine prediksi kebutuhan logistik (*demand forecasting*) menggunakan model *Random Forest* & *Prophet*.
4. **Database & Spatial Layer (PostgreSQL + PostGIS via Supabase)**: Penyimpanan data relasional dan analisis geospasial/spasial terpusat.

---

## 💡 Fitur Unggulan

- 📱 **Progressive Web App (PWA) Offline-First**: Memungkinkan pencatatan data logistik di area bencana tanpa sinyal internet menggunakan Service Worker & IndexedDB (otomatis sinkronisasi saat *online*).
- 🤖 **AI-Powered Demand Forecasting**: Prediksi kebutuhan logistik secara otomatis berdasarkan demografi pengungsi dan riwayat bencana.
- 🗺️ **Interactive GIS Map (Leaflet.js)**: Visualisasi spasial titik bencana, lokasi posko, dan status stok logistik secara *real-time*.
- 🔐 **5-Tier Role Access (RBAC)**: Hak akses berjenjang mulai dari **BNPB Pusat**, **BPBD Provinsi**, **Admin BPBD Kabupaten**, **Komando Posko**, hingga **Petugas Lapangan**.

---

## 🌐 Deployed Environment (Live Production)

| Service | Technology | Status / Public URL |
| :--- | :--- | :--- |
| **Laravel Backend & Web App** | Laravel 11, Tailwind CSS, Leaflet | [https://rescue-log.up.railway.app](https://rescue-log.up.railway.app) |
| **ML Predictive Engine** | Python, FastAPI, Scikit-learn | [https://fastapi-ml-production-eeee.up.railway.app](https://fastapi-ml-production-eeee.up.railway.app) |
| **Database Server** | PostgreSQL + PostGIS | Cloud Supabase Session Pooler (Port 6543) |
| **Infrastructure Hosting** | Railway Cloud | Automatic CI/CD Deployment |

---

## 🛠️ Tech Stack

- **Core Backend**: PHP 8.3+, Laravel 11
- **ML Engine**: Python 3.11+, FastAPI, Pandas, Scikit-Learn
- **Database**: PostgreSQL 15, PostGIS Extension (Supabase)
- **Frontend**: Blade Templates, Tailwind CSS, JavaScript (ES6), Leaflet.js
- **Deployment & Cloud**: Railway Cloud Platform

---

## 👥 Tim Pengembang

**Universitas Jenderal Achmad Yani Yogyakarta**
- **Fikri Egnafis** (Lead Developer / Technical Lead)
- **Ari Syaputra S. Prakon**
- **Zuvera Mega Chintia**

---

## 📄 Lisensi

Project ini dikembangkan untuk kebutuhan akademik dan kompetisi teknologi kebencanaan di bawah lisensi [MIT License](LICENSE).
