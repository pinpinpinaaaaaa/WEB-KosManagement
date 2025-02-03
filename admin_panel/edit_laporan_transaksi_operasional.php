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

// Mengecek apakah parameter 'id_transaksi_operasional' ada di URL
if (isset($_GET['id_transaksi_operasional'])) {
    $id_transaksi = $_GET['id_transaksi_operasional'];

    // Mengambil data transaksi berdasarkan ID
    $get_transaksi = "SELECT * FROM TRANSAKSI_OPERASIONAL WHERE id_transaksi_operasional='$id_transaksi'";
    $run_transaksi = mysqli_query($con, $get_transaksi);
    if ($row_transaksi = mysqli_fetch_assoc($run_transaksi)) {
        // Menyimpan data transaksi dalam variabel
        $jenis_transaksi = $row_transaksi['jenis_transaksi'];
        $deskripsi_transaksi = $row_transaksi['deskripsi_transaksi'];
        $jumlah_biaya = $row_transaksi['jumlah_biaya'];
        $tanggal_transaksi = $row_transaksi['tanggal_transaksi'];
        $id_pegawai = $row_transaksi['id_pegawai'];
    }
} else {
    echo "<script>alert('ID Transaksi tidak ditemukan.'); window.open('index.php?view_laporan_transaksi','_self');</script>";
    exit();
}

// Proses update data transaksi jika tombol 'update' ditekan
if (isset($_POST['update'])) {
    $jenis_transaksi = $_POST['jenis_transaksi'];
    $deskripsi_transaksi = $_POST['deskripsi_transaksi'];
    $jumlah_biaya = $_POST['jumlah_biaya'];
    $tanggal_transaksi = $_POST['tanggal_transaksi'];
    $id_pegawai = $_POST['id_pegawai'];

    // Query untuk memperbarui data transaksi
    $update_transaksi = "UPDATE TRANSAKSI_OPERASIONAL SET jenis_transaksi='$jenis_transaksi', deskripsi_transaksi='$deskripsi_transaksi', 
                         jumlah_biaya='$jumlah_biaya', tanggal_transaksi='$tanggal_transaksi', id_pegawai='$id_pegawai' 
                         WHERE id_transaksi_operasional='$id_transaksi'";

    // Menjalankan query update
    $run_update = mysqli_query($con, $update_transaksi);
    
    if ($run_update) {
        echo "<script>alert('Data Transaksi berhasil diperbarui!'); window.open('index.php?view_laporan_transaksi','_self');</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data transaksi.');</script>";
    }
}
?>
<body>
    <div class="container">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li><a href="index.php?dashboard">Dashboard</a></li>
            <li><a href="index.php?view_laporan_transaksi_operasional">View Laporan Transaksi Operasional</a></li>
            <li class="active">Edit Transaksi Operasional</li>
        </ol>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> Edit Transaksi Operasional</h3>
            </div>
            <div class="panel-body">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="jenis_transaksi">Jenis Transaksi</label>
                        <input type="text" name="jenis_transaksi" id="jenis_transaksi" class="form-control" required value="<?php echo $jenis_transaksi; ?>">
                    </div>

                    <div class="form-group">
                        <label for="deskripsi_transaksi">Deskripsi Transaksi</label>
                        <textarea name="deskripsi_transaksi" id="deskripsi_transaksi" class="form-control" rows="4" required><?php echo $deskripsi_transaksi; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="jumlah_biaya">Jumlah Biaya</label>
                        <input type="number" name="jumlah_biaya" id="jumlah_biaya" class="form-control" required value="<?php echo $jumlah_biaya; ?>">
                    </div>

                    <div class="form-group">
                        <label for="tanggal_transaksi">Tanggal Transaksi</label>
                        <input type="date" name="tanggal_transaksi" id="tanggal_transaksi" class="form-control" required value="<?php echo $tanggal_transaksi; ?>">
                    </div>

                    <div class="form-group">
                        <label for="id_pegawai">ID Pegawai</label>
                        <input type="text" name="id_pegawai" id="id_pegawai" class="form-control" required value="<?php echo $id_pegawai; ?>">
                    </div>

                    <div class="form-group">
                        <button type="submit" name="update" class="btn btn-primary">Update Transaksi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
