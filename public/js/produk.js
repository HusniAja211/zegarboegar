document.addEventListener('alpine:init', () => {
    Alpine.data('produkPage', () => ({
        tab: '',
        products: [],
        loading: false,
        kategoriList: [],

        init() { // <-- Tambahkan init method jika Anda menggunakannya di x-init
        // Logic yang ingin dijalankan saat komponen Alpine diinisialisasi
        this.loadProducts('Semua'); // Contoh: Memuat kategori default
        },

        async loadProducts(kategori) {
            this.tab = kategori;
            this.loading = true;

            try {
                const res = await fetch(`/produk/api?kategori=${encodeURIComponent(kategori)}`);
                const data = await res.json();
                this.products = data;
                this.renderProducts();
            } catch (e) {
                console.error('Gagal memuat produk:', e);
            } finally {
                this.loading = false;
            }
        },

        changeTab(kategori) {
            if (this.tab !== kategori) {
                this.loadProducts(kategori);
            }
        },

        renderProducts() {
            const container = document.getElementById('produkContainer');
            container.innerHTML = '';

            if (!this.products || this.products.length === 0) {
                container.innerHTML = `<p class="text-center col-span-full text-gray-500">Tidak ada produk di kategori ini.</p>`;
                return;
            }

            const today = new Date();
            today.setHours(0, 0, 0, 0);

            this.products.forEach(p => {
                let expDate = null;
                if (p.kadaluarsa && /^\d{4}-\d{2}-\d{2}$/.test(p.kadaluarsa)) {
                    const [y, m, d] = p.kadaluarsa.split('-').map(Number);
                    expDate = new Date(y, m - 1, d);
                    expDate.setHours(0, 0, 0, 0);
                }

                const isOutOfStock = Number(p.stok) <= 0;
                const isExpired = expDate && expDate.getTime() <= today.getTime();
                const isUnavailable = isOutOfStock || isExpired;

                const btnClass = isUnavailable
                    ? 'bg-red-600 text-white cursor-not-allowed opacity-80'
                    : 'bg-indigo-600 hover:bg-indigo-700 transform group-hover:translate-y-[-2px]';
                const btnText = isUnavailable
                    ? (isExpired ? 'Kadaluarsa' : 'Stok Habis')
                    : 'Beli';
                const btnDisabled = isUnavailable ? 'disabled' : '';

                container.innerHTML += `
                    <div class="max-w-xs bg-white rounded-lg shadow-lg overflow-hidden transform hover:shadow-xl hover:scale-[1.02] transition duration-200 ease-in-out cursor-pointer group">
                        <div class="relative h-36 w-full">
                            <img class="w-full h-full object-cover transition duration-500 group-hover:opacity-90 ${isExpired ? 'grayscale' : ''}" 
                                src="${p.gambar}" 
                                alt="Gambar Produk ${p.nama}">
                        </div>

                        <div class="p-3">
                            <h4 class="text-lg font-bold text-gray-900 leading-tight mb-1 truncate">${p.nama}</h4>
                            <p class="text-[11px] text-gray-500 mb-2">Kode: ${p.kode}</p>

                            <div class="flex items-center justify-between mb-1">
                                <p class="text-xl font-extrabold text-indigo-600">${this.formatRupiah(p.harga)}</p>
                                <p class="text-xs font-semibold ${isOutOfStock ? 'text-red-700 bg-red-100' : 'text-green-700 bg-green-100'} px-2.5 py-0.5 rounded-full">
                                    Stok: ${p.stok}
                                </p>
                            </div>

                            ${p.kadaluarsa ? `
                                <p class="text-[11px] ${isExpired ? 'text-red-600 font-semibold' : 'text-gray-500'}">
                                    Kadaluarsa: ${new Date(p.kadaluarsa).toLocaleDateString('id-ID')}
                                </p>` : ''}
                        </div>

                        <div class="p-3 pt-0">
                            <button ${btnDisabled}
                                class="w-full ${btnClass} py-2 rounded-lg text-sm font-semibold uppercase tracking-wide transition duration-150 shadow-md ${isUnavailable ? 'shadow-red-300' : 'shadow-indigo-300'}">
                                ${btnText}
                            </button>

                            <a href="/produk/edit/${p.id_produk}" 
                                class="block w-full bg-yellow-400 hover:bg-yellow-500 text-gray-900 text-center py-2 rounded-lg text-sm font-semibold uppercase tracking-wide transition duration-150 shadow-md shadow-yellow-300">
                                ✏️ Edit Produk
                            </a>

                        </div>

                        <div class="p-2 bg-gray-50 text-center border-t border-gray-200">
                            <p class="text-[10px] text-gray-500 mb-0.5">Barcode:</p>
                            <div class="visual-barcode font-mono tracking-wider text-gray-600 text-xs">
                                ${p.barcode ?? '-'}
                            </div>
                        </div>
                    </div>
                `;
            });
        },

        formatRupiah(angka) {
            return 'Rp ' + angka.toLocaleString('id-ID');
        }
    }));
});

