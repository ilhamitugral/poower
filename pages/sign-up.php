<?php

$meta = [
    'title' => 'Kayıt Ol | '.SITE_INFO['website']['title'],
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

$error = $fullname = $username = $email = $remail = '';

if(isset($_POST['sign-up'])) {
    $fullname = $secureData->post('fullname');
    $username = $secureData->post('username');
    $password = $secureData->post('password');
    $repassword = $secureData->post('repassword');
    $email = $secureData->post('email');
    $remail = $secureData->post('remail');

    if(empty($fullname)) {
        $error = Error('Kullanıcı adı boş bırakılamaz.');
    }else if(empty($username)) {
        $error = Error('Kullanıcı adı boş bırakılamaz.');
    }else if(empty($password)) {
        $error = Error('Şifre boş bırakılamaz.');
    }else if(empty($repassword)) {
        $error = Error('Tekrar şifre boş bırakılamaz.');
    }else if(empty($email)) {
        $error = Error('E-posta adresi boş bırakılamaz.');
    }else if(empty($remail)) {
        $error = Error('Tekrar e-posta adresi boş bırakılamaz.');
    }else if(strlen($fullname) < 3 && strlen($fullname) > 32) {
        $error = Error(sprintf('Tam isim %d - %d karakter arası uzunlukta olmalıdır.'), 3, 32);
    }else if(strlen($username) < 3 && strlen($username) > 32) {
        $error = Error(sprintf('Kullanıcı adı %d - %d karakter arası uzunlukta olmalıdır.'), 3, 32);
    }else if(strlen($password) < 8) {
        $error = Error(sprintf('Şifre en az %d karakter uzunluğunda olmalıdır.', 8));
    }else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = Error('E-posta adresi geçersiz.');
    }else if($password != $repassword) {
        $error = Error('Şifreler aynı değil.');
    }else if($email != $remail) {
        $error = Error('E-posta adresleri aynı değil.');
    }else {
        $password = $secureData->encrypt($password);

        $query = $db->query($usersPattern->createNewUsers([
            'username' => $username,
            'password' => $password,
            'email' => $email
        ]));
        
        if($query) {
            $db->commit();

            $token = $secureData->encrypt();

            // Session ekle
            $query = $db->query($usersPattern->createNewSession([
                'ip' => $_SERVER['REMOTE_ADDR'],
                'token' => $token,
                'id' => $db->insert_id,
                'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            ]));

            if($query) {
                $db->commit();
                $error = Success('Kullanıcı başarıyla oluşturuldu! Yönlendiriliyorsunuz...');
                $_SESSION['token'] = $token;
                setcookie('token', $token, time() + 86400 * 90, '/');
                header('refresh: 2; url='.SITE_INFO['website']['url'], 200);
            }else {
                $db->rollback();
                $error = Warning('Kullanıcı oluşturuldu ancak yeni session oluşturulamadı.');
            }
        }else {
            $db->rollback();
            $error = Warning('Kullanıcı oluşturulurken bir hata oluştu. Daha sonra tekrar deneyiniz.');
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
                <label class="form-label" for="fullname">Tam İsim:</label>
                <input type="text" name="fullname" id="fullname" class="form-control" value="{$fullname}" maxlength="32" required/>
            </div>
            <div class="form-outline mb-4">
                <label class="form-label" for="username">Kullanıcı Adı:</label>
                <input type="text" name="username" id="username" class="form-control" value="{$username}" maxlength="32" required/>
            </div>
            <div class="form-outline mb-4">
                <label class="form-label" for="password">Şifre:</label>
                <input type="password" name="password" id="password" class="form-control" required/>
            </div>
            <div class="form-outline mb-4">
                <label class="form-label" for="repassword">Tekrar Şifre:</label>
                <input type="password" name="repassword" id="repassword" class="form-control" required/>
            </div>
            <div class="form-outline mb-4">
                <label class="form-label" for="email">E-Posta:</label>
                <input type="email" name="email" id="email" class="form-control" value="{$email}" maxlength="256" required/>
            </div>
            <div class="form-outline mb-4">
                <label class="form-label" for="remail">Tekrar E-Posta:</label>
                <input type="email" name="remail" id="remail" class="form-control" value="{$remail}" maxlength="256" required/>
            </div>
            <div class="row mb-4">
                <div class="col">
                    <a href="{$site['website']['url']}sign-in/">Hesabın var mı? Giriş Yap</a>
                </div>
            </div>
            <button type="submit" name="sign-up" class="btn btn-primary btn-block mb-4">Kayıt Ol</button>
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