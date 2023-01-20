<?php

/*
 * This file is part of the package ku_video23.
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 * Sep 2022 Nanna Ellegaard, University of Copenhagen.
 */

defined('TYPO3') or die('Access denied.');

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
