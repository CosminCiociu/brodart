<?php
// Găsește exact blocul de imagine din Sisteme de prindere
$c = file_get_contents(__DIR__ . '/homepage-626-content.txt');

// Caută după imageId 1242 care e unic
$pos = strpos($c, '"imageId":1242');
if ($pos === false) {
    echo "imageId 1242 not found\n";
    exit(1);
}

// Caută începutul blocului
$start = strrpos(substr($c, 0, $pos), '<!-- wp:stackable/image');
$end = strpos($c, '<!-- /wp:stackable/image -->', $pos) + strlen('<!-- /wp:stackable/image -->');

$block = substr($c, $start, $end - $start);
echo "FOUND BLOCK:\n";
echo $block;
echo "\n\n---\n";

// Salvez pentru comparație
file_put_contents(__DIR__ . '/prindere-block.txt', $block);
echo "Saved to prindere-block.txt\n";
