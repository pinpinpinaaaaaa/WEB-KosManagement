<?php
// Memulai sesi
//session_start();

// Koneksi ke database
$con = mysqli_connect("localhost", "root", "", "kos");
if (!$con) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// Mengecek apakah admin sudah login
if (!isset($_SESSION['username'])) {
    echo "<script>window.open('../login.php', '_self')</script>";
    exit(); // Hentikan eksekusi kode lebih lanjut
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Fasilitas</title>

    <!-- Tambahkan CSS DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
</head>

<body>
    <div class="container">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li><a href="index.php">Dashboard</a></li>
            <li class="active">View Fasilitas</li>
        </ol>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-cogs"></i> Daftar Fasilitas</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="fasilitasTable" class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>ID Fasilitas</th>
                                <th>Nama Fasilitas</th>
                                <th>Deskripsi</th>
                                <th>Periode</th>
                                <th>Biaya per Penggunaan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Query untuk mendapatkan data fasilitas
                            $get_fasilitas = "SELECT * FROM FASILITAS";
                            $run_fasilitas = mysqli_query($con, $get_fasilitas);

                            if (mysqli_num_rows($run_fasilitas) > 0) {
                                while ($row_fasilitas = mysqli_fetch_assoc($run_fasilitas)) {
                                    $id_fasilitas = $row_fasilitas['id_fasilitas'];
                                    $nama_fasilitas = $row_fasilitas['nama_fasilitas'];
                                    $deskripsi_fasilitas = $row_fasilitas['deskripsi_fasilitas'];
                                    $periode = $row_fasilitas['periode'];
                                    $biaya_per_penggunaan = number_format($row_fasilitas['biaya_per_penggunaan'], 0, ',', '.');
                                    ?>
                                    <tr>
                                        <td><?php echo $id_fasilitas; ?></td>
                                        <td><?php echo $nama_fasilitas; ?></td>
                                        <td><?php echo $deskripsi_fasilitas; ?></td>
                                        <td><?php echo $periode; ?></td>
                                        <td>Rp <?php echo $biaya_per_penggunaan; ?></td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "<tr><td colspan='7' class='text-center'>Tidak ada data fasilitas.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Inisialisasi DataTables -->
    <script>
        $(document).ready(function () {
            $('#fasilitasTable').DataTable(); // Menambahkan DataTables
        });
    </script>
</body>

</html>