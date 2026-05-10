<?php
// Bu dosyada amaç, formdan gelen verileri güvenli biçimde alıp sonuç ekranında düzenli şekilde göstermek.
// =============================================
// İLETİŞİM_İSLE.PHP
// Form verilerini alır ve ekrana düzenli yazar
// =============================================

// Sadece POST kabul et
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: iletisim.html");
    exit;
}

// Verileri al ve temizle
// Verileri alırken trim ile boşlukları temizliyorum, htmlspecialchars ile de ekrana yazdırırken güvenli hale getiriyorum.
$adSoyad    = isset($_POST['adSoyad'])    ? htmlspecialchars(trim($_POST['adSoyad']))    : '';
$eposta     = isset($_POST['eposta'])     ? htmlspecialchars(trim($_POST['eposta']))     : '';
$telefon    = isset($_POST['telefon'])    ? htmlspecialchars(trim($_POST['telefon']))    : '';
$konu       = isset($_POST['konu'])       ? htmlspecialchars(trim($_POST['konu']))       : '';
$mesaj      = isset($_POST['mesaj'])      ? htmlspecialchars(trim($_POST['mesaj']))      : '';
$cinsiyet   = isset($_POST['cinsiyet'])   ? htmlspecialchars(trim($_POST['cinsiyet']))   : 'Belirtilmedi';
$yasAraligi = isset($_POST['yasAraligi']) ? htmlspecialchars(trim($_POST['yasAraligi'])) : 'Belirtilmedi';
$kvkk       = isset($_POST['kvkk'])       ? 'Kabul Edildi' : 'Kabul Edilmedi';

// İlgi alanları (checkbox array)
// Checkbox verileri dizi olarak geldiği için burada tek tek dolaşıp temizlemem gerekiyor.
$ilgiAlanlari = [];
if (isset($_POST['ilgiAlanlari']) && is_array($_POST['ilgiAlanlari'])) {
    foreach ($_POST['ilgiAlanlari'] as $ilgi) {
        $ilgiAlanlari[] = htmlspecialchars($ilgi);
    }
}
$ilgiStr = !empty($ilgiAlanlari) ? implode(', ', $ilgiAlanlari) : 'Seçilmedi';

// Gönderim zamanı
// Formun gönderildiği anı göstermek için tarih bilgisini burada alıyorum.
$gonderimZamani = date('d.m.Y H:i:s');
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form Sonucu | AFOZDEN</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Nunito:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <!-- Bu ekranı sonuç sayfası gibi düşündüm; kullanıcı gönderdiği bilgileri toplu şekilde tekrar görebilsin istedim. -->

  <!-- NAVİGASYON -->
  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <a class="navbar-brand" href="index.html">
        <i class="fas fa-code me-2"></i>Ahmet Fatih Özden
      </a>
      <div class="navbar-nav ms-auto">
        <a class="nav-link" href="iletisim.html">
          <i class="fas fa-arrow-left me-1"></i>Geri Dön
        </a>
      </div>
    </div>
  </nav>

  <div class="sayfa-baslik">
    <h1><i class="fas fa-check-circle me-3"></i>Form Alındı</h1>
    <p>Gönderilen veriler başarıyla işlendi</p>
  </div>

  <section class="py-5">
    <div class="container page-narrow">

      <div class="kart">
        <h5 class="text-accent font-baslik">
          <i class="fas fa-list me-2"></i>Gönderilen Form Verileri
        </h5>
        <small class="text-muted">Gönderim Zamanı: <?php echo $gonderimZamani; ?></small>

        <!-- Tablo yapısı sayesinde formdan gelen verileri daha okunur biçimde gösterebiliyorum. -->
        <table class="table table-dark table-borderless mt-3 mb-0">
          <tbody>
            <tr class="table-row-divider">
              <td class="table-label table-label-wide">Ad Soyad</td>
              <td><?php echo $adSoyad ?: '<span class="text-muted">Boş</span>'; ?></td>
            </tr>
            <tr class="table-row-divider">
              <td class="table-label">E-posta</td>
              <td><?php echo $eposta ?: '<span class="text-muted">Boş</span>'; ?></td>
            </tr>
            <tr class="table-row-divider">
              <td class="table-label">Telefon</td>
              <td><?php echo $telefon ?: '<span class="text-muted">Boş</span>'; ?></td>
            </tr>
            <tr class="table-row-divider">
              <td class="table-label">Konu</td>
              <td><?php echo $konu ?: '<span class="text-muted">Seçilmedi</span>'; ?></td>
            </tr>
            <tr class="table-row-divider">
              <td class="table-label">Cinsiyet</td>
              <td><?php echo $cinsiyet; ?></td>
            </tr>
            <tr class="table-row-divider">
              <td class="table-label">Yaş Aralığı</td>
              <td><?php echo $yasAraligi; ?></td>
            </tr>
            <tr class="table-row-divider">
              <td class="table-label">İlgi Alanları</td>
              <td><?php echo $ilgiStr; ?></td>
            </tr>
            <tr class="table-row-divider">
              <td class="table-label">KVKK</td>
              <td><?php echo $kvkk; ?></td>
            </tr>
            <tr>
              <td class="table-label">Mesaj</td>
              <td><?php echo $mesaj ?: '<span class="text-muted">Boş</span>'; ?></td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="text-center mt-4">
        <a href="iletisim.html" class="btn-ana">
          <i class="fas fa-redo me-2"></i>Yeni Form Gönder
        </a>
        <a href="index.html" class="btn-ikincil ms-3">
          <i class="fas fa-home me-2"></i>Ana Sayfa
        </a>
      </div>

    </div>
  </section>

  <footer>
    <div class="container">
      <p>© 2025 Afozden | Sakarya Üniversitesi Bilgisayar Mühendisliği</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
