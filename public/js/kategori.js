// kategori.js
async function deleteKategoriAjax(id) {
  // Konfirmasi penghapusan
  const res = await Swal.fire({
    title: "Hapus kategori?",
    text: "Data kategori akan dihapus secara permanen.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Ya, hapus",
    cancelButtonText: "Batal",
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6"
  });

  if (!res.isConfirmed) return;

  try {
    // Kirim request hapus ke server
    const response = await fetch(`/kategori/delete/${id}`, {
      method: "POST",
      headers: { "Accept": "application/json" },
      credentials: "same-origin"
    });

    // Jika server redirect (misalnya ke login)
    if (response.redirected) {
      window.location.href = response.url;
      return;
    }

    // Parse respons JSON
    let result;
    try {
      result = await response.json();
    } catch {
      throw new Error("Respons server tidak valid.");
    }

    // Tangani jika ada error dari server
    if (!response.ok || result.error) {
      let msg = "Terjadi kesalahan.";

      switch (result.error) {
        case "unauthorized":
          msg = "Anda tidak memiliki izin. Silakan login kembali.";
          break;
        case "notfound":
          msg = "Kategori tidak ditemukan.";
          break;
        case "kategori_in_use":
          msg = "Kategori ini masih digunakan oleh produk dan tidak dapat dihapus.";
          break;
        case "delete_failed":
          msg = "Gagal menghapus kategori. Silakan coba lagi.";
          break;
        default:
          msg = result.message || msg;
      }

      await Swal.fire({
        icon: "error",
        title: "Gagal Menghapus",
        text: msg
      });

      if (result.error === "unauthorized") {
        window.location.href = "/login";
      }

      return;
    }

    // Jika sukses
    await Swal.fire({
      icon: "success",
      title: "Berhasil",
      text: "Kategori berhasil dihapus."
    });

    // Redirect dengan param unik agar tidak bentrok dengan member.js
    window.location.href = "/kategori?success=kategori_deleted";

  } catch (err) {
    console.error("Delete error:", err);
    Swal.fire({
      icon: "error",
      title: "Kesalahan Server",
      text: err.message || "Terjadi kesalahan tak terduga."
    });
  }
}

// === Jalankan sekali saat halaman dimuat ===
// Tampilkan notifikasi sukses setelah redirect
document.addEventListener("DOMContentLoaded", () => {
  const params = new URLSearchParams(window.location.search);
  if (params.get("success") === "kategori_deleted") {
    Swal.fire({
      icon: "success",
      title: "Berhasil",
      text: "Kategori telah dihapus.",
      timer: 1500,
      showConfirmButton: false
    });
    // Hapus parameter dari URL agar alert tidak muncul lagi saat refresh
    window.history.replaceState({}, document.title, "/kategori");
  }
});
