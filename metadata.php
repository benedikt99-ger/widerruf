<?php

/**
 * Copyright © benedikt nünemann. All rights reserved.
 */

$sMetadataVersion = '2.1';
/**
 * Module information
 */
$aModule = [
    'id'          => \nuenemann\widerruf\Module::MODULE_ID,
    'title'       => 'Widerruf Formular für OXID 7',
    'description' => 'Widerruf Formular für OXID 7',
    'thumbnail'   => 'bn_logo.png',
    'version'     => '0.1.0',
    'author'      => 'Nünemann',
    'url'         => 'https://github.com/benedikt99-ger/widerruf',
    'email'       => 'benedikt@nuenemann.de',
	'extend' => [
		\OxidEsales\Eshop\Application\Model\Order::class => \nuenemann\widerruf\Model\Order::class,
		\OxidEsales\Eshop\Core\Email::class => \nuenemann\widerruf\Application\Extend\Email::class
	],
    'controllers' => [
        'OrderController'  => \nuenemann\widerruf\Controller\Admin\WiderrufController::class,
    ],	
    'templates' => [
        'withdrawalform.twig' => 'nuenemann/widerruf/Application/views/widerrufform.twig',
        'withdrawalEmailHtml.twig' => 'nuenemann/widerruf/Application/views/widerrufEmailHtml.twig',
        'withdrawalEmailPlain.twig' => 'nuenemann/widerruf/Application/views/widerrufEmailPlain.twig'
    ],	
   'settings' => [
        [
            'group' => 'WiderrufMain','name' => 'WiderrufSitekey',
            'type' => 'str','value' => '','position' => 0
        ],
        [
            'group' => 'WiderrufMain','name' => 'WiderrufSecret',
            'type' => 'str','value' => '','position' => 1
        ],
        [
            'group' => 'WiderrufMain','name' => 'WiderrufReasons',
            'type' => 'aarr','value' => [
                'small' => 'zu klein',
                'large' => 'zu groß',
                'dislike' => 'nicht gefallen'
            ],'position' => 2
        ],
        [
            'group' => 'WiderrufMain','name' => 'WiderrufEmail',
            'type' => 'str','value' => '','position' => 3
        ],
        [
            'group' => 'WiderrufMain','name' => 'WiderrufCC',
            'type' => 'arr','value' => [],'position' => 4
        ],
        [
            'group' => 'WiderrufMain','name' => 'WiderrufRetoureportal',
            'type' => 'str','value' => '','position' => 5
        ],
        [
            'group' => 'WiderrufMain','name' => 'WiderrufBootstrapVersion',
            'type' => 'select','value' => 'v3','constraints' => 'v3|v4','position' => 6
        ]
    ]
];