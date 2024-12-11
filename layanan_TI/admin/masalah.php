<?php
require_once '../db.php';
session_start();

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../user/main.php");
    exit();
}

try {
    $query = "SELECT issues.id, issues.category, issues.description, issues.status, users.username 
              FROM issues 
              JOIN users ON issues.user_id = users.id
              ORDER BY issues.id DESC";
    $stmt = $pdo->prepare($query);
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
            $updateQuery = "UPDATE issues SET status = :status WHERE id = :id";
            $updateStmt = $pdo->prepare($updateQuery);
            $updateStmt->execute([
                ':status' => $status,
                ':id' => $issue_id
            ]);

            header("Location: masalah.php");
            exit();
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
    <title>Daftar Masalah - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include('../nav/navbar-admin.php'); ?>
<div class="container mt-5">
    <h1 class="mb-4">Daftar Masalah yang Diajukan</h1>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Kategori</th>
                <th>Deskripsi</th>
                <th>Status</th>
                <th>Diajukan Oleh</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($issues)): ?>
                <tr>
                    <td colspan="6" class="text-center">Tidak ada masalah yang diajukan.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($issues as $issue): ?>
                    <tr>
                        <td><?= htmlspecialchars($issue['id']); ?></td>
                        <td><?= htmlspecialchars($issue['category']); ?></td>
                        <td><?= htmlspecialchars($issue['description']); ?></td>
                        <td><?= htmlspecialchars($issue['status']); ?></td>
                        <td><?= htmlspecialchars($issue['username']); ?></td>
                        <td>
                            <form method="POST" class="d-inline-block">
                                <input type="hidden" name="issue_id" value="<?= $issue['id']; ?>">
                                <select name="status" class="form-select form-select-sm" required>
                                    <option value="Belum Dikerjakan" <?= $issue['status'] == 'Belum Dikerjakan' ? 'selected' : ''; ?>>Belum Dikerjakan</option>
                                    <option value="In Progress" <?= $issue['status'] == 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                                    <option value="Solved" <?= $issue['status'] == 'Solved' ? 'selected' : ''; ?>>Solved</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary mt-1">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
