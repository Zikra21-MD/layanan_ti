<?php
include('../db.php');
session_start();

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../user/main.php");
    exit();
}

if (isset($_POST['delete_id'])) {
    $id = $_POST['delete_id'];

    // Memeriksa apakah ada entri di tabel 'issues' yang terkait dengan pengguna
    $queryCheck = "SELECT COUNT(*) FROM issues WHERE user_id = :id";
    $stmtCheck = $pdo->prepare($queryCheck);
    $stmtCheck->bindParam(':id', $id, PDO::PARAM_INT);
    $stmtCheck->execute();
    $countIssues = $stmtCheck->fetchColumn();

    if ($countIssues > 0) {
        // Menghapus entri yang terkait di tabel 'issues'
        $deleteIssues = "DELETE FROM issues WHERE user_id = :id";
        $stmtDeleteIssues = $pdo->prepare($deleteIssues);
        $stmtDeleteIssues->bindParam(':id', $id, PDO::PARAM_INT);
        $stmtDeleteIssues->execute();
    }

    // Menghapus pengguna setelah data terkait dihapus
    $query = "DELETE FROM users WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

}

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $query = "SELECT * FROM users WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = trim($_POST['name']);
        $username = trim($_POST['username']);
        $role = trim($_POST['role']);

        $updateQuery = "UPDATE users SET name = :name, username = :username, role = :role WHERE id = :id";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->bindParam(':name', $name);
        $updateStmt->bindParam(':username', $username);
        $updateStmt->bindParam(':role', $role);
        $updateStmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        if ($updateStmt->execute()) {
            header("Location: user_manage.php");
            exit();
        } else {
            echo "Gagal memperbarui data.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna</title>
    <!-- Menambahkan Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/styles.css">
    <!-- Menambahkan SweetAlert2 CSS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php include('../nav/navbar-admin.php'); ?>

<div class="container my-5">
    <h2 class="text-center mb-5">Daftar Pengguna</h2>

    <?php if (isset($_GET['edit'])): ?>
        <h3>Edit Pengguna</h3>
        <form action="user_manage.php?edit=<?= $user['id']; ?>" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($user['name']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-control" value="<?= htmlspecialchars($user['username']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select id="role" name="role" class="form-select">
                    <option value="user" <?= $user['role'] == 'user' ? 'selected' : ''; ?>>User</option>
                    <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Perbarui</button>
        </form>
        <br>
        <a href="user_manage.php" class="btn btn-secondary">Kembali ke Daftar Pengguna</a>
    <?php else: ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM users";
                $stmt = $pdo->prepare($query);
                $stmt->execute();
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($users as $user):
                ?>
                    <tr>
                        <td><?= $user['id']; ?></td>
                        <td><?= htmlspecialchars($user['name']); ?></td>
                        <td><?= htmlspecialchars($user['username']); ?></td>
                        <td><?= htmlspecialchars($user['role']); ?></td>
                        <td>
                            <form action="user_manage.php" method="GET" style="display:inline;">
                                <input type="hidden" name="edit" value="<?= $user['id']; ?>">
                                <button type="submit" class="btn btn-warning btn-sm">Edit</button>
                            </form>
                            <form action="user_manage.php" method="POST" style="display:inline;" id="deleteForm<?= $user['id']; ?>">
                                <input type="hidden" name="delete_id" value="<?= $user['id']; ?>">
                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $user['id']; ?>)">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<footer>
    <p>&copy; 2024 Layanan TI</p>
</footer>

<!-- Menambahkan Bootstrap JS dan Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

<script>
    function confirmDelete(userId) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Data pengguna ini akan dihapus secara permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form if user confirms
                document.getElementById('deleteForm' + userId).submit();
            }
        });
    }
</script>

</body>
</html>
