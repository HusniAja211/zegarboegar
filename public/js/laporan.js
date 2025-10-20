document.addEventListener("DOMContentLoaded", () => {
  const ctx = document.getElementById("chartKeuntungan");
  const tahunSelect = document.getElementById("tahun-select");
  const btnCetakTable = document.getElementById("btnCetakTable");
  const btnCetakGrafik = document.getElementById("btnCetakGrafik");
  let chartInstance = null;

  async function loadKeuntungan(tahun) {
    const res = await fetch(`/laporan/keuntungan?tahun=${tahun}`);
    const data = await res.json();

    const bulan = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"];
    if (chartInstance) chartInstance.destroy();

    chartInstance = new Chart(ctx, {
      type: "bar",
      data: {
        labels: bulan,
        datasets: [{
          label: `Total Keuntungan Tahun ${tahun}`,
          data: data.data,
          backgroundColor: "rgba(37, 99, 235, 0.6)",
          borderColor: "rgb(37, 99, 235)",
          borderWidth: 1,
        }],
      },
      options: {
        scales: { y: { beginAtZero: true } },
      },
    });
  }

  async function loadLaporanProduk(tahun) {
    const res = await fetch(`/laporan/penjualan?tahun=${tahun}`);
    const data = await res.json();

    const tbody = document.getElementById("tbodyLaporan");
    tbody.innerHTML = "";

    let totalPenjualan = 0, totalKeuntungan = 0, totalModal = 0;

    data.data.forEach((item, i) => {
      const row = document.createElement("tr");
      row.innerHTML = `
        <td class="border px-3 py-2 text-center">${i + 1}</td>
        <td class="border px-3 py-2">${item.nama_produk}</td>
        <td class="border px-3 py-2 text-center">${item.jumlah_terjual}</td>
        <td class="border px-3 py-2 text-right">Rp ${formatRupiah(item.total_penjualan)}</td>
        <td class="border px-3 py-2 text-right">Rp ${formatRupiah(item.total_keuntungan)}</td>
        <td class="border px-3 py-2 text-right">Rp ${formatRupiah(item.total_modal)}</td>`;
      tbody.appendChild(row);

      totalPenjualan += Number(item.total_penjualan);
      totalKeuntungan += Number(item.total_keuntungan);
      totalModal += Number(item.total_modal);
    });

    document.getElementById("totalPenjualan").textContent = "Rp " + formatRupiah(totalPenjualan);
    document.getElementById("totalKeuntungan").textContent = "Rp " + formatRupiah(totalKeuntungan);
    document.getElementById("totalModal").textContent = "Rp " + formatRupiah(totalModal);
  }

  function formatRupiah(angka) {
    return Number(angka).toLocaleString("id-ID");
  }

  tahunSelect.addEventListener("change", (e) => {
    const tahun = e.target.value;
    loadKeuntungan(tahun);
    loadLaporanProduk(tahun);
  });

  btnCetakTable.addEventListener("click", () => {
    const tahun = tahunSelect.value;
    window.open(`/laporan/cetakTablePDF?tahun=${tahun}`, "_blank");
  });

  btnCetakGrafik.addEventListener("click", async () => {
    const tahun = tahunSelect.value;
    const chartImage = chartInstance.toBase64Image();

    const formData = new FormData();
    formData.append("tahun", tahun);
    formData.append("chartBase64", chartImage);

    const response = await fetch("/laporan/cetakGrafikPDF", { method: "POST", body: formData });
    const blob = await response.blob();
    const url = window.URL.createObjectURL(blob);

    const a = document.createElement("a");
    a.href = url;
    a.download = `Grafik_Keuntungan_${tahun}.pdf`;
    a.click();

    window.URL.revokeObjectURL(url);
  });

  const defaultTahun = tahunSelect.value;
  loadKeuntungan(defaultTahun);
  loadLaporanProduk(defaultTahun);
});
