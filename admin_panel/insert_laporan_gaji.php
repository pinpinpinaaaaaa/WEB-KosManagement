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

// Ambil nilai enum untuk kolom status_customer
$enum_values = get_enum_values($con, 'gaji_pegawai', 'status_pembayaran');

// Proses tambah gaji
if (isset($_POST['submit'])) {
    // Mengambil ID terakhir dari database
    $query_last_id = "SELECT id_gaji FROM gaji_pegawai ORDER BY id_gaji DESC LIMIT 1";
    $result_last_id = mysqli_query($con, $query_last_id);
    $row_last_id = mysqli_fetch_assoc($result_last_id);

    // Jika ada ID terakhir, buat ID baru, jika tidak mulai dari G001
    if ($row_last_id) {
        $last_id = $row_last_id['id_gaji'];
        $number = (int) substr($last_id, 1); // Ambil angka dari ID terakhir
        $new_id = 'G' . str_pad($number + 1, 3, '0', STR_PAD_LEFT); // Format CXXX
    } else {
        $new_id = 'G001'; // ID pertama jika belum ada data
    }
    $id_pegawai = $_POST['id_pegawai'];
    $bulan = $_POST['bulan'];
    $jumlah_gaji = $_POST['jumlah_gaji'];
    $status_pembayaran = $_POST['status_pembayaran'];

    // Query untuk menambahkan gaji ke dalam database
    $insert_gaji = "INSERT INTO GAJI_PEGAWAI (id_gaji, id_pegawai, bulan, jumlah_gaji, status_pembayaran) 
                    VALUES ('$new_id', '$id_pegawai', '$bulan', '$jumlah_gaji', '$status_pembayaran')";

    $run_insert = mysqli_query($con, $insert_gaji);

    if ($run_insert) {
        echo "<script>alert('Data gaji berhasil ditambahkan!'); window.open('index.php?view_laporan_gaji', '_self');</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data gaji.');</script>";
    }
}
?>
<body>
    <div class="container">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li><a href="index.php?dashboard">Dashboard</a></li>
            <li><a href="index.php?view_laporan_gaji">View Laporan Gaji</a></li>
            <li class="active">Insert Laporan Gaji</li>
        </ol>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-plus"></i> Tambah Data Gaji Pegawai</h3>
            </div>
            <div class="panel-body">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="id_pegawai">ID Pegawai</label>
                        <input type="text" name="id_pegawai" id="id_pegawai" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="bulan">Bulan</label>
                        <input type="text" name="bulan" id="bulan" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="jumlah_gaji">Jumlah Gaji</label>
                        <input type="number" name="jumlah_gaji" id="jumlah_gaji" class="form-control" required>
                    </div>
                    <div class="form-group">
                            <label for="status_pembayaran">Status Customer</label>
                            <select name="status_pembayaran" id="status_pembayaran" class="form-control" required>
                                <option value="">Pilih Status</option>
                                <?php
                                foreach ($enum_values as $value) {
                                    echo "<option value=\"$value\">$value</option>";
                                }
                                ?>
                            </select>
                        </div>
                    <button type="submit" name="submit" class="btn btn-success">Tambah Data Gaji</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
