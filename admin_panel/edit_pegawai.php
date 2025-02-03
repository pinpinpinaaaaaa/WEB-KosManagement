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

// Proses tambah pegawai
if (isset($_POST['submit'])) {
    $id_pegawai = $_POST['id_pegawai'];
    $nama_pegawai = $_POST['nama_pegawai'];
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $gaji = $_POST['gaji'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Menggunakan hash untuk password
    $role = $_POST['role'];
    $alamat = $_POST['alamat'];
    $no_telp = $_POST['no_telp'];
    $email = $_POST['email'];

    // Query untuk menambahkan pegawai ke dalam database
    $insert_pegawai = "INSERT INTO PEGAWAI (id_pegawai, nama_pegawai, tanggal_mulai, gaji, username, password, role, alamat, no_telp, email) 
                        VALUES ('$id_pegawai', '$nama_pegawai', '$tanggal_mulai', '$gaji', '$username', '$password', '$role', '$alamat', '$no_telp', '$email')";

    $run_insert = mysqli_query($con, $insert_pegawai);

    if ($run_insert) {
        echo "<script>alert('Pegawai berhasil ditambahkan!'); window.open('index.php?view_pegawai', '_self');</script>";
    } else {
        echo "<script>alert('Gagal menambahkan pegawai.');</script>";
    }
}
?>

<body>
    <div class="container">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li><a href="index.php?dashboard">Dashboard</a></li>
            <li><a href="index.php?view_pegawai">View Pegawai</a></li>
            <li class="active">Insert Pegawai</li>
        </ol>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-plus"></i> Tambah Pegawai</h3>
            </div>
            <div class="panel-body">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="id_pegawai">ID Pegawai</label>
                        <input type="text" name="id_pegawai" id="id_pegawai" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="nama_pegawai">Nama Pegawai</label>
                        <input type="text" name="nama_pegawai" id="nama_pegawai" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="tanggal_mulai">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="gaji">Gaji</label>
                        <input type="number" name="gaji" id="gaji" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="role">Role</label>
                        <input type="text" name="role" id="role" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="no_telp">No. Telepon</label>
                        <input type="text" name="no_telp" id="no_telp" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <button type="submit" name="submit" class="btn btn-success">Tambah Pegawai</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
