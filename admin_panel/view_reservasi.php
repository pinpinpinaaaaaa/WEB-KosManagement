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
    <title>View Reservasi</title>

    <!-- Tambahkan CSS DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
</head>

<body>
    <div class="container">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li><a href="index.php?dashboard">Dashboard</a></li>
            <li class="active">View Reservasi</li>
        </ol>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-users"></i> Daftar Reservasi</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="reservasiTable" class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>ID Reservasi</th>
                                <th>Email Customer</th>
                                <th>ID Kamar</th>
                                <th>Tanggal Reservasi</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Query untuk mendapatkan data reservasi
                            $get_reservations = "SELECT * FROM reservasi_kamar";
                            $run_reservations = mysqli_query($con, $get_reservations);

                            if (!$run_reservations) {
                                die("Query Error: " . mysqli_error($con));
                            }

                            $i = 0; // Inisialisasi counter
                            while ($row_reservations = mysqli_fetch_array($run_reservations)) {
                                $id_reservasi = $row_reservations['id_reservasi'];
                                $id_customer = $row_reservations['id_customer'];
                                $id_kamar = $row_reservations['id_kamar'];
                                $tanggal_reservasi = $row_reservations['tanggal_reservasi'];
                                $status_reservasi = $row_reservations['status_reservasi'];

                                $i++;
                                ?>
                                <tr>
                                    <td><?php echo $id_reservasi; ?></td>
                                    <td>
                                        <?php
                                        // Query untuk mendapatkan email customer
                                        $get_customer = "SELECT email FROM customer WHERE id_customer='$id_customer'";
                                        $run_customer = mysqli_query($con, $get_customer);

                                        if ($run_customer && mysqli_num_rows($run_customer) > 0) {
                                            $row_customer = mysqli_fetch_array($run_customer);
                                            $customer_email = $row_customer['email'];
                                            echo $customer_email;
                                        } else {
                                            echo "Customer tidak ditemukan";
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo $id_kamar; ?></td>
                                    <td><?php echo $tanggal_reservasi; ?></td>
                                    <td>
                                        <?php
                                        if ($status_reservasi == 'Pending') {
                                            echo '<div style="color:red;">Pending</div>';
                                        } else {
                                            echo '<div style="color:green;">Dikonfirmasi</div>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <a href="index.php?delete_reservasi&id_reservasi=<?php echo $id_reservasi; ?>">
                                            <i class="fa fa-trash-o"></i> Delete
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
    <!-- Inisialisasi DataTables -->
    <script>
        $(document).ready(function () {
            $('#reservasiTable').DataTable(); // Menambahkan DataTables
        });
    </script>
</body>

</html>