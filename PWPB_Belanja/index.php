<?php
// Memulai koneksi ke database
$conn = new mysqli("localhost", "root", "", "belanja_online");

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data barang dari database
$sql = "SELECT nama_barang, harga, deskripsi, gambar FROM barang ORDER BY id_barang DESC";
$result = $conn->query($sql);

// Memulai sesi untuk menentukan apakah pengguna login atau tidak
session_start();
$is_logged_in = isset($_SESSION['role']);
$user_role = $is_logged_in ? $_SESSION['role'] : 'pendatang';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda - Belanja Online</title>
    <!-- Bootstrap 5 CSS -->
    <link href="bootstrap 5/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html, body {
        height: 100%;
        margin: 0;
        display: flex;
        flex-direction: column;
        }
        main {
        flex: 1;
        }
        footer {
        background-color: #343a40; /* Warna gelap */
        color: #fff; /* Warna teks putih */
        text-align: center;
        padding: 15px 0;
        }
        .main-header {
            background-color: #007bff;
            color: #fff;
            padding: 20px 0;
            text-align: center;
        }
        .product-item img {
            max-height: 200px;
            object-fit: cover;
            border-radius: 10px;
        }
        .product-item {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
            background-color: #fff;
            padding: 15px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <header class="main-header">
        <h1>Selamat Datang di Toko Online</h1>
        <?php if ($is_logged_in): ?>
            <p>Hai, <?php echo htmlspecialchars($_SESSION['name']); ?>! Anda login sebagai <?php echo htmlspecialchars($user_role); ?>.</p>
        <?php else: ?>
            <p>Anda belum login. <a href="login_pembeli.php" class="text-white">Login</a> atau <a href="register_pembeli.php" class="text-white">Daftar</a></p>
        <?php endif; ?>
    </header>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Toko Online</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
                    <?php if ($is_logged_in && $user_role === 'pembeli'): ?>
                        <li class="nav-item"><a class="nav-link" href="dashboard_pembeli.php">Dashboard</a></li>
                    <?php elseif ($is_logged_in && $user_role === 'penjual'): ?>
                        <li class="nav-item"><a class="nav-link" href="dashboard_penjual.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="add_product.php">Tambah Barang</a></li>
                    <?php endif; ?>
                    <?php if ($is_logged_in): ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container my-5">
        <section class="product-section">
            <h2 class="text-center mb-4">Produk Tersedia</h2>
            <div class="row">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="col-md-4">
                            <div class="product-item">
                                <?php if ($row['gambar']): ?>
                                    <img src="data:image/jpeg;base64,<?php echo base64_encode($row['gambar']); ?>" alt="Gambar Barang" class="img-fluid mb-3">
                                <?php else: ?>
                                    <img src="placeholder.jpg" alt="Gambar tidak tersedia" class="img-fluid mb-3">
                                <?php endif; ?>
                                <h3 class="h5"><?php echo htmlspecialchars($row['nama_barang']); ?></h3>
                                <p class="text-muted">Harga: Rp<?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
                                <p><?php echo htmlspecialchars($row['deskripsi']); ?></p>
                                <?php if ($is_logged_in && $user_role === 'pembeli'): ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-center">Tidak ada produk tersedia saat ini.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2024 Toko Online. Semua Hak Dilindungi.</p>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="bootstrap 5/js/bootstrap.bundle.min.js"></script>
</body>
</html>
