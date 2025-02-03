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

// Mengecek apakah parameter 'id_maintenance' ada di URL
if (isset($_GET['id_maintenance'])) {
    $id_maintenance = $_GET['id_maintenance'];

    // Mengambil data maintenance berdasarkan ID
    $get_maintenance = "SELECT * FROM maintenance WHERE id_maintenance='$id_maintenance'";
    $run_maintenance = mysqli_query($con, $get_maintenance);
    if ($row_maintenance = mysqli_fetch_assoc($run_maintenance)) {
        // Menyimpan data maintenance dalam variabel
        $id_kamar = $row_maintenance['id_kamar'];
        $maintenance_date = $row_maintenance['maintenance_date'];
        $description = $row_maintenance['description'];
        $status = $row_maintenance['status'];
    }
} else {
    echo "<script>alert('ID Maintenance tidak ditemukan.'); window.open('index.php?view_maintenance','_self');</script>";
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

$statusOptions = getEnumValues($con, 'maintenance', 'status');


// Proses update data maintenance jika tombol 'update' ditekan
if (isset($_POST['update'])) {
    $id_kamar = $_POST['id_kamar'];
    $maintenance_date = $_POST['maintenance_date'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    // Query untuk memperbarui data maintenance
    $update_maintenance = "UPDATE maintenance SET id_kamar='$id_kamar', maintenance_date='$maintenance_date', description='$description', status='$status' WHERE id_maintenance='$id_maintenance'";

    // Menjalankan query update
    $run_update = mysqli_query($con, $update_maintenance);
    
    if ($run_update) {
        echo "<script>alert('Data Maintenance berhasil diperbarui!'); window.open('view_maintenance.php','_self');</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data maintenance.');</script>";
    }
}
?>
<body>
    <div class="container">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li><a href="index.php?dashboard">Dashboard</a></li>
            <li><a href="index.php?view_maintenance">View Maintenance</a></li>
            <li class="active">Edit Maintenance</li>
        </ol>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> Edit Maintenance</h3>
            </div>
            <div class="panel-body">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="id_kamar">ID Kamar</label>
                        <input type="text" name="id_kamar" id="id_kamar" class="form-control" required value="<?php echo $id_kamar; ?>">
                    </div>

                    <div class="form-group">
                        <label for="maintenance_date">Tanggal Maintenance</label>
                        <input type="date" name="maintenance_date" id="maintenance_date" class="form-control" required value="<?php echo $maintenance_date; ?>">
                    </div>

                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea name="description" id="description" class="form-control" rows="4" required><?php echo $description; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="">Pilih Status</option>
                            <?php foreach ($statusOptions as $option) { ?>
                                <option value="<?php echo $option; ?>" <?php if ($option == $status)
                                       echo 'selected'; ?>>
                                    <?php echo $option; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <button type="submit" name="update" class="btn btn-primary">Update Maintenance</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
