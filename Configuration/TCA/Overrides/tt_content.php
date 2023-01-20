<?php

/*
 * This file is part of the package ku_video23.
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 * Sep 2022 Nanna Ellegaard, University of Copenhagen.
 */

defined('TYPO3') or die();

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

call_user_func(function () {
    $ll = 'LLL:EXT:ku_video23/Resources/Private/Language/locallang_be.xlf:';

    ExtensionUtility::registerPlugin(
        'ku_video23',
        'Pi1',
        $ll . 'video23_title',
        'ku-video23-icon'
    );
});

// Remove default plugin fields
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['kuvideo23_pi1'] = 'recursive,pages';