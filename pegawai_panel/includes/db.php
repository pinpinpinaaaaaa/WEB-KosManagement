<?php
// Database Connection
$host = "localhost"; // Ganti sesuai konfigurasi server Anda
$username = "root"; // Username database
$password = ""; // Password database
$database = "kos"; // Nama database Anda

$con = mysqli_connect($host, $username, $password, $database);

// Cek koneksi
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
