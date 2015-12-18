<?php

/**
 * Copyright (c) 2012, ROQUIN B.V. (C), http://www.roquin.nl
 *
 * @author:         J. de Groot
 * @file:           EventController.php
 * @description:    News event Controller, extending functionality from the News Controller
 */
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @package TYPO3
 * @subpackage roq_newsevent
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_RoqNewsevent_Controller_EventController extends \GeorgRinger\News\Controller\NewsController
{

    /**
     * eventRepository
     *
     * @var Tx_RoqNewsevent_Domain_Repository_EventRepository
     * @inject
     */
    protected $eventRepository;

    /**
     * Initializes the settings
     *
     * @param array $settings
     * @return array $settings
     */
    protected function initializeSettings($settings)
    {
        if (isset($settings['event']['dateField'])) {
            $settings['dateField'] = $settings['event']['dateField'];
        } else {
            $settings['dateField'] = 'eventStartdate';
        }

        return $settings;
    }

    /**
     * Overrides setViewConfiguration: Use event view configuration instead of news view configuration if an event
     * controller action is used
     *
     * @param \TYPO3\CMS\Extbase\Mvc\View\ViewInterface $view
     * @return void
     */
    protected function setViewConfiguration(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface $view)
    {
        $extbaseFrameworkConfiguration =
            $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);

        // Fetch the current controller action which is set in the news plugin
        $controllerConfigurationAction = implode(';',
            $extbaseFrameworkConfiguration['controllerConfiguration']['News']['actions']);

        parent::setViewConfiguration($view);

        // Check if the current controller configuration action matches with one of the event controller actions
        foreach ($GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['switchableControllerActions']['newItems'] as $switchableControllerActions => $value) {
            $action = str_replace('News->', '', $switchableControllerActions);

            if (strpos($action, $controllerConfigurationAction) !== false) {
                // the current controller configuration action matches with one of the event controller actions: set event view configuration
                $this->setEventViewConfiguration($view);
            }
        }
    }

    /**
     * Override templateRootPath, layoutRootPath and/or partialRootPath of the news view with event specific settings
     *
     * @param \TYPO3\CMS\Extbase\Mvc\View\ViewInterface $view
     * @return void
     */
    protected function setEventViewConfiguration(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface $view)
    {
        // Template Path Override
        $extbaseFrameworkConfiguration =
            $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);

        if (isset($extbaseFrameworkConfiguration['view']['event']['templateRootPath'])
            && strlen($extbaseFrameworkConfiguration['view']['event']['templateRootPath']) > 0
            && method_exists($view, 'setTemplateRootPath')
        ) {
            $view->setTemplateRootPath(GeneralUtility::getFileAbsFileName($extbaseFrameworkConfiguration['view']['event']['templateRootPath']));
        }
        if (isset($extbaseFrameworkConfiguration['view']['event']['layoutRootPath'])
            && strlen($extbaseFrameworkConfiguration['view']['event']['layoutRootPath']) > 0
            && method_exists($view, 'setLayoutRootPath')
        ) {
            $view->setLayoutRootPath(GeneralUtility::getFileAbsFileName($extbaseFrameworkConfiguration['view']['event']['layoutRootPath']));
        }
        if (isset($extbaseFrameworkConfiguration['view']['event']['partialRootPath'])
            && strlen($extbaseFrameworkConfiguration['view']['event']['partialRootPath']) > 0
            && method_exists($view, 'setPartialRootPath')
        ) {
            $view->setPartialRootPath(GeneralUtility::getFileAbsFileName($extbaseFrameworkConfiguration['view']['event']['partialRootPath']));
        }
    }

    /**
     * Create the demand object which define which records will get shown
     *
     * @param array $settings
     * @return \GeorgRinger\News\Domain\Model\Dto\NewsDemand
     */
    protected function eventCreateDemandObjectFromSettings($settings)
    {
        $demand = parent::createDemandObjectFromSettings($settings);
        $orderByAllowed = $demand->getOrderByAllowed();

        if (sizeof($orderByAllowed) > 0) {
            $orderByAllowed .= ',';
        }

        // set ordering
        if ($settings['event']['orderByAllowed']) {
            $demand->setOrderByAllowed($orderByAllowed . str_replace(' ', '', $settings['event']['orderByAllowed']));
        } else {
            // default orderByAllowed list
            $demand->setOrderByAllowed($orderByAllowed . 'tx_roqnewsevent_startdate,tx_roqnewsevent_starttime');
        }

        if ($demand->getArchiveRestriction() == 'archived') {
            if ($settings['event']['archived']['orderBy']) {
                $demand->setOrder($settings['event']['archived']['orderBy']);
            } else {
                // default ordering for archived events
                $demand->setOrder('tx_roqnewsevent_startdate DESC, tx_roqnewsevent_starttime DESC');
            }
        } else {
            if ($settings['event']['orderBy']) {
                $demand->setOrder($settings['event']['orderBy']);
            } else {
                // default ordering for active events
                $demand->setOrder('tx_roqnewsevent_startdate ASC, tx_roqnewsevent_starttime ASC');
            }
        }

        if ($settings['event']['startingpoint']) {
            $demand->setStoragePage(\GeorgRinger\News\Utility\Page::extendPidListByChildren($settings['event']['startingpoint'],
                    $settings['recursive']));
        }

        return $demand;
    }

    /**
     * Render a menu by dates, e.g. years, months or dates
     *
     * @param array $overwriteDemand
     * @return void
     */
    public function eventDateMenuAction(array $overwriteDemand = null)
    {
        $this->settings = $this->initializeSettings($this->settings);
        $demand = $this->eventCreateDemandObjectFromSettings($this->settings);

        $eventRecords = $this->eventRepository->findDemanded($demand);

        if (!$dateField = $this->settings['dateField']) {
            $dateField = 'eventStartdate';
        }

        $this->view->assignMultiple(array(
            'listPid' => ($this->settings['listPid'] ? $this->settings['listPid'] : $GLOBALS['TSFE']->id),
            'dateField' => $dateField,
            'events' => $eventRecords,
            'overwriteDemand' => $overwriteDemand,
        ));
    }

    /**
     * Output a list view of news events
     *
     * @param array $overwriteDemand
     * @return string the Rendered view
     */
    public function eventListAction(array $overwriteDemand = null)
    {
        $this->settings = $this->initializeSettings($this->settings);
        $demand = $this->eventCreateDemandObjectFromSettings($this->settings);

        if ($this->settings['disableOverrideDemand'] != 1 && $overwriteDemand !== null) {
            $demand = $this->overwriteDemandObject($demand, $overwriteDemand);
        }

        $newsRecords = $this->eventRepository->findDemanded($demand);

        $this->view->assignMultiple(array(
            'news' => $newsRecords,
            'overwriteDemand' => $overwriteDemand,
        ));

        \GeorgRinger\News\Utility\Cache::addPageCacheTagsByDemandObject($demand);
    }

    /**
     * Single view of a news event record
     *
     * @param Tx_RoqNewsevent_Domain_Model_Event $event
     * @param integer $currentPage current page for optional pagination
     * @return void
     */
    public function eventDetailAction(Tx_RoqNewsevent_Domain_Model_Event $event = null, $currentPage = 1)
    {
        $this->settings = $this->initializeSettings($this->settings);

        if (is_null($event)) {
            if ((int)$this->settings['singleNews'] > 0) {
                $previewNewsId = $this->settings['singleNews'];
            } elseif ($this->request->hasArgument('news_preview')) {
                $previewNewsId = $this->request->getArgument('news_preview');
            } else {
                $previewNewsId = $this->request->getArgument('news');
            }

            if ($this->settings['previewHiddenRecords']) {
                $event = $this->eventRepository->findByUid($previewNewsId, false);
            } else {
                $event = $this->eventRepository->findByUid($previewNewsId);
            }
        }

        if (is_null($event) && isset($this->settings['detail']['errorHandling'])) {
            $this->handleNoNewsFoundError($this->settings['detail']['errorHandling']);
        }

        $this->view->assignMultiple(array(
            'newsItem' => $event,
            'currentPage' => (int)$currentPage,
        ));

        \GeorgRinger\News\Utility\Page::setRegisterProperties($this->settings['detail']['registerProperties'], $event);
        if ($event instanceof Tx_RoqNewsevent_Domain_Model_Event) {
            \GeorgRinger\News\Utility\Cache::addCacheTagsByNewsRecords(array($event));
        }
    }
}

