# JSON to Word Converter

![JSON to Word Converter](https://raw.githubusercontent.com/HasanKoc33/jsonToWord/refs/heads/main/ss.png)
![JSON to Word Converter Output](https://raw.githubusercontent.com/HasanKoc33/jsonToWord/refs/heads/main/ss1.png)

Bu uygulama, JSON verilerini kolayca Word belgelerine dönüştürmenizi sağlayan bir web aracıdır. Kullanıcı dostu arayüzü sayesinde, JSON dosyalarınızdan istediğiniz verileri seçip Word belgesi olarak dışa aktarabilirsiniz.

## Özellikler

- 📁 Çoklu JSON dosya yükleme desteği
- 🔍 JSON verisi içinde interaktif gezinme
- ✨ Sürükle-bırak dosya yükleme
- 📝 Seçilen verileri Word belgesine aktarma
- 🎨 Modern ve kullanıcı dostu arayüz
- 🔧 Özelleştirilebilir çıktı formatı

## Gereksinimler

- PHP 7.0 veya üzeri
- XAMPP/Apache web sunucusu
- Composer (PHP bağımlılık yöneticisi)
- PhpOffice/PhpWord paketi

## Kurulum

1. Projeyi XAMPP'ın htdocs klasörüne klonlayın:
```bash
cd /Applications/XAMPP/xamppfiles/htdocs/
git clone [proje-url] jsonToWord
```

2. Proje dizinine gidin ve bağımlılıkları yükleyin:
```bash
cd jsonToWord
composer install
```

3. XAMPP'ı başlatın ve Apache sunucusunu çalıştırın

4. Tarayıcınızda aşağıdaki URL'i açın:
```
http://localhost/jsonToWord
```

## Kullanım

1. **JSON Dosyası Yükleme**
   - "Dosya Seç" butonuna tıklayın veya dosyaları sürükle-bırak yapın
   - Birden fazla JSON dosyası yükleyebilirsiniz
   - Yüklenen dosyalar sayfanın sol tarafında listelenecektir

2. **Veri Seçimi**
   - Yüklenen JSON dosyalarından birini seçin
   - JSON ağacında gezinerek istediğiniz veriyi bulun
   - İstediğiniz özelliği seçmek için üzerine tıklayın

3. **Word Belgesi Oluşturma**
   - İstediğiniz verileri seçtikten sonra "Seçili Dosyaları Dönüştür" butonuna tıklayın
   - Word belgesi otomatik olarak oluşturulup indirilecektir

## Özellik Detayları

- **Çoklu Dosya Desteği**: Birden fazla JSON dosyasını aynı anda yükleyip işleyebilirsiniz
- **İnteraktif JSON Görüntüleyici**: JSON verilerinizi hiyerarşik bir yapıda görüntüleyip gezinebilirsiniz
- **Akıllı Veri Seçimi**: İç içe geçmiş JSON verilerinde istediğiniz değerleri kolayca seçebilirsiniz
- **Özelleştirilmiş Word Çıktısı**: Seçilen veriler düzenli bir şekilde formatlanarak Word belgesine aktarılır

## Hata Giderme

- JSON dosyası yüklenemiyorsa, dosya formatını kontrol edin
- Word belgesi oluşturulamıyorsa, PHP'nin write iznine sahip olduğundan emin olun
- Sayfa yüklenmiyorsa, XAMPP/Apache servisinin çalıştığından emin olun

## Katkıda Bulunma

1. Bu projeyi fork edin
2. Yeni bir özellik dalı oluşturun (`git checkout -b yeni-ozellik`)
3. Değişikliklerinizi commit edin (`git commit -am 'Yeni özellik eklendi'`)
4. Dalınıza push yapın (`git push origin yeni-ozellik`)
5. Bir Pull Request oluşturun

## Lisans

Bu proje [MIT Lisansı](LICENSE) ile lisanslanmıştır.
