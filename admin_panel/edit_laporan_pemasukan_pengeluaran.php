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

// Mengecek apakah parameter 'id_laporan' ada di URL
if (isset($_GET['id_laporan'])) {
    $id_laporan = $_GET['id_laporan'];

    // Mengambil data laporan berdasarkan ID
    $get_laporan = "SELECT * FROM LAPORAN_PEMASUKAN_PENGELUARAN WHERE id_laporan='$id_laporan'";
    $run_laporan = mysqli_query($con, $get_laporan);
    if ($row_laporan = mysqli_fetch_assoc($run_laporan)) {
        // Menyimpan data laporan dalam variabel
        $tanggal = $row_laporan['tanggal'];
        $jenis_laporan = $row_laporan['jenis_laporan'];
        $sumber_transaksi = $row_laporan['sumber_transaksi'];
        $deskripsi = $row_laporan['deskripsi'];
        $jumlah_biaya = $row_laporan['jumlah_biaya'];
        $id_transaksi_customer = $row_laporan['id_transaksi_customer'];
        $id_transaksi_operasional = $row_laporan['id_transaksi_operasional'];
        $id_gaji_pegawai = $row_laporan['id_gaji_pegawai'];
    }
} else {
    echo "<script>alert('ID Laporan tidak ditemukan.'); window.open('index.php?view_laporan_pemasukan_pengeluaran', '_self');</script>";
    exit();
}

function getEnumValues($con, $table, $column)
{
    $query = "SHOW COLUMNS FROM `$table` LIKE '$column'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);

    $enumValues = str_replace(["enum(", ")", "'"], "", $row['Type']);
    return explode(",", $enumValues);
}

$jenisOptions = getEnumValues($con, 'laporan_pemasukan_pengeluaran', 'jenis_laporan');
$sumberOptions = getEnumValues($con, 'laporan_pemasukan_pengeluaran', 'sumber_transaksi');


// Proses update data laporan jika tombol 'update' ditekan
if (isset($_POST['update'])) {
    $tanggal = $_POST['tanggal'];
    $jenis_laporan = $_POST['jenis_laporan'];
    $sumber_transaksi = $_POST['sumber_transaksi'];
    $deskripsi = $_POST['deskripsi'];
    $jumlah_biaya = $_POST['jumlah_biaya'];
    $id_transaksi_customer = $_POST['id_transaksi_customer'];
    $id_transaksi_operasional = $_POST['id_transaksi_operasional'];
    $id_gaji_pegawai = $_POST['id_gaji_pegawai'];

    // Query untuk memperbarui data laporan
    $update_laporan = "UPDATE LAPORAN_PEMASUKAN_PENGELUARAN SET tanggal='$tanggal', jenis_laporan='$jenis_laporan', 
                        sumber_transaksi='$sumber_transaksi', deskripsi='$deskripsi', jumlah_biaya='$jumlah_biaya',
                        id_transaksi_customer='$id_transaksi_customer', id_transaksi_operasional='$id_transaksi_operasional', 
                        id_gaji_pegawai='$id_gaji_pegawai' WHERE id_laporan='$id_laporan'";

    $run_update = mysqli_query($con, $update_laporan);
    
    if ($run_update) {
        echo "<script>alert('Data laporan berhasil diperbarui!'); window.open('view_laporan_pemasukan_pengeluaran.php', '_self');</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data laporan.');</script>";
    }
}
?>

<body>
    <div class="container">
        <ol class="breadcrumb">
            <li><a href="index.php?dashboard">Dashboard</a></li>
            <li><a href="index.php?view_laporan_pemasukan_pengeluaran">View Laporan Pemasukan Pengeluaran</a></li>
            <li class="active">Edit Laporan Pemasukan Pengeluaran</li>
        </ol>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> Edit Data Laporan Pemasukan/Pengeluaran</h3>
            </div>
            <div class="panel-body">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control" required value="<?php echo $tanggal; ?>">
                    </div>
                    <div class="form-group">
                        <label for="jenis_laporan">Jenis Laporan</label>
                        <select name="jenis_laporan" id="jenis_laporan" class="form-control" required>
                            <option value="">Pilih Jenis Laporan</option>
                            <?php foreach ($jenisOptions as $option) { ?>
                                <option value="<?php echo $option; ?>" <?php if ($option == $status_pembayaran)
                                       echo 'selected'; ?>>
                                    <?php echo $option; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="sumber_transaksi">Sumber Transaksi</label>
                        <select name="sumber_transaksi" id="sumber_transaksi" class="form-control" required>
                            <option value="">Pilih Sumber Transaksi</option>
                            <?php foreach ($sumberOptions as $option) { ?>
                                <option value="<?php echo $option; ?>" <?php if ($option == $sumber_transaksi)
                                       echo 'selected'; ?>>
                                    <?php echo $option; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control" required><?php echo $deskripsi; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="jumlah_biaya">Jumlah Biaya</label>
                        <input type="number" name="jumlah_biaya" id="jumlah_biaya" class="form-control" required value="<?php echo $jumlah_biaya; ?>">
                    </div>
                    <div class="form-group">
                        <label for="id_transaksi_customer">ID Transaksi Customer</label>
                        <input type="text" name="id_transaksi_customer" id="id_transaksi_customer" class="form-control" value="<?php echo $id_transaksi_customer; ?>">
                    </div>
                    <div class="form-group">
                        <label for="id_transaksi_operasional">ID Transaksi Operasional</label>
                        <input type="text" name="id_transaksi_operasional" id="id_transaksi_operasional" class="form-control" value="<?php echo $id_transaksi_operasional; ?>">
                    </div>
                    <div class="form-group">
                        <label for="id_gaji_pegawai">ID Gaji Pegawai</label>
                        <input type="text" name="id_gaji_pegawai" id="id_gaji_pegawai" class="form-control" value="<?php echo $id_gaji_pegawai; ?>">
                    </div>
                    <button type="submit" name="update" class="btn btn-primary">Update Data Laporan</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
