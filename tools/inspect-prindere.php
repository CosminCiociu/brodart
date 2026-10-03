<?php
$c = file_get_contents(__DIR__ . '/homepage-626-content.txt');

// Găsește secțiunea "Sisteme de prindere" și extrage blocul complet
$pos = strpos($c, 'Sisteme de prindere');
if ($pos === false) {
    echo "Not found\n";
    exit(1);
}

// Caută începutul blocului image-box care conține această secțiune
$start = strrpos(substr($c, 0, $pos), '<!-- wp:stackable/image-box');
$end = strpos($c, '<!-- /wp:stackable/image-box -->', $pos) + strlen('<!-- /wp:stackable/image-box -->');

$block = substr($c, $start, $end - $start);
echo $block;
