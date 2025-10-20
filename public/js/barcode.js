document.addEventListener('DOMContentLoaded', () => {
    const inputBarcode = document.getElementById('input-barcode');
    const cartContainer = document.getElementById('cart-items');
    const totalTagihan = document.getElementById('total-tagihan');

    // Saat tekan Enter di input barcode
    inputBarcode.addEventListener('keypress', async (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            const kode = inputBarcode.value.trim();
            if (!kode) return;

            // Kirim request ke backend
            try {
                const res = await fetch('/keranjang/tambahByKode', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ kode_produk: kode })
                });

                const data = await res.json();
                if (data.status === 'success') {
                    // Refresh tampilan keranjang tanpa reload seluruh halaman
                    await refreshKeranjang();
                    inputBarcode.value = ''; // Kosongkan input
                } else {
                    alert(data.message || 'Gagal menambah produk.');
                }
            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan koneksi.');
            }
        }
    });

    // Fungsi untuk memuat ulang isi keranjang
    async function refreshKeranjang() {
        try {
            const res = await fetch('/keranjang'); // reload halaman via partial
            const text = await res.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(text, 'text/html');

            // Ganti bagian daftar item & total
            const newCart = doc.querySelector('#cart-items');
            const newTotal = doc.querySelector('#total-tagihan');

            if (newCart && newTotal) {
                cartContainer.innerHTML = newCart.innerHTML;
                totalTagihan.innerHTML = newTotal.innerHTML;
            }
        } catch (error) {
            console.error('Gagal refresh keranjang:', error);
        }
    }
});
