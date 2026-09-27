<?php

add_action('init', function () {
    register_block_style('core/button', [
        'name' => 'brodart-primary',
        'label' => 'Brodart Primary',
        'inline_style' => '
            .wp-block-button.is-style-brodart-primary .wp-block-button__link {
                background-color: var(--theme-palette-color-3);
                border: 1px solid transparent;
                border-radius: 3px;
                color: var(--theme-palette-color-8);
                font-family: var(--theme-button-font-family);
                font-size: var(--theme-button-font-size);
                font-weight: var(--theme-button-font-weight);
                letter-spacing: var(--theme-button-letter-spacing);
                text-transform: var(--theme-button-text-transform);
                transition: background-color 250ms ease, color 250ms ease, border-color 250ms ease;
            }
            .wp-block-button.is-style-brodart-primary .wp-block-button__link:hover {
                background-color: var(--theme-palette-color-2);
            }
        ',
    ]);

    register_block_style('core/button', [
        'name' => 'brodart-secondary',
        'label' => 'Brodart Secondary',
        'inline_style' => '
            .wp-block-button.is-style-brodart-secondary .wp-block-button__link {
                background-color: transparent;
                border: 1px solid var(--theme-palette-color-3);
                border-radius: 3px;
                color: var(--theme-palette-color-3);
                font-family: var(--theme-button-font-family);
                font-size: var(--theme-button-font-size);
                font-weight: var(--theme-button-font-weight);
                letter-spacing: var(--theme-button-letter-spacing);
                text-transform: var(--theme-button-text-transform);
                transition: background-color 250ms ease, color 250ms ease, border-color 250ms ease;
            }
            .wp-block-button.is-style-brodart-secondary .wp-block-button__link:hover {
                background-color: var(--theme-palette-color-6);
            }
        ',
    ]);
});
