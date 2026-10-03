<?php
// Generează blocul de înlocuire pentru secțiunea Sisteme de prindere
$images = array(1242, 1240, 1243); // Galerie 4 (existent), 2, 5
$base_url = 'http://localhost/brodart/wp-content/uploads/2026/09/';
$names = array(
    1242 => 'Draperii-perdele.ro-Brodart-galerie-de-prezentare-4.jpg',
    1240 => 'Draperii-perdele.ro-Brodart-galerie-de-prezentare-2.jpg',
    1243 => 'Draperii-perdele.ro-Brodart-galerie-de-prezentare-5.jpg',
);

$gallery_html = '';
foreach ($images as $id) {
    $url = $base_url . $names[$id];
    $gallery_html .= sprintf(
        '<figure><img src="%s" alt="Sistem de prindere Brodart — detaliu montaj" width="1920" height="1080" loading="lazy"/></figure>',
        $url
    );
}

$replacement = <<<HTML
<!-- wp:html -->
<div class="brodart-mini-gallery">{$gallery_html}</div>
<!-- /wp:html -->
HTML;

echo $replacement;
