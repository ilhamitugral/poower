<?php

function jsonData(string $filename = '') {
    if(file_exists($filename)) {
        return json_decode(file_get_contents($filename), true);
    }else {
        return [];
    }
}

function slugify($text, string $divider = '-') {
    $text = preg_replace('~[^\pL\d]+~u', $divider, $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, $divider);
    $text = preg_replace('~-+~', $divider, $text);
    $text = strtolower($text);
    
    if(empty($text)) {
        return 'n-a';
    }
    
    return $text;
}

function writeFile(string $filename, string $content = '') {
    if(file_exists($filename) && is_writable($filename)) {
        $file = fopen($filename, 'w');
        fwrite($file, $content);
        fclose($file);

        return true;
    }else {
        return false;
    }
}

?>