<?php

/**
 * Copyright (c) 2012, ROQUIN B.V. (C), http://www.roquin.nl
 *
 * @author:         Jochem de Groot <jochem@roquin.nl>
 * @file:           class.ext_update.php
 * @created:        26-9-12 16:28
 * @description:    Update class for updating news event from version 2.0.X to newer versions
 */
class ext_update extends \TYPO3\CMS\Backend\Module\BaseScriptClass
{

    /**
     * Main method that is called whenever UPDATE! menu was clicked. This method outputs the result of the update in HTML
     *
     * @return string: HTML to display
     */
    public function main()
    {
        $affectedRows = 0;
        $errorMessage = '';
        $this->content = '';
        $this->doc = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('noDoc');

        $this->doc->backPath = $GLOBALS['BACK_PATH'];

        if ($this->updateNewsEventRecords($errorMessage, $affectedRows) == 0) {
            $this->content .= $this->doc->section('',
                'The update has been performed successfully: ' . $affectedRows . ' row(s) affected.');
        } else {
            $this->content .= $this->doc->section('',
                'An error occurred while preforming updates. Error: ' . $errorMessage);
        }

        return $this->content;
    }

    /**
     * Updates news type and news event tx_roqnewsevent_is_event attribute in database news event records
     *
     * @param $errorMessage : stores the error message, if an error has been occurred
     * @param $affectedRows : stores the affected rows, when the query has been executed
     * @return integer: returns error code (0 == success)
     */
    protected function updateNewsEventRecords(&$errorMessage, &$affectedRows)
    {
        $errorCode = 0;
        $affectedRows = 0;
        $result = false;

        $result = $GLOBALS['TYPO3_DB']->exec_UPDATEquery(
            'tx_news_domain_model_news',
            "tx_news_domain_model_news.type LIKE 'Tx_RoqNewsevent_Event'",
            array(
                'type' => 0,
                'tx_roqnewsevent_is_event' => 1,
            )
        );

        if ($result) {
            $affectedRows = $GLOBALS['TYPO3_DB']->sql_affected_rows();
        } else {
            $errorCode = $GLOBALS['TYPO3_DB']->sql_errno();
            $errorMessage = 'Could not update table tx_news_domain_model_news. ' . $GLOBALS['TYPO3_DB']->sql_error() . ' (Error code: ' . $errorCode . ').';
        }

        return $errorCode;
    }

    /**
     * Check if the update is necessary, and whether the "UPDATE!" menu item should be shown.
     *
     * @return boolean: returns true if update should be performed
     */
    public function access()
    {
        $result = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            'tx_news_domain_model_news.type',
            'tx_news_domain_model_news',
            "tx_news_domain_model_news.type LIKE 'Tx_RoqNewsevent_Event'"
        );

        // check if there are news records which must be updated
        if (($result !== false) && ($GLOBALS['TYPO3_DB']->sql_num_rows($result) > 0)) {
            return true;
        }

        return false;
    }
}
