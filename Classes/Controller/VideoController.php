<?php

declare(strict_types=1);

namespace UniversityOfCopenhagen\KuVideo23\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class VideoController extends ActionController
{
    /**
     * Initiate the RequestFactory.
     */
    public function __construct(
        protected readonly RequestFactory $requestFactory,
    ) {
    }

    /**
     * Return data to fluid template.
     * @return ResponseInterface
     */
    public function videoSearchAction(): ResponseInterface
    {
        $cObjectData = $this->configurationManager->getContentObject()->data;
        $this->view->assign('data', $cObjectData);

        return $this->htmlResponse();
    }
}