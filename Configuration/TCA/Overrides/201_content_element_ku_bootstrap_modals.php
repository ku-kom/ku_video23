<?php

/*
 * This file is part of the package UniversityOfCopenhagen\KuVideo23.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3') or die('Access denied.');
call_user_func(function () {
    // Add Content Element
    if (!is_array($GLOBALS['TCA']['tt_content']['types']['ku_video23'] ?? false)) {
        $GLOBALS['TCA']['tt_content']['types']['ku_video23'] = [];
    }

    // Add content element PageTSConfig
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
        'ku_video23',
        'Configuration/TsConfig/Page/ku_video23.tsconfig',
        'LLL:EXT:ku_video23/Resources/Private/Language/locallang_be.xlf:video23_title'
    );

    // Add content element to selector list
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
        'tt_content',
        'CType',
        [
            'LLL:EXT:ku_video23/Resources/Private/Language/locallang_be.xlf:video23_title',
            'ku_video23',
            'ku-video23-icon',
            'ku_video23'
        ]
    );

    // Assign Icon
    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['ku_video23'] = 'ku-video23-icon';

    // New palette for modal content
    $GLOBALS['TCA']['tt_content']['palettes']['modals_content'] = array(
        'showitem' => 'tx_ku_video23_type,tx_ku_video23_size, tx_ku_video23_centered, --linebreak--, tx_ku_video23_button_label, --linebreak--, tx_ku_video23_modal_title, --linebreak--, image, --linebreak--, bodytext, --linebreak--, tx_ku_video23_content_elements','canNotCollapse' => 1
    );

    // Configure element type
    $GLOBALS['TCA']['tt_content']['types']['ku_video23'] = array_replace_recursive(
        $GLOBALS['TCA']['tt_content']['types']['ku_video23'],
        [
            'showitem' => '
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general,
                --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.headers;headers,
                --palette--;LLL:EXT:ku_video23/Resources/Private/Language/locallang_be.xlf:modal_content_settings;modals_content,
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
            ',
            'columnsOverrides' => [
                'bodytext' => [
                    'label' => 'LLL:EXT:ku_video23/Resources/Private/Language/locallang_be.xlf:modal_content',
                    'config' => [
                        'enableRichtext' => true,
                    ]
                ],
            ]
        ]
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', [
        'tx_ku_video23_button_label' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ku_video23/Resources/Private/Language/locallang_be.xlf:button_label',
            'description' => 'LLL:EXT:ku_video23/Resources/Private/Language/locallang_be.xlf:button_label_description',
            'config' => [
                'type' => 'input',
                'max' => 50,
                'eval' => 'trim,required'
            ],
        ],
        'tx_ku_video23_modal_title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ku_video23/Resources/Private/Language/locallang_be.xlf:modal_header',
            'config' => [
                'type' => 'input',
                'max' => 150,
                'eval' => 'trim'
            ],
        ],
        'tx_ku_video23_type' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ku_video23/Resources/Private/Language/locallang_be.xlf:modal_type',
            'description' => 'LLL:EXT:ku_video23/Resources/Private/Language/locallang_be.xlf:modal_type_desc',
            'onChange' => 'reload',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['LLL:EXT:ku_video23/Resources/Private/Language/locallang_be.xlf:modal_default','default'],
                    ['LLL:EXT:ku_video23/Resources/Private/Language/locallang_be.xlf:modal_records','content'],
                ],
            ],
        ],
        'tx_ku_video23_size' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ku_video23/Resources/Private/Language/locallang_be.xlf:modal_size',
            'description' => 'LLL:EXT:ku_video23/Resources/Private/Language/locallang_be.xlf:modal_size_desc',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['LLL:EXT:ku_video23/Resources/Private/Language/locallang_be.xlf:modal_size_sm','modal-sm'],
                    ['LLL:EXT:ku_video23/Resources/Private/Language/locallang_be.xlf:modal_size_default',''],
                    ['LLL:EXT:ku_video23/Resources/Private/Language/locallang_be.xlf:modal_size_lg','modal-lg'],
                    ['LLL:EXT:ku_video23/Resources/Private/Language/locallang_be.xlf:modal_size_xl','modal-xl'],
                ],
                'default' => 'modal-lg',
            ],
        ],
        'tx_ku_video23_centered' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ku_video23/Resources/Private/Language/locallang_be.xlf:modal_center',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                   [
                      0 => '',
                      1 => '',
                   ]
                ],
            ],
        ],
        'tx_ku_video23_content_elements' => [
             'displayCond' =>'FIELD:tx_ku_video23_type:=:content',
             'exclude' => true,
             'label' => 'LLL:EXT:ku_video23/Resources/Private/Language/locallang_be.xlf:modal_records',
             'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tt_content',
                'maxitems' => 10,
                'minitems' => 1,
                'size' => 5,
                'default' => 0,
                'suggestOptions' => [
                   'default' => [
                      'additionalSearchFields' => 'header, subheader'
                   ],
                ],
         ],
      ],
      'image' => [
        'exclude' => true,
        'label' => 'LLL:EXT:ku_video23/Resources/Private/Language/locallang_be.xlf:modal_media',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'image',
                [
                    'maxitems' => 1,
                    'filter' => [
                        0 => [
                            'parameters' => [
                                'allowedFileExtensions' => 'jpg,jpeg,png,svg',
                            ],
                        ],
                    ],
                    'appearance' => [
                        'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference'
                    ],
                    'overrideChildTca' => [
                        'columns' => [
                            'uid_local' => [
                                'config' => [
                                    'appearance' => [
                                        'elementBrowserAllowed' => 'jpg,jpeg,png,svg',
                                    ],
                                ],
                            ],
                        ],
                        'types' => [
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_UNKNOWN => [
                                'showitem' => '
                                    --palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                    --palette--;;filePalette'
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => [
                                'showitem' => '
                                    --palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                    --palette--;;filePalette'
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                                'showitem' => '
                                    --palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;
                                    imageoverlayPalette,
                                    --palette--;;filePalette'
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO => [
                                'showitem' => '
                                    --palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.audioOverlayPalette;audioOverlayPalette,
                                    --palette--;;filePalette'
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO => [
                                'showitem' => '
                                    --palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.videoOverlayPalette;videoOverlayPalette,
                                    --palette--;;filePalette'
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => [
                                'showitem' => '
                                    --palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                    --palette--;;filePalette'
                            ]
                        ]
                    ]
                ],
                $GLOBALS['TYPO3_CONF_VARS']['SYS']['mediafile_ext']
            ),
        ],
    ]);
});
