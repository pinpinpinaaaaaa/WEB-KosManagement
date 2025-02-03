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

// Validasi parameter 'id_pegawai'
if (isset($_GET['id_pegawai'])) {
    $delete_id = $_GET['id_pegawai'];

    // Query untuk menghapus data pegawai
    $delete_pegawai = "DELETE FROM PEGAWAI WHERE id_pegawai='$delete_id'";
    $run_delete = mysqli_query($con, $delete_pegawai);

    if ($run_delete) {
        echo "<script>alert('Pegawai berhasil dihapus!')</script>";
        echo "<script>window.open('index.php?view_pegawai','_self')</script>";
    } else {
        echo "<script>alert('Gagal menghapus pegawai!')</script>";
        echo "<script>window.open('index.php?view_pegawai','_self')</script>";
    }
} else {
    echo "<script>alert('Akses tidak valid.')</script>";
    echo "<script>window.open('index.php?view_pegawai','_self')</script>";
}
?>