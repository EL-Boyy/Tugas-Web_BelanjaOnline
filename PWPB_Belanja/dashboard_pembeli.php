<?php
session_start();

// Pastikan pengguna sudah login sebagai pembeli
if (!isset($_SESSION['id_pembeli'])) {
    header("Location: login_pembeli.php");
    exit;
}

// Pastikan hanya pembeli yang bisa mengakses halaman ini
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pembeli') {
    header("Location: login_pembeli.php");
    exit;
}

// Memulai koneksi ke database
$conn = new mysqli("localhost", "root", "", "belanja_online");

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data barang yang diunggah oleh penjual
$sql = "SELECT nama_barang, harga, deskripsi, gambar, id_penjual, id_barang FROM barang ORDER BY id_barang DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pembeli</title>
    <!-- Link ke Bootstrap CSS -->
    <link href="bootstrap 5/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #225e77; /* Warna latar belakang biru */
            color: #fff; /* Warna teks putih */
        }

        .product-item {
            height: 100%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 15px;
            background-color: #fff; /* Warna latar kotak produk putih */
            color: #000; /* Warna teks hitam */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center; /* Posisi elemen di tengah */
        }

        .product-item img {
            max-height: 200px;
            max-width: 100%; /* Membatasi lebar agar tetap responsif */
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .product-item h3 {
            font-size: 1.1rem;
            text-align: center;
        }

        footer {
            background-color: #343a40; /* Warna footer gelap */
            color: #fff; /* Warna teks putih */
            text-align: center;
            padding: 15px 0;
        }
    </style>
</head>
<body>
<header class="bg-primary text-white py-3">
    <div class="container d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3">Pembeli</h1>
            <p>Halo, <?php echo htmlspecialchars($_SESSION['name']); ?> !</p>
        </div>
        <div class="d-flex align-items-center">
            <!-- Tambahkan logo keranjang -->
            <a href="keranjang.php" class="btn btn-light btn-sm me-3">
                <img src="gambar/AlbedoBase.png" alt="Keranjang" style="width: 24px; height: 24px;"> <!-- Ganti "cart-icon.png" dengan gambar logo -->
            </a>
            <!-- Tombol Logout -->
            <a href="logout.php" class="btn btn-light btn-sm">Logout</a>
        </div>
    </div>
</header>

<main class="container my-5">
    <section>
        <h2 class="text-center mb-4 text-white">Produk Tersedia</h2>
        <div class="row g-4">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="col-md-4 d-flex">
                        <div class="product-item w-100">
                            <?php if ($row['gambar']): ?>
                                <a href="detail_produk.php?id_barang=<?php echo $row['id_barang']; ?>">
                                    <img src="data:image/jpeg;base64,<?php echo base64_encode($row['gambar']); ?>" alt="Gambar Barang">
                                </a>
                <?php else: ?>
                    <img src="placeholder.jpg" alt="Gambar tidak tersedia">
                        <?php endif; ?>
                        <h3><?php echo htmlspecialchars($row['nama_barang']); ?></h3>
                        <p class="text-muted text-center">Harga: Rp<?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
                        <p class="text-center"><?php echo htmlspecialchars($row['deskripsi']); ?></p>
                        <div class="d-flex justify-content-between">
                            <!-- Tombol Beli Sekarang -->
                            <a href="detail_produk.php?id_barang=<?php echo $row['id_barang']; ?>" class="btn btn-primary w-50 me-1">Beli Sekarang</a>
                            <!-- Tombol Masukkan ke Keranjang -->
                            <form action="tambah_keranjang.php" method="POST" class="w-50 ms-1">
                                <input type="hidden" name="id_pembeli" value="<?php echo $_SESSION['pembeli']; ?>">
                                <input type="hidden" name="id_barang" value="<?php echo $row['id_barang']; ?>">
                                <input type="hidden" name="jumlah" value="1"> <!-- Default jumlah 1 -->
                                <button type="submit" name="masuk" class="btn btn-secondary w-100">Masukkan ke Keranjang</button>
                            </form>
                        </div>
                    </div>
        </div>

                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <p class="text-center text-white">Tidak ada produk tersedia saat ini.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<footer class="bg-dark text-white text-center py-3">
    <p>&copy; 2024 Toko Online. Semua Hak Dilindungi.</p>
</footer>

<!-- Link ke Bootstrap JS Bundle -->
<script src="bootstrap 5/js/bootstrap.bundle.min.js"></script>
</body>
</html>
