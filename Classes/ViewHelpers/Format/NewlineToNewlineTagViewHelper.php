<?php
/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Replaces newlines in a string by a proprietary <newline /> tag for later post-processing
 * @see TrimViewHelper
 * @author Lorenz Ulrich <lorenz.ulrich@visol.ch>
 */
class Tx_RoqNewsevent_ViewHelpers_Format_NewlineToNewlineTagViewHelper extends AbstractViewHelper {

	/**
	 * @param string $content
	 * @return string
	 */
	public function render($content = NULL) {
		if (NULL === $content) {
			$content = $this->renderChildren();
		}
		$contentArray = explode(PHP_EOL, $content);
		$returnContent = '';
		$i = 0;
		foreach ($contentArray as $line) {
			if ($i > 0) {
				$returnContent .= '<newline />';
			}
			$returnContent .= $line;
			$i++;
		}
		return $returnContent;

	}

}