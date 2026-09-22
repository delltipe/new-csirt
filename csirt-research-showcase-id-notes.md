# Catatan Adaptasi & Metodologi Showcase CSIRT Versi Bahasa Indonesia

**Nama Berkas Artefak:** `csirt-research-showcase-id.html`  
**Berkas Rujukan Bahasa Inggris:** `csirt-research-showcase.html` (Tetap utuh tanpa perubahan)  
**Proyek Penelitian:** Desain Ulang & Evaluasi Empiris Portal Publik CSIRT Provinsi DKI Jakarta  
**Peneliti:** Abdul Latif (Universitas Bina Nusantara / Diskominfotik Provinsi DKI Jakarta)  
**Metodologi Riset:** Design Science Research Methodology (DSRM; Peffers et al., 2007)  
**Tanggal Rilis:** 22 September 2026  

---

## 1. Catatan Penerjemahan & Adaptasi Bahasa (Translation/Adaptation Notes)

Penerjemahan tidak dilakukan secara mesin kata-per-kata, melainkan diadaptasi agar selaras dengan **gaya tutur akademik perguruan tinggi Indonesia** dan mudah dipahami oleh audiens sidang (dosen pembimbing, dosen penguji, rekan sejawat, maupun pemangku kepentingan instansi):

1. **Nada Akademik Percakapan (Conversational Academic Tone):**
   - Frasa kaku seperti *"The evaluation indicates..."* diadaptasi menjadi *"Hasil evaluasi empiris menunjukkan..."*.
   - Istilah *"User perception"* diterjemahkan konsisten menjadi *"Persepsi kemudahan pengguna"*.
   - Istilah *"Implementation evidence"* diubah menjadi *"Bukti implementasi kode artefak"*.
   - Frasa *"Operational suitability"* diadaptasi menjadi *"Kesiapan operasional penanganan insiden resmi"*.
2. **Konsistensi Istilah Bahasa Indonesia:**
   - Dipilih kata tunggal **"pengguna"** dan **"praktisi"** secara konsisten di seluruh naskah, menghindari percampuran kata *user / pemakai / pengguna*.
   - Format angka desimal disesuaikan dengan konvensi penulisan ilmiah Indonesia, menggunakan koma (misal: `75,11`, `70,46`, `88,9%`, `0,0%`, `4,04 ± 0,71`).
3. **Pemberian Konteks Sederhana Sebelum Menampilkan Angka:**
   - Setiap instrumen atau standar teknis (seperti SUS, WCAG, WAVE, axe-core) selalu didahului kartu penjelasan singkat *"Apa Ini?"* sehingga audiens non-teknis memahami konteks pengukuran sebelum melihat angka hasil evaluasi.

---

## 2. Visualisasi Tambahan yang Diterapkan (Visualizations Added)

Mengikuti prinsip *"Visualisasikan Istilah Rumit" (Visualize Gibberish)*, versi Bahasa Indonesia menambahkan sejumlah komponen visual baru:

1. **Kotak Sampel Warna Nyata (Color Swatch Chips):**
   - Nilai heksadesimal warna tidak lagi ditampilkan sebagai teks mentah semata. Ditampilkan kartu visual kotak warna fisik berdampingan dengan kode heksadesimalnya:
     * Oranye Lama: `#FF6B00` (disertai tanda silang kontras gagal).
     * Biru Navy Baru: `#003580` (rasio kontras 8.4:1 lolos AA).
     * Deep Ink: `#0A0F1A` (rasio kontras 18.2:1 teks utama).
     * Crisp Mist: `#F4F5F7` (latar bagian pencegah mata lelah).
2. **Diagram Alur Perjalanan Rute (Visual Route Stepper):**
   - Jalur rute teknis diubah menjadi tahapan visual berpanah:
     `[1. Masuk/Daftar (/login)] ➔ [2. Persetujuan TaC] ➔ [3. Form Terpadu 4 Sekat] ➔ [4. Nomor Tiket INS]`.
3. **Kartu Komparasi Arsitektur Framework:**
   - Teks `Yii → Laravel` diubah menjadi kartu perbandingan dua sisi yang membedakan kelemahan Yii (kueri SQL langsung tanpa parameter ketat) vs keunggulan Laravel 12 (parameter PDO terikat, penanganan teks pentest tak terbatas, dan token desain terpusat).
4. **Kisi Unit Responden (Unit Sample Grid Dots):**
   - Proporsi mandat operasional 88,9% (24 dari 27 responden) divisualisasikan menggunakan 27 kotak glif responden (24 unit hijau aktif dan 3 unit abu-abu netral) sehingga perbandingannya langsung terlihat dalam 1 detik.
5. **Diagram Alur 3-Langkah Dampak Kode Sumber:**
   - Setiap temuan kode program disajikan dalam visual kausalitas 3 kotak:
     `[1. Implementasi Kode]` ➔ `[2. Perilaku Sistem Antarmuka]` ➔ `[3. Dampak terhadap Pengguna]`, diikuti tombol buka-tutup (accordion) untuk memeriksa cuplikan kode Laravel aslinya.
