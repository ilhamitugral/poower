<?php

$meta = [
    'title' => 'Giriş Yap | '.SITE_INFO['website']['title'],
    'description' => 'Deneme meta açıklama.'
];

if(isset($user)) {
    header('location: /', 401);
}

$filename = __DIR__.'/inc/header.php';
if(file_exists($filename)) {
    require $filename;
}else {
    echo sprintf('`%s` isimli dosya sistemde bulunamadı.', basename($filename));
}

$error = '';

if(isset($_POST['sign-in'])) {
    $username = $secureData->post('username');
    $password = $secureData->post('password');

    if(empty($username)) {
        $error = Error('Kullanıcı adı boş bırakılamaz.');
    }else if(empty($password)) {
        $error = Error('Şifre boş bırakılamaz.');
    }else {
        $password = $secureData->encrypt($password);

        $query = $db->query($usersPattern->login($username, $password));
        if($query->num_rows > 0) {
            $row = $query->fetch_array(MYSQLI_ASSOC);

            $token = $secureData->encrypt();

            $query = $db->query($usersPattern->createNewSession([
                'ip' => $_SERVER['REMOTE_ADDR'],
                'token' => $token,
                'id' => $row['id'],
                'user_agent' => $_SERVER['HTTP_USER_AGENT']
            ]));

            if($query) {
                $db->commit();

                $_SESSION['token'] = $token;
                setcookie('token', $token, time() + 86400 * 90, '/');

                $error = Success('Giriş başarıyla yapıldı! Yönlendiriliyorsunuz...');

                header('refresh: 2; url='.SITE_INFO['website']['url'], 200);
            }else {
                $db->rollback();

                $error = Warning('Session oluşturulurken bir hata oluştu. Daha sonra tekrar deneyiniz.');
            }
        }else {
            $error = Error('Kullanıcı adı veya şifre hatalı.');
        }
    }
}

$site = SITE_INFO;

// İçerik Başlangıcı

$content .= <<<HTML
<div class="topic-user-form">
    <div>
        {$error}
        <form action="" method="post">
            <div class="form-outline mb-4">
                <label class="form-label" for="username">Kullanıcı Adı:</label>
                <input type="text" name="username" id="username" class="form-control" />
            </div>
            <div class="form-outline mb-4">
                <label class="form-label" for="password">Şifre:</label>
                <input type="password" name="password" id="password" class="form-control" />
            </div>
            <div class="row mb-4">
                <!--<div class="col">
                    <a href="{$site['website']['url']}sign-up/">Şifremi Unuttum</a>
                </div>-->
                <div class="col">
                    <a href="{$site['website']['url']}sign-up/">Yeni Hesap Oluştur</a>
                </div>
            </div>
            <button type="submit" name="sign-in" class="btn btn-primary btn-block mb-4">Giriş Yap</button>
        </form>
    </div>
</div>
HTML;

// İçerik Bitişi

$filename = __DIR__.'/inc/footer.php';
if(file_exists($filename)) {
    require $filename;
}else {
    echo sprintf('`%s` isimli dosya sistemde bulunamadı.', basename($filename));
}

?>