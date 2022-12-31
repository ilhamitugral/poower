<?php

if(PMTR[0] == PATHINFO(__FILE__, PATHINFO_FILENAME)) {
    header('location: '.SITE_INFO['website']['url'], 401);
}

if(!isset($user)) {
    header('location: '.SITE_INFO['website']['url'], 401);
}

$import = [
    'css' => [
        'https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css'
    ],
    'headerJs' => [
        'https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js'
    ],
    'footerJs' => []
];

$filename = __DIR__.'/inc/header.php';
if(file_exists($filename)) {
    require $filename;
}else {
    echo sprintf('`%s` isimli dosya sistemde bulunamadı.', basename($filename));
}

// Başlık Alanı
$titleDom = InputField([
    'type' => 'text',
    'name' => 'topic-title',
    'value' => $title,
    'maxlength' => 100,
    'id' => 'topic-title',
    'class' => 'form-control',
    'text' => 'Başlık:',
    'placeholder' => 'İçerik Başlığı'
]);

$categoryDom = SelectField([
    'name' => 'topic-category',
    'value' => $title,
    'id' => 'topic-category',
    'class' => 'form-control',
    'text' => 'Kategori:',
    'selected' => $category,
    'children' => [
        ['value' => 1, 'text' => 'Seçiniz'],
        ['value' => 0, 'text' => 'Seçiniz'],
        ['value' => 0, 'text' => 'Seçiniz'],
        ['value' => 0, 'text' => 'Selected'],
        ['value' => 0, 'text' => 'Seçiniz'],
    ]
]);

$keywordsDom = InputField([
    'type' => 'text',
    'name' => 'topic-keywords',
    'value' => $keywords,
    'maxlength' => 500,
    'id' => 'topic-keywords',
    'class' => 'form-control',
    'text' => 'Anahtar Kelimeler:',
    'placeholder' => 'SEO Anahtar Kelimeler'
]);

// İçerik Başlangıcı
$content .= <<<HTML
{$error}
<form action="" method="post">
    <div class="card">
        <div class="card-body">
            <h1 class="card-title mb-3">{$pageTitle}</h1>
            {$titleDom}
            {$categoryDom}
            <div class="form-group">
                <textarea class="form-control" id="editor" name="topic-text">{$text}</textarea>
            </div>
            <script>
            var editor = new SimpleMDE({
                element: document.getElementById('editor'),
                toolbar: [
                    "bold", "italic", "strikethrough", "heading", "|",
                    "quote", "unordered-list", "ordered-list", "|",
                    "link", "image", "code", "table", "|",
                    "preview", "side-by-side", "fullscreen", "|",
                    "guide"
                ]
            });
            </script>
            {$keywordsDom}
            <div>
                <button type="submit" class="btn btn-primary" name="submit">Gönder</button>
            </div>
        </div>
    </div>
</form>
HTML;
// İçerik Bitişi

$filename = __DIR__.'/inc/footer.php';
if(file_exists($filename)) {
    require $filename;
}else {
    echo sprintf('`%s` isimli dosya sistemde bulunamadı.', basename($filename));
}

?>