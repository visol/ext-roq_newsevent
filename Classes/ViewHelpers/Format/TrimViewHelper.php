<?php
/**
 * Copyright (c) 2012, ROQUIN B.V. (C), http://www.roquin.nl
 *
 * @author:         J. de Groot <jochem@roquin.nl>
 * @file:           TrimViewHelper.php
 * @created:        26-7-12 15:58
 * @description:    ViewHelper to trim content with PHP trim function
 */

define('CR', "\r");          // Carriage Return: Mac
define('LF', "\n");          // Line Feed: Unix
define('CRLF', "\r\n");      // Carriage Return and Line Feed: Windows

class Tx_RoqNewsevent_ViewHelpers_Format_TrimViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * @param boolean $replaceDoubleSpaces Flag which defines if double spaces must be replaced with single spaces
	 * @param boolean $trimTabs Flag which defines if (indentation) tabs must be trimmed
	 * @param boolean $removeNewlineTags Flag which enables replacing proprietary <newline /> tags by spaces
	 * @param boolean $useWindowsLineEndings Flag which enables replacing line endings by Windows-style line endings
	 * @return string The trimmed string
	 */
	public function render($replaceDoubleSpaces = TRUE, $trimTabs = TRUE, $removeNewlineTags = FALSE, $useWindowsLineEndings = FALSE) {
		$content = $this->renderChildren();


        if ($replaceDoubleSpaces) {
            $content = preg_replace('/\s\s+/', ' ', $content);
        }

        if ($trimTabs) {
            $content = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*|[\t]*[\r\n]+/", "\n", $content);
        } else {
            $content = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $content);
        }

		if ($removeNewlineTags) {
			/*
			 These tags are inserted by the NewlineToNewlineTagViewHelper and mark a newline
			 inside a string (e.g. DESCRIPTION). This is a way of preserving them from striping
			 because a new line inside the DESCRIPTION must be indented by at least one space.
			*/
			$content = str_replace('<newline />', " ", $content);
		}

		if ($useWindowsLineEndings) {
			$content = str_replace(CR, CRLF, $content);
			$content = str_replace(LF, CRLF, $content);
		}

		return $content;

	}
}

?>