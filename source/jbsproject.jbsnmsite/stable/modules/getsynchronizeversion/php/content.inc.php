<?php

/**
 * This file is part of the osWFrame package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package osWFrame
 * @link https://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 */

if (null===($version=\JBSNewMedia\Site\SynchronizePacker::getVersion('current'))) {
	\osWFrame\Core\Settings::dieScript('undefined');
}

\osWFrame\Core\Settings::dieScript($version);

?>