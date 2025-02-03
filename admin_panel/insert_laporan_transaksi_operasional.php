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

// Proses tambah transaksi operasional
if (isset($_POST['submit'])) {
    // Mengambil ID terakhir dari database
    $query_last_id = "SELECT id_transaksi_operasional FROM transaksi_operasional ORDER BY id_transaksi_operasional DESC LIMIT 1";
    $result_last_id = mysqli_query($con, $query_last_id);
    $row_last_id = mysqli_fetch_assoc($result_last_id);

    // Jika ada ID terakhir, buat ID baru, jika tidak mulai dari M001
    if ($row_last_id) {
        $last_id = $row_last_id['id_transaksi_operasional'];
        $number = (int) substr($last_id, 1); // Ambil angka dari ID terakhir
        $new_id = 'O' . str_pad($number + 1, 3, '0', STR_PAD_LEFT); // Format CXXX
    } else {
        $new_id = 'O001'; // ID pertama jika belum ada data
    }
    $jenis_transaksi = $_POST['jenis_transaksi'];
    $deskripsi_transaksi = $_POST['deskripsi_transaksi'];
    $jumlah_biaya = $_POST['jumlah_biaya'];
    $tanggal_transaksi = $_POST['tanggal_transaksi'];
    $id_pegawai = $_POST['id_pegawai'];

    // Query untuk menambahkan transaksi operasional ke dalam database
    $insert_transaksi = "INSERT INTO TRANSAKSI_OPERASIONAL 
                         (id_transaksi_operasional, jenis_transaksi, deskripsi_transaksi, jumlah_biaya, tanggal_transaksi, id_pegawai) 
                         VALUES ('$new_id', '$jenis_transaksi', '$deskripsi_transaksi', '$jumlah_biaya', '$tanggal_transaksi', '$id_pegawai')";

    $run_insert = mysqli_query($con, $insert_transaksi);

    if ($run_insert) {
        echo "<script>alert('Transaksi operasional berhasil ditambahkan!'); window.open('index.php?view_laporan_transaksi_operasional', '_self');</script>";
    } else {
        echo "<script>alert('Gagal menambahkan transaksi operasional.');</script>";
    }
}
?>
<body>
    <div class="container">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li><a href="index.php?dashboard">Dashboard</a></li>
            <li><a href="index.php?view_laporan_transaksi_operasional">View Laporan Transaksi Operasional</a></li>
            <li class="active">Insert Laporan Transaksi Operasional</li>
        </ol>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-plus"></i> Tambah Transaksi Operasional</h3>
            </div>
            <div class="panel-body">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="jenis_transaksi">Jenis Transaksi</label>
                        <input type="text" name="jenis_transaksi" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="deskripsi_transaksi">Deskripsi Transaksi</label>
                        <textarea name="deskripsi_transaksi" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="jumlah_biaya">Jumlah Biaya</label>
                        <input type="number" name="jumlah_biaya" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="tanggal_transaksi">Tanggal Transaksi</label>
                        <input type="date" name="tanggal_transaksi" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="id_pegawai">ID Pegawai</label>
                        <input type="text" name="id_pegawai" class="form-control" required>
                    </div>
                    <button type="submit" name="submit" class="btn btn-success">Tambah Transaksi</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
