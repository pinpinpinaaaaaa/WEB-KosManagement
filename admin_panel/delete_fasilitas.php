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

// Validasi parameter 'id_fasilitas'
if (isset($_GET['id_fasilitas'])) {
    $delete_id = $_GET['id_fasilitas'];

    // Query untuk menghapus data fasilitas
    $delete_fasilitas = "DELETE FROM FASILITAS WHERE id_fasilitas='$delete_id'";
    $run_delete = mysqli_query($con, $delete_fasilitas);

    if ($run_delete) {
        echo "<script>alert('Fasilitas berhasil dihapus!')</script>";
        echo "<script>window.open('index.php?view_fasilitas','_self')</script>";
    } else {
        echo "<script>alert('Gagal menghapus fasilitas!')</script>";
        echo "<script>window.open('index.php?view_fasilitas','_self')</script>";
    }
} else {
    echo "<script>alert('Akses tidak valid.')</script>";
    echo "<script>window.open('index.php?view_fasilitas','_self')</script>";
}
?>