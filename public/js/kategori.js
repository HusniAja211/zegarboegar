async function deleteKategoriAjax(id) {
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
    const response = await fetch(`/kategori/delete/${id}`, {
      method: 'POST',
      headers: {
        'Accept': 'application/json'
      },
      credentials: 'same-origin'
    });

    // kalau server redirect ke login, response.ok bisa false atau status 302
    if (response.redirected) {
      // arahkan browser
      window.location.href = response.url;
      return;
    }

    if (!response.ok) {
      // baca body (bisa berisi html redirect) -> tampilkan error
      const text = await response.text();
      throw new Error(text || 'Server error');
    }

    // sukses → redirect ke daftar kategori dengan flag success
    window.location.href = '/kategori?success=deleted';

  } catch (err) {
    Swal.fire({
      icon: 'error',
      title: 'Gagal menghapus',
      text: 'Terjadi kesalahan saat menghapus kategori. Cek konsol/network.'
    });
    console.error('Delete error:', err);
  }
}
