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

// Mengecek apakah parameter 'id_transaksi' ada di URL
if (isset($_GET['id_transaksi'])) {
    $id_transaksi = $_GET['id_transaksi'];

    // Mengambil data transaksi berdasarkan ID
    $get_transaksi = "SELECT * FROM TRANSAKSI_CUSTOMER WHERE id_transaksi='$id_transaksi'";
    $run_transaksi = mysqli_query($con, $get_transaksi);
    if ($row_transaksi = mysqli_fetch_assoc($run_transaksi)) {
        // Menyimpan data transaksi dalam variabel
        $id_customer = $row_transaksi['id_customer'];
        $id_kamar = $row_transaksi['id_kamar'];
        $id_fasilitas = $row_transaksi['id_fasilitas'];
        $tanggal_transaksi = $row_transaksi['tanggal_transaksi'];
        $jenis_transaksi = $row_transaksi['jenis_transaksi'];
        $deskripsi_transaksi = $row_transaksi['deskripsi_transaksi'];
        $jumlah_biaya = $row_transaksi['jumlah_biaya'];
        $status_pembayaran = $row_transaksi['status_pembayaran'];
        $bukti_pembayaran = $row_transaksi['bukti_pembayaran'];
    }
} else {
    echo "<script>alert('ID Transaksi tidak ditemukan.'); window.open('index.php?view_laporan_transaksi_customer','_self');</script>";
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

$statusOptions = getEnumValues($con, 'transaksi_customer', 'status_pembayaran');


// Proses update data transaksi jika tombol 'update' ditekan
if (isset($_POST['update'])) {
    $id_customer = $_POST['id_customer'];
    $id_kamar = $_POST['id_kamar'];
    $id_fasilitas = !empty($_POST['id_fasilitas']) ? $_POST['id_fasilitas'] : null;
    $tanggal_transaksi = $_POST['tanggal_transaksi'];
    $jenis_transaksi = $_POST['jenis_transaksi'];
    $deskripsi_transaksi = $_POST['deskripsi_transaksi'];
    $jumlah_biaya = $_POST['jumlah_biaya'];
    $status_pembayaran = $_POST['status_pembayaran'];

    if (isset($_FILES['bukti_pembayaran']['name']) && $_FILES['bukti_pembayaran']['name'] != "") {
        $bukti_pembayaran = $_FILES['bukti_pembayaran']['name'];
        $tmp_name = $_FILES['bukti_pembayaran']['tmp_name'];
        $file_type = mime_content_type($tmp_name);

        // Validasi tipe file
        if (in_array($file_type, ['image/jpeg', 'image/png', 'image/gif'])) {
            move_uploaded_file($tmp_name, "images/bukti_pembayaran/$bukti_pembayaran");
        } else {
            echo "<script>alert('File yang diunggah harus berupa gambar (JPEG, PNG, GIF).')</script>";
            exit();
        }
    }

    // Query untuk memperbarui data transaksi
    $update_transaksi = "UPDATE TRANSAKSI_CUSTOMER 
                         SET id_kamar='$id_kamar', tanggal_transaksi='$tanggal_transaksi', 
                             jenis_transaksi='$jenis_transaksi', deskripsi_transaksi='$deskripsi_transaksi', 
                             jumlah_biaya='$jumlah_biaya', status_pembayaran='$status_pembayaran', 
                             bukti_pembayaran='$bukti_pembayaran', 
                             id_fasilitas=" . ($id_fasilitas ? "'$id_fasilitas'" : "NULL") . " 
                         WHERE id_transaksi='$id_transaksi'";

    // Menjalankan query update
    $run_update = mysqli_query($con, $update_transaksi);

    // Jika pembayaran Lunas, update nomor_kamar di tabel customer
    if ($status_pembayaran == 'Lunas') {
        $query_kamar = "SELECT nomor_kamar FROM persediaan_kamar WHERE id_kamar='$id_kamar'";
        $result_kamar = mysqli_query($con, $query_kamar);
        if ($row_kamar = mysqli_fetch_assoc($result_kamar)) {
            $nomor_kamar = $row_kamar['nomor_kamar'];
            $update_customer = "UPDATE customer SET nomor_kamar='$nomor_kamar' WHERE id_customer='$id_customer'";
            mysqli_query($con, $update_customer);
        }
        // **Update Status Reservasi Kamar Menjadi "Dikonfirmasi"**
        $update_reservasi = "UPDATE reservasi_kamar 
                             SET status_reservasi='Dikonfirmasi' 
                             WHERE id_kamar='$id_kamar' AND id_customer='$id_customer'";
        mysqli_query($con, $update_reservasi);
    }

    if ($run_update) {
        echo "<script>alert('Data transaksi berhasil diperbarui!'); window.open('index.php?view_laporan_transaksi_customer','_self');</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data transaksi. Error: " . mysqli_error($con) . "');</script>";
    }
}
?>

<body>
    <div class="container">
        <ol class="breadcrumb">
            <li><a href="index.php?dashboard">Dashboard</a></li>
            <li><a href="index.php?view_laporan_transaksi_customer">View Laporan Transaksi Customer</a></li>
            <li class="active">Edit Transaksi Customer</li>
        </ol>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> Edit Transaksi Customer</h3>
            </div>
            <div class="panel-body">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="id_customer">ID Customer</label>
                        <input type="text" name="id_customer" class="form-control" required
                            value="<?php echo $id_customer; ?>">
                    </div>
                    <div class="form-group">
                        <label for="id_kamar">ID Kamar</label>
                        <input type="text" name="id_kamar" class="form-control" value="<?php echo $id_kamar; ?>">
                    </div>
                    <?php if (!empty($id_fasilitas)) { ?>
                        <div class="form-group">
                            <label for="id_fasilitas">ID Fasilitas</label>
                            <input type="text" name="id_fasilitas" class="form-control"
                                value="<?php echo $id_fasilitas; ?>">
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="tanggal_transaksi">Tanggal Transaksi</label>
                        <input type="date" name="tanggal_transaksi" class="form-control" required
                            value="<?php echo $tanggal_transaksi; ?>">
                    </div>
                    <div class="form-group">
                        <label for="jenis_transaksi">Jenis Transaksi</label>
                        <input type="text" name="jenis_transaksi" class="form-control" required
                            value="<?php echo $jenis_transaksi; ?>">
                    </div>
                    <div class="form-group">
                        <label for="deskripsi_transaksi">Deskripsi Transaksi</label>
                        <textarea name="deskripsi_transaksi" class="form-control"
                            rows="3"><?php echo $deskripsi_transaksi; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="jumlah_biaya">Jumlah Biaya</label>
                        <input type="number" name="jumlah_biaya" class="form-control" required
                            value="<?php echo $jumlah_biaya; ?>">
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
                    <button type="submit" name="update" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>