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

if (!isset($_GET['release'])) {
	\osWFrame\Core\Network::sendHeader('Location: https://github.com/jbs-newmedia/synchronize/releases');
}

\osWFrame\Core\Network::sendHeader('Location: https://github.com/jbs-newmedia/synchronize/archive/'.$_GET['release'].'.zip');

?>