<?php

/*
 * This file is part of the package ku_video23.
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 * Sep 2022 Nanna Ellegaard, University of Copenhagen.
 */

/**
 * Icon registry
 */

defined('TYPO3_MODE') || die();

return [
    // icon identifier
    'ku-video23-icon' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        'source' => 'EXT:ku_video23/Resources/Public/Icons/Extension.svg',
    ],
];
