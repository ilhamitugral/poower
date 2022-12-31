<?php

$query = $db->query($topicPattern->displayTopic(PMTR[1]));
if($query->num_rows > 0) {
    $row = $query->fetch_array(MYSQLI_ASSOC);
 
    $import = [
        'css' => [
            'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/styles/github.min.css',
            SITE_INFO['website']['url'].'assets/css/markdown.css'
        ],
        'headerJs' => [
            'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/highlight.min.js'
        ],
    ];

    $meta = [
        'title' => 'İçerik | '.SITE_INFO['website']['title'],
        'description' => '',
        'keywords' => $row['keywords'],
        'robots' => 'index, nofollow'
    ];

    $filename = __ROOT__.'/assets/contents/'.$row['url'].'.md';
    if(file_exists($filename)) {
        $text = $parsedown->text(file_get_contents($filename));
    }else {
        $text = '';
    }
}else {
    $meta = [
        'title' => 'İçerik Bulunamadı | '.SITE_INFO['website']['title']
    ];

    $error = Error('İçerik bulunamadı.');
    http_response_code(404);
}

$filename = __DIR__.'/inc/header.php';
if(file_exists($filename)) {
    require $filename;
}else {
    echo sprintf('`%s` isimli dosya sistemde bulunamadı.', basename($filename));
}

// İçerik Başlangıcı

if($query->num_rows > 0) {
    $content .= <<<HTML
    <div class="card">
        <div class="card-body">
            <h1>{$row['title']}</h1>
            <div class="topic-content markdown-body">{$text}</div>
        </div>
        <script>hljs.highlightAll();</script>
    </div>
    HTML;
}else {
    $content .= $error;
}

// İçerik Bitişi

$filename = __DIR__.'/inc/footer.php';
if(file_exists($filename)) {
    require $filename;
}else {
    echo sprintf('`%s` isimli dosya sistemde bulunamadı.', basename($filename));
}

?>