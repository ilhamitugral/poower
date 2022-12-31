<?php

$meta = [
    'title' => 'Anasayfa | '.SITE_INFO['website']['title'],
    'description' => 'Deneme meta açıklama.'
];

$filename = __DIR__.'/inc/header.php';
if(file_exists($filename)) {
    require $filename;
}else {
    echo sprintf('`%s` isimli dosya sistemde bulunamadı.', basename($filename));
}

// Örnek Veriler
$topicBody = TopicBody([
    'title' => 'İçerik Başlığı',
    'description' => 'İçerik Yazısı',
    'url' => 'unity3d/3245/',
    'titleC' => 0,
    'commentC' => 0
]);

// İçerik Başlangıcı
$content .= <<<HTML
<div class="topic-panel">
    <div class="topic-main">
        <div class="topic-content">
            <div class="topic-body">
                <h2>
                    <a href="#">Ana Kategori</a>
                </h2>
            </div>
        </div>
    </div>
    <div class="topic-table">
        <div class="topic-head">
            <div class="topic-content">
                <div class="topic-heading">Kategori</div>
                <div class="topic-titles">Başlık</div>
                <div class="topic-comments">Cevap</div>
            </div>
        </div>
        {$topicBody}
        {$topicBody}
        {$topicBody}
        {$topicBody}
        {$topicBody}
    </div>
</div>
<div class="topic-panel">
    <div class="topic-main">
        <div class="topic-content">
            <div class="topic-body">
                <h2>
                    <a href="#">Ana Kategori</a>
                </h2>
            </div>
        </div>
    </div>
    <div class="topic-table">
        <div class="topic-head">
            <div class="topic-content">
                <div class="topic-heading">Kategori</div>
                <div class="topic-titles">Başlık</div>
                <div class="topic-comments">Cevap</div>
            </div>
        </div>
        {$topicBody}
        {$topicBody}
        {$topicBody}
        {$topicBody}
        {$topicBody}
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