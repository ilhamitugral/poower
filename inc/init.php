<?php

// Dahil edilecek dosyaları burada topluyoruz.
$files = [
    __DIR__.'/function.php',
    __DIR__.'/classes/class.database.php',
    __DIR__.'/classes/class.database.users.php',
    __DIR__.'/classes/class.database.topics.php',
    __DIR__.'/classes/class.secure.data.php',
    __DIR__.'/../pages/inc/theme.php',
    __DIR__.'/classes/class.parsedown.php'
];

foreach($files as $file) {
    if(file_exists($file)) {
        require $file;
    }else {
        die(sprintf('Kritik Hata: `%s` dosyası sistemde bulunamadı.', basename($file)));
    }
}

$secureData = new SecureData();
$parsedown = new Parsedown();

?>