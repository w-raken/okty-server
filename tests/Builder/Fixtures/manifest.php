<?php
return [
    'files' => ['Dockerfile', 'default.conf'],
    'config' => [
        'Dockerfile' => [
            'output' => './'
        ],
        'default.conf' => [
            'output' => './',
            'args' => [
                'root_folder' => [
                    'default' => ''
                ],
                'max_upload_size' => [
                    'default' => '2M'
                ],
                'php_container_id' => [
                    'default' => ''
                ]
            ]
        ]
    ]
];
