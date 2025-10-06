document.addEventListener('DOMContentLoaded', () => {
    const deleteButtons = document.querySelectorAll('.btn-delete');

    deleteButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const id = btn.dataset.id;

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data kasir ini akan dihapus permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `/kasir/delete/${id}`;
                }
            });
        });
    });

    // Deteksi parameter di URL untuk alert hasil
    const params = new URLSearchParams(window.location.search);
    if (params.has('success') && params.get('success') === 'deleted') {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Kasir telah dihapus.',
            showConfirmButton: false,
            timer: 1800
        });
    } else if (params.has('error')) {
        let msg = 'Terjadi kesalahan.';
        if (params.get('error') === 'notfound') msg = 'Kasir tidak ditemukan.';
        if (params.get('error') === 'failed') msg = 'Gagal menghapus data.';
        if (params.get('error') === 'active') msg = 'Kasir aktif tidak dapat dihapus. Nonaktifkan terlebih dahulu.';

        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: msg,
            showConfirmButton: true
        });
    }
});
