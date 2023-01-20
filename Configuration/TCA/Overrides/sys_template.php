<?php

defined('TYPO3') or die('Access denied.');

call_user_func(function () {
    /**
     * Temporary variables
     */
    $extensionKey = 'ku_video23';

    /**
     * Default TypoScript for ku_video23
     */
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        $extensionKey,
        'Configuration/TypoScript',
        'LLL:EXT:ku_video23/Resources/Private/Language/locallang_be.xlf:video23_title'
    );
});
