<?php
session_start();

$girisYapildi = isset($_SESSION['giris_yapildi']) && $_SESSION['giris_yapildi'] === true;
$ogrenciNo = $girisYapildi ? ($_SESSION['ogrenci_no'] ?? 'b251210068') : '';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AFOZDEN</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Nunito:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <a class="navbar-brand" href="index.php">
        <i class="fas fa-code me-2"></i>Ahmet Fatih Özden
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navMenu">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link active" href="index.php">Hakkımda</a></li>
          <li class="nav-item"><a class="nav-link" href="cv.html">CV</a></li>
          <li class="nav-item"><a class="nav-link" href="sehrim.html">Şehrim</a></li>
          <li class="nav-item"><a class="nav-link" href="mirasimiz.html">Mirasımız</a></li>
          <li class="nav-item"><a class="nav-link" href="ilgialanlarim.html">İlgi Alanlarım</a></li>
          <li class="nav-item"><a class="nav-link" href="iletisim.html">İletişim</a></li>
          <?php if ($girisYapildi): ?>
          <li class="nav-item"><span class="nav-link nav-session-badge"><i class="fas fa-user-check me-1"></i><?php echo htmlspecialchars($ogrenciNo); ?></span></li>
          <li class="nav-item"><a class="nav-link nav-link-logout" href="cikis.php"><i class="fas fa-sign-out-alt me-1"></i>Çıkış Yap</a></li>
          <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="login.php"><i class="fas fa-lock me-1"></i>Giriş</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <section class="hero-bolum">
    <div class="container">
      <div class="row align-items-center g-5">
        <div class="col-lg-4 text-center">
          <div class="profil-resim-cerceve mx-auto mb-3">
            🤖
          </div>
          <div class="d-flex flex-wrap justify-content-center gap-2 mt-3">
            <span class="yetenek-badge"><i class="fas fa-code me-1"></i>Web Dev</span>
            <span class="yetenek-badge"><i class="fas fa-robot me-1"></i>AI/Robotik</span>
            <span class="yetenek-badge"><i class="fas fa-dumbbell me-1"></i>Fitness</span>
            <span class="yetenek-badge"><i class="fas fa-palette me-1"></i>Tasarım</span>
          </div>
        </div>
        <div class="col-lg-8">
          <?php if ($girisYapildi): ?>
          <div class="session-welcome-chip mb-3">
            <i class="fas fa-circle-check me-2"></i>Oturum açık: <?php echo htmlspecialchars($ogrenciNo); ?>
          </div>
          <?php endif; ?>
          <p class="hero-unvan">Bilgisayar Mühendisliği Öğrencisi</p>
          <h1 class="hero-isim mt-2 mb-3">Merhaba, ben<br>Ahmet Fatih Özden</h1>
          <p class="text-secondary fs-5 mb-4">
            Sakarya Üniversitesi'nde 1. sınıf öğrencisiyim. Robotik, yapay zeka ve web geliştirme
            alanlarıyla ilgileniyorum. Küçük işletmelere web sitesi yapıyorum ve ileride
            kendi şirketimi kurmayı hedefliyorum.
          </p>
          <div class="d-flex flex-wrap gap-3">
            <a href="cv.html" class="btn-ana">
              <i class="fas fa-file-alt me-2"></i>CV'mi Gör
            </a>
            <a href="iletisim.html" class="btn-ikincil">
              <i class="fas fa-envelope me-2"></i>İletişime Geç
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="py-5">
    <div class="container">
      <div class="row g-4">
        <div class="col-lg-6">
          <div class="kart">
            <h4><i class="fas fa-user me-2"></i>Kişisel Bilgiler</h4>
            <div class="d-flex align-items-center mb-3 mt-3">
              <div class="bilgi-ikon">🎂</div>
              <div>
                <small class="text-muted">Doğum Tarihi</small>
                <div>17 Eylül 2005 - Diyarbakır'da doğdum, 6 aylıkken Kayseri'ye taşındım</div>
              </div>
            </div>
            <div class="d-flex align-items-center mb-3">
              <div class="bilgi-ikon">📍</div>
              <div>
                <small class="text-muted">Yaşadığım Yer</small>
                <div>Bursa, aslen Sivaslıyım</div>
              </div>
            </div>
            <div class="d-flex align-items-center mb-3">
              <div class="bilgi-ikon">🎓</div>
              <div>
                <small class="text-muted">Üniversite</small>
                <div>Sakarya Üniversitesi - Bilgisayar Mühendisliği 1. Sınıf</div>
              </div>
            </div>
            <div class="d-flex align-items-center">
              <div class="bilgi-ikon">🏙️</div>
              <div>
                <small class="text-muted">Geçmiş</small>
                <div>Hayatımın 16 yılını Kayseri'de yaşadım</div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="kart">
            <h4><i class="fas fa-heart me-2"></i>Hobiler ve Etkinlikler</h4>
            <ul class="list-unstyled mt-3">
              <li class="mb-3 d-flex align-items-center gap-2">
                <span class="emoji-md">🏋️</span>
                <div><strong>Fitness</strong> - Düzenli antrenman yapmayı seviyorum</div>
              </li>
              <li class="mb-3 d-flex align-items-center gap-2">
                <span class="emoji-md">💻</span>
                <div><strong>Web Geliştirme</strong> - Küçük işletmeler için web siteleri tasarlıyorum</div>
              </li>
              <li class="mb-3 d-flex align-items-center gap-2">
                <span class="emoji-md">🎨</span>
                <div><strong>Grafik ve Video</strong> - Photoshop, Illustrator, Sony Vegas Pro, After Effects</div>
              </li>
              <li class="mb-3 d-flex align-items-center gap-2">
                <span class="emoji-md">🤝</span>
                <div><strong>Network Kurma</strong> - Yeni insanlar tanımayı ve çevre genişletmeyi seviyorum</div>
              </li>
              <li class="d-flex align-items-center gap-2">
                <span class="emoji-md">📦</span>
                <div><strong>E-Ticaret</strong> - Trendyol'da mağaza işlettim, ticaret hep ilgimi çekiyor</div>
              </li>
            </ul>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="kart">
            <h4><i class="fas fa-bullseye me-2"></i>Hedeflerim</h4>
            <div class="mt-3">
              <div class="mb-3 p-3 highlight-card">
                <strong>🦾 Robot Kol</strong>
                <p class="mb-0 mt-1 text-secondary small">Yapay zeka destekli otomasyon sistemleri ve robot kol geliştirmek istiyorum.</p>
              </div>
              <div class="mb-3 p-3 highlight-card">
                <strong>🎙️ Sesli Asistan</strong>
                <p class="mb-0 mt-1 text-secondary small">Türkçe destekli, kişisel kullanım için sesli asistan projesi.</p>
              </div>
              <div class="p-3 highlight-card">
                <strong>🏠 Akıllı Ev</strong>
                <p class="mb-0 mt-1 text-secondary small">Yapay zeka ile entegre akıllı ev araçları geliştirmek.</p>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="kart">
            <h4><i class="fas fa-star me-2"></i>En Sevdiklerim</h4>
            <div class="mt-3">
              <div class="mb-3 d-flex align-items-center gap-3">
                <span class="emoji-lg">📺</span>
                <div>
                  <strong>En Sevdiğim Dizi</strong>
                  <p class="mb-0 text-secondary small">Leyla ile Mecnun - Hem komik hem derin, her sahnesinde farklı bir şey fark ediyorsun.</p>
                </div>
              </div>
              <div class="mb-3 d-flex align-items-center gap-3">
                <span class="emoji-lg">🛒</span>
                <div>
                  <strong>Girişimcilik</strong>
                  <p class="mb-0 text-secondary small">Trendyol'daki e-ticaret deneyimim bana ticaretin tadını tattırdı.</p>
                </div>
              </div>
              <div class="d-flex align-items-center gap-3">
                <span class="emoji-lg">🌆</span>
                <div>
                  <strong>Şehirler</strong>
                  <p class="mb-0 text-secondary small">Kayseri'de büyüdüm, şu an Bursa'da yaşıyorum.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="py-5 section-surface">
    <div class="container">
      <h2 class="text-center mb-4 fw-bold font-baslik">
        <span class="text-accent">Kullandığım</span> Araçlar
      </h2>
      <div class="row g-3 text-center">
        <div class="col-6 col-md-3">
          <div class="kart">
            <div class="emoji-xl">🖼️</div>
            <div class="mt-2 fw-semibold">Photoshop</div>
            <small class="text-muted">Görsel Düzenleme</small>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="kart">
            <div class="emoji-xl">✏️</div>
            <div class="mt-2 fw-semibold">Illustrator</div>
            <small class="text-muted">Vektör Tasarım</small>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="kart">
            <div class="emoji-xl">🎬</div>
            <div class="mt-2 fw-semibold">Sony Vegas Pro</div>
            <small class="text-muted">Video Düzenleme</small>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="kart">
            <div class="emoji-xl">✨</div>
            <div class="mt-2 fw-semibold">After Effects</div>
            <small class="text-muted">Animasyon ve VFX</small>
          </div>
        </div>
      </div>
    </div>
  </section>

  <footer>
    <div class="container">
      <p>&copy; 2025 Afozden | Sakarya Üniversitesi Bilgisayar Mühendisliği</p>
      <p class="mt-1">
        <a href="https://github.com/afzdnn58-png" target="_blank"><i class="fab fa-github me-2"></i>GitHub</a>
      </p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
