<?php

declare(strict_types=1);

$single = [
    'type' => 'object',
    'additionalProperties' => false,
    'required' => [
        "long_url",
    ],
    'properties' => [
        'long_url' => [
            'type' => 'string',
            'format' => 'uri',
        ],
        'title' => [
            'type' => 'string',
        ],
        'tags' => [
            'type' => 'array',
            'uniqueItems' => true,
            'items' => [
                'type' => 'string',
            ]
        ],
    ]
];

return [
    'single' => $single,
    'multiple' => [
        'type' => 'array',
        'minItems' => 1,
        'items' => $single,
    ]
];