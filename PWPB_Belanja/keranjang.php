<?php
session_start();

// Pastikan pengguna sudah login sebagai pembeli
if (!isset($_SESSION['id_pembeli'])) {
    header("Location: login_pembeli.php");
    exit;
}

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "belanja_online");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data keranjang berdasarkan id_pembeli
$id_pembeli = $_SESSION['pembeli'];

$sql = "
    SELECT k.id_keranjang, b.nama_barang, b.harga, k.jumlah, (b.harga * k.jumlah) AS total_harga
    FROM keranjang k
    JOIN barang b ON k.id_barang = b.id_barang
    WHERE k.id_pembeli = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_pembeli);
$stmt->execute();



if ($result->num_rows > 0) 
    echo "Data ditemukan: " . $result->num_rows . " item.";

$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Anda</title>
    <link href="bootstrap 5/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #225e77; /* Warna latar belakang */
            color: white; /* Warna teks */
        }
        header {
            background-color: #1b4a5c; /* Warna header lebih gelap */
            color: white; /* Warna teks header */
        }
        
        table {
            background-color: #fff; /* Warna latar tabel */
            color: #000; /* Warna teks tabel */
        }
        .table th {
            background-color: #1b4a5c; /* Warna header tabel */
            color: #fff; /* Warna teks header tabel */
        }
        .btn-back {
            background-color: #1b4a5c;
            color: #fff;
            border: none;
        }
        .btn-back:hover {
            background-color: #163b4a;
        }
        footer {
            background-color: #000; /* Warna hitam untuk footer */
            color: white;
            text-align: center;
            padding: 10px 0;
            margin-top: auto; /* Membuat footer berada di bagian paling bawah */
        }
    </style>
</head>
<body>
    <header class="text-center py-4">
        <h1 class="h3">Keranjang Belanja</h1>
        <p>Halo, <?php echo htmlspecialchars($_SESSION['name']); ?>! Berikut barang yang ada di keranjang Anda.</p>
    </header>

    <main class="container my-5">
        <div class="d-flex justify-content-between mb-4">
            <h2 class="text-center">Isi Keranjang</h2>
            <a href="dashboard_pembeli.php" class="btn btn-back">Kembali</a>
        </div>
        <?php if ($result->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Total Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo htmlspecialchars($row['nama_barang']); ?></td>
                                <td>Rp<?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                                <td><?php echo $row['jumlah']; ?></td>
                                <td>Rp<?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-center bg-light text-dark py-3 rounded">Keranjang Anda kosong.</p>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; 2024 Toko Online. Semua Hak Dilindungi.</p>
    </footer>

    <script src="bootstrap 5/js/bootstrap.bundle.min.js"></script>
</body>
</html>
