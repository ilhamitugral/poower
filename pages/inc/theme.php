<?php

function TopicBody(array $p = []) {
    foreach(['title', 'text', 'titleC', 'commentC'] as $d) {
        if(!array_key_exists($d, $p)) {
            $p[$d] = '-';
        }
    }

    $p['url'] = array_key_exists('url', $p) ? $p['url'] : '#';

    $url = SITE_INFO['website']['url'];
    return <<<HTML
    <div class="topic-body">
        <div class="topic-content">
            <div class="topic-heading">
                <h3 class="topic-title">
                    <a href="{$url}{$p['url']}">{$p['title']}</a>
                </h3>
                <p class="topic-text">{$p['description']}</p>
            </div>
            <div class="topic-titles">{$p['titleC']}</div>
            <div class="topic-comments">{$p['commentC']}</div>
        </div>
    </div>
    HTML;
}

function Success(string $t = '') {
    return <<<HTML
    <div class="alert alert-success" role="alert">
        <p>{$t}</p>
    </div>
    HTML;
} 

function Error(string $t = '') {
    return <<<HTML
    <div class="alert alert-danger" role="alert">
        <p>{$t}</p>
    </div>
    HTML;
}

function Warning(string $t = '') {
    return <<<HTML
    <div class="alert alert-warning" role="alert">
        <p>{$t}</p>
    </div>
    HTML;
}

function Info(string $t = '') {
    return <<<HTML
    <div class="alert alert-info" role="alert">
        <p>{$t}</p>
    </div>
    HTML;
}

function InputField(array $p = []) {
    
    $check = [
        'type', 'name', 'id', 'class', 'maxlength',
        'value', 'placeholder', 'text', 'autocomplete'
    ];

    $parameters = '';
    foreach($check as $c) {
        if(!array_key_exists($c, $p)) {
            $p[$c] = '';
        }else {
            $parameters .= ' '.$c.'="'.$p[$c].'"';
        }
    }

    $p['for'] = array_key_exists('id', $p) ? ' for="'.$p['id'].'"' : '';
    $p['text'] = array_key_exists('text', $p) ? $p['text'] : '';

    return <<<HTML
    <div class="form-group">
        <label{$p['for']}>{$p['text']}</label>
        <input{$parameters}>
    </div>
    HTML;
}

function SelectField(array $p = []) {

    $check = [
        'name', 'id', 'class', 'value', 'placeholder',
        'text'
    ];

    $parameters = '';
    foreach($check as $c) {
        if(!array_key_exists($c, $p)) {
            $p[$c] = '';
        }else {
            $parameters .= ' '.$c.'="'.$p[$c].'"';
        }
    }

    $p['selected'] = array_key_exists('selected', $p) ? $p['selected'] : '';
    $p['children'] = array_key_exists('children', $p) ? $p['children'] : [];
    $p['for'] = array_key_exists('id', $p) ? ' for="'.$p['id'].'"' : '';

    $children = '';
    foreach($p['children'] as $child) {

        foreach(['value', 'text'] as $d) {
            if(!array_key_exists($d, $child)) {
                $child[$d] = '-';
            }
        }

        $isSelected = $p['selected'] == $child['value'] ? ' selected' : '';
        $children .= '<option value="'.$child['value'].'" '.$isSelected.'>'.$child['text'].'</option>';
    }

    return <<<HTML
    <div class="form-group">
        <label{$p['for']}>{$p['text']}</label>
        <select {$parameters}>
            {$children}
        </select>
    </div>
    HTML;
}

?>