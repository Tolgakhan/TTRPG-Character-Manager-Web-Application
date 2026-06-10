# Doom Grimoire — TTRPG Karakter ve Yetenek Yönetim Sistemi

Masaüstü rol yapma oyunları (TTRPG) için geliştirilmiş, kullanıcıların kendi karakter çetelesini oluşturup yönetebildiği bir web uygulamasıdır. Karanlık fantezi ve *doom metal* estetiğine sahip, Bootstrap 5 tabanlı bir arayüz sunar.

---

## Özellikler

- **Kullanıcı kaydı ve girişi** — Güvenli şifre hash'leme (`password_hash` / `password_verify`)
- **Oturum yönetimi** — PHP `$_SESSION` ile kimlik doğrulama
- **Karakter çetelesi CRUD işlemleri**
  - Oluşturma (Create)
  - Listeleme (Read) — yalnızca giriş yapan kullanıcının karakterleri
  - Düzenleme (Update)
  - Silme (Delete)
- **İstatistikler** — Güç (Strength), Çeviklik (Agility), Varlık (Presence)
- **Yetenekler / Beceriler** — JSON formatında dinamik beceri listesi
- **Karanlık tema** — Yüksek kontrastlı, okunabilir doom-fantezi arayüzü

---

## Teknolojiler

| Katman      | Teknoloji                          |
|-------------|-------------------------------------|
| Backend     | Saf (vanilla) PHP — framework yok   |
| Veritabanı  | MySQL / MariaDB                     |
| DB Erişimi  | PDO + Prepared Statements           |
| Frontend    | HTML5, Bootstrap 5 (CDN), özel CSS  |
| Mimari      | Nesne Yönelimli Programlama (OOP)   |

---

## Gereksinimler

- **PHP 8.0** veya üzeri
- **MySQL 5.7+** veya **MariaDB 10.2+** (JSON sütun desteği gerekir)
- PDO MySQL eklentisi (`pdo_mysql`)
- Yerel geliştirme için: PHP yerleşik sunucusu veya Apache/Nginx
- Canlı sunucu için: FTP/SFTP erişimi ve hosting paneli (cPanel vb.)

---

## Proje Yapısı

```
ttrpg-character-manager/
├── sql/
│   └── schema.sql              # Veritabanı oluşturma betiği
├── config/
│   └── db.php                  # PDO veritabanı bağlantı sınıfı
├── classes/
│   ├── User.php                # Kullanıcı kayıt ve giriş işlemleri
│   └── Character.php           # Karakter CRUD işlemleri
├── includes/
│   ├── auth.php                # Oturum kontrolü ve yardımcı fonksiyonlar
│   ├── header.php              # Ortak üst menü ve HTML başlığı
│   ├── footer.php              # Ortak alt bilgi ve script dosyaları
│   └── character_helpers.php   # Form render ve POST verisi işleme
├── assets/
│   ├── css/
│   │   └── doom-theme.css      # Özel karanlık tema stilleri
│   └── js/
│       └── app.js              # Dinamik beceri satırı yönetimi
├── index.php                   # Kontrol paneli (giriş gerekli)
├── login.php                   # Giriş sayfası
├── register.php                # Kayıt sayfası
├── logout.php                  # Çıkış işlemi
├── character_create.php        # Yeni karakter oluşturma
├── character_edit.php          # Karakter düzenleme
└── character_delete.php        # Karakter silme (onay ekranı)
```

---

## Veritabanı Yapısı

### `users` tablosu

| Sütun          | Açıklama                              |
|----------------|---------------------------------------|
| `id`           | Birincil anahtar                      |
| `username`     | Benzersiz kullanıcı adı               |
| `password_hash`| Hash'lenmiş şifre (düz metin değil)   |
| `created_at`   | Kayıt tarihi                          |

### `characters` tablosu

| Sütun            | Açıklama                                      |
|------------------|-----------------------------------------------|
| `id`             | Birincil anahtar                              |
| `user_id`        | `users.id` ile foreign key                    |
| `character_name` | Karakter adı                                  |
| `class`          | Sınıf / arketip                               |
| `strength`       | Güç (1–20)                                    |
| `agility`        | Çeviklik (1–20)                               |
| `presence`       | Varlık (1–20)                                 |
| `abilities`      | Beceriler (JSON)                              |
| `created_at`     | Oluşturulma tarihi                            |

**Beceri JSON örneği:**

```json
[
  {"name": "Kılıç Ustalığı", "rank": 3},
  {"name": "Karanlık Büyü", "rank": 2}
]
```

### Siteyi test edin

```
http://95.130.171.20/~st20360859016/login.php
```

---

## Sayfalar

| Dosya                  | Erişim        | Açıklama                        |
|------------------------|---------------|---------------------------------|
| `register.php`         | Misafir       | Yeni hesap oluşturma            |
| `login.php`            | Misafir       | Giriş yapma                     |
| `logout.php`           | Herkes        | Oturumu sonlandırma             |
| `index.php`            | Giriş gerekli | Karakter listesi (kontrol paneli)|
| `character_create.php` | Giriş gerekli | Yeni karakter oluşturma         |
| `character_edit.php`   | Giriş gerekli | Mevcut karakteri düzenleme      |
| `character_delete.php` | Giriş gerekli | Karakteri silme (onay ekranı)   |

---

## Güvenlik

Bu proje aşağıdaki güvenlik önlemlerini uygular:

- **SQL Injection koruması** — Tüm sorgular PDO prepared statement ile çalışır
- **Şifre güvenliği** — Şifreler `password_hash()` ile saklanır, `password_verify()` ile doğrulanır
- **Oturum tabanlı kimlik doğrulama** — `$_SESSION` kullanılır; özel auth çerezleri yoktur
- **Kullanıcı izolasyonu** — Her sorgu `user_id` ile kapsanır; kullanıcılar yalnızca kendi karakterlerini görür ve düzenler
- **XSS koruması** — Kullanıcı girdileri `htmlspecialchars()` ile ekrana yazdırılır

---

## Sık Karşılaşılan Sorunlar

| Sorun | Olası çözüm |
|-------|-------------|
| Veritabanı bağlantı hatası | `config/db.php` içindeki host, kullanıcı adı ve veritabanı adını kontrol edin. Hosting panelindeki tam adları kullanın. |
| Boş sayfa / 500 hatası | PHP sürümünün 8.0+ olduğundan emin olun. Hosting hata günlüğünü (error log) kontrol edin. |
| CSS/JS yüklenmiyor | `assets/` klasörünün sunucuya doğru yüklendiğini doğrulayın. |
| Foreign key hatası | Önce `users`, sonra `characters` tablosunu oluşturun. |

---

## Lisans

Bu proje eğitim amaçlı geliştirilmiştir. Özgürce kullanabilir ve değiştirebilirsiniz.
