<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../sign_in.php");
    exit();
}

include('../db.php');

try {
    // Query untuk mendapatkan jumlah total pengguna
    $queryTotalUsers = $pdo->query("SELECT COUNT(*) AS total_users FROM users");
    $totalUsers = $queryTotalUsers->fetch(PDO::FETCH_ASSOC)['total_users'];

    // Query untuk mendapatkan jumlah total masalah
    $queryTotalIssues = $pdo->query("SELECT COUNT(*) AS total_issues FROM issues");
    $totalIssues = $queryTotalIssues->fetch(PDO::FETCH_ASSOC)['total_issues'];

    // Query untuk mendapatkan jumlah masalah yang dalam proses
    $queryInProgressIssues = $pdo->query("SELECT COUNT(*) AS in_progress FROM issues WHERE status = 'Belum Dikerjakan'");
    $inProgressIssues = $queryInProgressIssues->fetch(PDO::FETCH_ASSOC)['in_progress'];

    // Query untuk mendapatkan jumlah masalah yang selesai
    $queryCompletedIssues = $pdo->query("SELECT COUNT(*) AS completed FROM issues WHERE status = 'solved'");
    $completedIssues = $queryCompletedIssues->fetch(PDO::FETCH_ASSOC)['completed'];

    // Query untuk mendapatkan jumlah masalah yang belum diproses
    $queryPendingIssues = $pdo->query("SELECT COUNT(*) AS pending FROM issues WHERE status = 'In Progress'");
    $pendingIssues = $queryPendingIssues->fetch(PDO::FETCH_ASSOC)['pending'];
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
<?php include('../nav/navbar-admin.php'); ?>

<div class="container my-5">
    <h2 class="text-center mb-5">Selamat Datang, Admin!</h2>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <!-- Card untuk Total Pengguna -->
        <div class="col">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h3>Total Pengguna</h3>
                    <p class="fs-4"><?= $totalUsers; ?></p>
                </div>
            </div>
        </div>
        <!-- Card untuk Total Masalah -->
        <div class="col">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h3>Total Masalah</h3>
                    <p class="fs-4"><?= $totalIssues; ?></p>
                </div>
            </div>
        </div>
        <!-- Card untuk Masalah dalam Proses -->
        <div class="col">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h3>Masalah dalam Proses</h3>
                    <p class="fs-4"><?= $inProgressIssues; ?></p>
                </div>
            </div>
        </div>
        <!-- Card untuk Masalah Selesai -->
        <div class="col">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h3>Masalah Selesai</h3>
                    <p class="fs-4"><?= $completedIssues; ?></p>
                </div>
            </div>
        </div>
        <!-- Card untuk Masalah Belum Diproses -->
        <div class="col">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h3>Masalah Belum Diproses</h3>
                    <p class="fs-4"><?= $pendingIssues; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<footer>
    <p>&copy; 2024 Layanan TI - Admin Dashboard</p>
</footer>

<!-- Menambahkan Bootstrap JS dan Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
