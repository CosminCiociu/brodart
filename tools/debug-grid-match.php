<?php
$c = file_get_contents(__DIR__ . '/homepage-626-content-updated.txt');

// Găsește ambele griduri și le afișează pentru comparație
$pattern = '/<!-- wp:shortcode -->\s*\[products ids="821,820,819,818" columns="4"\]\s*<!-- \/wp:shortcode -->/s';
preg_match_all($pattern, $c, $matches, PREG_OFFSET_CAPTURE);

echo "Grid 1 exact content:\n";
var_export($matches[0][0][0]);
echo "\n\nGrid 2 exact content:\n";
var_export($matches[0][1][0]);
echo "\n";
