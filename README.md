

# Fpdo
( Version:beta )

PDO tabanlı class dosyası.

| veri işleme tipleri | Açıklama |
| ------ | ------ |
| select | sqldeki verileri okur |
| update | verileri günceler (**where** ve **set** zorunludur) |
| insert | Yeni veriyi sql'e kayıt eder |
| delete | Sql verisini siler  (**where** ile birlikte kullanılabilir) |

öncelikle pdo bağlantımızı yapalım
```php
try {
     $db = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASSWORD);
} catch ( PDOException $e ){
	die($e->getMessage());
}
/*SQL bağlantımızı yaptıktan sonra fpdo.class.php dosyamızı çağırıyoruz*/
require_once("fpdo.class.php");
	$fpdo = new fpdo($db);
```
SQL tablomuz aşağıdaki şekilde olsun
| id | isim | kullanici_adi | sifre | yetki | email |
| ------ | ------ |------ |------ |------ |------ |
| 1 | Furkan |  furkan | frkn#=YbswaRm*B9 | 1 | eposta@furkansenturk.com.tr|
| 2 | Mustafa | mustafa |wZ%AzmLt$%nd^6$N| 1 | example@furkans.net |
| 3 | Salih |  salih | HWZmY%UL4TznQdCR | 2|  example@furkans.net |
| 4 |  Atilla | atilla |jHHP+gXb59Xvh5U$|2| example@furkans.net |
| 5|  Doğukan | dogukan |uVb687&@pgTyChLY|2| example@furkans.net |

# SELECT
Tüm tabloyu çekme
```php
$fpdo->select("kullanicilar");
$kullanicilar = $fpdo->run();
$kullanici_listesi = $kullanicilar->fetchAll();
foreach ($kullanici_listesi as $kullanici) {
	echo "id:".$kullanici["id"]."\tisim:".$kullanici["isim"]."\tYetki:".$kullanici["yetki"]."\n";
}
```
ÇIKTISI:
```html
id:1	isim:Furkan   Yetki:1
id:2	isim:Mustafa  Yetki:1
id:3	isim:Salih    Yetki:2
id:4	isim:Atilla   Yetki:2
id:5	isim:Doğukan  Yetki:2
```
Tekli veri çekme
```php
$fpdo->select("kullanicilar");
$kullanicilar = $fpdo->run();
$kullanici = $kullanicilar->fetch();
echo "id:".$kullanici["id"]."\tisim:".$kullanici["isim"]."\tYetki:".$kullanici["yetki"]."\n";
```
ÇIKTISI:
```html
id:1	isim:Furkan		Yetki:1
```
 
limitli veri çekme
```php
$fpdo->select("kullanicilar");
$fpdo->limit(3);
$kullanicilar = $fpdo->run();
$kullanici_listesi = $kullanicilar->fetchAll();
foreach ($kullanici_listesi as $kullanici) {
	echo "id:".$kullanici["id"]."\tisim:".$kullanici["isim"]."\tYetki:".$kullanici["yetki"]."\n";
}
```
ÇIKTISI:
```html
id:1	isim:Furkan   Yetki:1
id:2	isim:Mustafa  Yetki:1
id:3	isim:Salih    Yetki:2
```

gelen verileri sıralama
```php
$fpdo->select("kullanicilar");
$fpdo->sirala("id","desc");
$kullanicilar = $fpdo->run();
$kullanici_listesi = $kullanicilar->fetchAll();
foreach ($kullanici_listesi as $kullanici) {
	echo "id:".$kullanici["id"]."\tisim:".$kullanici["isim"]."\tYetki:".$kullanici["yetki"]."\n";
}
```
ÇIKTISI:
```html
id:5	isim:Doğukan  Yetki:2
id:4	isim:Atilla   Yetki:2
id:3	isim:Salih    Yetki:2
id:2	isim:Mustafa  Yetki:1
id:1	isim:Furkan   Yetki:1
```
# UPDATE

```php
$fpdo->update("kullanicilar");
$fpdo->set("isim=:isim,sifre=:ysifre");
$fpdo->where("id=:id");
$array = array(
	"isim"=>"Furkansenturk",
	"ysifre"=>"12346",
	"id" => 1
);
$guncelle = $fpdo->run($array);
if($guncelle){
	echo "başarı ile güncellendi";
}
```
ÇIKTISI:
```html
başarı ile güncellendi
```

SQL tablomuz da aşağıdaki gibi olur
| id | isim | kullanici_adi | sifre | yetki | email |
| ------ | ------ |------ |------ |------ |------ |
| 1 | Furkansenturk |  furkan | 123456 | 1 | eposta@furkansenturk.com.tr|
| 2 | Mustafa | mustafa |wZ%AzmLt$%nd^6$N| 1 | example@furkans.net |
| 3 | Salih |  salih | HWZmY%UL4TznQdCR | 2|  example@furkans.net |
| 4 |  Atilla | atilla |jHHP+gXb59Xvh5U$|2| example@furkans.net |
| 5|  Doğukan | dogukan |uVb687&@pgTyChLY|2| example@furkans.net |

# INSERT
yeni veri ekleme
```php
$fpdo->insert("kullanicilar");
$fpdo->set("isim=:isim,kullanici_adi=:kul_adi,sifre=:ysifre,yetki=:yetki");
$array = array(
	"isim"=>"Hakan",
	"kul_adi"=>"hkn",
	"ysifre"=>"12346",
	"yetki" => 2
);
$guncelle = $fpdo->run($array);
if($guncelle){
	echo "başarı ile eklendi id=".$fpdo->lastInsertId();
}
```
ÇIKTISI:
```html
başarı ile eklendi id=6
```
SQL tablomuz da aşağıdaki şekilde olur
| id | isim | kullanici_adi | sifre | yetki | email |
| ------ | ------ |------ |------ |------ |------ |
| 1 | Furkan |  furkan | frkn#=YbswaRm*B9 | 1 | eposta@furkansenturk.com.tr|
| 2 | Mustafa | mustafa |wZ%AzmLt$%nd^6$N| 1 | example@furkans.net |
| 3 | Salih |  salih | HWZmY%UL4TznQdCR | 2|  example@furkans.net |
| 4 |  Atilla | atilla |jHHP+gXb59Xvh5U$|2| example@furkans.net |
| 5|  Doğukan | dogukan |uVb687&@pgTyChLY|2| example@furkans.net |
| 6|  Hakan| hkn |123456|2|  |
# DELETE
Belirli verileri silmek için
```php
$fpdo->delete("kullanicilar");
$fpdo->where("id=:id OR id=:id2");
$sil = $fpdo->run(array("id"=>1,"id2"=>2);
if($sil){
	echo "başarı ile silindi";
}
```
ÇIKTISI:
```html
başarı ile silindi
```
SQL tablomuz da aşağıdaki şekilde olur
| id | isim | kullanici_adi | sifre | yetki | email |
| ------ | ------ |------ |------ |------ |------ |
| 3 | Salih |  salih | HWZmY%UL4TznQdCR | 2|  example@furkans.net |
| 4 |  Atilla | atilla |jHHP+gXb59Xvh5U$|2| example@furkans.net |
| 5|  Doğukan | dogukan |uVb687&@pgTyChLY|2| example@furkans.net |

Tüm tabloyu boşaltmak için
```php
$fpdo->delete("kullanicilar");
$sil = $fpdo->run();
if($sil){
	echo "başarı ile kullanicilar tablosundaki veriler silindi.";
}
```
ÇIKTISI:
```html
başarı ile kullanicilar tablosundaki veriler silindi.
```
