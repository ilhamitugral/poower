<?php

$meta = [
    'title' => 'Gönderi Oluştur | '.SITE_INFO['website']['title'],
    'robots' => 'noindex, nofollow'
];

$error = $title = $category = $text = $keywords = '';

if(isset($_POST['submit'])) {
    $title = $secureData->post('topic-title');
    $category = $secureData->post('topic-category');
    $text = array_key_exists('topic-text', $_POST) ? $_POST['topic-text'] : '';
    $keywords = $secureData->post('topic-keywords');

    if(empty($title)) {
        $error = Error('Başlık boş bırakılamaz.');
    }else if(!is_numeric($category)) {
        $error = Error('Geçersiz kategori');
    }else if(empty($text)) {
        $error = Error('İçerik yazısı boş bırakılamaz.');
    }else {
        
        // Böyle içeriğin olup olmadığını kontrol edelim.
        $query = $db->query($topicPattern->checkTitle($title));
        if($query->num_rows > 0) {
            $error = Error('Böyle bir başlık zaten mevcut.');
        }else {
            $url = slugify($title);
            $query = $db->query($topicPattern->createNewTopic([
                'url' => $url,
                'parent' => '',
                'category' => $category,
                'author' => $user['id'],
                'title' => $title,
                'keywords' => $keywords,
                'status' => 1
            ]));

            if($query) {
                $filename = __ROOT__.'/assets/contents/'.$url.'.md';
                
                if(!file_exists($filename)) {
                    touch($filename);
                }

                if(writeFile($filename, $text)) {
                    $db->commit();
                    $error = Success('İçerik başarıyla oluşturuldu! Yönlendiriliyorsunuz...');
                    header('refresh: 2; url='.SITE_INFO['website']['url'], 200);
                }else {
                    $db->rollback();
                    $error = Warning('İçerik eklendi fakat dosya oluşturulamadı. Daha sonra tekrar deneyiniz.');
                }
            }else {
                $db->rollback();

                $error = Warning('İçerik oluşturulurken bir hata oluştu. Daha sonra tekrar deneyiniz.');
            }
        }
    }
}

$pageTitle = 'İçerik Oluştur';

$file = __DIR__.'/content-info.php';
if(file_exists($file)) {
    require $file;
}else {
    die(sprintf('Kritik Hata: `%s` isimli dosya sistemde bulunamadı.', basename($file)));
}

?>