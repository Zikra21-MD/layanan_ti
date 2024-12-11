<?php
include('db.php');
session_start(); // Mulai sesi untuk menyimpan data sesi pengguna

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Query untuk mengambil data pengguna berdasarkan username
    $query = "SELECT * FROM users WHERE username = :username AND password = :password";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Jika username dan password ditemukan
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Menyimpan data sesi
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $username; // Menyimpan username dalam sesi
        $_SESSION['role'] = $user['role']; // Menyimpan role pengguna dalam sesi

        $role = $user['role'];

        // Redirect berdasarkan role
        $redirectUrl = ($role === 'admin') ? "admin/main.php" : "user/main.php";
        header("Location: $redirectUrl");
        exit(); // Pastikan script berhenti setelah redirect
    } else {
        // Jika login gagal
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pengguna</title>

    <!-- Menambahkan CDN Bootstrap untuk styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">

    <!-- SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 w-100" style="max-width: 400px;">
            <h2 class="text-center mb-4">Login</h2>

            <!-- Menampilkan pesan kesalahan dengan SweetAlert -->
            <?php if (isset($error)): ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Login Gagal',
                        text: '<?= $error; ?>',
                    });
                </script>
            <?php endif; ?>

            <!-- Form Login -->
            <form action="sign_in.php" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>

            <!-- Link untuk Daftar -->
            <div class="mt-3 text-center">
                <p>Belum punya akun? <a href="registrasi.php">Daftar di sini</a></p>
            </div>
        </div>
    </div>

    <!-- Menambahkan Bootstrap JS dan Popper.js untuk interaksi dan styling dinamis -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
</body>
</html>
