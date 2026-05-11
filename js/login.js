// Kullanıcı isterse şifreyi açık görsün diye input tipini burada değiştiriyorum.
function sifreyiGoster() {
  const sifreAlani = document.getElementById('sifre');
  const gozIkonu = document.getElementById('gozIkonu');

  if (sifreAlani.type === 'password') {
    sifreAlani.type = 'text';
    gozIkonu.classList.remove('fa-eye');
    gozIkonu.classList.add('fa-eye-slash');
  } else {
    sifreAlani.type = 'password';
    gozIkonu.classList.remove('fa-eye-slash');
    gozIkonu.classList.add('fa-eye');
  }
}

// Form gönderilmeden önce alanlar doğru mu diye kontrol ettiğim ana fonksiyon burası.
function loginDenetle(olay) {
  const kullaniciAdi = document.getElementById('kullaniciAdi').value.trim();
  const sifre = document.getElementById('sifre').value.trim();
  const epostaRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  // Önce hata yok kabul ediyorum, bir sorun yakalarsam bunu true yapıyorum.
  let hataVar = false;

  document.getElementById('hata_kullaniciAdi').classList.remove('goster');
  document.getElementById('hata_sifre').classList.remove('goster');
  document.getElementById('kullaniciAdi').classList.remove('input-hatali');
  document.getElementById('sifre').classList.remove('input-hatali');
  document.getElementById('jsHataMesaji').innerHTML = '';

  // Mail boşsa ya da beklediğim formata uymuyorsa kullanıcıyı burada uyarıyorum.
  if (!kullaniciAdi || !epostaRegex.test(kullaniciAdi)) {
    document.getElementById('kullaniciAdi').classList.add('input-hatali');
    document.getElementById('hata_kullaniciAdi').classList.add('goster');
    hataVar = true;
  }

  // Şifre boş bırakılırsa formun gönderilmesini istemiyorum.
  if (!sifre) {
    document.getElementById('sifre').classList.add('input-hatali');
    document.getElementById('hata_sifre').classList.add('goster');
    hataVar = true;
  }

  // Hata varsa submit işlemini durdurup üstte genel uyarı gösteriyorum.
  if (hataVar) {
    olay.preventDefault();
    document.getElementById('jsHataMesaji').innerHTML = `
      <div class="alert-ozel alert-hata mb-3">
        <i class="fas fa-exclamation-circle me-2"></i>
        Lütfen tüm alanları doğru doldurun.
      </div>`;
  }
}

// Sayfa yüklenince buton ve form olaylarını bağlayıp sistemi aktif hale getiriyorum.
document.addEventListener('DOMContentLoaded', () => {
  const sifreBtn = document.getElementById('sifreBtnGoz');
  const form = document.getElementById('loginFormu');

  if (sifreBtn) {
    sifreBtn.addEventListener('click', sifreyiGoster);
  }

  if (form) {
    form.addEventListener('submit', loginDenetle);
  }
});
