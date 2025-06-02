<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Penjual</title>
    <link rel="stylesheet" href="CSS/style_login_pembeli.css">
</head>
<body>
    <div class="login-container">
        <h2>Login Penjual</h2>
        <form action="login_process.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" class="btn-login">Login</button>
        </form>
        <p>Belum punya akun? 
            <a href="register_penjual.php">Daftar sebagai Penjual</a>
        </p>
    </div>
</body>
</html>
