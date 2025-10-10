<?php
require_once __DIR__ . '/../../helpers/SessionManager.php';
SessionManager::start();
if (!SessionManager::isLoggedIn()) {
    header("Location: /login?error=unauthorized");
    exit;
}
$kasir = SessionManager::kasir();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Zegar Boegar'; ?></title>
    <link rel="stylesheet" href="/css/app.css">
</head>

<body class="h-full flex flex-col min-h-screen">

    <!-- Navbar (Statis) -->
    <nav style="
    background-image: url('/images/outer_background/outer_background.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;" class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
            <!-- Logo / Nama Aplikasi -->
            <div class="text-xl font-bold text-blue-700">Kasir Digital</div>

            <!-- Menu Navigasi -->
            <ul class="hidden md:flex space-x-6 text-gray-700 font-medium">
                <li><a href="/dashboard" class="hover:text-blue-600">Dashboard</a></li>
                <li><a href="/kasir" class="hover:text-blue-600">Kasir</a></li>
                <li><a href="/transaksi" class="hover:text-blue-600">Transaksi</a></li>
                <li><a href="/produk" class="hover:text-blue-600">Produk</a></li>
                <li><a href="/laporan" class="hover:text-blue-600">Laporan</a></li>
                <li><a href="#" class="hover:text-blue-600">
                    <svg fill="#000000" version="1.1" id="Capa_1" 
                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
                    width="30px" height="30px" viewBox="0 0 902.86 902.86" xml:space="preserve" 
                    transform="rotate(0)matrix(-1, 0, 0, 1, 0, 0)">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier"><g><g>
                        <path d="M671.504,577.829l110.485-432.609H902.86v-68H729.174L703.128,179.2L0,178.697l74.753,399.129h596.751V577.829z M685.766,247.188l-67.077,262.64H131.199L81.928,246.756L685.766,247.188z"></path>
                        <path d="M578.418,825.641c59.961,0,108.743-48.783,108.743-108.744s-48.782-108.742-108.743-108.742H168.717 c-59.961,0-108.744,48.781-108.744,108.742s48.782,108.744,108.744,108.744c59.962,0,108.743-48.783,108.743-108.744 c0-14.4-2.821-28.152-7.927-40.742h208.069c-5.107,12.59-7.928,26.342-7.928,40.742 C469.675,776.858,518.457,825.641,578.418,825.641z M209.46,716.897c0,22.467-18.277,40.744-40.743,40.744 c-22.466,0-40.744-18.277-40.744-40.744c0-22.465,18.277-40.742,40.744-40.742C191.183,676.155,209.46,694.432,209.46,716.897z M619.162,716.897c0,22.467-18.277,40.744-40.743,40.744s-40.743-18.277-40.743-40.744c0-22.465,18.277-40.742,40.743-40.742 S619.162,694.432,619.162,716.897z"></path> 
                    </g></g></g></svg>
                </a></li>
            </ul>

            <div class="relative">
                <!-- Tombol -->
                <button id="userMenuBtn" class="flex items-center space-x-2 focus:outline-none">
                    <img src="<?= $kasir['pfp'] ?>" alt="Profile Picture"
                         class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white">
                    </img>
                    <span class="font-medium"><?= $kasir['nama'] ?></span>
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Dropdown -->
                <div id="userMenu"
                    class="absolute right-0 mt-2 w-40 bg-white border rounded-lg shadow-lg hidden">
                    <a href="<?= $kasir ? '/kasir/' . $kasir['id'] : '/login' ?>" class="block px-4 py-2 hover:bg-gray-100">Pengaturan</a>
                    <form action="/logout" method="post" style="display:inline;">
                        <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100">Logout</button>
                    </form>
                </div>
            </div>

        </div>
        </div>
        </div>
    </nav>