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

// Validasi parameter 'id_customer'
if (isset($_GET['id_customer'])) {
    $delete_id = $_GET['id_customer'];

    // Query untuk menghapus data customer
    $delete_customer = "DELETE FROM customer WHERE id_customer='$delete_id'";
    $run_delete = mysqli_query($con, $delete_customer);

    if ($run_delete) {
        echo "<script>alert('customer berhasil dihapus!')</script>";
        echo "<script>window.open('index.php?view_penghuni','_self')</script>";
    } else {
        echo "<script>alert('Gagal menghapus customer!')</script>";
        echo "<script>window.open('index.php?view_penghuni','_self')</script>";
    }
} else {
    echo "<script>alert('Akses tidak valid.')</script>";
    echo "<script>window.open('index.php?view_penghuni','_self')</script>";
}
?>