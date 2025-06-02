<?php
session_start();

// Pastikan pengguna sudah login
if (!isset($_SESSION['id_pembeli'])) {
    header("Location: login_pembeli.php");
    exit;
}

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "belanja_online");

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil ID barang dari URL
$id_barang = $_GET['id_barang'] ?? null;

if (!$id_barang) {
    die("Produk tidak ditemukan.");
}

// Query untuk mendapatkan detail produk
$sql = "SELECT nama_barang, harga, deskripsi, gambar FROM barang WHERE id_barang = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_barang);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Produk tidak ditemukan.");
}

$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk</title>
    <link href="bootstrap 5/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #225e77; /* Latar belakang berwarna #225e77 */
            color: #fff; /* Warna teks putih */
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex: 1;
        }
        .product-detail {
            background-color: #ffffff; /* Warna latar konten putih */
            color: #000; /* Warna teks hitam */
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .product-detail img {
            max-width: 100%;
            border-radius: 10px;
        }
        .description {
            margin-top: 15px;
        }
        .action-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
        }
        .btn-primary {
            background-color: #225e77;
            border: none;
        }
        .btn-primary:hover {
            background-color: #1b4a5e;
        }
        .btn-secondary {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <header class="bg-primary text-white py-3 text-center">
        <h1 class="h3">Detail Produk</h1>
    </header>

    <main class="container my-5">
        <!-- Tombol Kembali -->
        <div class="mb-3">
            <button class="btn btn-secondary" onclick="history.back()"><--</button>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="row product-detail">
                    <!-- Bagian Gambar -->
                    <div class="col-md-6">
                        <?php if ($row['gambar']): ?>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['gambar']); ?>" alt="Gambar Produk">
                        <?php else: ?>
                            <img src="placeholder.jpg" alt="Gambar tidak tersedia">
                        <?php endif; ?>
                        <div class="description">
                            <h4>Deskripsi Produk</h4>
                            <p><?php echo htmlspecialchars($row['deskripsi']); ?></p>
                        </div>
                    </div>
                    <!-- Bagian Aksi -->
                    <div class="col-md-6 action-btn d-flex flex-column align-items-center">
                        <h2 class="text-center"><?php echo htmlspecialchars($row['nama_barang']); ?></h2>
                        <p class="text-center text-muted">Harga: Rp<?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
                        <form action="tambah_keranjang.php" method="POST" class="w-75 mt-4">
                            <input type="hidden" name="id_pembeli" value="<?php echo $_SESSION['id_pembeli']; ?>">
                            <input type="hidden" name="id_barang" value="<?php echo $id_barang; ?>">
                            <input type="hidden" name="jumlah" value="1"> <!-- Default jumlah 1 -->
                            <button type="submit" name="masuk" class="btn btn-primary w-100 py-2">Masukkan ke Keranjang</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2024 Toko Online. Semua Hak Dilindungi.</p>
    </footer>

    <!-- Link ke Bootstrap JS -->
    <script src="bootstrap 5/js/bootstrap.bundle.min.js"></script>
</body>
</html>
