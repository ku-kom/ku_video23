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
              'search' => $query
            ]
          ];

        if (!empty($url)) {
            try {
                $response = $requestFactory->request($url, 'POST', $additionalOptions);
                // Get the content on a successful request
                if ($response->getStatusCode() === 200) {
                    if (false !== strpos($response->getHeaderLine('Content-Type'), 'application/x-javascript')) {
                        //getContents() returns a string
                        $string = $response->getBody()->getContents();
                        // Remove invalid varible from video23 response
                        $visual = preg_replace('/^var visual = /', '', $string);
                        // Convert string back to json
                        $visual = iconv('ISO-8859-1', 'UTF-8', $visual);
                        $data = json_decode((string) $visual, true);
                        $videos = $data['photos'];
                        var_dump($videos);

                        foreach ($videos as $video) {
                            $newUid = StringUtility::getUniqueId('NEW');
                            $rows[$this->table . '_' . $newUid] = [
                                'class' => '',
                                'label' => $video['title'],
                                'path' => '',
                                'sprite' => '',
                                'style' => '',
                                'table' => $this->table,
                                'text' => '<div>' .$video['title'] . '</div>',
                                'uid' => $newUid . $video['photo_id'],
                            ];
                        }
                    }
                } else {
                    // Display error message
                    $message = GeneralUtility::makeInstance(
                        FlashMessage::class,
                        $this->getLanguageService()->sL('LLL:EXT:ku_video23/Resources/Private/Language/locallang_be.xlf:error'),
                        '',
                        FlashMessage::ERROR,
                        true
                    );
                    $flashMessageService = GeneralUtility::makeInstance(FlashMessageService::class);
                    $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
                    $messageQueue->addMessage($message);
                }

                return $rows;
            } catch (\Exception $e) {
                // Display error message
                $message = GeneralUtility::makeInstance(
                    FlashMessage::class,
                    $this->getLanguageService()->sL('LLL:EXT:ku_video23/Resources/Private/Language/locallang_be.xlf:error') .': ' . $e->getMessage(),
                    '',
                    FlashMessage::ERROR,
                    true
                );
                $flashMessageService = GeneralUtility::makeInstance(FlashMessageService::class);
                $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
                $messageQueue->addMessage($message);
            }
        }
    }

    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
