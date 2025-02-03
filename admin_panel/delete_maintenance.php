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

// Validasi parameter 'id_maintenance'
if (isset($_GET['id_maintenance'])) {
    $delete_id = $_GET['id_maintenance'];

    // Query untuk menghapus data maintenance
    $delete_maintenance = "DELETE FROM maintenance WHERE id_maintenance='$delete_id'";
    $run_delete = mysqli_query($con, $delete_maintenance);

    if ($run_delete) {
        echo "<script>alert('Maintenance berhasil dihapus!')</script>";
        echo "<script>window.open('index.php?view_maintenance','_self')</script>";
    } else {
        echo "<script>alert('Gagal menghapus maintenance!')</script>";
        echo "<script>window.open('index.php?view_maintenance','_self')</script>";
    }
} else {
    echo "<script>alert('Akses tidak valid.')</script>";
    echo "<script>window.open('index.php?view_maintenance','_self')</script>";
}
?>
