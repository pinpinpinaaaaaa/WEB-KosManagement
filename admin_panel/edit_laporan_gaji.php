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

// Mengecek apakah parameter 'id_gaji' ada di URL
if (isset($_GET['id_gaji'])) {
    $id_gaji = $_GET['id_gaji'];

    // Mengambil data gaji berdasarkan ID
    $get_gaji = "SELECT * FROM GAJI_PEGAWAI WHERE id_gaji='$id_gaji'";
    $run_gaji = mysqli_query($con, $get_gaji);
    if ($row_gaji = mysqli_fetch_assoc($run_gaji)) {
        // Menyimpan data gaji dalam variabel
        $id_pegawai = $row_gaji['id_pegawai'];
        $bulan = $row_gaji['bulan'];
        $jumlah_gaji = $row_gaji['jumlah_gaji'];
        $status_pembayaran = $row_gaji['status_pembayaran'];
    }
} else {
    echo "<script>alert('ID Gaji tidak ditemukan.'); window.open('index.php?view_laporan_gaji', '_self');</script>";
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

$statusOptions = getEnumValues($con, 'gaji_pegawai', 'status_pembayaran');

// Proses update data gaji jika tombol 'update' ditekan
if (isset($_POST['update'])) {
    $bulan = $_POST['bulan'];
    $jumlah_gaji = $_POST['jumlah_gaji'];
    $status_pembayaran = $_POST['status_pembayaran'];

    // Query untuk memperbarui data gaji
    $update_gaji = "UPDATE GAJI_PEGAWAI SET bulan='$bulan', jumlah_gaji='$jumlah_gaji', status_pembayaran='$status_pembayaran' WHERE id_gaji='$id_gaji'";

    $run_update = mysqli_query($con, $update_gaji);
    
    if ($run_update) {
        echo "<script>alert('Data gaji berhasil diperbarui!'); window.open('view_laporan_gaji.php', '_self');</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data gaji.');</script>";
    }
}
?>
<body>
    <div class="container">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li><a href="index.php?dashboard">Dashboard</a></li>
            <li><a href="index.php?view_laporan_gaji">View Laporan Gaji</a></li>
            <li class="active">Edit Laporan Gaji</li>
        </ol>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> Edit Data Gaji Pegawai</h3>
            </div>
            <div class="panel-body">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="bulan">Bulan</label>
                        <input type="text" name="bulan" id="bulan" class="form-control" required value="<?php echo $bulan; ?>">
                    </div>
                    <div class="form-group">
                        <label for="jumlah_gaji">Jumlah Gaji</label>
                        <input type="number" name="jumlah_gaji" id="jumlah_gaji" class="form-control" required value="<?php echo $jumlah_gaji; ?>">
                    </div>
                    <div class="form-group">
                        <label for="status_pembayaran">Status Pembayaran</label>
                        <select name="status_pembayaran" id="status_pembayaran" class="form-control" required>
                            <option value="">Pilih Status</option>
                            <?php foreach ($statusOptions as $option) { ?>
                                <option value="<?php echo $option; ?>" <?php if ($option == $status_pembayaran)
                                       echo 'selected'; ?>>
                                    <?php echo $option; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <button type="submit" name="update" class="btn btn-primary">Update Data Gaji</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
