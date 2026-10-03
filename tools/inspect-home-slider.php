<?php
$c = file_get_contents(__DIR__ . '/homepage-626-content.txt');
preg_match('/<style id="brodart-home-slider">(.*?)<\/style>/s', $c, $m);
echo $m[1] ?? 'not found';
echo "\n\n--- HTML block around slider ---\n";
$pos = strpos($c, 'brodart-home-slider');
echo substr($c, max(0, $pos - 500), 2000);
