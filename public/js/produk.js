document.addEventListener('DOMContentLoaded', () => {
  const produkContainer = document.getElementById('produkContainer');
  const kategoriTabs = document.querySelectorAll('.kategori-tab');
  const loadingMessage = document.getElementById('loadingMessage');

   if (!produkContainer || kategoriTabs.length === 0 || !loadingMessage) {
    return;
  }

  let activeTab = kategoriTabs[0]?.dataset.kategori || 'Semua Produk';

  // --- Fungsi render card produk ---
  function renderProducts(products) {
    produkContainer.innerHTML = products.map(p => `
      <div class="bg-white shadow-lg rounded-xl p-5 text-center hover:shadow-xl transition-all space-y-3">
        <img src="${p.gambar}" alt="${p.nama}" class="w-32 h-32 mx-auto mb-3 object-cover rounded-lg">
        <h3 class="font-semibold text-lg text-indigo-700 mb-1">${p.nama}</h3>
        <p class="text-gray-600 mb-2">Rp ${Number(p.harga).toLocaleString()}</p>

        <button 
          class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition w-full"
          data-id="${p.id_produk}"
          data-action="add">
          🛒 Tambah ke Keranjang
        </button>

        <a href="/produk/edit/${p.id_produk}" style="background-color: green"
           class="block w-full hover:bg-green-600 text-white text-center py-2 rounded-lg text-sm font-semibold uppercase tracking-wide transition duration-150 shadow-md">
          ✏️ Edit Produk
        </a>

        <button 
          class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition w-full"
          data-id="${p.id_produk}"
          data-action="delete">
          🗑️ Hapus Data
        </button>
      </div>
    `).join('');
  }

  // --- Fungsi ambil data produk ---
  async function loadProducts(kategori) {
    loadingMessage.classList.remove('hidden');
    produkContainer.innerHTML = '';

    try {
      const res = await fetch(`/produk/api?kategori=${encodeURIComponent(kategori)}`);
      const data = await res.json();
      renderProducts(data);
    } catch (err) {
      console.error('Gagal memuat produk:', err);
      produkContainer.innerHTML = '<p class="text-center text-red-500">Gagal memuat produk.</p>';
    } finally {
      loadingMessage.classList.add('hidden');
    }
  }

  // --- Event klik kategori ---
  kategoriTabs.forEach(tab => {
    tab.addEventListener('click', () => {
      kategoriTabs.forEach(t => t.classList.remove('border-b-4', 'border-indigo-600', 'bg-indigo-100', 'text-indigo-800'));
      tab.classList.add('border-b-4', 'border-indigo-600', 'bg-indigo-100', 'text-indigo-800');

      activeTab = tab.dataset.kategori;
      loadProducts(activeTab);
    });
  });

  // --- Event klik tombol pada produk ---
  produkContainer.addEventListener('click', async (e) => {
    const btn = e.target.closest('button[data-id]');
    if (!btn) return;

    const idProduk = btn.dataset.id;
    const action = btn.dataset.action;

    // Tambah ke keranjang
    if (action === 'add') {
      try {
        const res = await fetch(`/keranjang/tambah/${idProduk}`, { method: 'GET' });
        const result = await res.json();

        if (result.status === 'success') {
          Swal.fire('Berhasil', 'Produk ditambahkan ke keranjang!', 'success');
        } else {
          Swal.fire('Gagal', result.message || 'Terjadi kesalahan', 'error');
        }
      } catch (err) {
        Swal.fire('Error', 'Tidak bisa menambahkan ke keranjang', 'error');
      }
    }

    // Hapus produk
    if (action === 'delete') {
      const confirm = await Swal.fire({
        title: "Hapus Produk?",
        text: "Data produk akan dihapus secara permanen.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, hapus",
        cancelButtonText: "Batal",
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
      });

      if (confirm.isConfirmed) {
        try {
          const response = await fetch(`/produk/delete/${idProduk}`, { method: 'POST' });
          if (response.ok) {
            Swal.fire({
              icon: "success",
              title: "Produk dihapus",
              text: "Produk berhasil dihapus dari sistem.",
              timer: 1500,
              showConfirmButton: false
            });
            // reload ulang produk
            loadProducts(activeTab);
          } else {
            Swal.fire('Gagal', 'Gagal menghapus produk.', 'error');
          }
        } catch (error) {
          Swal.fire('Error', 'Terjadi kesalahan saat menghapus produk.', 'error');
        }
      }
    }
  });

  // --- Load awal ---
  loadProducts(activeTab);
  kategoriTabs[0]?.classList.add('border-b-4', 'border-indigo-600', 'bg-indigo-100', 'text-indigo-800');

});
