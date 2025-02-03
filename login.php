<?php

session_start();

// Koneksi ke database
$con = mysqli_connect("localhost", "root", "", "kos");
if (!$con) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" href="css/bs.css">
    <style>
        body{

        padding-top:10%;
        background-color: #B5C2FF;
        }

        .form-login {
        max-width:350px;
        padding:40px;
        border-radius:30px;
        background:white;
        margin:0 auto;

        }


        .form-login .form-login-heading{
        color:#337ab7;
        text-align:center;

        }

        .form-login .form-control {
        position:relative;
        height:auto;
        box-sizing:border-box;
        padding:10px;
        font-size:16px;

        }

        .form-login input[type="text"] {
        margin-bottom:5px;
        border-bottom-right-radius:0;
        border-bottom-left-radius: 0;

        }

        .form-login input[type="password"] {
        margin-bottom:10px;
        border-top-left-radius: 0;
        border-top-right-radius:0;
        }
    </style>
</head>

<body>

<div class="container"><!-- container Starts -->

    <form class="form-login" action="" method="post"><!-- form-login Starts -->

        <h2 class="form-login-heading">Login</h2>

        <input type="text" class="form-control" name="username" placeholder="Username" required>

        <input type="password" class="form-control" name="password" placeholder="Password" required>

        <button class="btn btn-lg btn-primary btn-block" type="submit" name="login">
            Log in
        </button>

    </form><!-- form-login Ends -->

</div><!-- container Ends -->

</body>

</html>

<?php

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    // Query untuk mendapatkan data user dari tabel PEGAWAI berdasarkan username
    $get_user = "SELECT * FROM PEGAWAI WHERE username='$username'";
    $run_user = mysqli_query($con, $get_user);

    if ($row_user = mysqli_fetch_assoc($run_user)) {
        // Verifikasi password langsung tanpa hashing
        if ($password === $row_user['password']) {
            $_SESSION['id_pegawai'] = $row_user['id_pegawai'];
            $_SESSION['username'] = $row_user['username'];
            $_SESSION['role'] = $row_user['role'];

            echo "<script>alert('Login berhasil!');</script>";

            // Redirect berdasarkan role
            if ($row_user['role'] == 'Admin') {
                echo "<script>window.open('admin_panel/index.php?dashboard', '_self');</script>";
            } else if ($row_user['role'] == 'Pegawai') {
                echo "<script>window.open('pegawai_panel/index.php?dashboard', '_self');</script>";
            } else if ($row_user['role'] == 'Owner') {
                echo "<script>window.open('owner_panel/index.php?dashboard', '_self');</script>";
            } else {
                echo "<script>alert('Role tidak valid! Hubungi admin.');</script>";
            }
        } else {
            echo "<script>alert('Password salah!');</script>";
        }
    } else {
        echo "<script>alert('Username tidak ditemukan!');</script>";
    }
}

?>
