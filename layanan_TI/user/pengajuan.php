<?php
require_once '../db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$success = $error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category = trim($_POST['category']);
    $description = trim($_POST['description']);
    $user_id = $_SESSION['user_id']; // Ambil ID pengguna dari sesi

    $query = "INSERT INTO issues (category, description, user_id, status) VALUES (:category, :description, :user_id, 'Belum Dikerjakan')";
    $stmt = $pdo->prepare($query);

    try {
        $stmt->execute([
            ':category' => $category,
            ':description' => $description,
            ':user_id' => $user_id
        ]);
        $success = "Masalah berhasil diajukan!";
    } catch (PDOException $e) {
        $error = "Terjadi kesalahan: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Ajukan Masalah</title>
</head>
<body>
    <?php include('../nav/navbar.php'); ?>
    <div class="container mt-5">
        <h2>Ajukan Masalah</h2>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= $success; ?></div>
        <?php elseif (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label for="category" class="form-label">Kategori Masalah:</label>
                <select name="category" id="category" class="form-select" required>
                    <option value="" disabled selected>Pilih kategori...</option>
                    <option value="Bug">Bug</option>
                    <option value="Koneksi">Koneksi</option>
                    <option value="Aplikasi">Aplikasi</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi Masalah:</label>
                <textarea name="description" id="description" class="form-control" placeholder="Deskripsikan masalah Anda..." required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Ajukan Masalah</button>
        </form>
    </div>
</body>
</html>
