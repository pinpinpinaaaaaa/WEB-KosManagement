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

// Validasi parameter 'id_kamar'
if (isset($_GET['id_kamar'])) {
    $delete_id = $_GET['id_kamar'];

    // Query untuk menghapus data kamar
    $delete_kamar = "DELETE FROM PERSEDIAAN_KAMAR WHERE id_kamar='$delete_id'";
    $run_delete = mysqli_query($con, $delete_kamar);

    if ($run_delete) {
        echo "<script>alert('Kamar berhasil dihapus!')</script>";
        echo "<script>window.open('index.php?view_kamar','_self')</script>";
    } else {
        echo "<script>alert('Gagal menghapus kamar!')</script>";
        echo "<script>window.open('index.php?view_kamar','_self')</script>";
    }
} else {
    echo "<script>alert('Akses tidak valid.')</script>";
    echo "<script>window.open('index.php?view_kamar','_self')</script>";
}
?>
