<?php
session_start();

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "belanja_online");

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Cek apakah form login dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Tetapkan role berdasarkan file asal
    $role = isset($_POST['role']) ? $_POST['role'] : null;

    // Tentukan role secara otomatis
    if (strpos($_SERVER['HTTP_REFERER'], 'login_pembeli.php') !== false) {
        $role = 'pembeli';
    } elseif (strpos($_SERVER['HTTP_REFERER'], 'login_penjual.php') !== false) {
        $role = 'penjual';
    }

    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($role === 'pembeli') {
        $table = 'login_pembeli';
        $redirect = 'dashboard_pembeli.php';
    } elseif ($role === 'penjual') {
        $table = 'login_penjual';
        $redirect = 'dashboard_penjual.php';
    } else {
        die("Role tidak diketahui.");
    }

    // Query untuk memeriksa username di tabel terkait
    $stmt = $conn->prepare("SELECT * FROM $table WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Ambil data user
        $user = $result->fetch_assoc();

        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            // Simpan data ke sesi
            $_SESSION['role'] = $role;
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['name'] = $role === 'pembeli' ? $user['nama'] : $user['nama_toko'];
            $_SESSION['alamat'] = $user['alamat'];
            $_SESSION['no_telepon'] = $user['no_telepon'];
            $_SESSION['id_pembeli'] = true;
            $_SESSION['pembeli'] = $user['id_pembeli'];
            // Arahkan ke dashboard
            header("Location: $redirect");
            exit;
        } else {
            echo "Password salah.";
        }
    } else {
        echo "Username tidak ditemukan.";
    }

    $stmt->close();
}
$conn->close();
?>
