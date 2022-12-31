<?php

session_start();
ob_start();

$filename = __DIR__.'/inc/init.php';
if(file_exists($filename)) {
    require $filename;
}else {
    die(sprintf('Kritik Hata: `%s` dosyası sistemde bulunamadı.', basename($filename)));
}

// ROOT sabit değişkeni oluşturalım.
define('__ROOT__', __DIR__);

// Site dosyalarını dahil ediyoruz.
define('SITE_INFO', jsonData(__DIR__.'/config/site.info.json'));

// Parametreleri ayıklayalım.
$pmtr = explode('/', $_SERVER['REQUEST_URI']);
array_shift($pmtr);
array_pop($pmtr);

define('PMTR', $pmtr);

// Veritabanı bilgileri
$dbInfo = jsonData(__DIR__.'/config/db.info.json');
$db = new Database($dbInfo);

// Database Pattern yapıları
$usersPattern = new DatabaseUsers($dbInfo['prefix']);
$topicPattern = new DatabaseTopics($dbInfo['prefix']);

// Database bilgileri ile işimiz bittiği için siliyoruz.
unset($dbInfo);

// Content verilerini tutmak amacıyla değişken oluşturalım.
$content = '';
$userError = '';

// Kullanıcının giriş yapıp yapmadığı kontrolünü gerçekleştirelim.
if(array_key_exists('token', $_SESSION)) {
    $token = $_SESSION['token'];
}else if(array_key_exists('token', $_COOKIE)) {
    $token = $_COOKIE['token'];
}else {
    $token = '';
}

// Elimizdeki token ile kontrolü sağlayalım.
if(!empty($token)) {   
    $query = $db->query($usersPattern->loginWithToken($token));
    if($query->num_rows > 0) {
        $user = $query->fetch_array(MYSQLI_ASSOC);

        // Last update bilgisini güncelle.
        $query = $db->query($usersPattern->update($token));

        if($query) {
            $db->commit();
            
            $_SESSION['token'] = $token;
            setcookie('token', $token, time() + 86400 * 90, '/');
        }else {
            $db->rollback();
            $userError = Warning('Kullanıcı son giriş bilgisi güncellenemedi.');
        }
    }else {
        header('location: '.SITE_INFO['website']['url'].'sign-out/', 401);
    }
}

// Sayfayı dahil edelim.
$page = array_key_exists(0, PMTR) ? PMTR[0] : 'homepage';
$filename = __DIR__.'/pages/'.$page.'.php';

if(file_exists($filename)) {
    require $filename;

    $change = [
        '' => '',
    ];

    echo str_replace(array_keys($change), array_values($change), $content);
}else {
    echo '404 - Sayfa bulunamadı.';
    http_response_code(404);
}

?>