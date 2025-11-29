<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\TaskParser;

class TaskSeeder extends Seeder
{
    public function run()
    {
        $text = <<<'EOT'
1-Fiyat listesine firmaların alış fiyatlarını girebileceğiz. Eğer firma bize API ile fiyatları veriyorsa o fiyatların üzerine biz kar marjımızı ekleyerek müşteriye göstereceğiz.Apı den gelmiyor ise yurtiçi tarafında kullandığımız fiyat listeleri var oraya yurtdışı alış ve satış fiyatlarını gireceğiz.
Firmalardan gelen fiyatlarda Entegrasyonların felaket senaryoları belirlenmeli ve kopuş anında datanın ne olacağına karar verilecek.
Entegrasyon testleri yazılmalı ve testler patladığında geliştirmeye izin verilmemeli. Bu testler sadece connection üzerine değil, fonksiyon bazlı da yazılmalı.
En kolayı anlık data çekmektir. Fakat belirli aralıklarla fiyatları çekmek de tartışılmalı. Ona göre tabloya nasıl yazılacağı ve nasıl bir mimari oluşturulacağı kararlaştırılacak.
Sistemde fiyat listesi ekleme zaten var. Bu tabloya gerekirse yeni sütunlar eklenerek üstüne geliştirme yapılabilir.
2-Ülke ve kargo ölçü bazlı Fiyat sorgulama yapan müşteri ekrana gelen fiyatlardan birini seçip yaparak üzerine tıklayınca kargo gönder sayfasına yönlenerek kargo gönder işlemlerini buradan yapacaktır.
Frontend tarafında eğer ülke Türkiye dışında bir ülke seçilirse adres posta kodu olarak eklenecek. Posta kodundan Google Maps Geocoding API ile adres verileri doldurulacak.
3-Müşterinin girmiş olduğu bilgiler bize onaya düşecektir gördüğümüz eksik varise müşterinin düzeltmesi için geri gönderelim ve geri gönderirken ona sms mesajı iletelim. tmm
Kargolar tablosunda status sütunu için yeni bir enum değeri (Approving) eklenip kargo yurtdışı ise bu durum atanacak.
4-Müşteri kargo gönderebilmesi için sisteme para yüklemesi gerekiyor kargosunu oluşturunca bakiye yetersiz olunca o saya üzerinden bakiyesini ödeyerek kaldığı yerden devam edip kargosunu gönderebilsin ve kargo gönderdeki işlemler bitince bize onaya düşsün
Hesapların accounting->wallets modelinde para birimlerine göre bakiyeler tutuluyor. Bakiye kontrolü bu modellere göre yapılacak.
5-Müşteri desi bilgisini az girmiş ise bize kargo firmasından dönen gerçek desi bilgisini apı den alıp müşterinin kartına satış fiyatlarımız üzerinden sistem otomatik güncelleyecek 
Hazır olan sistemde kargo güncellendiğinde fiyatlar otomatik hesaplanıp müşterinin bakiyesinden düşülüyor. Onay verilirken müşteri bakiyesi kontrol edilebilir.
6-MÜştirilere farklı firmaların farklı farklı fiyatları tanımlanabilir olacaktır. Hali hazırda var
Hazır olan sistemde fiyat listeleri bu şekilde çalışıyor. Fiyat listesine yurt içi veya yurt dışı olduğunu belirten bir sütun eklenecek.
7-Yurtdışı ayarlarında da müşteri yetkilendirmeleri tek ekrandan yapılabilir olmalıdır. Hali hazırda var
Hazır olan sistemde bu yetkilendirme var. Kargo gönderirken yurt dışı kargo yetkisi var mı diye kontrol edilecek.
8-Müşteri kargosu iade olunca sistem mail veya sms ile bildirmelidir iadelerin hangi şekillerde müşteriye bildirilmesi konusunda bilgileri biz vereceğiz.
Bildirim sistemine entegre edilecek
9-Müşteri önceki tüm gönderileri ile ilgili kayıtları ve belgeleri kendi ekranında görecektir.
Kargo listeleme sayfası zaten hazır.
10-Müşteri tüm gönderilerini tek sorgulama ekranından yapacaktır herhangi bir firmanın barkod numarasını yazınca o kargonun tüm detaylarını görebilecektir
11-Müşteri konşimento girerken ürün açıklamasında tükçe yazarken  o ürünün ismini İngilizce yazması gerekiyor yazdığı ürünün adı otomatik çeviri ile İngilizce olacaktır.
Gemini AI ya da DeepL gibi servisler kullanılabilir.
12-Vergilendirme ile ilgili değerler önceden girilecektir.
Kargo firmalarının ek ücretler (AdditionalFee) ilişkisine eklenip gerekli hesaplama fonksiyonu yazılacak.
13-Müşteri sisteme istediğinde kargosunun 2 adet resmini yükleyebilecektir.
Kargolar tablosuna bağlı kargo resimleri tablosu oluşturulup buraya yüklenebilir.
14-Sisteme ülke bazlı veya özel fiyatlar tanımlandığında müşterilere bildirim gönderebileceğiz bunu sms veya WhatsApp kanalları ile (WhatsApp ile toplu mesaj gönderme yazılımımızda var bunu sisteme entegre edebiliriz.
Sistemde hazır olan bildirimler sistemine entegre edilecek.
15-Müşterinin kargoları teslim olunca müşteriye seçeceğimiz iletişim kanalları ile ona bildirim yapabilelim. Ve yapabilirsek alıcı müşteriye de sms gönderelim veya mail gönderelim kargonuz yola çıktı diye.
Sistemde hazır olan bildirimler sistemine entegre edilecek.
16-Bazı ülkelerde bazı desi üzerinde gönderiler için özel fiyatlar oluyor bu durumlarda sisteme tanıtım yapınca bu değerler girilince müşteri özel fiyatı da görsün normal fiyatlarıda
Örnek Amerika için 30 desi üzeri gönderilerde 1 desi fiyatı 5 usd ama normalde bu normal gönderi olunca 10 usd müşteri fiyat hesaplarken bunu ekranda görsün tercihi kendi yapsın.
Pricing modeline kampanyalı fiyat sütunu eklenip eğer bu sütun değeri boş değilse verilerek çözülebilir.
17-Fiyatlarda expres gönderilerde teslim süreleri .5-8 gün olabiliyor diğer teslimler 3-5 gün oluyor biz müşteriye fiyat gösterirken teslim sürelerini de göstereceğiz.
Sistemde teslimat süreleri (DeliveryTime) modeli var. Bu model şuan sadece ilden ile teslimat süreleri için çalışıyor. Polimorfik tabloya çevrilerek hem ülke hem de il için kullanılabilir.
18-Yurtiçinde olduğu gibi tüm muhasebesel işlemler müşterinin cari kartında olacak bununla ilgili ayrıntıyı yeniden yazmıyorum sadece fark müşteri burada dövizle işlem yapıyor biz döviz olarak işlemleri tutacağız.
Hazır olan sistemde cari işlem (AccountingTransaction) zaten para birimi destekliyor.
19-Sisteme yeni kayıt olan müşteriye hoş geldiniz maili veya Whatsap ile gönderi yapılacak bu yurtiçinde de olacak.
Bildirim sistemine WhatsappChannel oluşturulacak.
20-Desiler burada buçuklu oluyor 1.5 gibi 2.5 gibi desi ayarlamaları bu şekilde yapılabilmeli.
Yurti içi kargolarda desi değeri aşağı ya da yukarı yuvarlanıyor. Eğer yurt dışı ise bu yuvarlama devre dışı bırakılacak ya da ondalık değer ayarlanacak.
21-Fiyat listelerinde indirim yapabilir olacağımız gibi artırmada yapabilmeliyiz mesela tüm fiyatlara %10 ekle veya %10'düş veya bazı desi aralıklarında bunları yap diyebilmeliyiz.
Pricing modeline kampanyalı fiyat sütunu eklenip eğer bu sütun değeri boş değilse verilerek çözülebilir.
22-Yurtdışı ile ilgili müşteri teklif isteyince o anda hesaplama yapılan ekrandaki fiyatları desi ülke bilgilerinin olduğu fiyatları müşteriye teklif olarak mail atıp bu teklifte müşterinin kartında kayıt altına almalıyız.
23-Sistemde hangi gönderiler için hangi evrakları tamamlanması konusunda bilgi verecek ekranımız olmalı 
A) Dosya
B) Numune gönderi
C) Mikro ihracat 
24-Müşteri kargo durumuyla ilgili tüm hareketlerini programdan görebileceği gibi bizim arada kullandığımız kargo taşıyıcı firmalarından aldığımız geri dönüşleri müşteriye ileteceğiz mesela pts kargo yola çıkınca bize mail gönderiyor bizde müşteriye aynı şekilde mail iletmeliyiz bu mail stocado dan gitmeli arada olan hiçbir firmanın bilgilerini müşteri görememelidir. Kargosunda sorun çıktı gümrükte takıldı bize aracı firmalardan bir bildirim gelince o bildirimi önce biz görüp sonra müşteriye bilgi vermek gerekiyorsa vereceğiz.
Sistemde kargo hareketleri zaten var eğer gerekirse yeni hareket türleri eklenecek.
25-Sistemde bazı raporlamalar  (raporlara eklemeler yapılacaktır)
     Günlük gönderi adeti
     Ülke adları
     Desi aralıkları
     Teslim edilen kargolar
      Yolda olan kargolar
      İade olan kargolar
      Gümrükte bekleyen kargolar
       Vergi çıkan kargolar
