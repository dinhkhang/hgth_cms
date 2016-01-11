<?php

$config['S'] = [
    'Menus' => [
        [ // Du lịch
            'name' => __('regions'),
            'icon' => 'fa fa-image',
            'child' => [
                [ // Địa danh
                    'name' => __('regions_list'),
                    'controller' => 'Regions',
                    'action' => 'index'
                ],
            ]
       ],
    ],
    'Lang' => [
        'vi' => __('Vietnamese'),
        'en' => __('English'),
    ]
];
