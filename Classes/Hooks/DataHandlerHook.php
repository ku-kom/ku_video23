<?php

declare(strict_types=1);

namespace UniversityOfCopenhagen\KuVideo23\Hooks;

/**
 * This file is part of the "ku_video23" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use UniversityOfCopenhagen\KuVideo23\Backend\Wizard\SuggestWizardReceiver;
use TYPO3\CMS\Backend\Utility\BackendUtility as BackendUtilityCore;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\StringUtility;

class DataHandlerHook
{
    protected DataHandler $localDataHandler;

    public function processDatamap_preProcessFieldArray(&$fieldArray, $table, $id, DataHandler $parentObject): void
    {
        if ($table !== 'tt_content') {
            return;
        }
        if (array_key_exists('tx_ku_video23', $fieldArray) === false) {
            return;
        }
        $value = $fieldArray['tx_ku_video23'];

        // var_dump($fieldArray);
        // die();

        GeneralUtility::makeInstance(ConnectionPool::class)
        ->getConnectionForTable('tt_content')
        ->update(
            'tt_content',
            [
                'tx_ku_video23' => $value,
            ],
            [
                'uid' => $id
            ]
        );

        unset($fieldArray['tx_ku_video23']);
    }
}
