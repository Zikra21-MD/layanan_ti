<?php
include('db.php');
session_start();

// Aktifkan error reporting untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

ob_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi password
    if ($password !== $confirm_password) {
        $_SESSION['error_message'] = 'Password dan konfirmasi password tidak sama!';
        header("Location: registrasi.php");
        exit();
    }

    $role = 'user';

    // Periksa apakah username sudah ada
    $query = "SELECT * FROM users WHERE username = :username";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Jika username sudah ada
        $_SESSION['error_message'] = 'Username sudah terdaftar, silakan gunakan username lain.';
        header("Location: registrasi.php");
        exit();
    }

    // Jika username belum terdaftar, lakukan insert ke database
    $query = "INSERT INTO users (name, username, password, role) VALUES (:name, :username, :password, :role)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password); // Simpan password sebagai teks biasa
    $stmt->bindParam(':role', $role);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Registrasi Berhasil! Silakan login.";
        header("Location: registrasi.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Terjadi Kesalahan, silakan coba lagi.";
        header("Location: registrasi.php");
        exit();
    }
}

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card" style="width: 100%; max-width: 400px;">
        <div class="card-body">
            <h2 class="card-title text-center mb-4">Registrasi</h2>

            <!-- Menampilkan pesan sukses atau error -->
            <?php
            if (isset($_SESSION['success_message'])) {
                echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Sukses!',
                        text: '" . $_SESSION['success_message'] . "',
                        timer: 3000,
                        showConfirmButton: false
                    });
                </script>";
                unset($_SESSION['success_message']);
            }

            if (isset($_SESSION['error_message'])) {
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: '" . $_SESSION['error_message'] . "',
                        timer: 3000,
                        showConfirmButton: false
                    });
                </script>";
                unset($_SESSION['error_message']);
            }
            ?>

            <form action="registrasi.php" method="POST" onsubmit="return validatePassword()">
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>

                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Daftar</button>
            </form>

            <div class="text-center mt-3">
                <p>Sudah punya akun? <a href="sign_in.php">Login di sini</a></p>
            </div>
        </div>
    </div>
</div>

<script>
    function validatePassword() {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;

        if (password !== confirmPassword) {
            Swal.fire({
                icon: 'error',
                title: 'Password Tidak Cocok',
                text:  'Periksa lagi password anda!',
            });
            return false;
        }
        return true;
    }
</script>
</body>
</html>
