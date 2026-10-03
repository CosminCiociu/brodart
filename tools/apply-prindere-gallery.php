<?php
// Înlocuiește imaginea statică din Sisteme de prindere cu mini-galeria
$c = file_get_contents(__DIR__ . '/homepage-626-content.txt');

// Blocul vechi (exact cum e în fișier)
$old_block = file_get_contents(__DIR__ . '/prindere-block.txt');

// Blocul nou: mini-galerie cu 3 imagini
$new_block = <<<'HTML'
<!-- wp:html -->
<div class="brodart-mini-gallery"><figure><img src="http://localhost/brodart/wp-content/uploads/2026/09/Draperii-perdele.ro-Brodart-galerie-de-prezentare-4.jpg" alt="Sistem de prindere Brodart — detaliu montaj" width="1920" height="1080" loading="lazy"/></figure><figure><img src="http://localhost/brodart/wp-content/uploads/2026/09/Draperii-perdele.ro-Brodart-galerie-de-prezentare-2.jpg" alt="Sistem de prindere Brodart — detaliu montaj" width="1920" height="1080" loading="lazy"/></figure><figure><img src="http://localhost/brodart/wp-content/uploads/2026/09/Draperii-perdele.ro-Brodart-galerie-de-prezentare-5.jpg" alt="Sistem de prindere Brodart — detaliu montaj" width="1920" height="1080" loading="lazy"/></figure></div>
<!-- /wp:html -->
HTML;

$count = 0;
$c = str_replace($old_block, $new_block, $c, $count);

if ($count === 0) {
    echo "ERROR: Old block not found\n";
    // Debug: caută manual
    $pos = strpos($c, 'd3f64ab');
    echo "d3f64ab found at position: " . ($pos !== false ? $pos : 'NO') . "\n";
    exit(1);
}

echo "Replaced {$count} occurrence(s)\n";
file_put_contents(__DIR__ . '/homepage-626-content-updated.txt', $c);
echo "Saved to homepage-626-content-updated.txt\n";

// Aplic și în baza de date
$pdo = new PDO('mysql:host=localhost;dbname=brodart_clean_20260927', 'root', '');
$stmt = $pdo->prepare('UPDATE wp_posts SET post_content = ? WHERE ID = 626');
$stmt->execute([$c]);
echo "Database updated\n";
