<?php

return [
    'plugin' => [
        'name' => 'Quick Edit',
        'description' => 'Edit quickly content of pages via dashboard widget.',
        'author' => 'Gergő Szabó'
    ],
    'widget' => [
        'type' => 'CMS type',
        'type_page' => 'Page',
        'type_content' => 'Content',
        'type_partials' => 'Partials',
        'type_layouts' => 'Layouts',
        'editor' => 'Type of editor',
        'editor_none' => 'None',
        'editor_rich' => 'Rich editor',
        'height_title' => 'Height (in pixel)',
        'height_description' => 'It doesn\'t work in Rich editor.',
        'size_title' => 'Size',
        'size_description' => 'It only works in Rich editor.',
        'size_large' => 'Large',
        'size_huge' => 'Huge',
        'size_giant' => 'Giant',
        'error_number' => 'The field can only contain numbers.',
        'error_page' => 'Please select page to edit.',
        'select' => '-- select --',
        'modify' => 'Last modify',
        'nodate' => 'no data'
    ]
];
