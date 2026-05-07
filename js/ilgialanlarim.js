// Sayfadaki gerekli HTML elemanlarını başta seçiyorum ki sonradan tekrar tekrar aramak zorunda kalmayayım.
const aramaFormu = document.getElementById('filmAramaFormu');
const aramaInput = document.getElementById('filmAramaInput');
const durumMetni = document.getElementById('filmDurum');
const bolumBaslik = document.getElementById('filmBolumBaslik');
const filmListesi = document.getElementById('filmListesi');
const yukleniyor = document.getElementById('filmYukleniyor');
const bilgiKutusu = document.getElementById('filmBilgiKutusu');
const kategoriButonlari = document.querySelectorAll('.kategori-buton');

// Kullanıcıya küçük bilgi veya uyarı mesajı göstermek istediğimde bu fonksiyonu çağırıyorum.
// Tip warning gelirse kutu daha dikkat çekici görünsün istedim.
function kutuMesajiGoster(mesaj, tip = 'normal') {
  bilgiKutusu.className = `kart film-info-box ${tip === 'warning' ? 'film-info-warning' : ''}`;
  bilgiKutusu.textContent = mesaj;
}

// Bilgi kutusu her zaman görünmesin diye ihtiyaç olmadığında burada tekrar gizli hale getiriyorum.
function kutuMesajiGizle() {
  bilgiKutusu.className = 'kart film-info-box is-hidden';
  bilgiKutusu.textContent = '';
}

// Yeni veri gelmeden önce eski kartları temizliyorum.
// Bunu yapmamın sebebi, eski sonuçlarla yeni sonuçların birbirine karışmaması.
function gorunumSifirla() {
  filmListesi.innerHTML = '';
  kutuMesajiGizle();
}

// Veri yüklenirken kullanıcı boş ekran görmesin diye spinner'ı açıyorum.
function yukleniyorGoster() {
  gorunumSifirla();
  yukleniyor.classList.remove('is-hidden');
}

// Veri geldiğinde ya da hata olduğunda yükleniyor animasyonunu kapatıyorum.
function yukleniyorGizle() {
  yukleniyor.classList.add('is-hidden');
}

// API'den bazen açıklama dolu geliyor, bazen eksik geliyor.
// O yüzden burada önce açıklama var mı diye bakıyorum, yoksa yedek bir metin döndürüyorum.
function kartAciklamaOlustur(film) {
  const aciklama = film.description || film.shortDescription || '';

  if (aciklama.trim()) {
    return aciklama.trim();
  }

  return 'Bu film icin aciklama verisi su anda kullanilamiyor.';
}

// Her bir film için ekranda görünecek kart yapısını burada hazırlıyorum.
// Yani API'den gelen ham veriyi, kullanıcıya gözükecek HTML kartına burada dönüştürüyorum.
function filmKartiniOlustur(film) {
  const sutun = document.createElement('div');
  sutun.className = 'col-sm-6 col-lg-4';

  // Yıl, tür ve puan bilgileri varsa gösteriyorum; yoksa alanı boş geçiyorum.
  // Böylece eksik veri yüzünden tasarım bozulmamış oluyor.
  const yil = film.year ? `<span class="film-meta-pill">${film.year}</span>` : '';
  const tur = film.genre ? `<span class="film-meta-pill">${film.genre}</span>` : '';
  const puan = film.rating
    ? `<span class="puan-badge"><i class="fas fa-star me-1"></i>${film.rating}</span>`
    : '<span class="puan-badge">Film</span>';

  // innerHTML ile kartın tüm içeriğini tek seferde üretiyorum.
  // Poster, başlık, açıklama ve detay linki burada birleşmiş oluyor.
  sutun.innerHTML = `
    <article class="film-kart modern-film-card">
      <div class="modern-film-poster-wrap">
        <img src="${film.poster}" alt="${film.title} afisi" loading="lazy">
        <div class="modern-film-overlay">${puan}</div>
      </div>
      <div class="film-kart-icerik modern-film-content">
        <div class="modern-film-meta">
          ${tur}
          ${yil}
        </div>
        <h5>${film.title}</h5>
        <p class="api-card-desc text-secondary mb-4">${kartAciklamaOlustur(film)}</p>
        <a class="btn-ikincil modern-film-link" href="${film.url}" target="_blank" rel="noopener noreferrer">
          Detaya Git
        </a>
      </div>
    </article>
  `;

  return sutun;
}

// Elimde dizi halinde gelen filmleri tek tek dolaşıp sayfaya basıyorum.
// Her film için önce kart oluşturuluyor, sonra liste alanına ekleniyor.
function filmleriGoster(filmler) {
  filmListesi.innerHTML = '';
  filmler.forEach((film) => {
    filmListesi.appendChild(filmKartiniOlustur(film));
  });
}

