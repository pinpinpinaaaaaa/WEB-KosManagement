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

// Mengecek apakah parameter 'id_fasilitas' ada di URL
if (isset($_GET['id_fasilitas'])) {
    $id_fasilitas = $_GET['id_fasilitas'];

    // Mengambil data fasilitas berdasarkan ID
    $get_fasilitas = "SELECT * FROM FASILITAS WHERE id_fasilitas='$id_fasilitas'";
    $run_fasilitas = mysqli_query($con, $get_fasilitas);
    if ($row_fasilitas = mysqli_fetch_assoc($run_fasilitas)) {
        // Menyimpan data fasilitas dalam variabel
        $nama_fasilitas = $row_fasilitas['nama_fasilitas'];
        $deskripsi_fasilitas = $row_fasilitas['deskripsi_fasilitas'];
        $periode = $row_fasilitas['periode'];
        $biaya_per_penggunaan = $row_fasilitas['biaya_per_penggunaan'];
    }
} else {
    echo "<script>alert('ID Fasilitas tidak ditemukan.'); window.open('index.php?view_fasilitas','_self');</script>";
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

$namaOptions = getEnumValues($con, 'fasilitas', 'nama_fasilitas');
$deskripsiOptions = getEnumValues($con, 'fasilitas', 'deskripsi_fasilitas');
$periodeOptions = getEnumValues($con, 'fasilitas', 'periode');

// Proses update data fasilitas jika tombol 'update' ditekan
if (isset($_POST['update'])) {
    $nama_fasilitas = $_POST['nama_fasilitas'];
    $deskripsi_fasilitas = $_POST['deskripsi_fasilitas'];
    $periode = $_POST['periode'];
    $biaya_per_penggunaan = $_POST['biaya_per_penggunaan'];

    // Proses penambahan nilai baru untuk enum
    if ($nama_fasilitas === 'add_new') {
        $nama_baru = $_POST['nama_fasilitas_baru'];
        if (!empty($nama_baru)) {
            $query = "ALTER TABLE fasilitas MODIFY nama_fasilitas ENUM('" . implode("','", array_merge($namaOptions, [$nama_baru])) . "')";
            mysqli_query($con, $query);
            $nama_fasilitas = $nama_baru; // Gunakan nilai baru
        }
    }

    // Query untuk memperbarui data fasilitas
    $update_fasilitas = "UPDATE FASILITAS SET nama_fasilitas='$nama_fasilitas', deskripsi_fasilitas='$deskripsi_fasilitas', biaya_per_penggunaan='$biaya_per_penggunaan', periode='$periode' WHERE id_fasilitas='$id_fasilitas'";

    // Menjalankan query update
    $run_update = mysqli_query($con, $update_fasilitas);

    if ($run_update) {
        echo "<script>alert('Data Fasilitas berhasil diperbarui!'); window.open('index.php?view_fasilitas','_self');</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data fasilitas.');</script>";
    }
}
?>

<body>
    <div class="container">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li><a href="index.php?dashboard">Dashboard</a></li>
            <li><a href="index.php?view_fasilitas">View Fasilitas</a></li>
            <li class="active">Edit Fasilitas</li>
        </ol>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> Edit Fasilitas</h3>
            </div>
            <div class="panel-body">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="nama_fasilitas">Nama Fasilitas</label>
                        <select name="nama_fasilitas" id="nama_fasilitas" class="form-control" required>
                            <option value="">Pilih Nama</option>
                            <?php foreach ($namaOptions as $option) { ?>
                                <option value="<?php echo $option; ?>" <?php if ($option == $nama_fasilitas)
                                       echo 'selected'; ?>>
                                    <?php echo $option; ?>
                                </option>
                            <?php } ?>
                            <option value="add_new">+ Tambah Baru</option>
                        </select>
                        <input type="text" name="nama_fasilitas_baru" id="nama_fasilitas_baru" class="form-control"
                            style="display:none;" placeholder="Masukkan Nama Baru">
                    </div>

                    <div class="form-group">
                        <label for="deskripsi_fasilitas">Deskripsi Fasilitas</label>
                        <select name="deskripsi_fasilitas" id="deskripsi_fasilitas" class="form-control" required>
                            <option value="">Pilih Deskripsi</option>
                            <?php foreach ($deskripsiOptions as $option) { ?>
                                <option value="<?php echo $option; ?>" <?php if ($option == $deskripsi_fasilitas)
                                       echo 'selected'; ?>>
                                    <?php echo $option; ?>
                                </option>
                            <?php } ?>
                            <option value="add_new">+ Tambah Baru</option>
                        </select>
                        <input type="text" name="deskripsi_fasilitas_baru" id="deskripsi_fasilitas_baru"
                            class="form-control" style="display:none;" placeholder="Masukkan Deskripsi Baru">
                    </div>

                    <div class="form-group">
                        <label for="periode">Periode</label>
                        <select name="periode" id="periode" class="form-control" required>
                            <option value="">Pilih Periode</option>
                            <?php foreach ($periodeOptions as $option) { ?>
                                <option value="<?php echo $option; ?>" <?php if ($option == $periode)
                                       echo 'selected'; ?>>
                                    <?php echo $option; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>


                    <div class="form-group">
                        <label for="biaya_per_penggunaan">Biaya per Penggunaan</label>
                        <input type="number" name="biaya_per_penggunaan" id="biaya_per_penggunaan" class="form-control"
                            required value="<?php echo $biaya_per_penggunaan; ?>">
                    </div>

                    <div class="form-group">
                        <button type="submit" name="update" class="btn btn-primary">Update Fasilitas</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('nama_fasilitas').addEventListener('change', function () {
            document.getElementById('nama_fasilitas_baru').style.display = this.value === 'add_new' ? 'block' : 'none';
        });

        document.getElementById('deskripsi_fasilitas').addEventListener('change', function () {
            document.getElementById('deskripsi_fasilitas_baru').style.display = this.value === 'add_new' ? 'block' : 'none';
        });
    </script>
</body>

</html>