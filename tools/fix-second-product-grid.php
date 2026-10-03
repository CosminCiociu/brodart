<?php
$c = file_get_contents(__DIR__ . '/homepage-626-content-updated.txt');

// Găsește al doilea grid de produse și îl înlocuiește cu produse diferite
$old_grid = '<!-- wp:shortcode -->
[products ids="821,820,819,818" columns="4"]
<!-- /wp:shortcode -->';

// Al doilea grid: produse draperii pentru echilibru (Canyon, Mohave, Serengeti, Sakura)
$new_grid = '<!-- wp:shortcode -->
[products ids="756,808,817,814" columns="4"]
<!-- /wp:shortcode -->';

$count = 0;
// Înlocuiește doar a doua apariție
$first_pos = strpos($c, $old_grid);
$second_pos = strpos($c, $old_grid, $first_pos + 1);

if ($second_pos !== false) {
    $c = substr_replace($c, $new_grid, $second_pos, strlen($old_grid));
    $count = 1;
}

echo "Replaced {$count} occurrence(s) of second grid\n";
file_put_contents(__DIR__ . '/homepage-626-content-final.txt', $c);

// Aplic în baza de date
$pdo = new PDO('mysql:host=localhost;dbname=brodart_clean_20260927', 'root', '');
$stmt = $pdo->prepare('UPDATE wp_posts SET post_content = ? WHERE ID = 626');
$stmt->execute([$c]);
echo "Database updated\n";
