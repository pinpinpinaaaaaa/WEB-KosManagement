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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Laporan Transaksi Operasional</title>

    <!-- Tambahkan CSS DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">

    <!-- Tambahkan jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Tambahkan JS DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
</head>

<body>
    <div class="container">
        <ol class="breadcrumb">
            <li><a href="index.php?dashboard">Dashboard</a></li>
            <li class="active">Laporan Transaksi Operasional</li>
        </ol>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-cogs"></i> Daftar Transaksi Operasional</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="transaksiOperasionalTable" class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>ID Transaksi</th>
                                <th>Jenis Transaksi</th>
                                <th>Deskripsi</th>
                                <th>Jumlah Biaya</th>
                                <th>Tanggal Transaksi</th>
                                <th>Nama Pegawai</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $get_transaksi = "SELECT * FROM TRANSAKSI_OPERASIONAL";
                            $run_transaksi = mysqli_query($con, $get_transaksi);

                            if (mysqli_num_rows($run_transaksi) > 0) {
                                while ($row_transaksi = mysqli_fetch_assoc($run_transaksi)) {
                                    $id_transaksi = $row_transaksi['id_transaksi_operasional'];
                                    $jenis_transaksi = $row_transaksi['jenis_transaksi'];
                                    $deskripsi_transaksi = $row_transaksi['deskripsi_transaksi'];
                                    $jumlah_biaya = number_format($row_transaksi['jumlah_biaya'], 0, ',', '.');
                                    $tanggal_transaksi = date('d-m-Y', strtotime($row_transaksi['tanggal_transaksi']));
                                    $nama_pegawai = $row_transaksi['nama_pegawai'];
                                    ?>
                                    <tr>
                                        <td><?php echo $id_transaksi; ?></td>
                                        <td><?php echo $jenis_transaksi; ?></td>
                                        <td><?php echo $deskripsi_transaksi; ?></td>
                                        <td>Rp <?php echo $jumlah_biaya; ?></td>
                                        <td><?php echo $tanggal_transaksi; ?></td>
                                        <td><?php echo $nama_pegawai; ?></td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "<tr><td colspan='8' class='text-center'>Tidak ada data transaksi operasional.</td></tr>";
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
            $('#transaksiOperasionalTable').DataTable({
                "paging": true, // Pagination
                "searching": true, // Fitur pencarian
                "ordering": true, // Sorting kolom
                "info": true, // Info jumlah data
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]], // Menampilkan jumlah data per halaman
                "order": [[4, "desc"]] // Default sorting berdasarkan tanggal transaksi (kolom ke-5, descending)
            });
        });
    </script>
</body>

</html>