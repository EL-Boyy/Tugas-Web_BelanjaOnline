<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "belanja_online");

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Tangkap data dari form
$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password
$email = $_POST['email'];
$nama = $_POST['nama'];
$alamat = $_POST['alamat'];
$no_telepon = $_POST['no_telepon'];

// Simpan ke database
$sql = "INSERT INTO login_pembeli (username, password, email, nama, alamat, no_telepon) 
        VALUES ('$username', '$password', '$email', '$nama', '$alamat', '$no_telepon')";

if ($conn->query($sql) === TRUE) {
    echo "Registrasi berhasil. <a href='login_pembeli.php'>Login di sini</a>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
