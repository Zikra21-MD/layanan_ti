<?php
session_start();
require_once '../db.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    echo "Anda belum login.";
    exit;
}

// Ambil ID pengguna dari sesi
$user_id = $_SESSION['user_id'];

// Periksa apakah tabel user benar-benar ada
try {
    $sql = "SELECT id, username, name, created_at FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "Data pengguna tidak ditemukan.";
        exit;
    }
} catch (mysqli_sql_exception $e) {
    echo "Kesalahan query: " . $e->getMessage();
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya</title>
    
    <!-- Link Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Link untuk CSS tambahan jika diperlukan -->
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <?php include('../nav/navbar.php'); ?>


    <div class="container mt-5">
        <h2 class="mb-4">Profil Saya</h2>

        <div class="card">
            <div class="card-body">
                <form action="#" method="post" class="profile-form">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username:</label>
                        <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama:</label>
                        <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="created_at" class="form-label">Tanggal Dibuat:</label>
                        <input type="text" id="created_at" name="created_at" class="form-control" value="<?php echo htmlspecialchars($user['created_at']); ?>" readonly>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Layanan TI - Profil Saya</p>
    </footer>

    <!-- Link untuk Bootstrap JS dan Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
