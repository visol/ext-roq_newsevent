<?php
namespace Roquin\RoqNewsevent\ViewHelpers;

use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3\CMS\Fluid\Core\ViewHelper\Exception\InvalidVariableException;

/**
 * Copyright (c) 2012, ROQUIN B.V. (C), http://www.roquin.nl
 *
 * @author:         J. de Groot <jochem@roquin.nl>
 * @file:           EventController.php
 * @description:    Translate view helper, extending the fluid translate viewhelper
 */
class TranslateViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
{
    /**
     * Return array element by key.
     *
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @throws InvalidVariableException
     * @return string
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        $value = parent::renderStatic($arguments, $renderChildrenClosure, $renderingContext);

        if (isset($value)) {
            return $value;
        }

        return LocalizationUtility::translate($arguments['key'], 'roq_newsevent', $arguments);
    }
}
