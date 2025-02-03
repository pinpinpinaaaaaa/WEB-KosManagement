<?php

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

// Fungsi untuk mendapatkan ID baru
function get_new_id($con)
{
    $query = "SELECT id_laporan FROM laporan_pemasukan_pengeluaran ORDER BY id_laporan DESC LIMIT 1";
    $result = mysqli_query($con, $query);
    if (!$result) {
        die("Error pada query get_new_id: " . mysqli_error($con));
    }
    if ($row = mysqli_fetch_assoc($result)) {
        $last_id = $row['id_laporan'];
        $number = (int) substr($last_id, 1);
        return 'L' . str_pad($number + 1, 3, '0', STR_PAD_LEFT);
    }
    return 'L001';
}

// Fungsi untuk menambahkan data ke tabel laporan
function insert_to_laporan($con, $tanggal, $jenis, $sumber, $deskripsi, $jumlah, $id_customer = null, $id_operasional = null, $id_gaji = null)
{
    // Cek apakah laporan dengan id_customer sudah ada
    $check_query = "SELECT * FROM laporan_pemasukan_pengeluaran WHERE id_transaksi_customer = '$id_customer'";
    $check_result = mysqli_query($con, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Jika sudah ada, tidak perlu memasukkan data lagi
        return false;
    }

    $id = get_new_id($con);

    // Atur nilai default untuk kunci asing jika null
    $id_customer = $id_customer ? "'$id_customer'" : "NULL";
    $id_operasional = $id_operasional ? "'$id_operasional'" : "NULL";
    $id_gaji = $id_gaji ? "'$id_gaji'" : "NULL";

    $query = "INSERT INTO laporan_pemasukan_pengeluaran 
              (id_laporan, tanggal, jenis_laporan, sumber_transaksi, deskripsi, jumlah_biaya, id_transaksi_customer, id_transaksi_operasional, id_gaji_pegawai)
              VALUES ('$id', '$tanggal', '$jenis', '$sumber', '$deskripsi', '$jumlah', $id_customer, $id_operasional, $id_gaji)";

    if (mysqli_query($con, $query)) {
        return true;
    } else {
        return false;
    }
}

// Ambil data dari tabel transaksi_customer yang statusnya 'Lunas'
$query_customer = "SELECT * FROM transaksi_customer WHERE status_pembayaran = 'Lunas'";
$result_customer = mysqli_query($con, $query_customer);
if (!$result_customer) {
    die("Error pada query transaksi_customer: " . mysqli_error($con));
}

while ($row = mysqli_fetch_assoc($result_customer)) {
    $tanggal = $row['tanggal_transaksi'];
    $jenis = 'Pemasukan';
    $sumber = 'Transaksi Customer';
    $deskripsi = $row['deskripsi_transaksi'];
    $jumlah = $row['jumlah_biaya'];
    $id_customer = $row['id_transaksi'];

    // Panggil fungsi untuk menambahkan data ke laporan
    insert_to_laporan($con, $tanggal, $jenis, $sumber, $deskripsi, $jumlah, $id_customer);
}

// Ambil data dari tabel transaksi_operasional
$query_operasional = "SELECT * FROM transaksi_operasional";
$result_operasional = mysqli_query($con, $query_operasional);
if (!$result_operasional) {
    die("Error pada query transaksi_operasional: " . mysqli_error($con));
}

while ($row = mysqli_fetch_assoc($result_operasional)) {
    $tanggal = $row['tanggal_transaksi'];
    $jenis = 'Pengeluaran';
    $sumber = 'Transaksi Operasional';
    $deskripsi = $row['deskripsi_transaksi'];
    $jumlah = $row['jumlah_biaya'];
    $id_operasional = $row['id_transaksi_operasional'];

    // Panggil fungsi untuk menambahkan data ke laporan
    insert_to_laporan($con, $tanggal, $jenis, $sumber, $deskripsi, $jumlah, null, $id_operasional);
}

// Ambil data dari tabel gaji_pegawai yang status pembayarannya 'Lunas'
$query_gaji = "SELECT * FROM gaji_pegawai WHERE status_pembayaran = 'Lunas'";
$result_gaji = mysqli_query($con, $query_gaji);
if (!$result_gaji) {
    die("Error pada query gaji_pegawai: " . mysqli_error($con));
}

