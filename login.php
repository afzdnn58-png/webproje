<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Giriş | AFOZDEN</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Nunito:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <!-- Bu sayfada kullanıcıdan giriş bilgilerini alıp önce JS sonra PHP tarafında kontrol etmek istedim. -->

  <div class="login-bg-layer"></div>
  <div class="login-bg-glow"></div>

  <div class="login-sayfa">
    <div class="login-kutu">
      <div class="login-logo">
        <div class="login-logo-icon">🔐</div>
        <h2>Güvenli Giriş</h2>
        <p class="mini-muted-link">AFOZDEN Kişisel Sitem</p>
      </div>

      <!-- PHP tarafında hata parametresi dönerse kullanıcıya burada uyarı gösteriyorum. -->
      <?php if (isset($_GET['hata']) && $_GET['hata'] == '1'): ?>
      <div class="alert-ozel alert-hata mb-3">
        <i class="fas fa-exclamation-triangle me-2"></i>
        Kullanıcı adı veya şifre hatalı!
      </div>
      <?php endif; ?>

      <!-- JavaScript ile oluşan anlık hata mesajları bu alana basılıyor. -->
      <div id="jsHataMesaji"></div>

      <!-- Tarayıcı uyarıları yerine kendi yazdığım doğrulama mesajlarını göstermek için novalidate ekledim. -->
      <form action="login_kontrol.php" method="POST" id="loginFormu" novalidate>
        <div class="mb-3">
          <label class="form-label">
            <i class="fas fa-user me-1 text-accent"></i>
            Kullanıcı Adı (Mail)
          </label>
          <input
            type="email"
            class="form-control"
            id="kullaniciAdi"
            name="kullaniciAdi"
            placeholder="b251210068@sakarya.edu.tr"
            autocomplete="username"
          >
          <div class="hata-mesaj" id="hata_kullaniciAdi">
            Geçerli bir e-posta adresi girin (örn: b251210068@sakarya.edu.tr)
          </div>
        </div>

        <!-- Şifreyi göster/gizle özelliğini kullanıcı deneme yaparken daha rahat olsun diye ekledim. -->
        <div class="mb-4">
          <label class="form-label">
            <i class="fas fa-lock me-1 text-accent"></i>
            Şifre
          </label>
          <div class="password-wrap">
            <input
              type="password"
              class="form-control password-input"
              id="sifre"
              name="sifre"
              placeholder="Öğrenci numaranız"
              autocomplete="current-password"
            >
            <button type="button" class="password-toggle" id="sifreBtnGoz">
              <i class="fas fa-eye" id="gozIkonu"></i>
            </button>
          </div>
          <div class="hata-mesaj" id="hata_sifre">Şifre boş bırakılamaz.</div>
        </div>

        <button type="submit" class="btn-ana w-100">
          <i class="fas fa-sign-in-alt me-2"></i>Giriş Yap
        </button>
      </form>

      <div class="text-center mt-4">
        <a href="index.php" class="mini-muted-link">
          <i class="fas fa-arrow-left me-1"></i>Ana Sayfaya Dön
        </a>
      </div>

      <!-- Test amacıyla örnek giriş bilgilerini ayrıca kutu içinde gösterdim. -->
      <div class="mt-4 p-3 test-box">
        <strong class="text-accent">Test Bilgileri:</strong><br>
        📧 b251210068@sakarya.edu.tr<br>
        🔑 b251210068
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js/login.js"></script>
</body>
</html>
