<?php
if (!isset($_SESSION['username'])) {
    echo "<script>window.open('../login.php', '_self')</script>";
    exit(); // Hentikan eksekusi kode lebih lanjut
} else {
    if (isset($_GET['user_profile'])) {
        $edit_username = $_GET['user_profile'];
        $get_user = "SELECT * FROM pegawai WHERE username='$edit_username'";
        $run_user = mysqli_query($con, $get_user);

        // Check if a user was found
        if (mysqli_num_rows($run_user) > 0) {
            $row_user = mysqli_fetch_array($run_user);

            $nama_pegawai = $row_user['nama_pegawai'];
            $username = $row_user['username'];
            $password = $row_user['password'];
            $alamat = $row_user['alamat'];
            $no_telp = $row_user['no_telp'];
            $email = $row_user['email'];
        } else {
            echo "<script>alert('User not found!')</script>";
            echo "<script>window.open('index.php?dashboard', '_self')</script>";
        }
    }
?>
<div class="row"><!-- 1 row Starts -->
    <div class="col-lg-12"><!-- col-lg-12 Starts -->
        <ol class="breadcrumb"><!-- breadcrumb Starts -->
            <li class="active">
                <i class="fa fa-dashboard"></i> Dashboard / Edit Profile
            </li>
        </ol><!-- breadcrumb Ends -->
    </div><!-- col-lg-12 Ends -->
</div><!-- 1 row Ends -->

<div class="row"><!-- 2 row Starts -->
    <div class="col-lg-12"><!-- col-lg-12 Starts -->
        <div class="panel panel-default"><!-- panel panel-default Starts -->
            <div class="panel-heading"><!-- panel-heading Starts -->
                <h3 class="panel-title">
                    <i class="fa fa-money fa-fw"></i> Edit Profile
                </h3>
            </div><!-- panel-heading Ends -->

            <div class="panel-body"><!-- panel-body Starts -->
                <form class="form-horizontal" method="post" enctype="multipart/form-data"><!-- form-horizontal Starts -->
                    <div class="form-group">
                        <label class="col-md-3 control-label">Nama Pegawai: </label>
                        <div class="col-md-6">
                            <input type="text" name="nama_pegawai" class="form-control" required value="<?php echo isset($nama_pegawai) ? $nama_pegawai : ''; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">Username: </label>
                        <div class="col-md-6">
                            <input type="text" name="username" class="form-control" required value="<?php echo isset($username) ? $username : ''; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">Password: </label>
                        <div class="col-md-6">
                            <input type="password" name="password" class="form-control" required value="<?php echo isset($password) ? $password : ''; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">Alamat: </label>
                        <div class="col-md-6">
                            <textarea name="alamat" class="form-control" required><?php echo isset($alamat) ? $alamat : ''; ?></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">No. Telepon: </label>
                        <div class="col-md-6">
                            <input type="text" name="no_telp" class="form-control" required value="<?php echo isset($no_telp) ? $no_telp : ''; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">Email: </label>
                        <div class="col-md-6">
                            <input type="email" name="email" class="form-control" required value="<?php echo isset($email) ? $email : ''; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label"></label>
                        <div class="col-md-6">
                            <input type="submit" name="update" value="Update User" class="btn btn-primary form-control">
                        </div>
                    </div>
                </form><!-- form-horizontal Ends -->
            </div><!-- panel-body Ends -->
        </div><!-- panel panel-default Ends -->
    </div><!-- col-lg-12 Ends -->
</div><!-- 2 row Ends -->

<?php
if (isset($_POST['update'])) {
    $nama_pegawai = $_POST['nama_pegawai'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $alamat = $_POST['alamat'];
    $no_telp = $_POST['no_telp'];
    $email = $_POST['email'];

    $update_user = "UPDATE pegawai SET 
                        nama_pegawai='$nama_pegawai', 
                        username='$username', 
                        password='$password', 
                        alamat='$alamat', 
                        no_telp='$no_telp', 
                        email='$email' 
                    WHERE username='$edit_username'";
    $run_user = mysqli_query($con, $update_user);

    if ($run_user) {
        echo "<script>alert('User Has Been Updated successfully and login again')</script>";
        echo "<script>window.open('login.php', '_self')</script>";
        session_destroy();
    }
}
?>
<?php } ?>
