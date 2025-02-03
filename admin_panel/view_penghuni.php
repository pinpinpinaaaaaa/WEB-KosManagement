<?php
// Memulai sesi
//session_start();

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
    <title>View Customer</title>

    <!-- Tambahkan CSS DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
</head>

<body>
    <div class="container">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li><a href="index.php?dashboard">Dashboard</a></li>
            <li class="active">View Customer</li>
        </ol>

        <!-- Tombol Tambah customer -->
        <div class="text-right" style="margin-bottom: 20px;">
            <a href="index.php?insert_penghuni" class="btn btn-success">
                <i class="fa fa-plus"></i> Tambah customer
            </a>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-users"></i> Daftar customer</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="customerTable" class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>ID customer</th>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>No Telepon</th>
                                <th>Email</th>
                                <th>Tanggal Daftar</th>
                                <th>Password</th>
                                <th>Nomor Kamar</th>
                                <th>Status customer</th>
                                <th>Edit</th>
                                <th>Hapus</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Query untuk mendapatkan data customer
                            $get_customer = "SELECT * FROM CUSTOMER";
                            $run_customer = mysqli_query($con, $get_customer);

                            while ($row_customer = mysqli_fetch_assoc($run_customer)) {
                                $id_customer = $row_customer['id_customer'];
                                $nama_customer = $row_customer['nama_customer'];
                                $alamat = $row_customer['alamat'];
                                $no_telp = $row_customer['no_telp'];
                                $email = $row_customer['email'];
                                $tanggal_daftar = $row_customer['tanggal_daftar'];
                                $Password = $row_customer['Password'];
                                $nomor_kamar = $row_customer['nomor_kamar'];
                                $status_customer = $row_customer['status_penghuni'];
                                ?>
                                <tr>
                                    <td><?php echo $id_customer; ?></td>
                                    <td><?php echo $nama_customer; ?></td>
                                    <td><?php echo $alamat; ?></td>
                                    <td><?php echo $no_telp; ?></td>
                                    <td><?php echo $email; ?></td>
                                    <td><?php echo $tanggal_daftar; ?></td>
                                    <td><?php echo $Password; ?></td>
                                    <td><?php echo $nomor_kamar; ?></td>
                                    <td><?php echo $status_customer; ?></td>
                                    <td>
                                        <a href="index.php?edit_penghuni&id_customer=<?php echo $id_customer; ?>" class="btn btn-primary btn-sm">
                                            <i class="fa fa-pencil"></i> Edit
                                        </a>
                                    </td>
                                    <td>
                                        <a href="index.php?delete_customer?id_customer=<?php echo $id_customer; ?>"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus customer ini?');">
                                            <i class="fa fa-trash"></i> Hapus
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#customerTable').DataTable(); // Menambahkan DataTables
        });
    </script>
</body>
</html>
