<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'BCA Internet Banking'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJX3fHQ7gV2v1+Qb3Wj8tF2GvX5g6lOgP2/5yOqt5v5t1EN0fD8wbFfLAX4w" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="styles_input.css">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="yellow-bar">
            <button class="logout-btn">[ LOGOUT ]</button>
        </div>
        <div class="header-blue">
            <div class="header-left">
                <img src="Asset/logo.png" alt="BCA Logo" class="logo">
                <span class="header-title">INDIVIDUAL</span>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="content">
        <!-- Sidebar -->
        <div class="sidebar">
            <ul>
            <li><a href="index.php" class="<?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">Beranda</a></li>
<li><a href="info_saldo.php" class="<?php echo ($current_page == 'info_saldo.php') ? 'active' : ''; ?>">Informasi Rekening</a></li>
<li><a href="topup-saldo.php" class="<?php echo ($current_page == 'topup-saldo.php') ? 'active' : ''; ?>">Input Saldo</a></li>
<li><a href="transfer.php" class="<?php echo ($current_page == 'transfer.php') ? 'active' : ''; ?>">Transfer Dana</a></li>

            </ul>
        </div>

        <!-- Dynamic Content -->
        <div class="main-content">
            <?php echo $content ?? 'Konten tidak ditemukan.'; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="white-bar">
                <p>Copyright &copy; 2000 
                    <img src="Asset/bca.png" alt="Bank BCA" class="logo2">
                    All Rights Reserved
                </p>
            </div>
        </div>
    </footer>
</body>
</html>
