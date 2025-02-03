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
$enum_values1 = get_enum_values($con, 'fasilitas', 'nama_fasilitas');
$enum_values2 = get_enum_values($con, 'fasilitas', 'deskripsi_fasilitas');
$enum_values3 = get_enum_values($con, 'fasilitas', 'periode');

// Proses tambah fasilitas
if (isset($_POST['submit'])) {
    // Mengambil ID terakhir dari database
    $query_last_id = "SELECT id_fasilitas FROM fasilitas ORDER BY id_fasilitas DESC LIMIT 1";
    $result_last_id = mysqli_query($con, $query_last_id);
    $row_last_id = mysqli_fetch_assoc($result_last_id);

    // Jika ada ID terakhir, buat ID baru, jika tidak mulai dari F001
    if ($row_last_id) {
        $last_id = $row_last_id['id_fasilitas'];
        $number = (int) substr($last_id, 1); // Ambil angka dari ID terakhir
        $new_id = 'F' . str_pad($number + 1, 3, '0', STR_PAD_LEFT); // Format CXXX
    } else {
        $new_id = 'F001'; // ID pertama jika belum ada data
    }
    if ($nama_fasilitas === 'add_new') {
        $nama_fasilitas_baru = $_POST['nama_fasilitas_baru'];
        if (!empty($nama_fasilitas_baru)) {
            // Update enum di database
            $update_enum_query = "ALTER TABLE fasilitas MODIFY nama_fasilitas ENUM('" . implode("','", array_merge($enum_values1, [$nama_fasilitas_baru])) . "')";
            mysqli_query($con, $update_enum_query);
            $nama_fasilitas = $nama_fasilitas_baru; // Gunakan nilai baru
        }
    }

    if ($deskripsi_fasilitas === 'add_new') {
        $deskripsi_fasilitas_baru = $_POST['deskripsi_fasilitas_baru'];
        if (!empty($deskripsi_fasilitas_baru)) {
            // Update enum di database
            $update_enum_query = "ALTER TABLE fasilitas MODIFY deskripsi_fasilitas ENUM('" . implode("','", array_merge($enum_values2, [$deskripsi_fasilitas_baru])) . "')";
            mysqli_query($con, $update_enum_query);
            $deskripsi_fasilitas = $deskripsi_fasilitas_baru; // Gunakan nilai baru
        }
    }
    $periode = $_POST['periode'];
    $biaya_per_penggunaan = $_POST['biaya_per_penggunaan'];

    // Query untuk menambahkan fasilitas ke dalam database
    $insert_fasilitas = "INSERT INTO FASILITAS (id_fasilitas, nama_fasilitas, deskripsi_fasilitas, periode, biaya_per_penggunaan) 
                        VALUES ('$new_id', '$nama_fasilitas', '$deskripsi_fasilitas', '$periode','$biaya_per_penggunaan')";

    $run_insert = mysqli_query($con, $insert_fasilitas);

    if ($run_insert) {
        echo "<script>alert('Fasilitas berhasil ditambahkan!'); window.open('index.php?view_fasilitas', '_self');</script>";
    } else {
        echo "<script>alert('Gagal menambahkan fasilitas.');</script>";
    }
}
?>

<body>
    <div class="container">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li><a href="index.php?dashboard">Dashboard</a></li>
            <li><a href="index.php?view_fasilitas">View Fasilitas</a></li>
            <li class="active">Insert Fasilitas</li>
        </ol>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-plus"></i> Tambah Fasilitas</h3>
            </div>
            <div class="panel-body">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="nama_fasilitas">Nama Fasilitas</label>
                        <select name="nama_fasilitas" id="nama_fasilitas" class="form-control" required>
                            <option value="">Pilih Nama Fasilitas</option>
                            <?php
                            foreach ($enum_values1 as $value) {
                                echo "<option value=\"$value\">$value</option>";
                            }
                            ?>
                            <option value="add_new">+ Tambah Baru</option>
                        </select>
                        <input type="text" name="nama_fasilitas_baru" id="nama_fasilitas_baru" class="form-control"
                            style="display:none;" placeholder="Masukkan Nama Fasilitas Baru">

                    </div>

                    <div class="form-group">
                        <label for="deskripsi_fasilitas">Deskripsi Fasilitas</label>
                        <select name="deskripsi_fasilitas" id="deskripsi_fasilitas" class="form-control" required>
                            <option value="">Pilih Deskripsi Fasilitas</option>
                            <?php
                            foreach ($enum_values2 as $value) {
                                echo "<option value=\"$value\">$value</option>";
                            }
                            ?>
                            <option value="add_new">+ Tambah Baru</option>
                        </select>
                        <input type="text" name="deskripsi_fasilitas_baru" id="deskripsi_fasilitas_baru" class="form-control" 
           style="display:none;" placeholder="Masukkan Deskripsi Fasilitas Baru">
                    </div>

                    <div class="form-group">
                        <label for="periode">periode</label>
                        <select name="periode" id="periode" class="form-control" required>
                            <option value="">Pilih periode</option>
                            <?php
                            foreach ($enum_values3 as $value) {
                                echo "<option value=\"$value\">$value</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="biaya_per_penggunaan">Biaya per Penggunaan</label>
                        <input type="number" name="biaya_per_penggunaan" id="biaya_per_penggunaan" class="form-control"
                            required>
                    </div>

                    <div class="form-group">
                        <button type="submit" name="submit" class="btn btn-success">Tambah Fasilitas</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('nama_fasilitas').addEventListener('change', function () {
            var input = document.getElementById('nama_fasilitas_baru');
            input.style.display = this.value === 'add_new' ? 'block' : 'none';
            if (this.value !== 'add_new') input.value = ''; // Bersihkan nilai input jika bukan "Tambah Baru"
        });

        document.getElementById('deskripsi_fasilitas').addEventListener('change', function () {
            var input = document.getElementById('deskripsi_fasilitas_baru');
            input.style.display = this.value === 'add_new' ? 'block' : 'none';
            if (this.value !== 'add_new') input.value = ''; // Bersihkan nilai input jika bukan "Tambah Baru"
        });
    </script>
</body>

</html>