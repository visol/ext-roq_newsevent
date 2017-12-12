<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "roq_newsevent".
 *
 * Auto generated 20-01-2014 16:02
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = [
    'title' => 'News event',
    'description' => 'Event extension based on the versatile news system. Supplies additional event functionality to news records.',
    'category' => 'plugin',
    'shy' => 0,
    'version' => '3.4.0',
    'dependencies' => 'news',
    'conflicts' => '',
    'priority' => '',
    'loadOrder' => '',
    'module' => '',
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'modify_tables' => 'tx_news_domain_model_news',
    'clearcacheonload' => 1,
    'lockType' => '',
    'author' => 'ROQUIN B.V.',
    'author_email' => 'extensions@roquin.nl',
    'author_company' => 'ROQUIN B.V.',
    'CGLcompliance' => null,
    'CGLcompliance_note' => null,
    'constraints' =>
        [
            'depends' =>
                [
                    'typo3' => '8.7.0-8.7.99',
                    'news' => '6.1.0-',
                ],
            'conflicts' =>
                [],
            'suggests' =>
                [],
        ],
    'suggests' =>
        [],
];
