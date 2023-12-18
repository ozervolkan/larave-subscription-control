
# Case

Laravel 10 ile sanctum api kullanarak, Kullanıcı Register-Login işlemleri ile kullanıcıya ait aboneliklerin yönetilmesi işlemlerini barındıran Case uygulaması. Job kullanılarak aboneliklerin yenilenmesi yapılabilir.



## Özellikler

- Register - Login
- Abonelik İşlemleri (CRUD)
- Job ile Abonelik yenileme



  
## Kurulum

Proje bir dizine çıkarılır. Terminal ile dizine gelinir. Bağımlılıklar yüklenir.
```javascript
composer update
```

Bu aşamada 

```
.env.example dosyası .env olarak değiştirilir ve veritabanı ayarları yapılır. DB olarak MySql kullanılacaktır.
```

Veritabanı migrationu yapılır.
```
php artisan migrate
```

Proje için key üretilir:

```
php artisan key:generate
```
Uygulama ayağa kaldırılır
```
php artisan serve
```
Bu işlemlerden sonra aşağıdaki url den yayına geçecektir.
```
http://127.0.0.1:8000
```
## Job 

Joblar için database kullanıldı. 

```bash 
  php artisan dispatch:RenewSubs
  php artisan queue:work
```
    