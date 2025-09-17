ZEGARBOEGAR/
│
├── public/                     # semua file yang diakses langsung oleh browser
│   ├── index.php                # entry point utama / router sederhana
│   ├── about.php                # contoh halaman statis
│   ├── contact.php              # contoh halaman statis
│   │
│   ├── css/
│   │   └── app.css              # hasil build dari Tailwind (FINAL dipakai browser)
│   ├── js/
│   │   └── app.js               # (opsional) hasil build JS
│   ├── images/                  # gambar siap dipakai browser
│   └── favicon.ico
│
├── src/                         # kode utama aplikasi (server-side PHP)
│   ├── views/                   # potongan tampilan
│   │   ├── 
│   │   └── components/          # komponen kecil reusable
|   |       ├── header.php       # bagian <head> + navbar
|   |       └── footer.php       # bagian footer
│   │
│   ├── controllers/             # logika halaman (opsional, jika pakai routing)
│   │   ├── HomeController.php
│   │   └── AboutController.php
│   │
│   ├── models/                  # query database / ORM
│   │   └── User.php
│   │
│   ├── helpers/                 # fungsi utilitas (misal base_url, sanitizer)
│   │   └── functions.php
│   │
│   └── config/                  # konfigurasi aplikasi
│       ├── database.php
│       └── app.php
│
├── assets/                      # sumber mentah untuk build (developer only)
│   ├── css/
│   │   └── input.css            # file input Tailwind (@tailwind base; ...)
│   └── js/
│       └── main.js              # source JS mentah (sebelum build)
│
├── storage/                     # file runtime (data yang berubah)
│   ├── uploads/                 # hasil upload user
│   ├── logs/                    # error log / custom log
│   └── cache/                   # cache file sementara
│
├── vendor/                      # dependency PHP (Composer)
│
├── tailwind.config.js           # konfigurasi Tailwind
├── package.json                 # dependency npm (tailwind, alpine.js, dsb.)
├── composer.json                # dependency PHP (misal PHPMailer, dotenv)
├── .gitignore
└── README.md
