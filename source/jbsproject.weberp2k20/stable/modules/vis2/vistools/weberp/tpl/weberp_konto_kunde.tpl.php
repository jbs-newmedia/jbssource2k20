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

?>

<?php echo $this->Form()->startForm('form_send', 'current', 'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage().'&kunde_id='.\osWFrame\Core\Settings::catchIntGetValue('kunde_id')) ?>

<table class="table table-striped">

	<tr>
		<th colspan="7"><?php if ($kunden_details['kunde_firma']!=''): ?><?php echo $kunden_details['kunde_firma'] ?><?php else: ?><?php echo $kunden_details['kunde_vorname'] ?><?php echo $kunden_details['kunde_nachname'] ?><?php endif ?></th>
	</tr>

	<?php foreach ($positionen as $date=>$posis): ?>

		<?php foreach ($posis as $pos):# print_a($pos); ?>

			<?php if ((isset($pos['rechnung_nr']))&&($pos['rechnung_storniert']==0)): ?>
				<tr>
					<td style="width: 35px; text-align: center;"<?php if ($pos['rechnung_bezahlt']==1): ?> class="bg-success"<?php endif ?><?php if ($pos['rechnung_bezahlt']==0): ?> class="bg-danger"<?php endif ?>>
						<i class="fas fa-file-invoice text-white"></i></td>
					<td style="width: 10%;"><?php echo substr($pos['rechnung_datum'], 6, 2) ?>.<?php echo substr($pos['rechnung_datum'], 4, 2) ?>.<?php echo substr($pos['rechnung_datum'], 0, 4) ?></td>
					<td style="width: 10%;">Rechnung</td>
					<td style="width: 10%;"><?php echo $pos['rechnung_nr'] ?></td>
					<td style="width: 10%; float: right:"><?php echo $Verwaltung->formatNumber($pos['rechnung_gesamt_brutto']) ?></td>
					<td style="width: 45%;">
						<?php echo $this->Form()->drawSelectField('R'.$pos['rechnung_nr'].'_b1', $buchung_select, $pos['buchung_id_1'], ['input_class'=>'selectpicker select-ellipsis-fix form-control', 'input_parameter'=>'data-live-search="true" data-width="100%" data-style="custom-select"']) ?>
						<br/>
						<?php echo $this->Form()->drawSelectField('R'.$pos['rechnung_nr'].'_b2', $buchung_select, $pos['buchung_id_2'], ['input_class'=>'selectpicker select-ellipsis-fix form-control', 'input_parameter'=>'data-live-search="true" data-width="100%" data-style="custom-select"']) ?>
						<br/>
						<?php echo $this->Form()->drawSelectField('R'.$pos['rechnung_nr'].'_b3', $buchung_select, $pos['buchung_id_3'], ['input_class'=>'selectpicker select-ellipsis-fix form-control', 'input_parameter'=>'data-live-search="true" data-width="100%" data-style="custom-select"']) ?>
					</td>
					<td style="width: 10%;">-</td>
				</tr>

			<?php endif ?>

			<?php if (isset($pos['buchung_checksum'])): ?>
				<tr>
					<td style="width: 35px; text-align: center;"<?php if ($pos['buchung_betrag']>0): ?> class="bg-success"<?php else: ?> class="bg-info"<?php endif ?>>
						<i class="fas fa-exchange-alt text-white"></i></td>
					<td style="width: 10%; float: right:"><?php echo substr($pos['buchung_buchungtag'], 6, 2) ?>.<?php echo substr($pos['buchung_buchungtag'], 4, 2) ?>.<?php echo substr($pos['buchung_buchungtag'], 0, 4) ?></td>
					<td style="width: 10%;">Buchung</td>
					<td style="width: 10%;"><?php echo $pos['buchung_id'] ?></td>
					<td style="width: 10%;"><?php echo $Verwaltung->formatNumber($pos['buchung_betrag']) ?></td>
					<td style="width: 45%;"><?php echo $pos['buchung_verwendungszweck'] ?></td>
					<td style="width: 10%;"><?php echo $pos['buchung_checksum'] ?></td>
				</tr>

			<?php endif ?>


		<?php endforeach ?>

	<?php endforeach ?>

</table>

<?php echo $this->Form()->drawSubmit('btn_ddm_submit', 'Speichern', ['input_class'=>'btn btn-primary']) ?>

<?php echo $this->Form()->drawHiddenField('action', 'dosend') ?>
<?php echo $this->Form()->endForm() ?>
