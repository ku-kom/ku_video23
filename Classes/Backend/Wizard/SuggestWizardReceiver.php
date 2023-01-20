<?php

declare(strict_types=1);

namespace UniversityOfCopenhagen\KuVideo23\Backend\Wizard;

/**
 * This file is part of the "news_tagsuggest" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Backend\Form\Wizard\SuggestWizardDefaultReceiver;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\StringUtility;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;

class SuggestWizardReceiver extends SuggestWizardDefaultReceiver
{
    public function queryTable(&$params, $recursionCounter = 0)
    {
        $rows = [];
        $requestFactory = GeneralUtility::makeInstance(RequestFactory::class);
        $url = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('ku_video23', 'uri');
        $query = strtolower($params['value']);
        $additionalOptions = [
            //'debug' => true,
            'form_params' => [
              'format' => 'json',
              'startrecord' => 0,
              'recordsperpage' => 100,
              'searchstring' => $query
            ]
          ];

        if (!empty($url)) {
            try {
                $response = $requestFactory->request($url, 'POST', $additionalOptions);
                // Get the content on a successful request
                if ($response->getStatusCode() === 200) {
                    if (false !== strpos($response->getHeaderLine('Content-Type'), 'application/json')) {
                        $string = $response->getBody()->getContents();
                        // getContents() returns a string
                        // Convert string back to json
                        $string = iconv('ISO-8859-1', 'UTF-8', $string);
                        $data = json_decode((string) $string, true);

                        $items = $data['root']['employees'];
                        foreach ($items as $employee) {
                            $newUid = StringUtility::getUniqueId('NEW');
                            $rows[$this->table . '_' . $newUid] = [
                                'class' => '',
                                'label' => $employee['PERSON_FORNAVN'],
                                'path' => '',
                                'sprite' => '',
                                'style' => '',
                                'table' => $this->table,
                                'text' => '<table class="table-items">
                                        <tr>
                                            <td class="img-fluid img-employee"><img src="'. $employee['FOTOURL'] .'" alt="" class="list-item-img" /></td>
                                            <td><div class="employee-name">'.$employee['PERSON_FORNAVN'] . ' ' . $employee['PERSON_EFTERNAVN'] .'</div>'. $employee['ANSAT_UOFF_STIL_TEKST'] .'<br>'. $employee['ANSAT_ARB_EMAIL'] .'</td>
                                        </tr>
                                    </table>',
                                'uid' => $employee['ANSAT_ARB_EMAIL'],
                            ];
                        }
                    }
                } else {
                    // Sisplay error message
                    $flashMessage = GeneralUtility::makeInstance(
                        \TYPO3\CMS\Core\Messaging\FlashMessage::class,
                        $this->getLanguageService()->sL('LLL:EXT:ku_video23/Resources/Private/Language/locallang_be.xlf:error'),
                        '',
                        \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR,
                        true
                    );
                    $flashMessageService = GeneralUtility::makeInstance(FlashMessageService::class);
                    $defaultFlashMessageQueue = $flashMessageService->getMessageQueueByIdentifier();
                    $defaultFlashMessageQueue->enqueue($flashMessage);
                }

                return $rows;
            } catch (\Exception $e) {
                // Display error message
                $this->addFlashMessage(
                    'Error: ' . $e->getMessage(),
                    '',
                    FlashMessage::ERROR,
                    false
                );
            }
        }
    }

    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}