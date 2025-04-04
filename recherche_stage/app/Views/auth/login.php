<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="public/css/login.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
        <div class="left-side"></div>
        <div class="right-side">
            <img src="public/images/LOGOPNG.png" alt="Logo" class="logo">
            <h1>Hey, Hello 👋</h1>
            <p>Enter your email and password to login.</p>

            <?php if (isset($error)): ?>
                <p style="color:red;"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <form method="post" action="index.php?controller=auth&action=login" id="login-form">
                <label>Email</label>
                <input type="email" name="email" id="email" placeholder="Enter your email" required value="<?php echo isset($_COOKIE['remember_me']) ? htmlspecialchars(json_decode(base64_decode($_COOKIE['remember_me']), true)['user_email']) : ''; ?>">

                <label>Mot de passe</label>
                <div class="password-container">
                    <input type="password" name="password" id="password" placeholder="Enter your password" required value="<?php echo isset($_COOKIE['remember_me']) ? htmlspecialchars(json_decode(base64_decode($_COOKIE['remember_me']), true)['user_password']) : ''; ?>">
                    <i class="fas fa-eye" id="togglePassword"></i>
                </div>

                <div class="options">
                    <!-- Option pour "remember me" -->
                    <label>
                        <input type="checkbox" name="remember_me"> Se souvenir de moi
                    </label>
                </div>

                <button type="submit">Se connecter</button>
            </form>
        </div>
    </div>

    <script src="public/js/login.js"></script>
    <script src="public/js/cookieBanner.js"></script>
</body>
</html>
