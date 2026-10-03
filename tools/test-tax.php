<?php
require dirname(__DIR__) . '/wp-load.php';
$t = get_taxonomy('product_cat');
echo 'singular_name: ' . $t->labels->singular_name . PHP_EOL;
echo 'name: ' . $t->labels->name . PHP_EOL;