6. **Glosarium Istilah Penelitian Khusus Indonesia:**
   - Ditambahkan tabel glosarium di bagian akhir untuk menguraikan definisi praktis DSRM, SUS, WCAG 2.1 AA, WAVE, axe-core, Straight-lining, dan Artefak.

---

## 3. Istilah Teknis yang Disederhanakan (Technical Terms Simplified)

| Istilah Bahasa Inggris Asli | Adaptasi Bahasa Indonesia yang Bermakna | Penjelasan Tambahan |
| :--- | :--- | :--- |
| **DSRM (Peffers et al., 2007)** | Metodologi Penelitian Design Science | Dijelaskan sebagai metode ilmiah rekayasa produk nyata, bukan sekadar survei teori. |
| **System Usability Scale (SUS)** | Kuesioner Baku Usabilitas Sistem | Diberi keterangan batas kelayakan rata-rata dunia (ambang 68,0). |
| **Straight-lining / Acquiescence** | Bias Pengisian Lurus / Jawaban Seragam | Dijelaskan secara matematis mengapa pilihan seragam menghasilkan nilai tepat 50,0. |
| **TaC Gate** | Gerbang Persetujuan Syarat & Ketentuan | Dijelaskan sebagai pintu persetujuan etika pelaporan kerentanan siber (Responsible Disclosure). |
| **Bound Parameters (PDO)** | Pengikatan Parameter Kueri Database Aman | Dijelaskan dampaknya: payload pentest tanda petik tidak lagi merusak server database. |
| **Semantic HTML (`label for`)** | Hubungan Judul Kolom yang Terbaca Mesin | Dijelaskan dampaknya: pembaca layar tunanetra mengenali nama kolom saat berpindah kursor. |

---

## 4. Informasi Teknis yang Dikonversi Menjadi Visual (Converted into Visuals)

1. **Skor Kontras Matematis:** Diubah menjadi status lulus/gagal berwarna hijau dan merah dengan perbandingan terhadap batas 4.5:1.
2. **Distribusi Skala Likert 1–5:** Diubah menjadi diagram batang divergen 100% yang memperlihatkan hilangnya bar warna merah (nilai 2: Tidak Puas) dari 8,7% menjadi 0,0%.
3. **Penyebab Crash Database:** Diilustrasikan melalui **Simulator Penanganan Error Interaktif** yang memperagakan crash `CDbException` lama vs layar peringatan `#validation-summary` baru dengan tautan lompat ke kolom yang salah.
4. **Penerbitan Tiket Pelaporan:** Ditampilkan dalam kartu status 5 tahapan alur resmi CSIRT (`menunggu_validasi` ➔ `divalidasi` ➔ `ditindaklanjuti` ➔ `dipulihkan` ➔ `selesai`).

---

## 5. Konten yang Tetap Dipertahankan dalam Bentuk Baku (Preserved Standard Terms)

Beberapa istilah standar internasional tetap dipertahankan nama aslinya agar tidak kehilangan ketepatan rujukannya dalam naskah skripsi:
- **Nama Framework:** *Laravel 12* dan *Yii Framework*.
- **Nama Standar Aksesibilitas:** *WCAG 2.1 AA* (Web Content Accessibility Guidelines).
- **Nama Perangkat Lunak Audit:** *WAVE Evaluation Tool* dan *axe-core*.
- **Nama Metrik Baku:** *System Usability Scale (SUS)*.
- **Format Tiket:** *INS-YYYY-XXXX*.

---

## 6. Penyesuaian Tata Letak Akibat Panjang Kalimat Bahasa Indonesia

Secara tipografi, kalimat Bahasa Indonesia rata-rata memiliki panjang 15% hingga 25% lebih panjang dibanding Bahasa Inggris. Penyesuaian tata letak berikut telah diimplementasikan:
1. **Pita Navigasi Atas (`.nav-sections`):** Lebar maksimum diperluas menjadi `630px` dengan teks pill yang dipadatkan (misal: "1. Ringkasan", "4. Komparasi Visual", "7. Usabilitas & SUS") agar tidak saling bertumpuk.
2. **Kartu Grid Triangulasi & Intervensi:** Menggunakan `font-size: 13.5px` dengan jarak spasi baris `line-height: 1.6` agar paragraf Bahasa Indonesia tetap nyaman dibaca tanpa terpotong.
3. **Tabel Dekonstruksi Polaritas:** Menggunakan kolom tabel dengan persentase lebar proporsional agar teks deskripsi butir pertanyaan Indonesia (misal: *"Ingin sering menggunakan sistem ini"*) tidak memicu pemenggalan kata yang canggung.
4. **Responsif Proyektor:** Telah diuji pada resolusi desktop 1366×768 dan 1920×1080 sehingga cocok digunakan saat presentasi sidang menggunakan proyektor atau layar rapat.
