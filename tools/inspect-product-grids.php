<?php
$c = file_get_contents(__DIR__ . '/homepage-626-content-updated.txt');

// Găsește ambele griduri de produse
$pattern = '/<!-- wp:shortcode -->\s*\[products ids="821,820,819,818" columns="4"\]\s*<!-- \/wp:shortcode -->/s';
preg_match_all($pattern, $c, $matches, PREG_OFFSET_CAPTURE);

echo "Found " . count($matches[0]) . " product grids\n";
foreach ($matches[0] as $i => $match) {
    echo "Grid " . ($i + 1) . " at position " . $match[1] . "\n";
    echo substr($match[0], 0, 200) . "\n---\n";
}

// Verifică dacă există și alt text între ele
$first_end = $matches[0][0][1] + strlen($matches[0][0][0]);
$second_start = $matches[0][1][1];
echo "\nContent between grids (" . ($second_start - $first_end) . " chars):\n";
echo substr($c, $first_end, min(500, $second_start - $first_end));
