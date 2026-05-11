<?php
// Bu dosyada giriş formundan gelen bilgileri kontrol edip doğruysa kullanıcıyı oturumla içeri alıyorum.
// =============================================
// LOGIN_KONTROL.PHP
// Giriş bilgilerini kontrol eder
// =============================================

// Tanımlı kullanıcı bilgileri
// Veritabanı yerine sabit bilgi kullandım çünkü burada amacım temel giriş mantığını göstermekti.
$dogru_kullanici = "b251210068@sakarya.edu.tr";
$dogru_sifre     = "b251210068";

// Sadece POST isteği kabul et
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

// Formdan gelen verileri al ve temizle
// Formdan gelen değerleri alıp baştaki ve sondaki boşlukları temizliyorum.
$kullanici_adi = isset($_POST['kullaniciAdi']) ? trim($_POST['kullaniciAdi']) : '';
$sifre         = isset($_POST['sifre'])        ? trim($_POST['sifre'])        : '';

// Boş alan kontrolü
// Eğer kullanıcı alanlardan birini boş bırakırsa tekrar giriş sayfasına yönlendiriyorum.
if (empty($kullanici_adi) || empty($sifre)) {
    header("Location: login.php?hata=1");
    exit;
}

// Kullanıcı adı ve şifre kontrolü
// Burada kullanıcının girdiği değerleri doğru bilgilerle birebir karşılaştırıyorum.
if ($kullanici_adi === $dogru_kullanici && $sifre === $dogru_sifre) {
    // Başarılı giriş
    // Bilgiler doğruysa session başlatıp kullanıcının giriş yaptığını saklıyorum.
    session_start();
    $_SESSION['giris_yapildi'] = true;
    $_SESSION['ogrenci_no']    = "b251210068";
    header("Location: hosgeldiniz.php");
    exit;
} else {
    // Hatalı giriş
    // Eşleşme yoksa hata parametresiyle tekrar login ekranına gönderiyorum.
    header("Location: login.php?hata=1");
    exit;
}
?>
