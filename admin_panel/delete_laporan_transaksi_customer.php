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

// Validasi parameter 'id_transaksi'
if (isset($_GET['id_transaksi'])) {
    $delete_id = $_GET['id_transaksi'];

    // Query untuk menghapus data transaksi
    $delete_transaksi = "DELETE FROM TRANSAKSI_CUSTOMER WHERE id_transaksi='$delete_id'";
    $run_delete = mysqli_query($con, $delete_transaksi);

    if ($run_delete) {
        echo "<script>alert('Transaksi berhasil dihapus!');</script>";
        echo "<script>window.open('index.php?view_laporan_transaksi_customer','_self');</script>";
    } else {
        echo "<script>alert('Gagal menghapus transaksi!');</script>";
        echo "<script>window.open('index.php?view_laporan_transaksi_customer','_self');</script>";
    }
} else {
    echo "<script>alert('Akses tidak valid.');</script>";
    echo "<script>window.open('index.php?view_laporan_transaksi_customer','_self');</script>";
}
?>