while ($row = mysqli_fetch_assoc($result_gaji)) {
    $tanggal = $row['bulan']; // Menggunakan bulan sebagai tanggal transaksi
    $jenis = 'Pengeluaran';
    $sumber = 'Gaji Pegawai';
    $deskripsi = "Gaji untuk pegawai ID: " . $row['id_pegawai'];
    $jumlah = $row['jumlah_gaji'];
    $id_gaji = $row['id_gaji'];

    // Panggil fungsi untuk menambahkan data ke laporan
    insert_to_laporan($con, $tanggal, $jenis, $sumber, $deskripsi, $jumlah, null, null, $id_gaji);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Laporan Pemasukan dan Pengeluaran</title>

    <!-- Tambahkan CSS DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

    <!-- Tambahkan jQuery dan DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
</head>

<body>
    <div class="container">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li><a href="index.php?dashboard">Dashboard</a></li>
            <li class="active">View Laporan Pemasukan dan Pengeluaran</li>
        </ol>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-money"></i> Daftar Laporan Pemasukan dan Pengeluaran</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="laporanTable" class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>ID Laporan</th>
                                <th>Tanggal</th>
                                <th>Jenis Laporan</th>
                                <th>Sumber Transaksi</th>
                                <th>Deskripsi</th>
                                <th>Jumlah Biaya</th>
                                <th>ID Transaksi Customer</th>
                                <th>ID Transaksi Operasional</th>
                                <th>ID Gaji Pegawai</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Query untuk mendapatkan data laporan
                            $get_laporan = "SELECT * FROM laporan_pemasukan_pengeluaran";
                            $run_laporan = mysqli_query($con, $get_laporan);

                            if (mysqli_num_rows($run_laporan) > 0) {
                                while ($row_laporan = mysqli_fetch_assoc($run_laporan)) {
                                    $id_laporan = $row_laporan['id_laporan'];
                                    $tanggal = date('d-m-Y', strtotime($row_laporan['tanggal']));
                                    $jenis_laporan = $row_laporan['jenis_laporan'];
                                    $sumber_transaksi = $row_laporan['sumber_transaksi'];
                                    $deskripsi = $row_laporan['deskripsi'];
                                    $jumlah_biaya = number_format($row_laporan['jumlah_biaya'], 0, ',', '.');
                                    $id_transaksi_customer = $row_laporan['id_transaksi_customer'];
                                    $id_transaksi_operasional = $row_laporan['id_transaksi_operasional'];
                                    $id_gaji_pegawai = $row_laporan['id_gaji_pegawai'];
                                    ?>
                                    <tr>
                                        <td><?php echo $id_laporan; ?></td>
                                        <td><?php echo $tanggal; ?></td>
                                        <td><?php echo $jenis_laporan; ?></td>
                                        <td><?php echo $sumber_transaksi; ?></td>
                                        <td><?php echo $deskripsi; ?></td>
                                        <td>Rp <?php echo $jumlah_biaya; ?></td>
                                        <td><?php echo $id_transaksi_customer; ?></td>
                                        <td><?php echo $id_transaksi_operasional; ?></td>
                                        <td><?php echo $id_gaji_pegawai; ?></td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "<tr><td colspan='11' class='text-center'>Tidak ada data laporan pemasukan/pengeluaran.</td></tr>";
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
            $('#laporanTable').DataTable({
                "paging": true, // Mengaktifkan fitur pagination
                "searching": true, // Mengaktifkan fitur pencarian
                "ordering": true, // Mengaktifkan fitur sortir
                "info": true, // Menampilkan info jumlah data
                "dom": 'Bfrtip', // Menambahkan tombol export dan print
                "buttons": [
                    {
                        extend: 'excelHtml5',
                        title: 'Laporan Pemasukan dan Pengeluaran'
                    },
                    {
                        extend: 'print',
                        title: 'Laporan Pemasukan dan Pengeluaran'
                    }
                ]
            });
        });
    </script>
</body>

</html>