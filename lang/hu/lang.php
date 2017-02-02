<?php

return [
    'plugin' => [
        'name' => 'Gyors szerkesztő',
        'description' => 'Módosítsa gyorsan az oldalak tartalmát widget segítségével.',
        'author' => 'Szabó Gergő'
    ],
    'widget' => [
        'type' => 'Típus',
        'type_page' => 'Oldalak',
        'type_content' => 'Tartalom',
        'type_static_pages' => 'Lapok',
        'type_partials' => 'Részlapok',
        'type_layouts' => 'Elrendezések',
        'editor' => 'Szerkesztő típusa',
        'editor_none' => 'Nincs',
        'editor_rich' => 'Word-szerű',
        'height_title' => 'Magasság (pixelben)',
        'height_description' => 'A Word-szerű szerkesztőnél nem működik.',
        'size_title' => 'Méret',
        'size_description' => 'Csak a Word-szerű szerkesztőnél működik.',
        'size_large' => 'Közepes',
        'size_huge' => 'Nagy',
        'size_giant' => 'Hatalmas',
        'theme_title' => 'Alapértelmezett téma',
        'theme_description' => 'Csak az aktív téma fájlai listázódnak.',
        'error_number' => 'A mező csak számokat tartalmazhat.',
        'error_page' => 'Válasszon ki egy oldalt a szerkesztéshez.',
        'select' => '-- válasszon --',
        'modify' => 'Utoljára módosítva',
        'nodate' => 'nincs adat'
    ]
];
