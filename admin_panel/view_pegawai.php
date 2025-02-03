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

?>
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>View Pegawai</title>

    <!-- Tambahkan CSS DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
</head>

<body>
    <div class="container">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li><a href="index.php?dashboard">Dashboard</a></li>
            <li class="active">View Pegawai</li>
        </ol>

        <!-- Tombol Tambah Pegawai -->
        <div class="text-right" style="margin-bottom: 20px;">
            <a href="index.php?insert_pegawai" class="btn btn-success">
                <i class="fa fa-plus"></i> Tambahkan Pegawai
            </a>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-users"></i> Daftar Pegawai</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="pegawaiTable" class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>ID Pegawai</th>
                                <th>Nama Pegawai</th>
                                <th>Tanggal Mulai</th>
                                <th>Gaji</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Alamat</th>
                                <th>No. Telp</th>
                                <th>Email</th>
                                <th>Edit</th>
                                <th>Hapus</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Query untuk mendapatkan data pegawai
                            $get_pegawai = "SELECT * FROM PEGAWAI";
                            $run_pegawai = mysqli_query($con, $get_pegawai);

                            if (mysqli_num_rows($run_pegawai) > 0) {
                                while ($row_pegawai = mysqli_fetch_assoc($run_pegawai)) {
                                    $id_pegawai = $row_pegawai['id_pegawai'];
                                    $nama_pegawai = $row_pegawai['nama_pegawai'];
                                    $tanggal_mulai = $row_pegawai['tanggal_mulai'];
                                    $gaji = number_format($row_pegawai['gaji'], 0, ',', '.');
                                    $username = $row_pegawai['username'];
                                    $role = $row_pegawai['role'];
                                    $alamat = $row_pegawai['alamat'];
                                    $no_telp = $row_pegawai['no_telp'];
                                    $email = $row_pegawai['email'];
                            ?>
                            <tr>
                                <td><?php echo $id_pegawai; ?></td>
                                <td><?php echo $nama_pegawai; ?></td>
                                <td><?php echo $tanggal_mulai; ?></td>
                                <td>Rp <?php echo $gaji; ?></td>
                                <td><?php echo $username; ?></td>
                                <td><?php echo ucfirst($role); // Mengubah huruf pertama menjadi kapital ?></td>
                                <td><?php echo $alamat; ?></td>
                                <td><?php echo $no_telp; ?></td>
                                <td><?php echo $email; ?></td>
                                <td>
                                    <a href="index.php?edit_pegawai&id_pegawai=<?php echo $id_pegawai; ?>" class="btn btn-primary btn-sm">
                                        <i class="fa fa-pencil"></i> Edit
                                    </a>
                                </td>
                                <td>
                                    <a href="index.php?delete_pegawai&id_pegawai=<?php echo $id_pegawai; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus pegawai ini?');">
                                        <i class="fa fa-trash-o"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                            <?php
                                }
                            } else {
                                echo "<tr><td colspan='11' class='text-center'>Tidak ada data pegawai.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#pegawaiTable').DataTable(); // Menambahkan DataTables
        });
    </script>
</body>
</html>
