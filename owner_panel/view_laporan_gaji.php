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
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Laporan Gaji</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
</head>

<body>
    <div class="container">
        <ol class="breadcrumb">
            <li><a href="index.php?dashboard">Dashboard</a></li>
            <li class="active">View Laporan Gaji</li>
        </ol>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-money"></i> Daftar Laporan Gaji</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="laporanGajiTable" class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>ID Gaji</th>
                                <th>ID Pegawai</th>
                                <th>Bulan</th>
                                <th>Jumlah Gaji</th>
                                <th>Status Pembayaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $get_gaji = "SELECT * FROM GAJI_PEGAWAI";
                            $run_gaji = mysqli_query($con, $get_gaji);

                            if (mysqli_num_rows($run_gaji) > 0) {
                                while ($row_gaji = mysqli_fetch_assoc($run_gaji)) {
                                    $id_gaji = $row_gaji['id_gaji'];
                                    $id_pegawai = $row_gaji['id_pegawai'];
                                    $bulan = $row_gaji['bulan'];
                                    $jumlah_gaji = number_format($row_gaji['jumlah_gaji'], 0, ',', '.');
                                    $status_pembayaran = $row_gaji['status_pembayaran'];
                                    ?>
                                    <tr>
                                        <td><?php echo $id_gaji; ?></td>
                                        <td><?php echo $id_pegawai; ?></td>
                                        <td><?php echo $bulan; ?></td>
                                        <td>Rp <?php echo $jumlah_gaji; ?></td>
                                        <td><?php echo $status_pembayaran; ?></td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "<tr><td colspan='7' class='text-center'>Tidak ada data laporan gaji.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#laporanGajiTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true
            });
        });
    </script>
</body>

</html>