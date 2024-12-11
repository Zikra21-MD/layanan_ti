<?php
include '../db.php';

try {
    $sql = "SELECT * FROM issues";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $issues = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Terjadi kesalahan: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $issue_id = $_POST['issue_id'];
    $status = $_POST['status'];

    if (!in_array($status, ['Belum Dikerjakan', 'In Progress', 'Solved'])) {
        echo "Status yang dipilih tidak valid.";
    } else {
        try {
            $updateQuery = "UPDATE masalah SET status = :status WHERE id = :id";
            $updateStmt = $pdo->prepare($updateQuery);
            $updateStmt->execute([
                ':status' => $status,
                ':id' => $issue_id
            ]);
            header("Location: masalah.php");
            exit;
        } catch (PDOException $e) {
            die("Terjadi kesalahan: " . $e->getMessage());
        }
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
    <title>Daftar Masalah</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
<?php include('../nav/navbar.php'); ?>

<div class="main-content container mt-4">
    <h1>Daftar Masalah yang Diajukan</h1>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Kategori</th>
                <th>Deskripsi</th>
                <th>Status</th>
                <th>Waktu Pengajuan</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($issues)): ?>
                <tr>
                    <td colspan="6" style="text-align:center;">Tidak ada masalah yang diajukan.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($issues as $issue): ?>
                    <tr>
                        <td><?= htmlspecialchars($issue['id']); ?></td>
                        <td><?= htmlspecialchars($issue['category']); ?></td>
                        <td><?= htmlspecialchars($issue['description']); ?></td>
                        <td><?= htmlspecialchars($issue['status']); ?></td>
                        <td><?= htmlspecialchars($issue['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<footer>
    <p>&copy; 2024 Layanan TI - Admin Dashboard</p>
</footer>

<!-- Menambahkan Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybYbBrC5kFaa4k4p97RmQJ9JAPXvsdd8lRj8+18l4E0D6nQpF" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0r8rsx3kfdVdT0VqkzLzgzRoxXMk8wD53uT/vY2jdF7Rpd6S" crossorigin="anonymous"></script>
</body>
</html>
