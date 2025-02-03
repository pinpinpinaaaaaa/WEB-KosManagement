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

// Validasi parameter 'id_laporan'
if (isset($_GET['id_laporan'])) {
    $delete_id = $_GET['id_laporan'];

    // Query untuk menghapus data laporan
    $delete_laporan = "DELETE FROM LAPORAN_PEMASUKAN_PENGELUARAN WHERE id_laporan='$delete_id'";
    $run_delete = mysqli_query($con, $delete_laporan);

    if ($run_delete) {
        echo "<script>alert('Data laporan berhasil dihapus!');</script>";
        echo "<script>window.open('index.php?view_laporan_pemasukan_pengeluaran', '_self');</script>";
    } else {
        echo "<script>alert('Gagal menghapus data laporan.');</script>";
        echo "<script>window.open('index.php?view_laporan_pemasukan_pengeluaran', '_self');</script>";
    }
} else {
    echo "<script>alert('ID Laporan tidak ditemukan.');</script>";
    echo "<script>window.open('index.php?view_laporan_pemasukan_pengeluaran', '_self');</script>";
}
?>
