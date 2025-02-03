<?php

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    echo "<script>window.open('../login.php', '_self')</script>";
    exit(); // Hentikan eksekusi kode lebih lanjut
}

// Ambil username dari sesi
$username = $_SESSION['username'];
?>

<body>
    <nav class="navbar navbar-inverse navbar-fixed-top"><!-- navbar navbar-inverse navbar-fixed-top Starts -->
        <div class="navbar-header"><!-- navbar-header Starts -->
            <button type="button" class="navbar-toggle" data-toggle="collapse"
                data-target=".navbar-ex1-collapse"><!-- navbar-ex1-collapse Starts -->
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button><!-- navbar-ex1-collapse Ends -->
            <a class="navbar-brand" href="index.php?dashboard">Owner Panel</a>
        </div><!-- navbar-header Ends -->

        <ul class="nav navbar-right top-nav"><!-- nav navbar-right top-nav Starts -->
            <li class="dropdown"><!-- dropdown Starts -->
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><!-- dropdown-toggle Starts -->
                    <i class="fa fa-user"></i> <?php echo $username; ?> <b class="caret"></b>
                </a><!-- dropdown-toggle Ends -->
                <ul class="dropdown-menu"><!-- dropdown-menu Starts -->
                    <li><a href="index.php?user_profile=<?php echo $username; ?>"><i class="fa fa-fw fa-user"></i>
                            Profile</a></li>
                    <li><a href="../logout.php"><i class="fa fa-fw fa-power-off"></i> Log Out</a></li>
                </ul><!-- dropdown-menu Ends -->
            </li><!-- dropdown Ends -->
        </ul><!-- nav navbar-right top-nav Ends -->

        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <!-- collapse navbar-collapse navbar-ex1-collapse Starts -->
            <ul class="nav navbar-nav side-nav"><!-- nav navbar-nav side-nav Starts -->
                <li><a href="index.php?dashboard"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a></li>
                <li><!-- li Starts -->
                    <a href="#" data-toggle="collapse" data-target="#users"><i class="fa fa-fw fa-pencil"></i> Laporan
                        <i class="fa fa-fw fa-caret-down"></i></a>
                    <ul id="users" class="collapse">
                        <li><a href="index.php?view_laporan_pemasukan_pengeluaran"> Laporan Keuangan </a></li>
                        <li><a href="index.php?view_laporan_transaksi_customer"> Laporan Transaksi Customer </a></li>
                        <li><a href="index.php?view_laporan_transaksi_operasional"> Laporan Transaksi Operasional </a>
                        </li>
                        <li><a href="index.php?view_laporan_gaji"> Laporan Gaji Pegawai </a></li>
                    </ul>
                </li><!-- li Ends -->
                <li><a href="index.php?view_reservasi"><i class="fa fa-fw fa-edit"></i> Reservasi Kamar</a></li>
                <li><a href="index.php?view_penghuni"><i class="fa fa-fw fa-pencil"></i> Penghuni</a></li>
                <li><a href="index.php?view_kamar"><i class="fa fa-fw fa-list"></i> Kamar</a></li>
                <li><a href="index.php?view_fasilitas"><i class="fa fa-fw fa-edit"></i> Fasilitas</a></li>
                <li><a href="index.php?view_maintenance"><i class="fa fa-fw fa-edit"></i> Maintenance</a></li>
                <li><a href="index.php?view_pegawai"><i class="fa fa-fw fa-pencil"></i> Pegawai</a></li>
                <li><!-- li Starts -->
                    <a href="../logout.php">
                        <i class="fa fa-fw fa-power-off"></i> Log Out
                    </a>
                </li><!-- li Ends -->
            </ul><!-- nav navbar-nav side-nav Ends -->
        </div><!-- collapse navbar-collapse navbar-ex1-collapse Ends -->
    </nav><!-- navbar navbar-inverse navbar-fixed-top Ends -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>