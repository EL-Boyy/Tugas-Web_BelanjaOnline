<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Pembeli</title>
    <!-- Link ke CSS Bootstrap 5 -->
    <link href="bootstrap 5/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #225e77; 
        }
        .login-container {
            background-color: #50a6d0; /* Warna biru muda */
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .btn-primary {
            border: none;
        }
        .image-container img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }
    </style>
</head>
<body>
<div class="container d-flex justify-content-between align-items-center min-vh-100">
    <!-- Bagian Gambar -->
    <div class="image-container w-50 me-4">
        <img src="gambar/AlbedoBase.png" alt="Gambar Albedo">
    </div>

    <!-- Bagian Form -->
    <div class="login-container w-50">
        <h2 class="text-center mb-4">Daftar Sebagai Pembeli</h2>
        <form action="register_pembeli_process.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" id="nama" name="nama" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea id="alamat" name="alamat" class="form-control" required></textarea>
            </div>

            <div class="mb-3">
                <label for="no_telepon" class="form-label">No. Telepon</label>
                <input type="text" id="no_telepon" name="no_telepon" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Daftar</button>
        </form>

        <p class="text-center mt-3">Sudah punya akun? <a href="login_pembeli.php">Login di sini</a></p>
    </div>
</div>

<!-- Tambahkan link ke JavaScript Bootstrap 5 -->
<script src="bootstrap 5/js/bootstrap.bundle.min.js"></script>
</body>
</html>
