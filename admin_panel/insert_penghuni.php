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
$enum_values = get_enum_values($con, 'customer', 'status_penghuni');

// Proses tambah customer
if (isset($_POST['submit'])) {
    // Mengambil ID terakhir dari database
    $query_last_id = "SELECT id_customer FROM customer ORDER BY id_customer DESC LIMIT 1";
    $result_last_id = mysqli_query($con, $query_last_id);
    $row_last_id = mysqli_fetch_assoc($result_last_id);

    // Jika ada ID terakhir, buat ID baru, jika tidak mulai dari C001
    if ($row_last_id) {
        $last_id = $row_last_id['id_customer'];
        $number = (int) substr($last_id, 1); // Ambil angka dari ID terakhir
        $new_id = 'C' . str_pad($number + 1, 3, '0', STR_PAD_LEFT); // Format CXXX
    } else {
        $new_id = 'C001'; // ID pertama jika belum ada data
    }
    $nama_customer = $_POST['nama_customer'];
    $alamat = $_POST['alamat'];
    $no_telp = $_POST['no_telp'];
    $email = $_POST['email'];
    $tanggal_daftar = $_POST['tanggal_daftar'];
    $password = $_POST['password'];
    $nomor_kamar = $_POST['nomor_kamar'];
    $status_customer = $_POST['status_customer'];

    // Cek status kamar
    $query_check_kamar = "SELECT status_kamar FROM persediaan_kamar WHERE nomor_kamar = '$nomor_kamar'";
    $result_check_kamar = mysqli_query($con, $query_check_kamar);
    $row_kamar = mysqli_fetch_assoc($result_check_kamar);

    if (!$row_kamar) {
        echo "<script>alert('Nomor kamar tidak ditemukan.'); window.history.back();</script>";
        exit();
    } elseif ($row_kamar['status_kamar'] == 'Terisi') {
        echo "<script>alert('Nomor kamar yang dipilih sudah terisi. Silakan pilih kamar lain.'); window.history.back();</script>";
        exit();
    }

    // Query untuk menambahkan customer ke dalam database
    $insert_customer = "INSERT INTO customer (id_customer, nama_customer, alamat, no_telp, email, tanggal_daftar, password, nomor_kamar, status_penghuni) 
                        VALUES ('$new_id', '$nama_customer', '$alamat', '$no_telp', '$email', '$tanggal_daftar', '$password', '$nomor_kamar', '$status_penghuni')";

    $run_insert = mysqli_query($con, $insert_customer);

    if ($run_insert) {
        // Update status kamar menjadi "Terisi"
        $update_kamar = "UPDATE persediaan_kamar SET status_kamar = 'Terisi' WHERE nomor_kamar = '$nomor_kamar'";
        mysqli_query($con, $update_kamar);

        echo "<script>alert('customer berhasil ditambahkan!'); window.open('index.php?view_penghuni', '_self');</script>";
    } else {
        echo "<script>alert('Gagal menambahkan customer.');</script>";
    }
}
?>

<body>
    <div class="container">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li><a href="index.php?dashboard">Dashboard</a></li>
            <li><a href="index.php?view_penghuni">View customer</a></li>
            <li class="active">Insert customer</li>
        </ol>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-plus"></i> Tambah customer</h3>
            </div>
            <div class="panel-body">
                <form method="POST" action="">
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="nama_customer">Nama Customer</label>
                            <input type="text" name="nama_customer" id="nama_customer" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea name="alamat" id="alamat" class="form-control" required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="no_telp">Nomor Telepon</label>
                            <input type="text" name="no_telp" id="no_telp" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="tanggal_daftar">Tanggal Daftar</label>
                            <input type="date" name="tanggal_daftar" id="tanggal_daftar" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="nomor_kamar">Nomor Kamar</label>
                            <input type="text" name="nomor_kamar" id="nomor_kamar" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="status_customer">Status Customer</label>
                            <select name="status_customer" id="status_customer" class="form-control" required>
                                <option value="">Pilih Status</option>
                                <?php
                                foreach ($enum_values as $value) {
                                    echo "<option value=\"$value\">$value</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <button type="submit" name="submit" class="btn btn-success">Tambah customer</button>
                        </div>
                    </form>
            </div>
        </div>
    </div>
</body>

</html>