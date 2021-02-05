<?php

/**
 * This file is part of the VIS2:WebERP package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package VIS2:WebERP
 * @link https://jbs-newmedia.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 */

?>

<th class="ddm_element_<?php echo $this->getListElementValue($element, 'id') ?>">
	<?php echo \osWFrame\Core\HTML::outputString($this->getListElementValue($element, 'title')) ?>
</th>