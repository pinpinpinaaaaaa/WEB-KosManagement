<?php
// Koneksi ke database
$con = mysqli_connect("localhost", "root", "", "kos");
if (!$con) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

function get_enum_values($con, $table, $column) {
    $query = "SHOW COLUMNS FROM $table LIKE '$column'";
    $result = mysqli_query($con, $query);
    if (!$result) {
        die("Error in query: " . mysqli_error($con));
    }
    $row = mysqli_fetch_assoc($result);

    if ($row && strpos($row['Type'], 'enum') !== false) {
        preg_match("/^enum\((.*)\)$/", $row['Type'], $matches);
        return isset($matches[1]) ? str_getcsv($matches[1], ",", "'") : [];
    }
    return [];
}

$enum_values1 = get_enum_values($con, 'persediaan_kamar', 'tipe_kamar');
$enum_values2 = get_enum_values($con, 'persediaan_kamar', 'status_kamar');

if (isset($_POST['submit'])) {
    $nomor_kamar = $_POST['nomor_kamar'];
    $tipe_kamar = $_POST['tipe_kamar'];
    if ($tipe_kamar === 'add_new') {
        $tipe_kamar_baru = $_POST['tipe_kamar_baru'];
        if (!empty($tipe_kamar_baru)) {
            $update_enum_query = "ALTER TABLE persediaan_kamar MODIFY tipe_kamar ENUM('" . implode("','", array_merge($enum_values1, [$tipe_kamar_baru])) . "')";
            $update_enum_result = mysqli_query($con, $update_enum_query);
            if ($update_enum_result) {
                $tipe_kamar = $tipe_kamar_baru;
            } else {
                die("Gagal menambahkan tipe_kamar baru: " . mysqli_error($con));
            }
        }
    }
    $harga_per_bulan = $_POST['harga_per_bulan'];
    $status_kamar = $_POST['status_kamar'];
    $deskripsi_kamar = $_POST['deskripsi_kamar'];
    $gambar_kamar = $_FILES['gambar_kamar']['name'];
    $temp_gambar = $_FILES['gambar_kamar']['tmp_name'];
    $target_dir = "images/kamar/";

    if (!move_uploaded_file($temp_gambar, $target_dir . $gambar_kamar)) {
        die("Gagal mengunggah gambar.");
    }

    $query_last_id = "SELECT id_kamar FROM persediaan_kamar ORDER BY id_kamar DESC LIMIT 1";
    $result_last_id = mysqli_query($con, $query_last_id);
    $row_last_id = mysqli_fetch_assoc($result_last_id);

    $new_id = $row_last_id ? 'K' . str_pad((int) substr($row_last_id['id_kamar'], 1) + 1, 3, '0', STR_PAD_LEFT) : 'K001';

    $insert_kamar = "INSERT INTO persediaan_kamar (id_kamar, nomor_kamar, tipe_kamar, harga_per_bulan, status_kamar, gambar_kamar, deskripsi_kamar) 
        VALUES ('$new_id', '$nomor_kamar', '$tipe_kamar', '$harga_per_bulan', '$status_kamar', '$gambar_kamar', '$deskripsi_kamar')";

    if (mysqli_query($con, $insert_kamar)) {
        echo "<script>alert('Kamar berhasil ditambahkan!');</script>";
        echo "<script>window.open('index.php?view_kamar', '_self');</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat menambahkan kamar.');</script>";
    }
}
?>

<body>
<div class="container">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li><a href="index.php?dashboard">Dashboard</a></li>
            <li><a href="index.php?view_kamar">View Kamar</a></li>
            <li class="active">Insert Kamar</li>
        </ol>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-plus"></i> Tambah Kamar Baru</h3>
            </div>
            <div class="panel-body">
                <form method="POST" action=" "enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="nomor_kamar">Nomor Kamar</label>
                        <input type="text" name="nomor_kamar" id="nomor_kamar" class="form-control"  required>
                    </div>

                    <div class="form-group">
                            <label for="tipe_kamar">Tipe Kamar</label>
                            <select name="tipe_kamar" id="tipe_kamar" class="form-control" required>
                                <option value="">Pilih Tipe</option>
                                <?php
                                foreach ($enum_values1 as $value) {
                                    echo "<option value=\"$value\">$value</option>";
                                }
                                ?>
                                <option value="add_new">+ Tambah Baru</option>
                            </select>
                            <input type="text" name="tipe_kamar_baru" id="tipe_kamar_baru" class="form-control"
                            style="display:none;" placeholder="Masukkan Tipe Kamar Baru">
                        </div>

                    <div class="form-group">
                        <label for="harga_per_bulan">Harga per Bulan</label>
                        <input type="number" name="harga_per_bulan" id="harga_per_bulan" class="form-control" required>
                    </div>

                    <div class="form-group">
                            <label for="status_kamar">Status Kamar</label>
                            <select name="status_kamar" id="status_kamar" class="form-control" required>
                                <option value="">Pilih Status</option>
                                <?php
                                foreach ($enum_values2 as $value) {
                                    echo "<option value=\"$value\">$value</option>";
                                }
                                ?>
                            </select>
                        </div>

                    <div class="form-group">
                        <label for="gambar_kamar">Gambar Kamar</label>
                        <input type="file" name="gambar_kamar" id="gambar_kamar" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="deskripsi_kamar">Deskripsi Kamar</label>
                        <textarea name="deskripsi_kamar" id="deskripsi_kamar" class="form-control" rows="4"
                            required></textarea>
                        </div>

                    <div class="form-group">
                        <button type="submit" name="submit" class="btn btn-success">Tambah Kamar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
