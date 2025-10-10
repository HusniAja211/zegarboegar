document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);

    // ✅ Alert sukses / info / error dari URL
    if (urlParams.has("success") || urlParams.has("info") || urlParams.has("error")) {
        let icon = "info", title = "", text = "";

        if (urlParams.get("success") === "updated") {
            icon = "success";
            title = "Berhasil!";
            text = "Data kasir berhasil diperbarui.";
        }

        if (urlParams.get("info") === "no_changes") {
            icon = "info";
            title = "Tidak ada perubahan";
            text = "Tidak ada data yang diubah.";
        }

        if (urlParams.get("error")) {
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
                default:
                    text = "Terjadi kesalahan yang tidak diketahui.";
            }
        }

        Swal.fire({
            icon,
            title,
            text,
            confirmButtonColor: icon === "success" ? "#2563eb" : "#ef4444"
        });
    }

    // ✅ Konfirmasi sebelum submit form update
    const btnUpdate = document.getElementById("btnUpdateKasir");
    const form = btnUpdate ? btnUpdate.closest("form") : null;

    if (btnUpdate && form) {
        btnUpdate.addEventListener("click", function(e) {
            e.preventDefault(); // cegah submit langsung

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
});
