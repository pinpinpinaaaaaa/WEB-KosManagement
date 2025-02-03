<?php
if (!isset($_SESSION['username'])) {
    echo "<script>window.open('../login.php', '_self')</script>";
    exit(); // Hentikan eksekusi kode lebih lanjut
}

// Koneksi ke database
$con = mysqli_connect("localhost", "root", "", "kos");
if (!$con) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// Validasi parameter 'id_gaji'
if (isset($_GET['id_gaji'])) {
    $delete_id = $_GET['id_gaji'];

    // Query untuk menghapus data gaji
    $delete_gaji = "DELETE FROM GAJI_PEGAWAI WHERE id_gaji='$delete_id'";
    $run_delete = mysqli_query($con, $delete_gaji);

    if ($run_delete) {
        echo "<script>alert('Data gaji berhasil dihapus!');</script>";
        echo "<script>window.open('index.php?view_laporan_gaji', '_self');</script>";
    } else {
        echo "<script>alert('Gagal menghapus data gaji.');</script>";
        echo "<script>window.open('index.php?view_laporan_gaji', '_self');</script>";
    }
}