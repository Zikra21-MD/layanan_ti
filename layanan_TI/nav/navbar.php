<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <!-- Logo & Brand Name -->
        <a class="navbar-brand" href="#">
            <img src="https://ps.uinib.ac.id/wp-content/uploads/2021/03/Logo-UIN-IB-Padang.png" alt="Logo" style="height: 30px; vertical-align: middle;">
            Dashboard Layanan TI
        </a>

        <!-- Button toggler untuk mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu utama -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto"> <!-- ms-auto untuk menempatkan menu di kanan -->
                <li class="nav-item">
                    <a class="nav-link" href="../user/main.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../user/pengajuan.php">Pengajuan Masalah</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../user/status.php">Lihat Masalah</a>
                </li>
                <!-- Profile dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://static.vecteezy.com/system/resources/previews/000/550/731/original/user-icon-vector.jpg" alt="Profile Logo" class="profile-icon" style="width: 30px; height: 30px; border-radius: 50%;">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="../user/profil.php">Profil Saya</a></li>
                        <li><a class="dropdown-item" href="../log-out.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
