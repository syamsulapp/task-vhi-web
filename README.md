# introduce

<h5>task vhi-web di build menggunakan framework laravel versi 10 dengan versi php 8.2, jadi pastikan versi php anda sudah diatas versi >=7</h5>

<h5>
aktifkan require module php yang dibutukan untuk menjalankan framework tersebut, beberapa modul yang di harus di aktifkan ialah pdo_mysqli, mysqli, xml  dan mbstring, zip, dan curl
</h5>

# composer run

```Bash
composer install
```

# Start Local Development Server Ngandre API

```Bash
php -S localhost:8000 -t public || php artisan serve
```

# migrate table db dan menjalankan seed

```Bash
#migrate datanya
php artisan migrate
# jalankan sedder
php artisan db:seed
# jalankan storage link
php artisan storage:link


# jika ingin rollback table nya jalan kan perintah di bawah ini(optional)
php artisan migrate:rollback

```

# Endpoint Auth API

```Bash
#baseUrl
localhost:8000 -> sesuaikan dengan base url kalian

#Login
{{base_url}}/auth/login ->POST
#register
{{base_url}}/auth/register ->POST
#logout
{{base_url}}/auth/logout ->POST


```

# Endpoint API PHOTOS

# Access Ke Endpoint API Yang Menggunakan Session

<h5>jika ingin mengakses api yang menggunakan session, maka anda harus mengirimkan 2 buah object/param seperti dibawah ini, kirim ketiga buah object tersebut melalui request header</h5>

<h5>Object param yang dikirim lewat request header</h5>

```JSON
{
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
}
```

```Bash

#API master data product
#api tersebut mempunyai endpoint yang sama tetapi dibedakan berdasarkan request method
{{base_url}}/user/produk -> GET (menampilkan list produk)
{{base_url}}/user/produk -> POST (menambah data produk)
{{base_url}}/user/produk -> PUT (mengubah data produk)
{{base_url}}/user/produk -> DELETE (menghapus data produk)

```
