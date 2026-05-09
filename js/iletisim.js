// Bu fonksiyon, hatalı alanı kırmızı gösterip ilgili hata mesajını açıyor.
function inputHata(id, hataMesajiId) {
  const el = document.getElementById(id);
  el.classList.add('input-hatali');
  el.classList.remove('input-basarili');
  document.getElementById(hataMesajiId).classList.add('goster');
}

// Bu fonksiyon, doğru doldurulan alanı başarılı şekilde işaretliyor.
function inputBasarili(id, hataMesajiId) {
  const el = document.getElementById(id);
  el.classList.add('input-basarili');
  el.classList.remove('input-hatali');
  if (hataMesajiId) {
    document.getElementById(hataMesajiId).classList.remove('goster');
  }
}

// Her yeni denemede eski hata izlerini temizliyorum ki ekran karışık görünmesin.
function tumHatalariTemizle() {
  const alanlar = ['adSoyad', 'eposta', 'telefon', 'konu', 'mesaj', 'kvkk'];
  alanlar.forEach((alan) => {
    const el = document.getElementById(alan);
    if (el) {
      el.classList.remove('input-hatali', 'input-basarili');
    }
    const hataEl = document.getElementById(`hata_${alan}`);
    if (hataEl) {
      hataEl.classList.remove('goster');
    }
  });
  document.getElementById('genelMesaj').innerHTML = '';
}

// Tüm doğrulama kurallarını tek yerde toplayıp iki farklı yaklaşımda da ortak kullanıyorum.
function kurallar() {
  const adSoyad = document.getElementById('adSoyad').value.trim();
  const eposta = document.getElementById('eposta').value.trim();
  const telefon = document.getElementById('telefon').value.trim();
  const konu = document.getElementById('konu').value;
  const mesaj = document.getElementById('mesaj').value.trim();
  const kvkk = document.getElementById('kvkk').checked;

  // Regex ile temel e-posta ve telefon formatını kontrol ediyorum.
  const epostaRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  const telefonRegex = /^[0-9]{10,11}$/;

  return {
    adSoyad: adSoyad.length < 3,
    eposta: !epostaRegex.test(eposta),
    telefon: !telefonRegex.test(telefon),
    konu: konu === '',
    mesaj: mesaj.length < 10,
    kvkk: !kvkk,
  };
}

// Bu bölümde saf JavaScript ile form doğrulaması yapıyorum.
function vanillaJsDenetle() {
  tumHatalariTemizle();
  const hatalar = kurallar();
  let hataVar = false;

  // Hata nesnesini dolaşıp hangi alan yanlışsa ona göre görünüm veriyorum.
  for (const [alan, hatalimi] of Object.entries(hatalar)) {
    if (hatalimi) {
      inputHata(alan, `hata_${alan}`);
      hataVar = true;
    } else if (alan !== 'kvkk') {
      inputBasarili(alan, `hata_${alan}`);
    }
  }

  // Kullanıcının üstte genel sonucu görmesi için mesaj alanını ayrıca güncelliyorum.
  const mesajAlani = document.getElementById('genelMesaj');
  if (hataVar) {
    mesajAlani.innerHTML = `
      <div class="alert-ozel alert-hata mb-3">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Vanilla JS:</strong> Formda hatalar var, lütfen düzeltin.
      </div>`;
  } else {
    mesajAlani.innerHTML = `
      <div class="alert-ozel alert-basari mb-3">
        <i class="fas fa-check-circle me-2"></i>
        <strong>Vanilla JS:</strong> Form doğrulama başarılı! Form PHP'ye gönderiliyor...
      </div>`;
    setTimeout(() => {
      document.getElementById('iletisimFormu').submit();
    }, 1200);
  }
}

// Bu kısımda aynı doğrulama mantığını Vue yaklaşımıyla göstermeye çalıştım.
function vueJsDenetle() {
  tumHatalariTemizle();
  const hatalar = kurallar();
  const { createApp, ref, reactive } = Vue;

  // Vue'yu burada küçük bir bileşen gibi kullanıp reactive mesaj üretmek istedim.
  const uygulama = createApp({
    setup() {
      // Her alanın hata mı başarılı mı olduğunu tek nesnede tutuyorum.
      const durumlar = reactive({
        adSoyad: hatalar.adSoyad ? 'hata' : 'basarili',
        eposta: hatalar.eposta ? 'hata' : 'basarili',
        telefon: hatalar.telefon ? 'hata' : 'basarili',
        konu: hatalar.konu ? 'hata' : 'basarili',
        mesaj: hatalar.mesaj ? 'hata' : 'basarili',
        kvkk: hatalar.kvkk ? 'hata' : 'basarili',
      });

      // En az bir hata var mı yok mu bilgisini genel mesaj için kullanıyorum.
      const hataVar = Object.values(hatalar).some((h) => h);
      const mesaj = ref(
        hataVar
          ? '⛔ Vue.js: Formda hatalar var, lütfen düzeltin.'
          : '✅ Vue.js doğrulama başarılı! Form gönderiliyor...'
      );
      const mesajTipi = ref(hataVar ? 'alert-hata' : 'alert-basari');

      // Vue mesajı reactive olsa da input class değişimlerini burada doğrudan veriyorum.
      for (const [alan, durum] of Object.entries(durumlar)) {
        const el = document.getElementById(alan);
        const hataEl = document.getElementById(`hata_${alan}`);
        if (!el) {
          continue;
        }
        if (durum === 'hata') {
          el.classList.add('input-hatali');
          if (hataEl) {
            hataEl.classList.add('goster');
          }
        } else if (alan !== 'kvkk') {
          el.classList.add('input-basarili');
        }
      }

      return { mesaj, mesajTipi, hataVar };
    },
    // Template kısmında sadece üst taraftaki bilgilendirme kutusunu oluşturuyorum.
    template: `
      <div :class="'alert-ozel mb-3 ' + mesajTipi">
        <i :class="hataVar ? 'fas fa-times-circle me-2' : 'fas fa-check-circle me-2'"></i>
        <strong>Vue.js:</strong> {{ mesaj.replace('⛔ Vue.js: ', '').replace('✅ Vue.js doğrulama başarılı! ', '') }}
        <span v-if="!hataVar"> Form gönderiliyor...</span>
      </div>
    `,
  });

  // Vue uygulamasını sadece mesaj alanına bağlıyorum.
  const mesajKonteyner = document.getElementById('genelMesaj');
  uygulama.mount(mesajKonteyner);

  // Eğer hata yoksa kısa bekleme sonrası formu PHP tarafına gönderiyorum.
  if (!Object.values(hatalar).some((h) => h)) {
    setTimeout(() => {
      document.getElementById('iletisimFormu').submit();
    }, 1200);
  }
}

// Sayfa tamamen yüklenince buton olaylarını bağlayıp sistemi aktif hale getiriyorum.
document.addEventListener('DOMContentLoaded', () => {
  const vanillaBtn = document.getElementById('vanillaBtn');
  const vueBtn = document.getElementById('vueBtn');

  if (vanillaBtn) {
    vanillaBtn.addEventListener('click', vanillaJsDenetle);
  }

  if (vueBtn) {
    vueBtn.addEventListener('click', vueJsDenetle);
  }
});
