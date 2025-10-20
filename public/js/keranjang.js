document.addEventListener('DOMContentLoaded', () => {
  const totalTagihanElement = document.getElementById('total-tagihan');
  if (!totalTagihanElement || !totalTagihanElement.dataset.totalKotor) {
    return;
  }

  const inputPoin = document.getElementById('input-potongan-poin');
  const inputUang = document.getElementById('input-uang');
  const kembalianElement = document.getElementById('kembalian');
  const btnCheckout = document.getElementById('btn-checkout');
  const inputMemberTelp = document.getElementById('input-member-telp');
  const memberInfoElement = document.getElementById('member-info');
  const poinSaatIniElement = document.getElementById('poin-saat-ini');
  const mainEl = document.querySelector('main');

  if (!mainEl) {
    console.error('[Keranjang.js] Elemen <main> tidak ditemukan.');
    return;
  }

  const totalKotor = parseFloat(totalTagihanElement.dataset.totalKotor) || 0;
  const nilaiPoin = 1;
  let maxPoin = 0;

  const formatRupiah = (angka) => 'Rp ' + Math.abs(angka).toLocaleString('id-ID');

  const cariMember = async () => {
  const telp = (inputMemberTelp?.value || '').replace(/[^0-9]/g, '');
  if (!telp) return;

  try {
    const res = await fetch(`/member/api?t=${encodeURIComponent(telp)}`);
    const data = await res.json();

    if (res.ok && data.status === 'success') {
      maxPoin = data.poin;
      memberInfoElement.textContent = `Member: ${data.nama}`;
      poinSaatIniElement.textContent = `Rp ${data.poin.toLocaleString()} (${data.poin} Poin)`;
      inputPoin.disabled = false;
    } else {
      memberInfoElement.textContent = 'Member tidak ditemukan.';
      poinSaatIniElement.textContent = 'Rp 0 (0 Poin)';
      inputPoin.disabled = true;
      maxPoin = 0;
    }

    hitungPembayaran();
  } catch (err) {
    console.error('Gagal fetch member:', err);
  }
};


  const hitungPembayaran = () => {
    let potongan = parseFloat(inputPoin?.value) || 0;
    let uangMasuk = parseFloat(inputUang?.value) || 0;

    if (maxPoin > 0 && potongan > maxPoin) {
      potongan = maxPoin;
      inputPoin.value = maxPoin;
    }

    const totalTagihanBersih = Math.max(0, totalKotor - potongan * nilaiPoin);
    const sisaKembalian = uangMasuk - totalTagihanBersih;

    totalTagihanElement.textContent = formatRupiah(totalTagihanBersih);

    if (sisaKembalian >= 0) {
      kembalianElement.textContent = formatRupiah(sisaKembalian);
      kembalianElement.closest('div')?.classList?.remove('bg-red-50', 'border-red-300');
      kembalianElement.closest('div')?.classList?.add('bg-blue-50', 'border-blue-300');
      btnCheckout.disabled = false;
    } else {
      kembalianElement.textContent = formatRupiah(sisaKembalian) + ' (Kurang)';
      kembalianElement.closest('div')?.classList?.remove('bg-blue-50', 'border-blue-300');
      kembalianElement.closest('div')?.classList?.add('bg-red-50', 'border-red-300');
      btnCheckout.disabled = true;
    }
  };

  // === Tombol Aksi (+, −, hapus) ===
  mainEl.addEventListener('click', async (e) => {
    const btn = e.target.closest('button[data-action][data-id]');
    if (!btn) return;

    const id = btn.dataset.id;
    const action = btn.dataset.action;

    try {
      let res;

      if (action === 'plus') {
        res = await fetch(`/keranjang/tambah/${encodeURIComponent(id)}`, { method: 'GET' });
      } 
      else if (action === 'minus') {
        res = await fetch(`/keranjang/kurangi/${encodeURIComponent(id)}`, { method: 'POST' });
      } 
      else if (action === 'delete') {
        // 🔥 Pakai SweetAlert2 untuk konfirmasi hapus
        const result = await Swal.fire({
          title: 'Hapus Produk?',
          text: 'Produk ini akan dihapus dari keranjang Anda.',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Ya, hapus',
          cancelButtonText: 'Batal',
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          reverseButtons: true
        });

        if (!result.isConfirmed) return;

        // Tampilkan loading sebelum fetch
        Swal.fire({
          title: 'Menghapus...',
          text: 'Mohon tunggu sebentar',
          didOpen: () => Swal.showLoading(),
          allowOutsideClick: false,
          allowEscapeKey: false,
          showConfirmButton: false
        });

        res = await fetch(`/keranjang/hapus/${encodeURIComponent(id)}`, { method: 'POST' });
      }

      if (res) {
        const data = await res.json();

        if (res.ok && data.status === 'success') {
          Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Jumlah Produk Berhasil Diubah.',
            timer: 1500,
            showConfirmButton: false
          }).then(() => {
            window.location.reload();
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: data.message || 'Terjadi kesalahan saat menghapus.',
          });
        }
      }

    } catch (err) {
      console.error('[Keranjang.js] Error:', err);
      Swal.fire({
        icon: 'error',
        title: 'Gagal Terhubung',
        text: 'Tidak dapat menghubungi server. Coba lagi nanti.',
      });
    }
  });

  // Tombol Checkout
  btnCheckout?.addEventListener('click', async () => {
  const telp = (inputMemberTelp?.value || '').replace(/[^0-9]/g, '');
  const poinDipakai = parseFloat(inputPoin?.value) || 0;
  const bayar = parseFloat(inputUang?.value) || 0;
  const totalTagihan = parseFloat(totalTagihanElement.dataset.totalKotor) || 0;

  try {
    Swal.fire({
      title: 'Memproses Pembayaran...',
      text: 'Mohon tunggu sebentar.',
      didOpen: () => Swal.showLoading(),
      allowOutsideClick: false,
      allowEscapeKey: false,
      showConfirmButton: false
    });

    const res = await fetch('/keranjang/checkout', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        telp,
        poinDipakai,
        bayar,
        totalTagihan
      })
    });

    const data = await res.json();

    if (res.ok && data.status === 'success') {
      Swal.fire({
        icon: 'success',
        title: 'Pembayaran Berhasil!',
        text: 'Anda akan diarahkan ke halaman struk.',
        timer: 1500,
        showConfirmButton: false
      }).then(() => {
          window.location.href = `/transaksi/selesai/${encodeURIComponent(data.kode_transaksi)}`;
      });
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Gagal Checkout',
        text: data.message || 'Terjadi kesalahan saat memproses transaksi.'
      });
    }
  } catch (err) {
    console.error('[Checkout Error]', err);
    Swal.fire({
      icon: 'error',
      title: 'Gagal Terhubung',
      text: 'Tidak dapat menghubungi server. Coba lagi nanti.'
    });
  }
});


  // === Input events ===
  inputPoin?.addEventListener('input', hitungPembayaran);
  inputUang?.addEventListener('input', hitungPembayaran);
  inputMemberTelp?.addEventListener('input', cariMember);

  // Init awal
  cariMember();
  hitungPembayaran();
});
