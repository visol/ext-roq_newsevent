<?php

if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

$GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['switchableControllerActions']['newItems']['--div--'] = 'Events';
$GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['switchableControllerActions']['newItems']['News->eventList;News->eventDetail'] = 'List view';
$GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['switchableControllerActions']['newItems']['News->eventDetail'] = 'Detail view';
$GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['switchableControllerActions']['newItems']['News->eventDateMenu'] = 'Date menu';

// Page module hook
$GLOBALS['TYPO3_CONF_VARS']['EXT']['news'][\GeorgRinger\News\Hooks\PageLayoutView::class]['extensionSummary']['roq_newsevent']
    = \Roquin\RoqNewsevent\Hooks\CmsLayout::class . '->extensionSummary';

// Language labels for Page Layout View
$GLOBALS['TYPO3_CONF_VARS']['SYS']['locallangXMLOverride']['EXT:news/Resources/Private/Language/locallang_be.xlf'][] = 'EXT:roq_newsevent/Resources/Private/Language/locallang_be.xlf';
$GLOBALS['TYPO3_CONF_VARS']['SYS']['locallangXMLOverride']['EXT:news/Resources/Private/Language/de.locallang_be.xlf'][] = 'EXT:roq_newsevent/Resources/Private/Language/de.locallang_be.xlf';
