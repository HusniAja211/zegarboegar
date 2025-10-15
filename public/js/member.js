document.addEventListener('DOMContentLoaded', () => {
  const deleteButtons = document.querySelectorAll('.btn-delete-member');

  deleteButtons.forEach(btn => {
    btn.addEventListener('click', async (e) => {
      e.preventDefault();
      const id = btn.dataset.id;
      if (!id) return;

      const confirmRes = await Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: "Data member ini akan dihapus permanen.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
      });

      if (!confirmRes.isConfirmed) return;

      try {
        const res = await fetch(`/member/delete/${id}`, {
          method: 'POST',
          headers: {
            'X-Requested-With': 'XMLHttpRequest' // optional, memudahkan deteksi AJAX di server
          },
          credentials: 'same-origin'
        });

        // Jika server redirect (controller mengirim header Location dan fetch mengikuti),
        // response.redirected akan true dan response.url = lokasi akhir.
        if (res.redirected) {
          // langsung ikut redirect (controller biasanya meng-redirect ke /member?success=deleted)
          window.location.href = res.url;
          return;
        }

        // Kalau tidak redirect, coba parse JSON atau text untuk pesan
        let text = await res.text();
        // jika server mengembalikan HTML, kita reload halaman atau tampilkan pemberitahuan
        Swal.fire({
          icon: 'success',
          title: 'Selesai',
          text: 'Permintaan dikirim. Memuat ulang halaman...',
          timer: 1200,
          showConfirmButton: false
        }).then(() => location.reload());
      } catch (err) {
        console.error('Gagal hapus:', err);
        Swal.fire({
          icon: 'error',
          title: 'Gagal menghapus',
          text: 'Terjadi kesalahan saat menghapus data.'
        });
      }
    });
  });

  // Deteksi query param untuk notifikasi hasil (option)
  const params = new URLSearchParams(window.location.search);
  if (params.get('success') === 'deleted') {
    Swal.fire({
      icon: 'success',
      title: 'Berhasil',
      text: 'Member telah dihapus.',
      timer: 1400,
      showConfirmButton: false
    });
    // Hapus param dari URL agar pesan tidak muncul lagi
    window.history.replaceState({}, document.title, window.location.pathname);
  }
});
