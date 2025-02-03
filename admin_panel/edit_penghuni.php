<?php

// Koneksi ke database
$con = mysqli_connect("localhost", "root", "", "kos");
if (!$con) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

if (!isset($_SESSION['username'])) {
    echo "<script>window.open('../login.php', '_self')</script>";
    exit(); // Hentikan eksekusi kode lebih lanjut
}


// Mengecek apakah parameter 'id_customer' ada di URL
if (isset($_GET['id_customer'])) {
    $id_customer = $_GET['id_customer'];

    // Mengambil data customer berdasarkan ID
    $get_customer = "SELECT * FROM customer WHERE id_customer='$id_customer'";
    $run_customer = mysqli_query($con, $get_customer);
    if ($row_customer = mysqli_fetch_assoc($run_customer)) {
        // Menyimpan data customer dalam variabel
        $nama_customer = $row_customer['nama_customer'];
        $alamat = $row_customer['alamat'];
        $no_telp = $row_customer['no_telp'];
        $email = $row_customer['email'];
        $tanggal_daftar = $row_customer['tanggal_daftar'];
        $password = $row_customer['Password'];
        $nomor_kamar_lama = $row_customer['nomor_kamar']; // Menyimpan nomor kamar lama
        $status_penghuni = $row_customer['status_penghuni'];
    }
} else {
    echo "<script>alert('ID customer tidak ditemukan.'); window.open('index.php?view_customer','_self');</script>";
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

$statusOptions = getEnumValues($con, 'customer', 'status_penghuni');


// Proses update data customer jika tombol 'update' ditekan
if (isset($_POST['update'])) {
    $nama_customer = $_POST['nama_customer'];
    $alamat = $_POST['alamat'];
    $no_telp = $_POST['no_telp'];
    $email = $_POST['email'];
    $tanggal_daftar = $_POST['tanggal_daftar'];
    $password = $_POST['password'];
    $nomor_kamar_baru = $_POST['nomor_kamar'];
    $status_penghuni = $_POST['status_penghuni'];

    // Cek status kamar baru jika nomor kamar berubah
    if ($nomor_kamar_baru !== $nomor_kamar_lama) {
        $query_check_kamar = "SELECT status_kamar FROM persediaan_kamar WHERE nomor_kamar = '$nomor_kamar_baru'";
        $result_check_kamar = mysqli_query($con, $query_check_kamar);
        $row_kamar = mysqli_fetch_assoc($result_check_kamar);

        if (!$row_kamar) {
            echo "<script>alert('Nomor kamar tidak ditemukan.'); window.history.back();</script>";
            exit();
        } elseif ($row_kamar['status_kamar'] == 'Terisi') {
            echo "<script>alert('Nomor kamar yang dipilih sudah terisi. Silakan pilih kamar lain.'); window.history.back();</script>";
            exit();
        }
    }

    // Query untuk memperbarui data customer
    $update_customer = "UPDATE customer 
                        SET nama_customer='$nama_customer', 
                            alamat='$alamat', 
                            no_telp='$no_telp', 
                            email='$email', 
                            tanggal_daftar='$tanggal_daftar', 
                            password='$password', 
                            nomor_kamar='$nomor_kamar_baru', 
                            status_penghuni='$status_penghuni' 
                        WHERE id_customer='$id_customer'";

    // Menjalankan query update
    $run_update = mysqli_query($con, $update_customer);

    if ($run_update) {
        // Update status kamar jika nomor kamar berubah
        if ($nomor_kamar_baru !== $nomor_kamar_lama) {
            // Set kamar lama menjadi "Tersedia"
            $update_kamar_lama = "UPDATE persediaan_kamar SET status_kamar = 'Tersedia' WHERE nomor_kamar = '$nomor_kamar_lama'";
            mysqli_query($con, $update_kamar_lama);

            // Set kamar baru menjadi "Terisi"
            $update_kamar_baru = "UPDATE persediaan_kamar SET status_kamar = 'Terisi' WHERE nomor_kamar = '$nomor_kamar_baru'";
            mysqli_query($con, $update_kamar_baru);
        }

        if ($status_penghuni == 'Tidak Aktif' && $nomor_kamar) {
            $update_kamar = "UPDATE persediaan_kamar SET status_kamar = 'Tersedia' WHERE nomor_kamar = '$nomor_kamar'";
            mysqli_query($con, $update_kamar);
        }

        echo "<script>alert('Data customer berhasil diperbarui!'); window.open('index.php?view_penghuni','_self');</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data customer.'); window.open('index.php?view_penghuni','_self');</script>";
    }
}
?>

<body>
    <div class="container">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li><a href="index.php?dashboard">Dashboard</a></li>
            <li><a href="index.php?view_penghuni">View customer</a></li>
            <li class="active">Edit customer</li>
        </ol>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> Edit customer</h3>
            </div>
            <div class="panel-body">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="nama_customer">Nama customer</label>
                        <input type="text" name="nama_customer" id="nama_customer" class="form-control" required
                            value="<?php echo $nama_customer; ?>">
                    </div>

                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control"
                            required><?php echo $alamat; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="no_telp">No Telepon</label>
                        <input type="text" name="no_telp" id="no_telp" class="form-control" required
                            value="<?php echo $no_telp; ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" required
                            value="<?php echo $email; ?>">
                    </div>

                    <div class="form-group">
                        <label for="tanggal_daftar">Tanggal Daftar</label>
                        <input type="date" name="tanggal_daftar" id="tanggal_daftar" class="form-control" required
                            value="<?php echo $tanggal_daftar; ?>">
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="text" name="password" id="password" class="form-control" required
                            value="<?php echo $password; ?>">
                    </div>

                    <div class="form-group">
                        <label for="nomor_kamar_baru">Nomor Kamar</label>
                        <input type="text" name="nomor_kamar" id="nomor_kamar" class="form-control" required
                            value="<?php echo $nomor_kamar_lama; ?>">
                    </div>


                    <div class="form-group">
                        <label for="status_penghuni">Status customer</label>
                        <select name="status_penghuni" id="status_penghuni" class="form-control" required>
                            <option value="">Pilih Status</option>
                            <?php foreach ($statusOptions as $option) { ?>
                                <option value="<?php echo $option; ?>" <?php if ($option == $status_penghuni)
                                       echo 'selected'; ?>>
                                    <?php echo $option; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>


                    <div class="form-group">
                        <button type="submit" name="update" class="btn btn-primary">Update customer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>