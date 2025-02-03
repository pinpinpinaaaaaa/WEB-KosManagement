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
$enum_values1 = get_enum_values($con, 'laporan_pemasukan_pengeluaran', 'jenis_laporan');
$enum_values2 = get_enum_values($con, 'laporan_pemasukan_pengeluaran', 'sumber_transaksi');

// Proses tambah laporan pemasukan/pengeluaran
if (isset($_POST['submit'])) {
    // Mengambil ID terakhir dari database
    $query_last_id = "SELECT id_laporan FROM laporan_pemasukan_pengeluaran ORDER BY id_laporan DESC LIMIT 1";
    $result_last_id = mysqli_query($con, $query_last_id);
    $row_last_id = mysqli_fetch_assoc($result_last_id);

    // Jika ada ID terakhir, buat ID baru, jika tidak mulai dari L001
    if ($row_last_id) {
        $last_id = $row_last_id['id_laporan'];
        $number = (int) substr($last_id, 1); // Ambil angka dari ID terakhir
        $new_id = 'L' . str_pad($number + 1, 3, '0', STR_PAD_LEFT); // Format CXXX
    } else {
        $new_id = 'L001'; // ID pertama jika belum ada data
    }
    $tanggal = $_POST['tanggal'];
    $jenis_laporan = $_POST['jenis_laporan'];
    $sumber_transaksi = $_POST['sumber_transaksi'];
    $deskripsi = $_POST['deskripsi'];
    $jumlah_biaya = $_POST['jumlah_biaya'];
    $id_transaksi_customer = $_POST['id_transaksi_customer'];
    $id_transaksi_operasional = $_POST['id_transaksi_operasional'];
    $id_gaji_pegawai = $_POST['id_gaji_pegawai'];

    // Query untuk menambahkan laporan pemasukan/pengeluaran ke dalam database
    $insert_laporan = "INSERT INTO LAPORAN_PEMASUKAN_PENGELUARAN 
                        (id_laporan, tanggal, jenis_laporan, sumber_transaksi, deskripsi, jumlah_biaya, id_transaksi_customer, id_transaksi_operasional, id_gaji_pegawai)
                        VALUES ('$new_id', '$tanggal', '$jenis_laporan', '$sumber_transaksi', '$deskripsi', '$jumlah_biaya', '$id_transaksi_customer', '$id_transaksi_operasional', '$id_gaji_pegawai')";

    $run_insert = mysqli_query($con, $insert_laporan);

    if ($run_insert) {
        echo "<script>alert('Data laporan pemasukan/pengeluaran berhasil ditambahkan!'); window.open('index.php?view_laporan_pemasukan_pengeluaran', '_self');</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data laporan pemasukan/pengeluaran.');</script>";
    }
}
?>
<body>
    <div class="container">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li><a href="index.php?dashboard">Dashboard</a></li>
            <li><a href="index.php?view_laporan_pemasukan_pengeluaran">View Laporan Pemasukan Pengeluaran</a></li>
            <li class="active">Insert Laporan Pemasukan Pengeluaran</li>
        </ol>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-plus"></i> Tambah Data Laporan Pemasukan/Pengeluaran</h3>
            </div>
            <div class="panel-body">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control" required>
                    </div>
                    <div class="form-group">
                            <label for="jenis_laporan">Jenis Laporan</label>
                            <select name="jenis_laporan" id="jenis_laporan" class="form-control" required>
                                <option value="">Pilih Jenis</option>
                                <?php
                                foreach ($enum_values1 as $value) {
                                    echo "<option value=\"$value\">$value</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="sumber_transaksi">Sumber Transaksi</label>
                            <select name="sumber_transaksi" id="sumber_transaksi" class="form-control" required>
                                <option value="">Pilih Sumber</option>
                                <?php
                                foreach ($enum_values2 as $value) {
                                    echo "<option value=\"$value\">$value</option>";
                                }
                                ?>
                            </select>
                        </div>
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="jumlah_biaya">Jumlah Biaya</label>
                        <input type="number" name="jumlah_biaya" id="jumlah_biaya" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="id_transaksi_customer">ID Transaksi Customer</label>
                        <input type="text" name="id_transaksi_customer" id="id_transaksi_customer" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="id_transaksi_operasional">ID Transaksi Operasional</label>
                        <input type="text" name="id_transaksi_operasional" id="id_transaksi_operasional" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="id_gaji_pegawai">ID Gaji Pegawai</label>
                        <input type="text" name="id_gaji_pegawai" id="id_gaji_pegawai" class="form-control">
                    </div>
                    <button type="submit" name="submit" class="btn btn-success">Tambah Data Laporan</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
