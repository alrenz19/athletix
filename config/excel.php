<?php

return [
    'exports' => [
        'chunk_size' => 1000,
        'pre_calculate_formulas' => false,
        'strict_null_comparison' => false,
        'csv' => [
            'delimiter' => ',',
            'enclosure' => '"',
            'line_ending' => PHP_EOL,
            'use_bom' => false,
            'include_separator_line' => false,
            'excel_compatibility' => false,
            'output_encoding' => '',
        ],
        'properties' => [
            'creator' => 'Maatwebsite',
            'lastModifiedBy' => 'Maatwebsite',
            'title' => 'Spreadsheet',
            'description' => 'Default spreadsheet export',
            'subject' => 'Spreadsheet export',
            'keywords' => 'laravel, spreadsheet, excel',
            'category' => 'Excel',
            'manager' => 'Maatwebsite',
            'company' => 'Maatwebsite',
        ],
    ],

    'imports' => [
        'read_only' => true,
        'ignore_empty' => false,
        'heading_row' => [
            'formatter' => 'slug',
        ],
        'csv' => [
            'delimiter' => null,
            'enclosure' => '"',
            'escape_character' => '\\',
            'contiguous' => false,
            'input_encoding' => 'UTF-8',
        ],
        'properties' => [
            'creator' => 'Maatwebsite',
            'lastModifiedBy' => 'Maatwebsite',
            'title' => 'Spreadsheet',
            'description' => 'Default spreadsheet import',
            'subject' => 'Spreadsheet import',
            'keywords' => 'laravel, spreadsheet, excel',
            'category' => 'Excel',
            'manager' => 'Maatwebsite',
            'company' => 'Maatwebsite',
        ],
    ],

    'extension_detector' => [
        'xlsx' => 'Xlsx',
        'xlsm' => 'Xlsx',
        'xltx' => 'Xlsx',
        'xltm' => 'Xlsx',
        'xls' => 'Xls',
        'xlt' => 'Xls',
        'ods' => 'Ods',
        'ots' => 'Ods',
        'slk' => 'Slk',
        'xml' => 'Xml',
        'gnumeric' => 'Gnumeric',
        'htm' => 'Html',
        'html' => 'Html',
        'csv' => 'Csv',
        'tsv' => 'Csv',
        'pdf' => 'Dompdf',
    ],

    'value_binder' => [
        'default' => Maatwebsite\Excel\DefaultValueBinder::class,
    ],

    'cache' => [
        'driver' => 'memory',
        'batch' => [
            'memory_limit' => 60000,
        ],
        'illuminate' => [
            'store' => null,
        ],
    ],

    'transactions' => [
        'handler' => 'db',
        'db' => [
            'connection' => null,
        ],
    ],

    'temporary_files' => [
        'local_path' => storage_path('framework/cache/laravel-excel'),
        'remote_disk' => null,
        'remote_prefix' => null,
        'force_resync_remote' => null,
    ],
];