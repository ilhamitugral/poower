<?php

// Dahil edilecek dosyaları belirleyelim.
if(!isset($import)) {
    $import = [
        'css' => [],
        'headerJs' => [],
        'footerJs' => []
    ];
}else {
    $import = [
        'css' => array_key_exists('css', $import) ? $import['css'] : [],
        'headerJs' => array_key_exists('headerJs', $import) ? $import['headerJs'] : [],
        'footerJs' => array_key_exists('footerJs', $import) ? $import['footerJs'] : []
    ];
}

// Maksimum eleman sayısını tutacak.
$count = max([
    count($import['css']),
    count($import['headerJs']),
    count($import['footerJs'])
]);

// Dahil edilecekleri değişkende saklayalım.
$css = $headerJs = $footerJs = '';

for($i = 0; $i < $count; $i++) {
    if($i < count($import['css'])) {
        $css .= '<link rel="stylesheet" href="'.$import['css'][$i].'">';
    }

    if($i < count($import['headerJs'])) {
        $headerJs .= '<script src="'.$import['headerJs'][$i].'"></script>';
    }

    if($i < count($import['footerJs'])) {
        $footerJs .= '<script src="'.$import['footerJs'][$i].'"></script>';
    }
}

if(isset($meta)) {
    foreach(['title', 'description', 'keywords', 'robots'] as $m) {
        if(!array_key_exists($m, $meta)) {
            $meta[$m] = '';
        }
    }
}else {
    $meta = [
        'title' => SITE_INFO['website']['title'],
        'description' => '',
        'keywords' => '',
        'robots' => 'noindex, nofollow'
    ];
}

$site = SITE_INFO;

// Kullanıcıların giriş durumuna göre linkleri gösterelim.
if(isset($user)) {
    $links = <<<HTML
    <li class="nav-item">
        <a class="nav-link" href="{$site['website']['url']}create/">Oluştur</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{$site['website']['url']}sign-out/">Çıkış Yap</a>
    </li>
    HTML;
}else {
    $links = <<<HTML
    <li class="nav-item">
        <a class="nav-link" href="{$site['website']['url']}sign-in/">Giriş Yap</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{$site['website']['url']}sign-up/">Kayıt Ol</a>
    </li>
    HTML;
}

$content .= <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$meta['title']}</title>
    <meta name="description" content="{$meta['description']}">
    <meta name="keywords" content="{$meta['keywords']}">
    <meta name="robots" content="{$meta['robots']}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="{$site['website']['url']}assets/css/style.css?v={$site['version']}">{$css}{$headerJs}
</head>
<body>
    {$userError}
    <nav class="topic-navbar-header navbar navbar-expand-lg navbar-light mb-4">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
            <a class="navbar-brand" href="{$site['website']['url']}">{$site['website']['title']}</a>
            <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                {$links}
            </ul>
        </div>
    </nav>
    <div class="container">
HTML;
?>