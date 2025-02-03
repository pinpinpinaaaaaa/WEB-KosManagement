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

// Validasi parameter 'edit_kamar'
if (isset($_GET['id_kamar'])) {
    $id_kamar = $_GET['id_kamar'];

    // Query untuk mendapatkan data kamar berdasarkan ID
    $get_kamar = "SELECT * FROM PERSEDIAAN_KAMAR WHERE id_kamar='$id_kamar'";
    $run_kamar = mysqli_query($con, $get_kamar);

    // Validasi apakah data ditemukan
    if ($row_kamar = mysqli_fetch_assoc($run_kamar)) {
        $nomor_kamar = $row_kamar['nomor_kamar'];
        $tipe_kamar = $row_kamar['tipe_kamar'];
        $harga_per_bulan = $row_kamar['harga_per_bulan'];
        $status_kamar = $row_kamar['status_kamar'];
        $gambar_kamar = $row_kamar['gambar_kamar'];
        $deskripsi_kamar = $row_kamar['deskripsi_kamar'];
    } else {
        echo "<script>alert('Kamar tidak ditemukan.')</script>";
        echo "<script>window.open('index.php?view_kamar','_self')</script>";
        exit();
    }
}

function getEnumValues($con, $table, $column)
{
    $query = "SHOW COLUMNS FROM `$table` LIKE '$column'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);

    $enumValues = str_replace(["enum(", ")", "'"], "", $row['Type']);
    return explode(",", $enumValues);
}

$tipeOptions = getEnumValues($con, 'persediaan_kamar', 'tipe_kamar');
$statusOptions = getEnumValues($con, 'persediaan_kamar', 'status_kamar');


// Jika form disubmit
if (isset($_POST['update'])) {
    $nomor_kamar = $_POST['nomor_kamar'];
    $tipe_kamar = $_POST['tipe_kamar'];
    $harga_per_bulan = $_POST['harga_per_bulan'];
    $status_kamar = $_POST['status_kamar'];
    $deskripsi_kamar = $_POST['deskripsi_kamar'];

    // Mengunggah gambar baru jika ada
    if (isset($_FILES['gambar_kamar']['name']) && $_FILES['gambar_kamar']['name'] != "") {
        $gambar_kamar = $_FILES['gambar_kamar']['name'];
        $tmp_name = $_FILES['gambar_kamar']['tmp_name'];
        $file_type = mime_content_type($tmp_name);

        // Validasi tipe file
        if (in_array($file_type, ['image/jpeg', 'image/png', 'image/gif'])) {
            move_uploaded_file($tmp_name, "images/kamar/$gambar_kamar");
        } else {
            echo "<script>alert('File yang diunggah harus berupa gambar (JPEG, PNG, GIF).')</script>";
            exit();
        }
    }

    // Proses penambahan nilai baru untuk enum
    if ($tipe_kamar === 'add_new') {
        $tipe_kamar_baru = $_POST['tipe_kamar_baru'];
        if (!empty($tipe_kamar_baru)) {
            $query = "ALTER TABLE persediaan_kamar MODIFY tipe_kamar ENUM('" . implode("','", array_merge($tipeOptions, [$tipe_kamar_baru])) . "')";
            mysqli_query($con, $query);
            $tipe_kamar = $tipe_kamar_baru;
        }
    }


    // Query update kamar
    $update_kamar = "UPDATE PERSEDIAAN_KAMAR SET 
                        nomor_kamar='$nomor_kamar', 
                        tipe_kamar='$tipe_kamar', 
                        harga_per_bulan='$harga_per_bulan', 
                        status_kamar='$status_kamar', 
                        gambar_kamar='$gambar_kamar', 
                        deskripsi_kamar='$deskripsi_kamar' 
                     WHERE id_kamar='$id_kamar'";

    $run_update = mysqli_query($con, $update_kamar);

    if ($run_update) {
        echo "<script>alert('Kamar berhasil diperbarui!')</script>";
        echo "<script>window.open('index.php?view_kamar','_self')</script>";
    } else {
        echo "<script>alert('Gagal memperbarui kamar.')</script>";
    }
}
?>

<body>
    <div class="container">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li><a href="index.php?dashboard">Dashboard</a></li>
            <li><a href="index.php?view_kamar">View Kamar</a></li>
            <li class="active">Edit Kamar</li>
        </ol>

        <!-- Form Edit Kamar -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> Edit Kamar</h3>
            </div>
            <div class="panel-body">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="nomor_kamar">Nomor Kamar</label>
                        <input type="text" name="nomor_kamar" class="form-control" required
                            value="<?php echo $nomor_kamar; ?>">
                    </div>
                    <div class="form-group">
                        <label for="tipe_kamar">Tipe Kamar</label>
                        <select name="tipe_kamar" id="tipe_kamar" class="form-control" required>
                            <option value="">Pilih Tipe Kamar</option>
                            <?php foreach ($tipeOptions as $option) { ?>
                                <option value="<?php echo $option; ?>" <?php if ($option == $tipe_kamar)
                                       echo 'selected'; ?>>
                                    <?php echo $option; ?>
                                </option>
                            <?php } ?>
                            <option value="add_new">+ Tambah Baru</option>
                        </select>
                        <input type="text" name="tipe_kamar_baru" id="tipe_kamar_baru" class="form-control"
                            style="display:none;" placeholder="Masukkan Deskripsi Baru">
                    </div>
                    <div class="form-group">
                        <label for="harga_per_bulan">Harga Per Bulan</label>
                        <input type="number" name="harga_per_bulan" class="form-control" required
                            value="<?php echo $harga_per_bulan; ?>">
                    </div>
                    <div class="form-group">
                        <label for="status_kamar">Status Kamar</label>
                        <select name="status_kamar" id="status_kamar" class="form-control" required>
                            <option value="">Pilih Status</option>
                            <?php foreach ($statusOptions as $option) { ?>
                                <option value="<?php echo $option; ?>" <?php if ($option == $status_kamar)
                                       echo 'selected'; ?>>
                                    <?php echo $option; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="gambar_kamar">Gambar Kamar</label>
                        <input type="file" name="gambar_kamar" class="form-control">
                        <?php if ($gambar_kamar) { ?>
                            <p>Gambar saat ini: <img src="images/kamar/<?php echo $gambar_kamar; ?>" width="100"></p>
                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <label for="deskripsi_kamar">Deskripsi Kamar</label>
                        <textarea name="deskripsi_kamar" class="form-control"
                            required><?php echo $deskripsi_kamar; ?></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="update" class="btn btn-primary">Update Kamar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('tipe_kamar').addEventListener('change', function () {
            document.getElementById('tipe_kamar_baru').style.display = this.value === 'add_new' ? 'block' : 'none';
        });
    </script>
</body>

</html>