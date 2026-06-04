Dokumentasi & Konteks Proyek SPK Wisata Pesawaran

1. Informasi Umum Proyek
- Judul: Perancangan Sistem Pendukung Keputusan Berbasis Website Untuk Rekomendasi Tempat Wisata Terbaik Menggunakan Metode Additive Ratio Assessment (ARAS) Di Kabupaten Pesawaran.
- Metode Pengambilan Keputusan: ARAS (Additive Ratio Assessment).
- Metode Pengembangan: Waterfall.
- Lingkungan Pengembangan: Antigravity IDE.

2. Tech Stack & Arsitektur

- Backend: PHP dengan Framework Laravel (Arsitektur MVC).
- Frontend: HTML, CSS, JavaScript, Framework Bootstrap (Wajib Responsif).
- Database: MySQL (dikelola via phpMyAdmin).
- Integrasi Pihak Ketiga: Google Maps API (untuk pemetaan lokasi, rute, dan visualisasi persebaran destinasi wisata).

3. Aktor & Fitur Utama (Berdasarkan Use Case & Flowchart)
Sistem memiliki dua aktor utama dengan fungsionalitas sebagai berikut:
- Admin (Dinas Pariwisata):
* Login.
* Kelola Data Wisata (Alternatif).
* Kelola Kriteria & Nilai.
* Kelola Berita (Pariwisata).
* Kelola Notifikasi.
* Kelola Akun User.
* Generate & Cetak Laporan (Hasil perhitungan ARAS).
- Wisatawan (User):
* Registrasi & Login.
* Lihat Tempat Wisata (Detail, Fasilitas, dan Peta/Google Maps).
* Mengisi Bobot Kriteria: User menginput preferensi bobot secara dinamis (Sistem harus memvalidasi total bobot = 100% atau 1.0).
* Lihat Hasil Rekomendasi (Berdasarkan input bobot user & perhitungan ARAS).
* Lihat, Like & Komentar Berita.
* Lihat Notifikasi.

4. Kriteria Penilaian (ARAS)
Sistem menggunakan 6 kriteria utama:
* C1: Aksesibilitas (Benefit)
* C2: Fasilitas (Benefit)
* C3: Kebersihan (Benefit)
* C4: Keamanan (Benefit)
* C5: Harga Tiket (Cost)
* C6: Jumlah Pengunjung (Benefit)

5. Alternatif Tempat Wisata (Fokus: Wisata Air)
* A1: Pantai Mutun
* A2: Pantai Sari Ringgung
* A3: Pulau Pahawang
* A4: Pantai Klara
* A5: Teluk Hantu

6. Konsep Matematika ARAS pada Sistem
Proses perhitungan yang harus dieksekusi sistem setelah user memasukkan bobot:
* Matriks Keputusan (X): Menyusun data nilai alternatif terhadap kriteria, ditambah menentukan nilai optimum (A0).
* Normalisasi Matriks (R):
  * Benefit: R_ij = X_ij / Σ X_ij
  * Cost: R_ij = (1/X_ij) / Σ (1/X_ij)
* Matriks Ternormalisasi Berbobot (V): Mengalikan matriks normalisasi dengan bobot inputan user (V_ij = R_ij * W_j).
* Fungsi Optimasi (S): Menjumlahkan baris alternatif (S_i = Σ V_ij).
* Derajat Utilitas (K) / Ranking: K_i = S_i / S_0. (Nilai K tertinggi adalah rekomendasi wisata terbaik).

7. Instruksi AI (Rules for Gemini)
* Ketika developer meminta bantuan dalam workspace ini, AI harus:
  * Memberikan solusi kode berbasis Laravel dan Bootstrap.
  * Memastikan logika Controller untuk perhitungan ARAS terpisah dengan rapi, efisien, dan menangani edge case (misal: pembagian dengan nol, validasi total bobot wajib 1 atau 100%).
  * Memberikan panduan integrasi Google Maps API (menampilkan marker dan rute) menggunakan JavaScript/Blade template.
* Menjaga UI/UX tetap interaktif dan responsif di berbagai ukuran layar sesuai standar pengujian UAT.