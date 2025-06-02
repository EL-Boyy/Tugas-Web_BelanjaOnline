<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pembeli</title>
    <!-- Bootstrap CSS -->
    <link href="bootstrap 5/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #225e77; /* Biru */
        }
        .login-container {
            background-color: #50a6d0; /* Warna biru muda */
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .btn-login {
            width: 100%;
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
        <h2 class="text-center mb-4">Login Pembeli</h2>
        <form action="login_process.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary btn-login w-100">Login</button>
        </form>
        <p class="mt-3 text-center">
            Belum punya akun? 
            <a href="register_pembeli.php">Daftar sebagai Pembeli</a>
        </p>
    </div>
</div>

<!-- Bootstrap JS Bundle -->
<script src="bootstrap 5/js/bootstrap.bundle.min.js"></script>
</body>
</html>
