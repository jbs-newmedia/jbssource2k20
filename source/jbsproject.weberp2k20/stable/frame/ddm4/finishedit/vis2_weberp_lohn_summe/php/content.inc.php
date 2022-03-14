<?php

/**
 * This file is part of the VIS2:WebERP package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package VIS2:WebERP
 * @link https://jbs-newmedia.com
 * @license MIT License
 */

$QupdateData=self::getConnection();
$QupdateData->prepare('UPDATE :table_weberp_lohn: SET lohn_gesamt_netto=lohn_01_netto+lohn_02_netto+lohn_03_netto+lohn_04_netto+lohn_05_netto+lohn_06_netto+lohn_07_netto+lohn_08_netto+lohn_09_netto+lohn_10_netto+lohn_11_netto+lohn_12_netto WHERE lohn_id=:lohn_id:');
$QupdateData->bindTable(':table_weberp_lohn:', 'weberp_lohn');
$QupdateData->bindInt(':lohn_id:', $this->getIndexElementStorage($ddm_group));
$QupdateData->execute();

$QupdateData=self::getConnection();
$QupdateData->prepare('UPDATE :table_weberp_lohn: SET lohn_gesamt_brutto=lohn_01_brutto+lohn_02_brutto+lohn_03_brutto+lohn_04_brutto+lohn_05_brutto+lohn_06_brutto+lohn_07_brutto+lohn_08_brutto+lohn_09_brutto+lohn_10_brutto+lohn_11_brutto+lohn_12_brutto WHERE lohn_id=:lohn_id:');
$QupdateData->bindTable(':table_weberp_lohn:', 'weberp_lohn');
$QupdateData->bindInt(':lohn_id:', $this->getIndexElementStorage($ddm_group));
$QupdateData->execute();

?>