// Kullanıcının hangi kategoriye bastığını görsel olarak belli etmek için aktif butonu burada güncelliyorum.
// Böylece ekranda hangi listenin açık olduğu daha net anlaşılıyor.
function aktifKategoriyiGuncelle(aktifButon) {
  kategoriButonlari.forEach((button) => {
    button.classList.toggle('aktif', button === aktifButon);
  });
}

// Burası işin merkez noktası diyebilirim.
// Çünkü kategori seçilince de arama yapılınca da en sonunda veri çekme işlemi burada gerçekleşiyor.
async function filmleriYukle(params, baslik) {
  yukleniyorGoster();
  bolumBaslik.textContent = baslik;
  durumMetni.textContent = 'Filmler yukleniyor...';

  try {
    // Parametreleri URL formatına çeviriyorum.
    // Mesela type=search&query=matrix gibi bir yapı oluşuyor.
    const sorgu = new URLSearchParams(params);
    const response = await fetch(`film_api.php?${sorgu.toString()}`, {
      method: 'GET',
      cache: 'no-store',
    });

    // PHP dosyasından JSON veri beklediğim için cevabı burada json olarak çözüyorum.
    const data = await response.json();
    yukleniyorGizle();

    // Eğer sunucu tarafında bir hata varsa bunu burada yakalayıp catch tarafına gönderiyorum.
    if (!response.ok || !data.ok) {
      throw new Error(data.message || 'Film verisi alinamadi.');
    }

    // Sonuç dizisi boş geldiyse kullanıcıya direkt uygun bir mesaj göstermek istedim.
    if (!Array.isArray(data.results) || data.results.length === 0) {
      kutuMesajiGoster('Bu secim icin gosterilecek film bulunamadi. Farkli bir arama ya da kategori dene.', 'warning');
      durumMetni.textContent = 'Sonuc bulunamadi.';
      return;
    }

    // Veri başarılı geldiyse kartları ekrana basıyorum.
    filmleriGoster(data.results);

    // PHP tarafı bazen gerçek API yerine yedek film listesi döndürebiliyor.
    // Bunu da kullanıcıya küçük bir durum metniyle anlatıyorum.
    durumMetni.textContent = data.fallback
      ? `${data.results.length} film listelendi. Yedek liste gosteriliyor.`
      : `${data.results.length} film listelendi.`;
  } catch (error) {
    // Buraya düşüyorsa ya fetch tarafında ya da veri çözümleme tarafında bir problem olmuş demektir.
    yukleniyorGizle();
    kutuMesajiGoster('Film verisi alinamadi. Birkac saniye sonra tekrar dene.', 'warning');
    durumMetni.textContent = error.message || 'Bir hata olustu.';
  }
}

// Kullanıcı arama formunu gönderdiğinde sayfanın normal şekilde yenilenmesini durduruyorum.
// Çünkü ben burada klasik form gönderimi yerine JavaScript ile arama yapmak istiyorum.
aramaFormu.addEventListener('submit', async (event) => {
  event.preventDefault();
  const arama = aramaInput.value.trim();

  // Çok kısa aramalarda gereksiz istek atılmasın diye en az 2 karakter şartı koydum.
  if (arama.length < 2) {
    durumMetni.textContent = 'Lutfen en az 2 karakterlik bir arama yap.';
    aramaInput.focus();
    return;
  }

  // Arama yapılınca kategori seçimi öne çıkmasın istedim, bu yüzden aktiflikleri temizliyorum.
  kategoriButonlari.forEach((button) => {
    button.classList.remove('aktif');
  });

  // Burada type=search parametresiyle PHP tarafına arama isteği gönderiyorum.
  await filmleriYukle({ type: 'search', query: arama }, `"${arama}" icin Sonuclar`);
});

// Her kategori butonuna tıklama olayı bağlıyorum.
// Kullanıcı hangi türe basarsa o türe ait film listesi yükleniyor.
kategoriButonlari.forEach((button) => {
  button.addEventListener('click', async () => {
    // Önce aktif butonu güncelliyorum ki görsel olarak hangi kategori seçili belli olsun.
    aktifKategoriyiGuncelle(button);

    // Sonra butonun data-kategori bilgisini alıp PHP dosyasına gönderiyorum.
    await filmleriYukle(
      { type: 'category', category: button.dataset.kategori },
      button.dataset.label || 'Film Listesi'
    );
  });
});

// Sayfa ilk açıldığında boş gelmesin diye varsayılan olarak popüler filmleri otomatik yüklüyorum.
filmleriYukle({ type: 'category', category: 'featured' }, 'Populer Filmler');
