<?php
// Înlocuiește imaginea statică din Sisteme de prindere cu mini-galeria
$c = file_get_contents(__DIR__ . '/homepage-626-content.txt');

$old_block = <<<'HTML'
<!-- wp:stackable/image {"uniqueId":"d3f64ab","imageUrl":"http://localhost/brodart/wp-content/uploads/2026/09/Draperii-perdele.ro-Brodart-galerie-de-prezentare-4.jpg","imageId":1242,"imageWidthAttribute":1920,"imageHeightAttribute":1080,"imageOverlayOpacity":0,"imageHeight":280,"imageOverlayColorParentHover":"var(\u002d\u002dtheme-palette-color-8, #ffffff)","imageOverlayOpacityParentHover":0.4,"imageZoomParentHover":1.1} -->
<div class="wp-block-stackable-image stk-block-image stk-block stk-d3f64ab" data-block-id="d3f64ab"><style>.stk-d3f64ab .stk-img-wrapper{height:280px !important;--stk-gradient-overlay:0 !important;}:where(.stk-hover-parent:hover,  .stk-hover-parent.stk--is-hovered) .stk-d3f64ab .stk-img-wrapper img{transform:scale(1.1) !important;}:where(.stk-hover-parent:hover,  .stk-hover-parent.stk--is-hovered) .stk-d3f64ab .stk-img-wrapper::after{background-color:var(\u002d\u002dtheme-palette-color-8, #ffffff) !important;}:where(.stk-hover-parent:hover,  .stk-hover-parent.stk--is-hovered) .stk-d3f64ab .stk-img-wrapper{--stk-gradient-overlay:0.4 !important;}</style><figure><span class="stk-img-wrapper stk-image--shape-stretch"><img class="stk-img wp-image-1242" src="http://localhost/brodart/wp-content/uploads/2026/09/Draperii-perdele.ro-Brodart-galerie-de-prezentare-4.jpg" width="1920" height="1080"/></span></figure></div>
<!-- /wp:stackable/image -->
HTML;

$new_block = <<<'HTML'
<!-- wp:html -->
<div class="brodart-mini-gallery"><figure><img src="http://localhost/brodart/wp-content/uploads/2026/09/Draperii-perdele.ro-Brodart-galerie-de-prezentare-4.jpg" alt="Sistem de prindere Brodart — detaliu montaj" width="1920" height="1080" loading="lazy"/></figure><figure><img src="http://localhost/brodart/wp-content/uploads/2026/09/Draperii-perdele.ro-Brodart-galerie-de-prezentare-2.jpg" alt="Sistem de prindere Brodart — detaliu montaj" width="1920" height="1080" loading="lazy"/></figure><figure><img src="http://localhost/brodart/wp-content/uploads/2026/09/Draperii-perdele.ro-Brodart-galerie-de-prezentare-5.jpg" alt="Sistem de prindere Brodart — detaliu montaj" width="1920" height="1080" loading="lazy"/></figure></div>
<!-- /wp:html -->
HTML;

$count = 0;
$c = str_replace($old_block, $new_block, $c, $count);

if ($count === 0) {
    echo "ERROR: Old block not found\n";
    exit(1);
}

echo "Replaced {$count} occurrence(s)\n";
file_put_contents(__DIR__ . '/homepage-626-content-updated.txt', $c);
echo "Saved to homepage-626-content-updated.txt\n";
