<?php
$c = file_get_contents(__DIR__ . '/homepage-626-content-updated.txt');

// Caută pozițiile exacte ale shortcode-urilor products
$pattern = '/\[products ids="821,820,819,818" columns="4"\]/';
preg_match_all($pattern, $c, $matches, PREG_OFFSET_CAPTURE);

echo "Found " . count($matches[0]) . " shortcode(s)\n";
foreach ($matches[0] as $i => $m) {
    echo "Shortcode " . ($i + 1) . " at position: " . $m[1] . "\n";
    echo "Context: " . substr($c, max(0, $m[1] - 50), 150) . "\n---\n";
}

// Verifică dacă primul grid e încă acolo după modificările anterioare
$check = strpos($c, '821,820,819,818');
echo "\nFirst occurrence of '821,820,819,818': " . ($check !== false ? $check : 'NOT FOUND') . "\n";
