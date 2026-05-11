<?php
// =============================================
// HOŞGELDİNİZ.PHP
// Giriş sonrası gösterilen sayfa
// =============================================

session_start();

// Oturum yoksa login'e geri gönder
if (!isset($_SESSION['giris_yapildi']) || $_SESSION['giris_yapildi'] !== true) {
    header("Location: login.php");
    exit;
}

$ogrenci_no = $_SESSION['ogrenci_no'];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hoşgeldiniz | AFOZDEN</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Nunito:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <div class="login-sayfa">
    <div class="login-kutu text-center">

      <div class="welcome-icon">🎉</div>

      <h2 class="font-baslik text-accent">Hoşgeldiniz</h2>
      <h3 class="mt-2 mb-4">
        <?php echo htmlspecialchars($ogrenci_no); ?>
      </h3>

      <p class="text-muted">Sisteme başarıyla giriş yaptınız.</p>

      <div class="d-flex flex-column gap-3 mt-4">
        <a href="index.php" class="btn-ana">
          <i class="fas fa-home me-2"></i>Ana Sayfaya Git
        </a>
        <a href="cikis.php" class="btn-ikincil">
          <i class="fas fa-sign-out-alt me-2"></i>Çıkış Yap
        </a>
      </div>

    </div>
  </div>

</body>
</html>
