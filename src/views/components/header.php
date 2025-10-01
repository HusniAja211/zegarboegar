<?php 
require_once __DIR__ . '/../../helpers/SessionManager.php';
SessionManager::start();


if (!SessionManager::isLoggedIn()) {
  header("Location: /login?error=unauthorized");
  exit;
}

$kasir = SessionManager::kasir();

var_dump($_SESSION);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Zegar Boegar'; ?></title>
    <link rel="stylesheet" href="/css/app.css">
</head>

<body>

    <!-- Navbar (Statis) -->
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
            <!-- Logo / Nama Aplikasi -->
            <div class="text-xl font-bold text-blue-700">Kasir Digital</div>

            <!-- Menu Navigasi -->
            <ul class="hidden md:flex space-x-6 text-gray-700 font-medium">
                <li><a href="#" class="hover:text-blue-600">Dashboard</a></li>
                <li><a href="#" class="hover:text-blue-600">Transaksi</a></li>
                <li><a href="#" class="hover:text-blue-600">Produk</a></li>
                <li><a href="#" class="hover:text-blue-600">Laporan</a></li>
                <li>
                    <form action="/logout" method="post" style="display:inline;">
                        <button type="submit" class="hover:text-blue-600">Logout</button>
                    </form>
                </li>
            </ul>

            <!-- Profil User -->
            <div class="relative group">
                <button class="flex items-center space-x-2 focus:outline-none">
                    <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white">K</div>
                    <span class="font-medium">Kasir Toko</span>
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <!-- Dropdown -->
                <div
                    class="absolute right-0 mt-2 w-40 bg-white border rounded-lg shadow-lg hidden group-hover:block">
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100">Pengaturan</a>
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100">Logout</a>
                </div>
            </div>
        </div>
    </nav>
    <main class="bg-gray-50 text-gray-800">