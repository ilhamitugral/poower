<?php

$location = __ROOT__;

$pmtr = explode('?', $_SERVER['REQUEST_URI'])[0];
$pmtr = explode('/', $pmtr);

array_shift($pmtr);

$count = count($pmtr);
for($i = 0; $i < $count; $i++) {
    $location .= '/'.$pmtr[$i];
}

switch(pathinfo($pmtr[$count-1], PATHINFO_EXTENSION)) {
    case 'css':
        $type = 'text/css';
    break;

    case 'js':
        $type = 'text/javascript';
    break;

    case '':
    default:
        exit();
    break;
}

if(file_exists($location)) {
    header('Content-Type: '.$type);
    readfile($location);
}else {
    echo '404 - Bulunamadı';
    http_response_code(404);
}
?>