<?php

namespace Roquin\RoqNewsevent\Hooks;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2011 Georg Ringer <typo3@ringerge.org>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use GeorgRinger\News\Hooks\PageLayoutView;

/**
 * Hook to display verbose information about pi1 plugin in Web>Page module
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class CmsLayout
{

    /**
     * Returns information about this extension's pi1 plugin
     *
     * @param array $params Parameters to the hook
     * @param PageLayoutView $pageLayoutView
     * @return string Information about pi1 plugin
     */
    public function extensionSummary(array $params, PageLayoutView $pageLayoutView)
    {
        switch ($params['action']) {
            case 'news_eventlist':
                $pageLayoutView->getStartingPoint();
                $pageLayoutView->getCategorySettings();
                $pageLayoutView->getDetailPidSetting();
                $pageLayoutView->getTimeRestrictionSetting();
                $pageLayoutView->getTemplateLayoutSettings($params['row']['pid']);
                $pageLayoutView->getArchiveSettings();
                $pageLayoutView->getTopNewsRestrictionSetting();
                $pageLayoutView->getOrderSettings();
                $pageLayoutView->getOffsetLimitSettings();
                $pageLayoutView->getListPidSetting();
                $pageLayoutView->getTagRestrictionSetting();
                break;
            case 'news_eventdetail':
                $pageLayoutView->getSingleNewsSettings();
                $pageLayoutView->getDetailPidSetting();
                $pageLayoutView->getTemplateLayoutSettings($params['row']['pid']);
                break;
            case 'news_eventdatemenu':
                $pageLayoutView->getStartingPoint();
                $pageLayoutView->getTimeRestrictionSetting();
                $pageLayoutView->getTopNewsRestrictionSetting();
                $pageLayoutView->getArchiveSettings();
                $pageLayoutView->getDateMenuSettings();
                $pageLayoutView->getCategorySettings();
                $pageLayoutView->getTemplateLayoutSettings($params['row']['pid']);
                break;
            default:
        }

    }

}