// ✅ SWEETALERT KONFIRMASI DELETE
async function confirmDelete(id) {
    const result = await Swal.fire({
        title: "Hapus Produk?",
        text: "Data produk akan dihapus secara permanen.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, hapus",
        cancelButtonText: "Batal",
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        reverseButtons: true
    });

    if (result.isConfirmed) {
        try {
            const response = await fetch(`/produk/delete/${id}`, { method: "POST" });

            if (response.redirected) {
                window.location.href = response.url;
                return;
            }

            Swal.fire({
                icon: "success",
                title: "Produk dihapus",
                text: "Produk berhasil dihapus dari sistem.",
                timer: 1500,
                showConfirmButton: false
            });
        } catch (error) {
            Swal.fire({
                icon: "error",
                title: "Gagal menghapus",
                text: "Terjadi kesalahan saat menghapus produk."
            });
        }
    }
}

// ✅ SWEETALERT GLOBAL UNTUK SUCCESS / ERROR
document.addEventListener("DOMContentLoaded", () => {
    const params = new URLSearchParams(window.location.search);

    if (params.has("success") || params.has("error")) {
        let icon = "info", title = "", text = "";

        if (params.get("success") === "created") {
            icon = "success";
            title = "Produk Ditambahkan!";
            text = "Data produk baru berhasil disimpan.";
        }

        if (params.get("success") === "deleted") {
            icon = "success";
            title = "Produk Dihapus!";
            text = "Produk berhasil dihapus dari sistem.";
        }

        if (params.get("error")) {
            icon = "error";
            title = "Gagal Menyimpan!";
            switch (params.get("error")) {
                case "invalid_file":
                    text = "Format gambar tidak valid. Gunakan JPG, PNG, atau WEBP.";
                    break;
                case "upload_failed":
                    text = "Upload gambar gagal. Coba lagi.";
                    break;
                case "save_failed":
                    text = "Terjadi kesalahan saat menyimpan ke database.";
                    break;
                case "unauthorized":
                    text = "Sesi kamu telah habis. Silakan login ulang.";
                    break;
                case "kode_exists":
                case "nama_exists":
                    text = "Kode atau nama produk telah dipakai.";
                    break;
                default:
                    text = "Terjadi kesalahan yang tidak diketahui.";
            }
        }

        Swal.fire({
            icon,
            title,
            text,
            confirmButtonColor: icon === "success" ? "#2563eb" : "#ef4444",
        }).then(() => {
            if (icon === "success") {
                // Hapus query param tanpa reload ulang
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        });
    }
});

//Confirm Delete Function
async function confirmDelete(id) {
    const result = await Swal.fire({
        title: "Hapus Produk?",
        text: "Data produk akan dihapus secara permanen.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, hapus",
        cancelButtonText: "Batal",
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        reverseButtons: true
    });

    if (result.isConfirmed) {
        try {
            const response = await fetch(`/produk/delete/${id}`, { method: "POST" });

            if (response.redirected) {
                window.location.href = response.url;
                return;
            }

            Swal.fire({
                icon: "success",
                title: "Produk dihapus",
                text: "Produk berhasil dihapus dari sistem.",
                timer: 1500,
                showConfirmButton: false
            });
        } catch (error) {
            Swal.fire({
                icon: "error",
                title: "Gagal menghapus",
                text: "Terjadi kesalahan saat menghapus produk."
            });
        }
    }
}