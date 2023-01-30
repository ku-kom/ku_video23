<?php

/*
 * This file is part of the package ku_video23.
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 * Sep 2022 Nanna Ellegaard, University of Copenhagen.
 */

defined('TYPO3') or die('Access denied.');

$contentTypeName = 'ku_video23';
$ll = 'LLL:EXT:'.$contentTypeName.'/Resources/Private/Language/locallang_be.xlf:';

// Add Content Element
if (!is_array($GLOBALS['TCA']['tt_content']['types'][$contentTypeName] ?? false)) {
    $GLOBALS['TCA']['tt_content']['types'][$contentTypeName] = [];
}

// Add content element to selector list
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'CType',
    [
        $ll . 'video23_title',
        $contentTypeName,
        'ku-video23-icon'
    ],
    'special',
    'after'
);

// Assign Icon
$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes'][$contentTypeName] = 'ku-video23-icon';

// Configure element type
$GLOBALS['TCA']['tt_content']['types'][$contentTypeName] = array_replace_recursive(
    $GLOBALS['TCA']['tt_content']['types'][$contentTypeName],
    [
        'showitem' => '
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general,
                --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.headers;headers,
                tx_ku_video23_search,
            --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
                --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.frames;frames,
                --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.appearanceLinks;appearanceLinks,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                --palette--;;language,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                --palette--;;hidden,
                --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.access;access,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
                categories,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
                rowDescription,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
        '
    ]
);

// Register fields
$GLOBALS['TCA']['tt_content']['columns'] = array_replace_recursive(
    $GLOBALS['TCA']['tt_content']['columns'],
    [
        'tx_ku_video23_search' => [
            'label' => $ll . 'video23_desc',
            'config' => [
                'placeholder' => $ll . 'video23_desc',
                'type' => 'group',
                'allowed' => 'tt_content',
                'minitems' => 0,
                'maxitems' => 10,
                'fieldControl' => [
                    'elementBrowser' => [
                        'disabled' => true,
                    ],
                ],
                'suggestOptions' => [
                    'default' => [
                        'minimumCharacters' => 2,
                        'maxItemsInResultList' => 20,
                        'maxPathTitleLength' => 50,
                        'searchWholePhrase' => true,
                        'receiverClass' => \UniversityOfCopenhagen\KuVideo23\Backend\Wizard\SuggestWizardReceiver::class
                    ],
                ],
            ]
        ]
    ]
);
