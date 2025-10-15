/**
 * file: kasirManager.js
 * Gabungan logika konfirmasi delete, update, dan tampilan alert hasil operasi
 * Menggunakan library SweetAlert2 (Swal).
 */

document.addEventListener('DOMContentLoaded', () => {
    
    // --- I. Fungsi Helper untuk Menampilkan SweetAlert ---
    
    /**
     * Menampilkan SweetAlert berdasarkan parameter yang diberikan.
     * @param {string} icon - 'success', 'error', 'warning', 'info', 'question'
     * @param {string} title - Judul alert
     * @param {string} text - Isi pesan
     * @param {boolean} showConfirm - Tampilkan tombol konfirmasi
     * @param {number} timer - Durasi tampil (ms). 0 jika showConfirm=true.
     */
    const showAlert = (icon, title, text, showConfirm = true, timer = 0) => {
        Swal.fire({
            icon: icon,
            title: title,
            text: text,
            showConfirmButton: showConfirm,
            timer: showConfirm ? 0 : timer,
            confirmButtonColor: icon === 'success' ? '#2563eb' : (icon === 'error' ? '#ef4444' : '#3085d6')
        });
    };


    // -------------------------------------------------------------
    // --- II. Logika Konfirmasi Delete dan Update ---
    // -------------------------------------------------------------

    // 1. Konfirmasi Delete
    const deleteButtons = document.querySelectorAll('.btn-delete');

    deleteButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const id = btn.dataset.id;

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data kasir ini akan dihapus permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `/kasir/delete/${id}`;
                }
            });
        });
    });

    // 2. Konfirmasi Update Form
    const btnUpdate = document.getElementById("btnUpdateKasir");
    const form = btnUpdate ? btnUpdate.closest("form") : null;

    if (btnUpdate && form) {
        btnUpdate.addEventListener("click", function(e) {
            e.preventDefault();

            Swal.fire({
                title: "Yakin ingin simpan perubahan?",
                text: "Data akan diperbarui secara permanen.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, simpan!",
                cancelButtonText: "Batal",
                confirmButtonColor: "#2563eb",
                cancelButtonColor: "#6b7280",
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    }


    // -------------------------------------------------------------
    // --- III. Logika Alert Hasil Operasi (Update/Store/Delete) ---
    // -------------------------------------------------------------
    const urlParams = new URLSearchParams(window.location.search);

    if (urlParams.has("success") || urlParams.has("info") || urlParams.has("error")) {
        let icon = "info";
        let title = "";
        let text = "";

        // --- Logika SUCCESS ---
        if (urlParams.has('success')) {
            icon = "success";
            title = "Berhasil!"; // <--- DIPERBAIKI: Judul selalu ada

            switch (urlParams.get("success")) {
                case "updated":
                    text = "Data berhasil diperbarui."; // <--- DIPERBAIKI: Pesan untuk update
                    break;
                case "deleted":
                    text = "Kasir telah dihapus.";
                    break;
                case "stored":
                    text = "Kasir baru telah ditambahkan.";
                    break;
                default:
                    text = "Operasi berhasil.";
                    break;
            }
            
            // Tampilkan success alert dengan timer singkat
            showAlert(icon, title, text, false, 1800);
            return;

        }
        
        // --- Logika INFO ---
        if (urlParams.get("info") === "no_changes") {
            icon = "info";
            title = "Tidak ada perubahan";
            text = "Tidak ada data yang diubah.";
        }
        
        // --- Logika ERROR ---
        else if (urlParams.has('error')) {
            icon = "error";
            title = "Gagal!";

            switch (urlParams.get("error")) {
                case "password_mismatch":
                    text = "Password baru dan konfirmasi tidak cocok!";
                    break;
                case "invalid_file":
                    text = "Tipe file tidak valid. Gunakan JPG, PNG, atau WEBP.";
                    break;
                case "upload_failed":
                    text = "Upload gambar gagal. Coba lagi.";
                    break;
                case "exception":
                    text = decodeURIComponent(urlParams.get("msg") || "Terjadi kesalahan server.");
                    break;
                case "unauthorized":
                    text = "Sesi kamu habis. Silakan login ulang.";
                    break;
                case "email_exists":
                    text = "Email sudah digunakan kasir lain.";
                    break;
                
                // 🚨 DIPERBAIKI: Penanganan error 'active'
                case "active": 
                    text = 'Kasir aktif tidak dapat dihapus. Nonaktifkan terlebih dahulu.';
                    break;
                
                // Penanganan error Delete
                case "notfound":
                    text = 'Kasir tidak ditemukan.';
                    break;
                case "failed":
                    text = 'Gagal menghapus data.';
                    break;

                default:
                    text = "Terjadi kesalahan yang tidak diketahui.";
                    break;
            }
        }
        
        // Tampilkan info/error alert
        showAlert(icon, title, text, true);
    }
});