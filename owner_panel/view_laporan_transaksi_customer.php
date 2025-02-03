<?php
// Memulai sesi
//session_start();

// Koneksi ke database
$con = mysqli_connect("localhost", "root", "", "kos");
if (!$con) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

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
    <title>View Laporan Transaksi Customer</title>

    <!-- Tambahkan CSS DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
</head>

<body>
    <div class="container">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li><a href="index.php?dashboard">Dashboard</a></li>
            <li class="active">View Laporan Transaksi Customer</li>
        </ol>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-cogs"></i> Daftar Transaksi Customer</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="transaksiCustomerTable" class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>ID Transaksi</th>
                                <th>ID Customer</th>
                                <th>ID Kamar</th>
                                <th>ID Fasilitas</th>
                                <th>Tanggal Transaksi</th>
                                <th>Jenis Transaksi</th>
                                <th>Deskripsi Transaksi</th>
                                <th>Jumlah Biaya</th>
                                <th>Status Pembayaran</th>
                                <th>Bukti Pembayaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Query untuk mendapatkan data transaksi
                            $get_transaksi = "SELECT * FROM TRANSAKSI_CUSTOMER";
                            $run_transaksi = mysqli_query($con, $get_transaksi);

                            if (mysqli_num_rows($run_transaksi) > 0) {
                                while ($row_transaksi = mysqli_fetch_assoc($run_transaksi)) {
                                    $id_transaksi = $row_transaksi['id_transaksi'];
                                    $id_customer = $row_transaksi['id_customer'];
                                    $id_kamar = $row_transaksi['id_kamar'];
                                    $id_fasilitas = $row_transaksi['id_fasilitas'];
                                    $tanggal_transaksi = $row_transaksi['tanggal_transaksi'];
                                    $jenis_transaksi = $row_transaksi['jenis_transaksi'];
                                    $deskripsi_transaksi = $row_transaksi['deskripsi_transaksi'];
                                    $jumlah_biaya = number_format($row_transaksi['jumlah_biaya'], 0, ',', '.');
                                    $status_pembayaran = $row_transaksi['status_pembayaran'];
                                    $bukti_pembayaran = $row_transaksi['bukti_pembayaran'];
                                    ?>
                                    <tr>
                                        <td><?php echo $id_transaksi; ?></td>
                                        <td><?php echo $id_customer; ?></td>
                                        <td><?php echo $id_kamar; ?></td>
                                        <td><?php echo $id_fasilitas; ?></td>
                                        <td><?php echo $tanggal_transaksi; ?></td>
                                        <td><?php echo $jenis_transaksi; ?></td>
                                        <td><?php echo $deskripsi_transaksi; ?></td>
                                        <td>Rp <?php echo $jumlah_biaya; ?></td>
                                        <td><?php echo $status_pembayaran; ?></td>
                                        <td>
                                            <?php if ($bukti_pembayaran) { ?>
                                                <img src="images/bukti_pembayaran/<?php echo $bukti_pembayaran ?>"
                                                    alt="Gambar Kamar" width="100">
                                            <?php } else { ?>
                                                <span>Gambar tidak tersedia</span>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "<tr><td colspan='12' class='text-center'>Tidak ada data transaksi.</td></tr>";
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
            $('#transaksiCustomerTable').DataTable({
                "paging": true,  // Mengaktifkan pagination
                "searching": true,  // Mengaktifkan fitur pencarian
                "ordering": true,  // Mengaktifkan fitur sortir
                "info": true,  // Menampilkan info total data
            });
        });
    </script>
</body>

</html>