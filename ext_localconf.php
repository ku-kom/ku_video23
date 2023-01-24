<?php

/*
 * This file is part of the package ku_video23.
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 * Sep 2022 Nanna Ellegaard, University of Copenhagen.
 */

defined('TYPO3') or die();

use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

$typo3VersionNumber = VersionNumberUtility::convertVersionNumberToInteger(
    VersionNumberUtility::getNumericTypo3Version()
);

// Only include page.tsconfig if TYPO3 version is below 12 so that it is not imported twice.
if ($typo3VersionNumber < 12000000) {
    ExtensionManagementUtility::addPageTSConfig('
      @import "EXT:ku_video23/Configuration/page.tsconfig"
   ');
}

// KU register hook
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['ku_video23'] = \UniversityOfCopenhagen\KuVideo23\Hooks\DataHandlerHook::class;
