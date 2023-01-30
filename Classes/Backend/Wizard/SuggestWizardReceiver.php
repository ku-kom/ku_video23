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
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\StringUtility;
use UniversityOfCopenhagen\KuVideo23\Utility\Video;

class SuggestWizardReceiver extends SuggestWizardDefaultReceiver
{
    public const DELIMITER = '__--__';
    public function queryTable(&$params, $recursionCounter = 0)
    {
        $rows = [];
        $requestFactory = GeneralUtility::makeInstance(RequestFactory::class);
        $domain = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('ku_video23', 'uri');
        $endpoint = '/api/photo/list';
        $url = $domain . $endpoint;
        $query = strtolower($params['value']);
        $additionalOptions = [
            //'debug' => true,
            'form_params' => [
              'format' => 'json',
              'search' => $query
            ]
          ];

        if (isset($url)) {
            try {
                $response = $requestFactory->request($url, 'POST', $additionalOptions);
                // Get the content on a successful request
                // e.g. https://video.ku.dk/api/photo/list?format=json&search=morten
                if ($response->getStatusCode() === 200) {
                    if (false !== strpos($response->getHeaderLine('Content-Type'), 'application/x-javascript')) {
                        // getContents() returns a string
                        $string = $response->getBody()->getContents();
                        // Remove invalid "var visual" variable from video23 response
                        $visual = preg_replace('/^var visual = /', '', $string);
                        // Decode string to json
                        $data = json_decode((string) $visual, true);
                        // Get video node
                        $videos = $data['photos'];

                        //var_dump($videos);
                        

                        foreach ($videos as $video) {
                            $duration = Video::formatTimestamp($video['video_length']);
                            $thumb = $domain . $video['quad100_download'];
                            $title = htmlspecialchars($video['title'], ENT_QUOTES);

                            $newUid = StringUtility::getUniqueId('NEW');
                            $rows[$this->table . '_' . $newUid] = [
                                'class' => '',
                                'label' => $video['title'],
                                'path' => '',
                                'sprite' => '',
                                'style' => '',
                                'table' => $this->table,
                                'text' => '<div class="video-data">
                                            <div class="video-img"><img src="'. $thumb .'" alt="" class="img-fluid" /></div>
                                            <div class="video-content">
                                                <div class="video-title">'. $title . '</div>'. sprintf($this->getLanguageService()->sL('LLL:EXT:ku_video23/Resources/Private/Language/locallang_be.xlf:duration')) .': '. $duration .'<br>'. sprintf($this->getLanguageService()->sL('LLL:EXT:ku_video23/Resources/Private/Language/locallang_be.xlf:views')) .': '. $video['view_count'] .'</div>
                                          </div>',
                                'uid' => $newUid . self::DELIMITER . $video['photo_id'],
                            ];
                        }

                        return $rows;
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
