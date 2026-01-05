# 🎾 CourtPlay - Elevate Your Game with AI 🚀

![CourtPlay Logo](README/logo.png)

## 👋 Project Apa Sih Ini?

Halo guys! Selamat datang di **CourtPlay**! 🎉

CourtPlay adalah aplikasi web super canggih yang didesain khusus buat kamu para pecinta Tenis dan Padel. Project ini bertujuan untuk merevolusi cara kamu berlatih dengan bantuan **Artificial Intelligence (AI)** dan **Computer Vision**. 🤖👀

Bayangin aja, kamu tinggal upload video latihan atau pertandingan kamu, terus *boom*! 💥 Sistem kita bakal nganalisis video itu dan ngasih kamu insight mendalam kayak statistik pukulan (forehand, backhand, serve), heatmap pergerakan, sampai minimap replay. Gak cuma itu, ada fitur sosial medianya juga lho, jadi bisa pamer statistik ke temen-temen! 😎

---

## 🔗 Repository Terkait

Project ini gak berdiri sendiri, ada "otak" AI-nya yang terpisah. Cekidot repository lainnya di sini:

*   🧠 **Backend AI Service:** [CourtPlay Backend](https://github.com/AbiyaMakruf/TelU-Tubes-BisnisDigital-CourtPlay-Backend)  
    *Ini tempat di mana magic terjadi! Service Python yang ngurusin processing video.*
*   🧪 **AI Development & Research:** [CourtPlay AI Development](https://github.com/AbiyaMakruf/TelU-Tubes-BisnisDigital-CourtPlay-AIDevelopment)  
    *Tempat eksperimen model AI, training, dan riset algoritma.*

---

## 🛠️ Tech Stack yang Kita Pake

Kita pake teknologi-teknologi kekinian biar performanya ngebut dan stabil! ⚡

![Tech Stack](README/tech_stack.png)

### Versi Tech Stack (Laporan Akhir)
Detail variasi stack yang kami gunakan juga tersedia di folder `laporan_akhir/`:

| Backend & AI | Development Model |
| :---: | :---: |
| ![Tech Stack Backend & AI](laporan_akhir/tech_stack_backend_ai.png) | ![Tech Stack Development Model](laporan_akhir/tech_stack_development_model.png) |

| General Overview | Web App |
| :---: | :---: |
| ![Tech Stack General](laporan_akhir/tech_stack_general.png) | ![Tech Stack Web App](laporan_akhir/tech_stack_webapp.png) |

*   **Frontend & Backend Web:** Laravel 10 (PHP), Livewire 3, Blade Templates, Bootstrap 5 (Glassmorphism Style ✨).
*   **Database:** PostgreSQL (via Supabase).
*   **AI & Processing:** Python, OpenCV, YOLO, PyTorch.
*   **Cloud Infrastructure (GCP):**
    *   ☁️ **Cloud Run:** Buat hosting aplikasi web dan AI worker (Serverless!).
    *   📦 **Artifact Registry:** Nyimpen Docker image.
    *   🗄️ **Cloud Storage (GCS):** Nyimpen video user dan aset.
    *   📨 **Pub/Sub:** Antrian messaging buat komunikasi asinkron antara Web dan AI worker.
*   **Integrations:**
    *   🤖 **Gemini AI:** Buat generate berita otomatis.
    *   💸 **Xendit:** Payment gateway buat langganan.
    *   🔔 **Pusher:** Realtime notifikasi.
    *   📧 **Mailtrap:** Testing email.

---

## ✨ Fitur-Fitur Kece

### 👤 User Features
*   **Video Upload & Analysis:** Drag & drop video, tunggu AI kerja, dapet hasil analisis lengkap!
*   **Interactive Analytics:**
    *   📊 **Stats:** Hitungan pukulan akurat.
    *   🔥 **Heatmap:** Liat area mana yang paling sering kamu injak di lapangan.
    *   🗺️ **Minimap:** Replay pergerakan pemain dan bola dari pandangan atas.
*   **Social Hub:** Follow temen, liat profil mereka, dan share hasil latihan.
*   **Matchmaking:** Cari lawan tanding yang selevel atau booking lapangan (Simulation).
*   **News Portal:** Berita olahraga terkini yang digenerate sama AI (Gemini).
*   **Subscription Plans:** Langganan buat fitur premium.

### 👮 Admin Features
*   **Dashboard:** Monitoring user, project, dan kesehatan server (Cloud Run Metrics).
*   **User Management:** Atur user yang daftar.
*   **Project Management:** Pantau video yang diupload user.
*   **AI News Editor:** Bikin berita gampang banget, tinggal ketik judul, AI yang nulis isinya + cariin prompt gambar! 🤯

---

## ⚙️ Persiapan (Prerequisites)

Sebelum jalanin project ini, pastiin kamu udah siapin "bumbu-bumbu" ini ya:

1.  **Google Cloud Platform (GCP) Account:**
    *   Aktifin **Cloud Run**, **Artifact Registry**, **Cloud Storage**, dan **Pub/Sub**.
    *   Bikin Service Account dengan permission yang sesuai.
2.  **Supabase Project:** Buat database PostgreSQL.
3.  **Mailtrap Account:** Buat nangkep email testing.
4.  **Xendit Account:** Buat simulasi pembayaran (Test Mode).
5.  **Pusher Account:** Buat fitur realtime.
6.  **Google AI Studio (Gemini):** Ambil API Key buat fitur generate berita.
7.  **Docker:** Wajib banget buat containerization.

---

## 🚀 Cara Jalanin (How to Run)

### 🏠 Di Local (Laptop Kamu)

1.  **Clone Repo ini:**
    ```bash
    git clone https://github.com/AbiyaMakruf/TelU-Tubes-BisnisDigital-CourtPlay-Web.git
    ```
2.  **Install Dependencies:**
    ```bash
    composer install
    npm install
    ```
3.  **Setup Environment:**
    *   Copy `.env.example` jadi `.env`.
    *   Isi semua config database, GCP credentials, Pusher, Xendit, dll.
4.  **Generate Key & Migrate:**
    ```bash
    php artisan key:generate
    php artisan migrate --seed
    ```
5.  **Jalanin Server:**
    ```bash
    npm run dev
    php artisan serve
    ```
    Buka `http://localhost:8000` deh! 🥳

### ☁️ Di Cloud (Deployment)

Kita pake **CI/CD** lho! 😎 Jadi setiap ada push ke branch `main` (atau branch tertentu), GitHub Actions bakal otomatis:
1.  Build Docker Image.
2.  Push ke Google Artifact Registry.
3.  Deploy revisi baru ke Google Cloud Run.

Cek file `.github/workflows/main.yml` buat liat detailnya. Praktis banget kan? Gak perlu deploy manual!

---

## 📸 Galeri Screenshot

Biar gak penasaran, nih liat penampakan aplikasinya. UI-nya pake gaya **Glassmorphism** + **Neon Green** yang futuristik abis! 🟢⚫

### 🌐 Landing Page & Public
| Landing Page 1 | Landing Page 2 |
| :---: | :---: |
| ![LP1](README/landingpage_1.png) | ![LP2](README/landingpage_2.png) |

| Landing Page 3 | Landing Page 4 | Landing Page 5 |
| :---: | :---: | :---: |
| ![LP3](README/landingpage_3.png) | ![LP4](README/landingpage_4.png) | ![LP5](README/landingpage_5.png) |

| About Us | Pricing | News |
| :---: | :---: | :---: |
| ![About](README/aboutus_1.png) | ![Pricing](README/pricing.png) | ![News](README/news.png) |

### 🔐 Authentication
| Login | Register |
| :---: | :---: |
| ![Login](README/login.png) | ![Register](README/register.png) |

### 👤 User Dashboard & Features
| Homepage | Upload Video |
| :---: | :---: |
| ![Home](README/homepage.png) | ![Upload](README/upload.png) |

| Analytics Detail | Profile | Social |
| :---: | :---: | :---: |
| ![Analytics](README/analytics_1.png) | ![Profile](README/profile.png) | ![Social](README/social.png) |

| Matchmaking | Friend Profile |
| :---: | :---: |
| ![Matchmaking](README/matchmaking_1.png) | ![Friend](README/friendprofile.png) |

### 👮 Admin Dashboard
| Dashboard | User Management | Project Management |
| :---: | :---: | :---: |
| ![Admin1](README/admin_1.png) | ![Admin2](README/admin_2.png) | ![Admin3](README/admin_3.png) |

---

## 🧠 Output Analytics AI

Ini nih hasil kerja keras si AI! Dia bisa ngasih visualisasi keren kayak gini:

### 📹 Video Analysis Results
*(Klik link atau download file di folder `README/output_analytics/` buat liat videonya)*

*   **Output Full Analysis:** `output.mp4`
*   **Heatmap Player:** `heatmap_player.mp4`
*   **Minimap Player:** `minimap_player.mp4`
*   **Minimap Ball:** `minimap_ball.mp4`
*   **Player Keypoints:** `playerKeyPoint.mp4`

### 🖼️ Visual Output
| Heatmap Player | Minimap Ball |
| :---: | :---: |
| ![Heatmap](README/output_analytics/heatmap_player.png) | ![Minimap](README/output_analytics/minimap_ball.png) |

---

## 📂 Laporan Akhir & Media

*   **Video Output AI:** `laporan_akhir/Contoh video output AI analytics.mp4`
*   **Video Demo Web App:** `laporan_akhir/Video demo web app.mp4`
*   **Slide Presentasi:** tersedia di folder `laporan_akhir/` (lihat dokumen PDF presentasi).

---

## 🖼️ Poster Project

![Poster](README/poster.png)

---

Made with ❤️ and ☕ by **CourtPlay Team**.
Enjoy the game! 🎾
