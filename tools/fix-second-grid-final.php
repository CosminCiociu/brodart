<?php
$c = file_get_contents(__DIR__ . '/homepage-626-content-updated.txt');

// Găsește pozițiile exacte
$search = '[products ids="821,820,819,818" columns="4"]';
$first_pos = strpos($c, $search);
$second_pos = strpos($c, $search, $first_pos + strlen($search));

echo "First shortcode at: {$first_pos}\n";
echo "Second shortcode at: {$second_pos}\n";

if ($second_pos === false) {
    echo "ERROR: Second shortcode not found\n";
    exit(1);
}

// Înlocuiește doar al doilea
$replacement = '[products ids="756,808,817,814" columns="4"]';
$c = substr_replace($c, $replacement, $second_pos, strlen($search));

echo "Replaced second grid with draperii products\n";
file_put_contents(__DIR__ . '/homepage-626-content-final.txt', $c);

// Aplic în baza de date
$pdo = new PDO('mysql:host=localhost;dbname=brodart_clean_20260927', 'root', '');
$stmt = $pdo->prepare('UPDATE wp_posts SET post_content = ? WHERE ID = 626');
$stmt->execute([$c]);
echo "Database updated\n";
