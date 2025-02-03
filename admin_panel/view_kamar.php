<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Kamar</title>

    <!-- Tambahkan CSS DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
</head>

<body>
    <div class="container">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li><a href="index.php?dashboard">Dashboard</a></li>
            <li class="active">View Kamar</li>
        </ol>

        <!-- Tombol Tambah Kamar -->
        <div class="text-right" style="margin-bottom: 20px;">
            <a href="index.php?insert_kamar" class="btn btn-success">
                <i class="fa fa-plus"></i> Tambah Kamar
            </a>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-building-o"></i> Daftar Kamar</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="kamarTable" class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nomor Kamar</th>
                                <th>Tipe Kamar</th>
                                <th>Harga per Bulan</th>
                                <th>Status</th>
                                <th>Gambar Kamar</th>
                                <th>Deskripsi</th>
                                <th>Edit</th>
                                <th>Hapus</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Koneksi ke database
                            $con = mysqli_connect("localhost", "root", "", "kos");
                            if (!$con) {
                                die("Koneksi ke database gagal: " . mysqli_connect_error());
                            }

                            // Query untuk mendapatkan data kamar
                            $get_kamar = "SELECT * FROM PERSEDIAAN_KAMAR";
                            $run_kamar = mysqli_query($con, $get_kamar);
                            $i = 0;

                            while ($row_kamar = mysqli_fetch_assoc($run_kamar)) {
                                $i++;
                                $id_kamar = $row_kamar['id_kamar'];
                                $nomor_kamar = $row_kamar['nomor_kamar'];
                                $tipe_kamar = $row_kamar['tipe_kamar'];
                                $harga_per_bulan = number_format($row_kamar['harga_per_bulan'], 0, ',', '.');
                                $status_kamar = $row_kamar['status_kamar'];
                                $gambar_kamar = $row_kamar['gambar_kamar'];
                                $deskripsi_kamar = $row_kamar['deskripsi_kamar'];
                                ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $nomor_kamar; ?></td>
                                    <td><?php echo $tipe_kamar; ?></td>
                                    <td>Rp <?php echo $harga_per_bulan; ?></td>
                                    <td><?php echo $status_kamar; ?></td>
                                    <td>
                                        <?php if ($gambar_kamar) { ?>
                                            <img src="images/kamar/<?php echo $gambar_kamar ?>" alt="Gambar Kamar" width="100">
                                        <?php } else { ?>
                                            <span>Gambar tidak tersedia</span>
                                        <?php } ?>
                                    </td>
                                    <td><?php echo $deskripsi_kamar; ?></td>
                                    <td>
                                        <a href="index.php?edit_kamar&id_kamar=<?php echo $id_kamar; ?>" class="btn btn-primary btn-sm">
                                            <i class="fa fa-pencil"></i> Edit
                                        </a>
                                    </td>
                                    <td>
                                        <a href="index.php?delete_kamar&id_kamar=<?php echo $id_kamar; ?>" class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash-o"></i> Hapus
                                        </a>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Tambahkan pustaka JS jQuery dan DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- Inisialisasi DataTables -->
    <script>
        $(document).ready(function () {
            $('#kamarTable').DataTable(); // Menambahkan DataTables
        });
    </script>
</body>
</html>
