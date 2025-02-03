<?php
if (!isset($_SESSION['username'])) {
    echo "<script>window.open('../login.php', '_self')</script>";
    exit(); // Hentikan eksekusi kode lebih lanjut
}

// Inisialisasi variabel
$count_penghuni = 0;
$count_pegawai = 0;
$count_fasilitas = 0;
$count_transaksi_customer = 0;
$count_gaji_pegawai = 0;
$total_keuangan = 0;
$count_rooms = 0;
$count_reservasi_pending = 0;
$count_reservasi_dikonfirmasi = 0;

// Lakukan query untuk mendapatkan data dari database
$result = mysqli_query($con, "SELECT COUNT(*) AS count FROM customer");
if ($row = mysqli_fetch_assoc($result)) {
    $count_penghuni = $row['count'];
}

$result = mysqli_query($con, "SELECT COUNT(*) AS count FROM pegawai");
if ($row = mysqli_fetch_assoc($result)) {
    $count_pegawai = $row['count'];
}

$result = mysqli_query($con, "SELECT COUNT(*) AS count FROM fasilitas");
if ($row = mysqli_fetch_assoc($result)) {
    $count_fasilitas = $row['count'];
}

$result = mysqli_query($con, "SELECT COUNT(*) AS count FROM transaksi_customer");
if ($row = mysqli_fetch_assoc($result)) {
    $count_transaksi_customer = $row['count'];
}

$result = mysqli_query($con, "SELECT COUNT(*) AS count FROM gaji_pegawai");
if ($row = mysqli_fetch_assoc($result)) {
    $count_gaji_pegawai = $row['count'];
}

$result = mysqli_query($con, "SELECT COUNT(*) AS count FROM PERSEDIAAN_KAMAR"); // Query untuk kamar
if ($row = mysqli_fetch_assoc($result)) {
    $count_rooms = $row['count']; // Isi nilai $count_rooms
}

$result = mysqli_query($con, "
    SELECT 
        SUM(CASE WHEN status_reservasi = 'Pending' THEN 1 ELSE 0 END) AS pending,
        SUM(CASE WHEN status_reservasi = 'Dikonfirmasi' THEN 1 ELSE 0 END) AS dikonfirmasi
    FROM RESERVASI_KAMAR
");

if ($row = mysqli_fetch_assoc($result)) {
    $count_reservasi_pending = $row['pending'];
    $count_reservasi_dikonfirmasi = $row['dikonfirmasi'];
}

$result = mysqli_query($con, "
    SELECT 
        SUM(CASE 
                WHEN jenis_laporan = 'Pemasukan' THEN jumlah_biaya
                WHEN jenis_laporan = 'Pengeluaran' THEN -jumlah_biaya
                ELSE 0 
            END) AS total
    FROM LAPORAN_PEMASUKAN_PENGELUARAN
");

// Ambil hasil query
$total_keuangan = 0;
if ($result && $row = mysqli_fetch_assoc($result)) {
    $total_keuangan = $row['total'];
}

?>

<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li class="active">
                <i class="fa fa-dashboard"></i> Dashboard
            </li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 col-md-10">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-clock-o fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"> <?php echo $count_reservasi_pending; ?> </div>
                        <div>Reservasi Pending</div>
                    </div>
                </div>
            </div>
            <a href="index.php?view_reservasi_pending">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>

    <div class="col-lg-4 col-md-10">
        <div class="panel panel-success">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-check fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"> <?php echo $count_reservasi_dikonfirmasi; ?> </div>
                        <div>Reservasi Dikonfirmasi</div>
                    </div>
                </div>
            </div>
            <a href="index.php?view_reservasi_dikonfirmasi">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>

    <div class="col-lg-4 col-md-10">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-bed fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"> <?php echo $count_rooms; ?> </div>
                        <div>Total Kamar</div>
                    </div>
                </div>
            </div>
            <a href="index.php?view_kamar">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-lg-4 col-md-6">
        <div class="panel panel-green">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-users fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"> <?php echo $count_penghuni; ?> </div>
                        <div>Total Penghuni</div>
                    </div>
                </div>
            </div>
            <a href="index.php?view_penghuni">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="panel panel-yellow">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"> <?php echo $count_pegawai; ?> </div>
                        <div>Total Pegawai</div>
                    </div>
                </div>
            </div>
            <a href="index.php?view_pegawai">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="panel panel-red">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-cogs fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"> <?php echo $count_fasilitas; ?> </div>
                        <div>Total Fasilitas</div>
                    </div>
                </div>
            </div>
            <a href="index.php?view_fasilitas">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 col-md-6">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-file-invoice-dollar fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"> <?php echo $count_transaksi_customer; ?> </div>
                        <div>Laporan Transaksi Customer</div>
                    </div>
                </div>
            </div>
            <a href="index.php?view_laporan_transaksi_customer">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="panel panel-success">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa-solid fa-wallet fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"> <?php echo $count_gaji_pegawai; ?> </div>
                        <div>Laporan Gaji Pegawai</div>
                    </div>
                </div>
            </div>
            <a href="index.php?view_laporan_gaji">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-line-chart fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"> <?php echo $total_keuangan; ?> </div>
                        <div>Laporan Keuangan</div>
                    </div>
                </div>
            </div>
            <a href="index.php?view_laporan_keuangan">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-wrench fa-fw"></i> Maintenance Kamar
                </h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ID Maintenance</th>
                                <th>ID Kamar</th>
                                <th>Tanggal Maintenance</th>
                                <th>Deskripsi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            $get_maintenance = "SELECT * FROM MAINTENANCE ORDER BY maintenance_date DESC LIMIT 0,5";
                            $run_maintenance = mysqli_query($con, $get_maintenance);

                            while ($row_maintenance = mysqli_fetch_array($run_maintenance)) {
                                $id_maintenance = $row_maintenance['id_maintenance'];
                                $id_kamar = $row_maintenance['id_kamar'];
                                $maintenance_date = $row_maintenance['maintenance_date'];
                                $description = $row_maintenance['description'];
                                $status = $row_maintenance['status'];
                                $i++;
                            ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $id_maintenance; ?></td>
                                <td><?php echo $id_kamar; ?></td>
                                <td><?php echo $maintenance_date; ?></td>
                                <td><?php echo $description; ?></td>
                                <td>
                                    <?php
                                    if ($status == 'Pending') {
                                        echo '<span class="label label-warning">Pending</span>';
                                    } else {
                                        echo '<span class="label label-success">Selesai</span>';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="text-right">
                    <a href="index.php?view_maintenance">
                        View All Maintenance <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel">
        </div>
    </div>
</div>


<?php 
 ?>
