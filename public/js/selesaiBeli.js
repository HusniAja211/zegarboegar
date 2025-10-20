document.addEventListener('DOMContentLoaded', () => {
  const btnWA = document.getElementById('btnWA');
  if (!btnWA) return;

  const kode = btnWA.dataset.kode;

  btnWA.addEventListener('click', async () => {
    Swal.fire({
      title: 'Mengirim...',
      text: 'Sedang mengirim struk ke WhatsApp pelanggan...',
      allowOutsideClick: false,
      didOpen: () => Swal.showLoading()
    });

    try {
      const res = await fetch(`/transaksi/kirimWA/${encodeURIComponent(kode)}`);
      const text = await res.text();

      if (res.ok && text.includes('"status":true')) {
        Swal.fire({
          icon: 'success',
          title: 'Berhasil!',
          text: 'Struk berhasil dikirim ke WhatsApp pelanggan 🎉',
          confirmButtonColor: '#16a34a'
        });
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Gagal!',
          text: 'Terjadi kesalahan saat mengirim struk ke WhatsApp.',
          confirmButtonColor: '#d33'
        });
      }
    } catch (err) {
      Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: 'Tidak dapat terhubung ke server.',
        confirmButtonColor: '#d33'
      });
    }
  });
});
