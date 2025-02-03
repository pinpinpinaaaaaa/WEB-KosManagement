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

// Ambil nilai enum untuk kolom status
$enum_values = get_enum_values($con, 'maintenance', 'status');

// Proses tambah maintenance
if (isset($_POST['submit'])) {
    // Mengambil ID terakhir dari database
    $query_last_id = "SELECT id_maintenance FROM maintenance ORDER BY id_maintenance DESC LIMIT 1";
    $result_last_id = mysqli_query($con, $query_last_id);
    $row_last_id = mysqli_fetch_assoc($result_last_id);

    // Jika ada ID terakhir, buat ID baru, jika tidak mulai dari M001
    if ($row_last_id) {
        $last_id = $row_last_id['id_maintenance'];
        $number = (int) substr($last_id, 1); // Ambil angka dari ID terakhir
        $new_id = 'M' . str_pad($number + 1, 3, '0', STR_PAD_LEFT); // Format CXXX
    } else {
        $new_id = 'M001'; // ID pertama jika belum ada data
    }
    $id_kamar = $_POST['id_kamar'];
    $maintenance_date = $_POST['maintenance_date'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    // Query untuk menambahkan maintenance ke dalam database
    $insert_maintenance = "INSERT INTO maintenance (id_maintenance, id_kamar, maintenance_date, description, status) 
                           VALUES ('$new_id', '$id_kamar', '$maintenance_date', '$description', '$status')";

    $run_insert = mysqli_query($con, $insert_maintenance);

    if ($run_insert) {
        echo "<script>alert('Maintenance berhasil ditambahkan!'); window.open('index.php?view_maintenance', '_self');</script>";
    } else {
        echo "<script>alert('Gagal menambahkan maintenance.');</script>";
    }
}
?>

<body>
    <div class="container">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li><a href="index.php?dashboard">Dashboard</a></li>
            <li><a href="index.php?view_maintenance">View Maintenance</a></li>
            <li class="active">Insert Maintenance</li>
        </ol>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-plus"></i> Tambah Maintenance</h3>
            </div>
            <div class="panel-body">
                <form method="POST" action="">

                    <div class="form-group">
                        <label for="id_kamar">ID Kamar</label>
                        <input type="text" name="id_kamar" id="id_kamar" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="maintenance_date">Tanggal Maintenance</label>
                        <input type="date" name="maintenance_date" id="maintenance_date" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea name="description" id="description" class="form-control" rows="4" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="">Pilih Status</option>
                            <?php
                            foreach ($enum_values as $value) {
                                echo "<option value=\"$value\">$value</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <button type="submit" name="submit" class="btn btn-success">Tambah Maintenance</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>