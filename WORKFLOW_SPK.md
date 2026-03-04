# TEMPLATE.md - Blueprint Proyek  

## Nama Proyek  
**Nama:** Sistem Informasi SPK Berbasis Profile Matching (SPK-PM)

---

## Deskripsi  
Sistem Informasi **Sistem Pendukung Keputusan (SPK)** berbasis metode **Profile Matching** yang dapat digunakan untuk berbagai studi kasus, seperti:

- Program Seleksi Penerima Beasiswa  
- Pemilihan Karyawan Terbaik  
- Seleksi Kandidat  
- Studi kasus evaluasi dan penilaian lainnya  

Sistem dirancang fleksibel dan modular sehingga admin dapat membuat studi kasus baru, menentukan aspek penilaian, kriteria, bobot, serta melakukan perhitungan otomatis untuk menghasilkan ranking dan rekomendasi keputusan.

---

## Fitur Utama  

### 1. Manajemen Studi Kasus & Kriteria
- Membuat studi kasus baru (misal: Seleksi Beasiswa, Karyawan Terbaik, dll.)
- Menentukan aspek penilaian
- Menentukan kriteria
- Menentukan tipe kriteria (Core Factor / Secondary Factor)
- Mengatur bobot masing-masing kriteria

### 2. Manajemen Data Alternatif (Peserta/Kandidat)
- Input data peserta/kandidat
- Input nilai berdasarkan setiap kriteria
- Edit dan hapus data peserta

### 3. Perhitungan Otomatis Metode Profile Matching
- Perhitungan GAP (selisih nilai dengan profil ideal)
- Konversi bobot nilai GAP
- Perhitungan Core Factor (CF)
- Perhitungan Secondary Factor (SF)
- Perhitungan nilai total
- Perankingan otomatis

### 4. Laporan & Export Hasil
- Ranking hasil seleksi
- Detail perhitungan
- Export ke PDF/Excel

### 5. Manajemen User & Hak Akses
- Role Admin
- Role Penilai (opsional)
- Role Viewer (opsional)

---

## Teknologi yang Digunakan  

- **Framework:** Laravel  
- **Database:** PostgreSQL  
- **Version Control & CI/CD:** Git  
- **Hosting:** Vercel  
- **Development Environment:** Local HDD `/mnt/data/share/projects/`

---

## Tahapan Kerja  

1. **Inisialisasi Proyek**
   - Setup project Laravel  
   - Konfigurasi PostgreSQL  
   - Setup environment di `/mnt/data/share/projects/`  
   - Inisialisasi repository Git  

2. **Perancangan Sistem & Database**
   - Desain ERD  
   - Desain relasi tabel:
     - Users
     - Studi Kasus
     - Aspek
     - Kriteria
     - Alternatif
     - Penilaian
     - Hasil

3. **Implementasi Backend**
   - CRUD Studi Kasus
   - CRUD Kriteria & Bobot
   - CRUD Alternatif
   - Modul Perhitungan Profile Matching
   - API endpoint (opsional)

4. **Desain & Implementasi UI**
   - Dashboard
   - Form Input Data
   - Halaman Perhitungan & Ranking
   - Tampilan responsif

5. **Testing & Debugging**
   - Unit testing perhitungan
   - Validasi hasil manual vs sistem
   - Uji performa dan validasi input

6. **CI/CD & Deployment**
   - Setup pipeline Git
   - Build & deploy ke Vercel
   - Konfigurasi environment production

---

## Target Deploy  

- **Environment:** Production  
- **Server Tujuan:** Vercel (backend Laravel + PostgreSQL terkonfigurasi)

---

## Catatan Tambahan  

- Sistem harus modular dan scalable.  
- Struktur perhitungan Profile Matching harus fleksibel (CF/SF dapat diatur per studi kasus).  
- Menggunakan autentikasi dan validasi input yang baik.  
- Struktur kode mengikuti best practice Laravel (MVC + Service Layer untuk perhitungan).  
- Deadline proyek: (menyesuaikan kebutuhan).
