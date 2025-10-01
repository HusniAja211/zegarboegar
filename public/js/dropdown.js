function initDropdown(btnId, menuId) {
  const btn = document.getElementById(btnId);
  const menu = document.getElementById(menuId);

  if (btn && menu) {
    // toggle saat klik tombol
    btn.addEventListener("click", (e) => {
      e.stopPropagation();
      menu.classList.toggle("hidden");
    });

    // tutup kalau klik di luar
    document.addEventListener("click", (e) => {
      if (!btn.contains(e.target) && !menu.contains(e.target)) {
        menu.classList.add("hidden");
      }
    });
  }
}

document.addEventListener("DOMContentLoaded", () => {
  initDropdown("userMenuBtn", "userMenu");
});