26-Teslimat Kanıt Belgesi eklenecek ürün teslim olduğunda taşıyıcı firmanın sistemine yüklediği belge
Kargolar tablosuna bir sütun ya da ilişkili tablo eklenip eğer taşıyıcı firma bu belgeyi veriyorsa otomatik vermiyorsa manuel eklenecek.
27-PTT DE YURTDIŞINDA KARGODA hacim büyük kilo düşük ise kilo fiyatını vereceğiz.Normalde diğer firmalarda hacim büyükse hacim kg büyükse kg baz alınır ama ptt deki durum kg küçük ise kg fiyatı verilir
28-Ups te yurtiçi ve yurtdışında yakıt ücretlerini aşağıdaki linkten otomatik çektirip hesaplamayı yaptırtacağız 
https://www.ups.com/tr/tr/support/shipping-support/shipping-costs-rates/fuel-surcharges.page
29-Yurtdışı kargo gönderirken veya hesaplama yaparken ana kriter ülkedeki adresin posta kodu.
Bu baz alınarak fiyatlandırma yapılıyor bizim entegre çalışacağımız firmalardan gerek fiyat hesaplama Gerekse kargo gönderirken posta kodunu alarak hesap yapmamız gerekiyor bu çok önemli bir durum. Bunu dikkate alarak işlemleri yapmamız gerekiyor .
Ayrıca yine yurtdışı kargolar ile ilgili kutu ebatları var mesela sağ kenar ölçüsü 101 olunca sol kenar 45 cm Olunca kargoya extra maliyet geliyor biz en-boy-yükseklik çarpıp sonucu alıyoruz örnek 45 desi çıktı Ama ups pts in bununla ilgili bir standardı var o standartlar programa girilecektir biz programdan ilk hesaplama yaparken bunu dikkate almalıyız.
Bununla ilgili kutu ebatları ne olunca extra maliyet geliyor biz size o ölçüleri vereceğiz o ölçülerde ona uygun Fiyatlandırma yapılacaktır.
Bu ölçüleri girdikten sonra normal ölçüler için program otomatik yönlendirme yapsın standart gönderinizi bu şekilde yaparsanız Maliyetiniz bukadar düşecektir diye bunu detaylı konuşalım Extra ücretler varise ekranda belirtilmeli 
Mikro ihracat gönderisi olunca mikro ihracat ile ilgili altta menüler gelmeli ve bu ekranlarda zorunlu alanlar var onlar zorunlu olmalı.
Numune olunca ilgili altta menüler açılmalı zorunlu olanlar doldurulmalı
Doküman olunca bununla ilgili altta menüler olmalı
Her gönderi türüne göre menüler gelmeli
EOT;

        // Create a default project if not exists, or get the first one
        $user = \App\Models\User::first();
        $project = \App\Models\Project::firstOrCreate(
            ['name' => 'Stocado Main'],
            [
                'description' => 'Main project for Stocado tasks',
                'created_by' => $user ? $user->id : 1, // Fallback to 1 if no user
            ]
        );

        // Attach user to project if not already attached
        if ($user && !$project->users()->where('user_id', $user->id)->exists()) {
            $project->users()->attach($user->id, ['role' => 'admin']);
        }

        $parser = new TaskParser();
        $parser->parseAndCreateTasks($text, $project->id);
    }
}
