<?php
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

/**
 * Hook to display verbose information about pi1 plugin in Web>Page module
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_Roqnewsevent_Hooks_CmsLayout extends Tx_News_Hooks_CmsLayout {

	/**
	 * Path to the locallang file
	 * @var string
	 */
	const LLPATH_NEWSEVENT = 'LLL:EXT:roq_newsevent/Resources/Private/Language/locallang_be.xml:';

	/**
	 * Returns information about this extension's pi1 plugin
	 *
	 * @param array $params Parameters to the hook
	 * @return string Information about pi1 plugin
	 */
	public function getExtensionSummary(array $params) {
		$result = $actionTranslationKey = '';

		if ($params['row']['list_type'] == self::KEY . '_pi1') {
			$this->flexformData = \TYPO3\CMS\Core\Utility\GeneralUtility::xml2array($params['row']['pi_flexform']);

			// if flexform data is found
			$actions = $this->getFieldFromFlexform('switchableControllerActions');
			if (!empty($actions)) {
				$actionList = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(';', $actions);

				// translate the first action into its translation
				$actionTranslationKey = strtolower(str_replace('->', '_', $actionList[0]));
				$actionTranslation = $GLOBALS['LANG']->sL(self::LLPATH_NEWSEVENT . 'flexforms_general.mode.' . $actionTranslationKey);

				$result .= '<pre>' . $actionTranslation . '</pre>';

			} else {
				$result = $GLOBALS['LANG']->sL(self::LLPATH . 'flexforms_general.mode.not_configured');
			}

			if (is_array($this->flexformData)) {

				switch ($actionTranslationKey) {
					case 'news_list':
					case 'news_detail':
					case 'news_datemenu':
					case 'category_list':
					case 'tag_list':
						return '';
					case 'news_eventlist':
						$this->getStartingPoint();
						$this->getTimeRestrictionSetting();
						$this->getTopNewsRestrictionSetting();
						$this->getOrderSettings();
						$this->getCategorySettings();
						$this->getArchiveSettings();
						$this->getOffsetLimitSettings();
						$this->getDetailPidSetting();
						$this->getListPidSetting();
						break;
					case 'news_eventdetail':
						$this->getSingleNewsSettings();
						$this->getDetailPidSetting();
						break;
					case 'news_eventdatemenu':
						$this->getStartingPoint();
						$this->getTimeRestrictionSetting();
						$this->getTopNewsRestrictionSetting();
						$this->getDateMenuSettings();
						$this->getCategorySettings();
						break;
					default:
				}

				// for all views
				$this->getOverrideDemandSettings();
				$this->getTemplateLayoutSettings($params['row']['pid']);

				$result .= $this->renderSettingsAsTable();
			}
		}

		return $result;
	}

}