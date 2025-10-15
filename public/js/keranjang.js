// File: /js/keranjang.js

document.addEventListener('DOMContentLoaded', () => {
  // === 1️⃣ CEK APAKAH HALAMAN INI ADALAH KERANJANG ===
  const totalTagihanElement = document.getElementById('total-tagihan');

  // 🚨 Jika elemen utama keranjang tidak ditemukan → hentikan script
  if (!totalTagihanElement || !totalTagihanElement.dataset.totalKotor) {
    console.info('[Keranjang.js] Bukan halaman keranjang — script dilewati.');
    return;
  }

  console.log('[Keranjang.js] Halaman keranjang terdeteksi, inisialisasi...');

  // === 2️⃣ ELEMENT DOM YANG DIGUNAKAN ===
  const inputPoin = document.getElementById('input-potongan-poin');
  const inputUang = document.getElementById('input-uang');
  const kembalianElement = document.getElementById('kembalian');
  const btnCheckout = document.getElementById('btn-checkout');
  const inputMemberTelp = document.getElementById('input-member-telp');
  const memberInfoElement = document.getElementById('member-info');
  const poinSaatIniElement = document.getElementById('poin-saat-ini');

  // === 3️⃣ DATA AWAL DARI PHP ===
  const totalKotor = parseFloat(totalTagihanElement.dataset.totalKotor);
  const nilaiPoin = 1; // 1 poin = Rp 1

  // === 4️⃣ DATABASE MEMBER (contoh statis, nanti bisa dari API/PHP) ===
  const memberDatabase = {
    '081234567890': { nama: 'HusniNice1', poin: 50000 },
    '085000111222': { nama: 'Budi Santoso', poin: 12500 },
  };

  let maxPoin = 0;

  // === 5️⃣ FORMAT RUPIAH ===
  const formatRupiah = (angka) => {
    return 'Rp ' + Math.abs(angka).toLocaleString('id-ID');
  };

  // === 6️⃣ CARI MEMBER BERDASARKAN NOMOR TELEPON ===
  const cariMember = () => {
    const telp = inputMemberTelp.value.replace(/[^0-9]/g, '');
    const member = memberDatabase[telp];

    maxPoin = 0;
    inputPoin.value = 0;
    inputPoin.disabled = true;

    if (member) {
      maxPoin = member.poin;
      memberInfoElement.textContent = `Member: ${member.nama}`;
      poinSaatIniElement.textContent = `${formatRupiah(maxPoin)} (${maxPoin} Poin)`;
      poinSaatIniElement.dataset.poinNilai = maxPoin;
      inputPoin.disabled = false;
    } else {
      memberInfoElement.textContent = 'Member belum teridentifikasi.';
      poinSaatIniElement.textContent = `${formatRupiah(0)} (0 Poin)`;
      poinSaatIniElement.dataset.poinNilai = 0;
    }

    hitungPembayaran();
  };

  // === 7️⃣ HITUNG PEMBAYARAN SECARA DINAMIS ===
  const hitungPembayaran = () => {
    let potongan = parseFloat(inputPoin.value) || 0;
    let uangMasuk = parseFloat(inputUang.value) || 0;

    if (maxPoin > 0 && potongan > maxPoin) {
      potongan = maxPoin;
      inputPoin.value = maxPoin;
    }

    const totalTagihanBersih = totalKotor - potongan * nilaiPoin;

    if (potongan * nilaiPoin > totalKotor) {
      potongan = totalKotor / nilaiPoin;
      inputPoin.value = potongan;
    }

    const sisaKembalian = uangMasuk - totalTagihanBersih;

    totalTagihanElement.textContent = formatRupiah(totalTagihanBersih);

    if (sisaKembalian >= 0) {
      kembalianElement.textContent = formatRupiah(sisaKembalian);
      kembalianElement.closest('div').classList.remove('bg-red-50', 'border-red-300');
      kembalianElement.closest('div').classList.add('bg-blue-50', 'border-blue-300');
      btnCheckout.disabled = false;
    } else {
      kembalianElement.textContent = formatRupiah(sisaKembalian) + ' (Kurang)';
      kembalianElement.closest('div').classList.remove('bg-blue-50', 'border-blue-300');
      kembalianElement.closest('div').classList.add('bg-red-50', 'border-red-300');
      btnCheckout.disabled = true;
    }
  };

  // === 8️⃣ EVENT LISTENERS ===
  inputPoin.addEventListener('input', hitungPembayaran);
  inputUang.addEventListener('input', hitungPembayaran);
  inputMemberTelp.addEventListener('input', cariMember);

  // === 9️⃣ INISIALISASI AWAL ===
  cariMember();
  hitungPembayaran();
});
