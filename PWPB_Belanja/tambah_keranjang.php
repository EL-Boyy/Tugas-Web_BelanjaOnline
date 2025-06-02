<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
$conn = new mysqli("localhost", "root", "", "belanja_online");

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Cek metode request
if (isset($_POST['masuk'])) {
    $id_pembeli = $_POST['id_pembeli'];
    $id_barang = $_POST['id_barang'];
    $jumlah = $_POST['jumlah'];

    if (!$id_pembeli || !$id_barang || !$jumlah) {
        die("Data tidak lengkap: id_pembeli=$id_pembeli, id_barang=$id_barang, jumlah=$jumlah");
    }

    // Lanjutkan dengan logika penyimpanan...
}

    // Periksa apakah barang sudah ada di keranjang pembeli
    $sql_check = "SELECT * FROM keranjang WHERE id_pembeli = ? AND id_barang = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ii", $id_pembeli, $id_barang);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        // Jika barang sudah ada, tambahkan jumlah
        $sql_update = "UPDATE keranjang SET jumlah = jumlah + ? WHERE id_pembeli = ? AND id_barang = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("iii", $jumlah, $id_pembeli, $id_barang);
        $stmt_update->execute();
    } else {
        // Jika barang belum ada, masukkan barang baru ke keranjang
        $sql_insert = "INSERT INTO keranjang (id_pembeli, id_barang, jumlah) VALUES (?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("iii", $id_pembeli, $id_barang, $jumlah);
        $stmt_insert->execute();
    }

    // Redirect ke halaman keranjang
    header("Location: keranjang.php");
    exit;

