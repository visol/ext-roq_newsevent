<?php
namespace Roquin\RoqNewsevent\ViewHelpers\Format;

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
 * ViewHelper to format a date, using strftime
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class DateViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{

    /**
     * Render the supplied DateTime object as a formatted date.
     *
     * @param mixed $date either a DateTime object or a string that is accepted by DateTime constructor
     * @param string $format Format String which is taken to format the Date/Time
     * @param bool $currentDate if true, the current date is used
     * @param bool $strftime if true, the strftime is used instead of date()
     * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception
     * @return string Formatted date
     */
    public function render($date = null, $format = '%Y-%m-%d', $currentDate = false, $strftime = true)
    {
        if ($currentDate) {
            if ($strftime) {
                return strftime($format, $GLOBALS['EXEC_TIME']);
            } else {
                return date($format, $GLOBALS['EXEC_TIME']);
            }
        }

        if ($date === null) {
            $date = $this->renderChildren();
            if ($date === null) {
                return '';
            }
        }
        if (!$date instanceof \DateTime) {
            try {
                $date = new \DateTime($date);
            } catch (\Exception $exception) {
                throw new \TYPO3Fluid\Fluid\Core\ViewHelper\Exception('"' . $date . '" could not be parsed by DateTime constructor.',
                    1241722579);
            }
        }

        if ($strftime) {
            return strftime($format, $date->format('U'));
        } else {
            return date($format, $date->format('U'));
        }
    }
}
