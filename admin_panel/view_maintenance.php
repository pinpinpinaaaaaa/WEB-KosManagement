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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>View Maintenance</title>

    <!-- Tambahkan CSS DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

    <!-- Tambahkan jQuery dan DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
</head>

<body>
    <div class="container">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li><a href="index.php">Dashboard</a></li>
            <li class="active">View Maintenance</li>
        </ol>

        <!-- Tombol Tambah Maintenance -->
        <div class="text-right" style="margin-bottom: 20px;">
            <a href="index.php?insert_maintenance" class="btn btn-success">
                <i class="fa fa-plus"></i> Tambahkan Maintenance
            </a>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-wrench"></i> Daftar Maintenance</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="perawatanTable" class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>ID Maintenance</th>
                                <th>ID Kamar</th>
                                <th>Tanggal Maintenance</th>
                                <th>Deskripsi</th>
                                <th>Status</th>
                                <th>Edit</th>
                                <th>Hapus</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Query untuk mendapatkan data maintenance
                            $get_maintenance = "SELECT * FROM maintenance";
                            $run_maintenance = mysqli_query($con, $get_maintenance);

                            if (mysqli_num_rows($run_maintenance) > 0) {
                                while ($row_maintenance = mysqli_fetch_assoc($run_maintenance)) {
                                    $id_maintenance = $row_maintenance['id_maintenance'];
                                    $id_kamar = $row_maintenance['id_kamar'];
                                    $maintenance_date = $row_maintenance['maintenance_date'];
                                    $description = $row_maintenance['description'];
                                    $status = $row_maintenance['status'];
                                    ?>
                                    <tr>
                                        <td><?php echo $id_maintenance; ?></td>
                                        <td><?php echo $id_kamar; ?></td>
                                        <td><?php echo $maintenance_date; ?></td>
                                        <td><?php echo $description; ?></td>
                                        <td>
                                            <?php
                                            echo $status == 'Pending'
                                                ? '<span class="label label-warning">Pending</span>'
                                                : '<span class="label label-success">Selesai</span>';
                                            ?>
                                        </td>
                                        <td>
                                            <a href="index.php?edit_maintenance&id_maintenance=<?php echo $id_maintenance; ?>"
                                                class="btn btn-primary btn-sm">
                                                <i class="fa fa-pencil"></i> Edit
                                            </a>
                                        </td>
                                        <td>
                                            <a href="index.php?delete_maintenance&id_maintenance=<?php echo $id_maintenance; ?>"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Yakin ingin menghapus data maintenance ini?');">
                                                <i class="fa fa-trash-o"></i> Hapus
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "<tr><td colspan='7' class='text-center'>Tidak ada data maintenance.</td></tr>";
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
            $('#perawatanTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true
            }); // Menginisialisasi DataTables
        });
    </script>
</body>

</html>