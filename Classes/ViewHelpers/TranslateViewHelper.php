<?php

/**
 * Copyright (c) 2012, ROQUIN B.V. (C), http://www.roquin.nl
 *
 * @author:         J. de Groot <jochem@roquin.nl>
 * @file:           EventController.php
 * @description:    Translate view helper, extending the fluid translate viewhelper
 */
class Tx_RoqNewsevent_ViewHelpers_TranslateViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
{

    /**
     * Translate a given key or use the tag body as default.
     *
     * @return string The translated key or tag body if key doesn't exist
     */
    public function render()
    {
        $value = parent::render();

        if (!isset($value)) {
            $value = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate($this->arguments['key'], 'roq_newsevent',
                $this->arguments);
        }

        return $value;
    }
}
