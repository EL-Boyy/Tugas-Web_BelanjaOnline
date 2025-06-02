<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Pastikan hanya penjual yang bisa mengakses halaman ini
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'penjual') {
    header("Location: login.php");
    exit;
}

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "belanja_online");

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Inisialisasi pesan
$success = $error = "";

// Ambil barang yang dijual oleh penjual saat ini
$query = "SELECT * FROM barang WHERE id_penjual = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $_SESSION['id_penjual']); // Menggunakan id_penjual dari sesi
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()): ?>
        <div class="product-item">
            <img src="data:image/jpeg;base64,<?= base64_encode($row['gambar']) ?>" alt="Gambar Barang">
            <h3><?= htmlspecialchars($row['nama_barang']) ?></h3>
            <p>Harga: Rp<?= number_format($row['harga'], 0, ',', '.') ?></p>
            <p>Stok: <?= $row['stok'] ?></p>
            <p><?= htmlspecialchars($row['deskripsi']) ?></p>
        </div>
    <?php endwhile;
} else {
    echo "<p>Anda belum menjual barang apa pun.</p>";
}

// Debugging: Menampilkan ID Penjual
echo "ID Penjual: " . $_SESSION['id_penjual'];

// Ambil ID penjual dari sesi
$id_penjual = $_SESSION['id_penjual'];
$username = $_SESSION['username'];

// Proses penambahan barang
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_barang = trim($_POST['nama_barang']);
    $deskripsi = trim($_POST['deskripsi']);
    $harga = filter_var($_POST['harga'], FILTER_VALIDATE_FLOAT);
    $stok = filter_var($_POST['stok'], FILTER_VALIDATE_INT);
    $id_penjual = $_SESSION['id_penjual'];

    // Validasi input
    if (empty($nama_barang) || empty($deskripsi) || !$harga || !$stok) {
        $error = "Semua field harus diisi dengan benar.";
    } elseif (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        // Validasi gambar
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($_FILES['gambar']['tmp_name']);
        $fileSize = $_FILES['gambar']['size']; // Ukuran dalam byte

        if (!in_array($fileType, $allowedTypes)) {
            $error = "Hanya file gambar dengan format JPEG, PNG, atau GIF yang diizinkan.";
        }  else {
            // Proses upload gambar
            $gambar = file_get_contents($_FILES['gambar']['tmp_name']);

            // Query untuk menyimpan data barang
            $query = "INSERT INTO barang (nama_barang, deskripsi, harga, stok, gambar, id_penjual) 
                      VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssdisb", $nama_barang, $deskripsi, $harga, $stok, $gambar, $id_penjual);
            $stmt->send_long_data(4, $gambar); // Untuk menyimpan data gambar (blob)

            if ($stmt->execute()) {
                $success = "Barang berhasil ditambahkan!";
            } else {
                $error = "Terjadi kesalahan: " . $stmt->error;
            }
        }
    } else {
        $error = "Gagal mengupload gambar. Pastikan Anda memilih file gambar yang valid.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Penjual</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <h1 class="text-center mb-4">Dashboard Penjual</h1>

    <h2 class="mb-3">Tambah Barang</h2>
    <?php if ($success): ?>
        <div class="alert alert-success" role="alert">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="row g-3">
        <div class="col-md-6">
            <label for="nama_barang" class="form-label">Nama Barang</label>
            <input type="text" class="form-control" name="nama_barang" id="nama_barang" required>
        </div>
        <div class="col-md-6">
            <label for="harga" class="form-label">Harga</label>
            <input type="number" class="form-control" name="harga" id="harga" step="0.01" required>
        </div>
        <div class="col-md-12">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea class="form-control" name="deskripsi" id="deskripsi" rows="3" required></textarea>
        </div>
        <div class="col-md-6">
            <label for="stok" class="form-label">Stok</label>
            <input type="number" class="form-control" name="stok" id="stok" required>
        </div>
        <div class="col-md-6">
            <label for="gambar" class="form-label">Gambar</label>
            <input type="file" class="form-control" name="gambar" id="gambar" accept="image/*" required>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary w-100">Upload Barang</button>
        </div>
    </form>

    <h2 class="mt-5">Barang yang Anda Jual</h2>
    <div class="row g-4">
        <?php
        $query = "SELECT * FROM barang WHERE id_penjual = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $_SESSION['id_penjual']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="card h-100">
                        <img src="data:image/jpeg;base64,<?= base64_encode($row['gambar']) ?>" class="card-img-top" alt="Gambar Barang">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($row['nama_barang']) ?></h5>
                            <p class="card-text">
                                Harga: Rp<?= number_format($row['harga'], 0, ',', '.') ?><br>
                                Stok: <?= $row['stok'] ?><br>
                                <?= htmlspecialchars($row['deskripsi']) ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endwhile;
        } else {
            echo "<p class='text-muted'>Anda belum menjual barang apa pun.</p>";
        }
        ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
