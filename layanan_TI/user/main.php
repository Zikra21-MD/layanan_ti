<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header("Location: ../sign_in.php");
    exit();
}
if (!isset($_SESSION['user_id'])) {
    echo "Anda belum login.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>

<!-- Include Navbar -->
<?php include('../nav/navbar.php'); ?>

<!-- Main Content -->
<div class="main-content container mt-4">
    <h2>Selamat Datang di Dashboard Layanan TI</h2>
    <p>Anda dapat mengajukan masalah, melihat masalah yang telah diajukan, atau mengakses informasi lainnya.</p>
</div>

<!-- Footer -->
<footer>
    <p>&copy; 2024 Layanan TI - Admin Dashboard</p>
</footer>

</body>
</html>
