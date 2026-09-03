# TODO - Management Dokumen (Filament)

## Step 1 - Audit & Rencana
- [x] Analisis file terkait Documents (Table, Form, List page, Bell widget)
- [x] Putuskan hybrid tetap didukung

## Step 2 - Perbaiki DocumentForm
- [x] Tambahkan opsi `hybrid` pada `document_type`
- [x] Sinkronkan logika `required/hidden/helperText` untuk `hybrid`
- [x] Tambahkan field `file_name` agar `$set('file_name', ...)` tidak gagal

## Step 3 - Perbaiki DocumentsTable
- [x] Hapus duplikasi/konflik tampilan `status` (terselesaikan via kolom notifikasi tunggal)
- [x] Hapus import yang tidak dipakai (pindah ke `Storage`, import `Dom\Text` tidak lagi dipakai)
- [x] Perbaiki URL tombol `Unduh` menggunakan `Storage::disk('documents')->url(...)`

## Step 4 - Perbaiki DocumentsHeaderBellWidget
- [x] Hapus `->access()` dari query (menghindari crash jika scope tidak ada)
- [x] Rapikan cast pendingCount ke int

## Step 5 - Verifikasi
- [ ] Test halaman list dokumen: tampil, tombol “Tambah Dokumen” berfungsi
- [ ] Test form: upload file/hybrid, validasi, simpan tersimpan
- [ ] Test tombol “Unduh”
- [ ] Test bell widget: pending count & dropdown tampil
