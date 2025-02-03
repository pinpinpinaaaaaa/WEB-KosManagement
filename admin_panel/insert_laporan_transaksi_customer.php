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

// Fungsi untuk mendapatkan nilai enum dari database// Fungsi untuk mendapatkan nilai enum dari database
function get_enum_values($con, $table, $column)
{
    // Query untuk mendapatkan tipe kolom
    $query = "SHOW COLUMNS FROM $table LIKE '$column'";
    $result = mysqli_query($con, $query);
    if (!$result) {
        die("Error in query: " . mysqli_error($con));
    }
    $row = mysqli_fetch_assoc($result);

    // Pastikan tipe kolom ditemukan dan merupakan enum
    if ($row && strpos($row['Type'], 'enum') !== false) {
        // Ekstrak nilai enum dari string "enum('value1','value2',...)"
        $type = $row['Type'];
        preg_match("/^enum\((.*)\)$/", $type, $matches);
        if (isset($matches[1])) {
            $enum = str_getcsv($matches[1], ",", "'");
            return $enum; // Array nilai enum
        }
    }
    return []; // Jika tidak ditemukan atau bukan enum
}

// Ambil nilai enum untuk kolom 
$enum_values = get_enum_values($con, 'customer', 'status_penghuni');

// Proses tambah transaksi
if (isset($_POST['submit'])) {
    // Mengambil ID terakhir dari database
    $query_last_id = "SELECT id_transaksi FROM transaksi_customer ORDER BY id_transaksi DESC LIMIT 1";
    $result_last_id = mysqli_query($con, $query_last_id);
    $row_last_id = mysqli_fetch_assoc($result_last_id);

    // Jika ada ID terakhir, buat ID baru, jika tidak mulai dari T001
    if ($row_last_id) {
        $last_id = $row_last_id['id_transaksi'];
        $number = (int) substr($last_id, 1); // Ambil angka dari ID terakhir
        $new_id = 'T' . str_pad($number + 1, 3, '0', STR_PAD_LEFT); // Format CXXX
    } else {
        $new_id = 'T001'; // ID pertama jika belum ada data
    }
    $id_customer = $_POST['id_customer'];
    $id_kamar = $_POST['id_kamar'];
    $id_fasilitas = $_POST['id_fasilitas'];
    $tanggal_transaksi = $_POST['tanggal_transaksi'];
    $jenis_transaksi = $_POST['jenis_transaksi'];
    $deskripsi_transaksi = $_POST['deskripsi_transaksi'];
    $jumlah_biaya = $_POST['jumlah_biaya'];
    $status_pembayaran = $_POST['status_pembayaran'];
    // Upload Gambar
    $bukti_pembayaran = $_FILES['bukti_pembayaran']['name']; // Nama file gambar
    $temp_bukti_pembayaran = $_FILES['bukti_pembayaran']['tmp_name']; // Path sementara file
    $target_dir = "images/bukti_pembayaran/";
    ;

    // Memindahkan gambar ke folder yang sesuai
    move_uploaded_file($temp_bukti_pembayaran, $target_dir . $bukti_pembayaran);

    // Query untuk menambahkan transaksi ke dalam database
    $insert_transaksi = "INSERT INTO TRANSAKSI_CUSTOMER 
                         (id_transaksi, id_customer, id_kamar, id_fasilitas, tanggal_transaksi, jenis_transaksi, deskripsi_transaksi, jumlah_biaya, status_pembayaran, bukti_pembayaran) 
                         VALUES ('$new_id', '$id_customer', '$id_kamar', '$id_fasilitas', '$tanggal_transaksi', '$jenis_transaksi', '$deskripsi_transaksi', '$jumlah_biaya', '$status_pembayaran', '$bukti_pembayaran')";

    $run_insert = mysqli_query($con, $insert_transaksi);

    if ($run_insert) {
        // Periksa jika status pembayaran lunas dan jenis transaksi reservasi
        if ($status_pembayaran == 'Lunas' && $jenis_transaksi == 'Reservasi') {
            // Update status_reservasi di tabel reservasi_kamar menjadi Dikonfirmasi
            $update_reservasi = "UPDATE reservasi_kamar 
                                 SET status_reservasi = 'Dikonfirmasi' 
                                 WHERE id_customer = '$id_customer' AND id_kamar = '$id_kamar'";
                                 
            $run_update_reservasi = mysqli_query($con, $update_reservasi);
            
            if ($run_update_reservasi) {
                echo "<script>alert('Status reservasi berhasil diperbarui menjadi Dikonfirmasi.');</script>";
            } else {
                echo "<script>alert('Gagal memperbarui status reservasi.');</script>";
            }
        }
    
        echo "<script>alert('Transaksi berhasil ditambahkan!'); window.open('index.php?view_laporan_transaksi_customer', '_self');</script>";
    } else {
        echo "<script>alert('Gagal menambahkan transaksi.');</script>";
    }
}
?>

<body>
    <div class="container">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li><a href="index.php?dashboard">Dashboard</a></li>
            <li><a href="index.php?view_laporan_transaksi_customer">View Laporan Transaksi Customer</a></li>
            <li class="active">Insert Laporan Transaksi Customer</li>
        </ol>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-plus"></i> Tambah Transaksi Customer</h3>
            </div>
            <div class="panel-body">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="id_customer">ID Customer</label>
                        <input type="text" name="id_customer" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="id_kamar">ID Kamar</label>
                        <input type="text" name="id_kamar" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="id_fasilitas">ID Fasilitas</label>
                        <input type="text" name="id_fasilitas" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="tanggal_transaksi">Tanggal Transaksi</label>
                        <input type="date" name="tanggal_transaksi" class="form-control" required>
                    </div>
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
                        <label for="bukti_pembayaran">Bukti Pembayaran</label>
                        <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="status_pembayaran">Status Pembayaran</label>
                        <select name="status_pembayaran" id="status_pembayaran" class="form-control" required>
                            <option value="">Pilih Status</option>
                            <?php
                            foreach ($enum_values as $value) {
                                echo "<option value=\"$value\">$value</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" name="submit" class="btn btn-success">Tambah Transaksi</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>