<?php

$file = __DIR__.'/content-info.php';
if(file_exists($file)) {
    require $file;
}else {
    die(sprintf('Kritik Hata: `%s` isimli dosya sistemde bulunamadı.', basename($file)));
}

?>