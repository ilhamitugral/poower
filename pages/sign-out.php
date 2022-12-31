<?php

$meta = [
    'title' => 'Çıkış Yapılıyor... | '.SITE_INFO['website']['title'],
    'robots' => 'noindex, nofollow'
];

$error = '';

// Çıkış yapmayı sağlayalım.
if(array_key_exists('token', $_SESSION)) {
    $token = $_SESSION['token'];
}else if(array_key_exists('token', $_COOKIE)) {
    $token = $_COOKIE['token'];
}else {
    $token = '';
}

if(!empty($token)) {
    $query = $db->query($usersPattern->logout($token));
    if($query) {
        $db->commit();
    }else {
        $db->rollback();
        $error = Warning('Çıkış yaparken hata ile karşılaşıldı ama işleminizi etkilemiyor.');
    }
}

session_destroy();
setcookie('token', '', time()-1, '/');
header('refresh: 2; url=/', 200);

$filename = __DIR__.'/inc/header.php';
if(file_exists($filename)) {
    require $filename;
}else {
    echo sprintf('`%s` isimli dosya sistemde bulunamadı.', basename($filename));
}

// İçerik Başlangıcı
$content .= $error.Success('Başarıyla çıkış yapıldı! Yönlendiriliyorsunuz...');
// İçerik Bitişi

$filename = __DIR__.'/inc/footer.php';
if(file_exists($filename)) {
    require $filename;
}else {
    echo sprintf('`%s` isimli dosya sistemde bulunamadı.', basename($filename));
}

?>