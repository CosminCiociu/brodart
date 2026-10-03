<?php
$c = file_get_contents(__DIR__ . '/homepage-626-content.txt');

// Găsește toate blocurile de tip card / imagine cu link
preg_match_all('/<!-- wp:stackable\/[^\s]+ .*?-->/s', $c, $blocks);
foreach ($blocks[0] as $b) {
    if (strpos($b, 'image') !== false || strpos($b, 'card') !== false || strpos($b, 'figure') !== false) {
        echo substr($b, 0, 400) . "\n---\n";
    }
}

// Găsește secțiunea cu "Perdele" / "Draperii" (cardurile de categorie)
$pos = strpos($c, 'Alege atmosfera potrivită');
echo "\n=== SECTIUNEA COLECTII (context) ===\n";
echo substr($c, $pos - 200, 5000);
