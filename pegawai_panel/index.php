<?php

session_start();
include("includes/db.php");


if (!isset($_SESSION['username'])) {
    echo "<script>window.open('../login.php','_self')</script>";
}
// Ambil data user dari sesi
$username_session = $_SESSION['username'];
$role_session = $_SESSION['role'];

// Query data user berdasarkan sesi
$get_user = "SELECT * FROM PEGAWAI WHERE username='$username_session'";
$run_user = mysqli_query($con, $get_user);

if ($run_user && $row_user = mysqli_fetch_assoc($run_user)) {
    $id_user = $row_user['id_pegawai'];
    $role = $row_user['role'];
} else {
    echo "<script>alert('Data user tidak ditemukan!');</script>";
    echo "<script>window.open('login.php','_self')</script>";
    exit();
}

// Statistik data dari database
$result = mysqli_query($con, "SELECT COUNT(*) AS count FROM customer");
if ($row = mysqli_fetch_assoc($result)) {
    $count_penghuni = $row['count'];
}

$result = mysqli_query($con, "SELECT COUNT(*) AS count FROM fasilitas");
if ($row = mysqli_fetch_assoc($result)) {
    $count_fasilitas = $row['count'];
}

$result = mysqli_query($con, "SELECT COUNT(*) AS count FROM PERSEDIAAN_KAMAR"); // Query untuk kamar
if ($row = mysqli_fetch_assoc($result)) {
    $count_rooms = $row['count']; // Isi nilai $count_rooms
}



?>

<!DOCTYPE html>
<html>

<head>
    <title>Pegawai Panel</title>
    <link href="../css/bs.css" rel="stylesheet">
    <link href="../css/gaya.css" rel="stylesheet">
    <link href="../font-awesome/css/font-awesome.min.css" rel="stylesheet">
</head>

<body>

    <div id="wrapper">
        <!-- wrapper Starts -->
        <?php include("includes/sidebar.php"); ?>


        <div id="page-wrapper">
            <!-- page-wrapper Starts -->

            <div class="container-fluid">
                <!-- container-fluid Starts -->

                <?php
                // Halaman dashboard
                if (isset($_GET['dashboard'])) {
                    include("dashboard.php");
                }

                // Halaman kamar
                if (isset($_GET['view_kamar'])) {
                    include("view_kamar.php");
                }

                // Halaman fasilitas
                if (isset($_GET['view_fasilitas'])) {
                    include("view_fasilitas.php");
                }

                // Halaman manajemen customer
                if (isset($_GET['view_penghuni'])) {
                    include("view_penghuni.php");
                }

                // Halaman manajemen maintenance
                if (isset($_GET['view_maintenance'])) {
                    include("view_maintenance.php");
                }

                if (isset($_GET['insert_maintenance'])) {
                    include("insert_maintenance.php");
                }

                // Profil admin
                if (isset($_GET['user_profile'])) {
                    include("user_profile.php");
                }
                ?>

            </div>
            <!-- container-fluid Ends -->

        </div>
        <!-- page-wrapper Ends -->

    </div>
    <!-- wrapper Ends -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <!-- Tambahkan pustaka JS jQuery dan DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
</body>

</html>

<?php
?>