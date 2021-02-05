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

<div class="card shadow mb-4">
	<div class="card-body">

		<div class="mb-2">

			<i class="fas fa-home fa-fw"></i>
			<a href="<?php echo $this->buildHrefLink('current', 'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage().'&ordner=0') ?>"><?php echo \osWFrame\Core\HTML::outputString($VIS2_Mandant->getName()) ?></a>
			<?php if (isset($explorer['breadcrumb'])): ?>

				<?php foreach ($explorer['breadcrumb'] as $breadcrumb): ?>

					<?php if ($breadcrumb['current']==true): ?>

						➥ <?php echo \osWFrame\Core\HTML::outputString($breadcrumb['ordner_titel']) ?>

					<?php else: ?>

						➥ <a href="<?php echo $this->buildHrefLink('current', 'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage().'&ordner='.$breadcrumb['ordner_id']) ?>"><?php echo \osWFrame\Core\HTML::outputString($breadcrumb['ordner_titel']) ?></a>

					<?php endif ?>

				<?php endforeach ?>

			<?php endif ?>

		</div>

		<?php /* ?>
<?php if (isset($explorer['current'])):?>
	<div class="row">
		<div class="col-sm-10"><i class="fas fa-folder"></i> <a href="<?php echo $this->buildHrefLink('current', 'vistool='.osW_VIS2::getInstance()->getTool().'&vispage='.osW_VIS2_Navigation::getInstance()->getPage().'&ordner='.$explorer['current']['ordner_parent_id'])?>">..</a></div>
		<div class="col-sm-2">---</div>
	</div>
<?php endif?>
<?php */ ?>

		<?php if ($explorer['dirs']!=[]): ?>

			<?php foreach ($explorer['dirs'] as $dir): ?>

				<div class="row">
					<div class="col-sm-10"><i class="fas fa-folder fa-fw"></i>
						<a href="<?php echo $this->buildHrefLink('current', 'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage().'&ordner='.$dir['ordner_id']) ?>"><?php echo \osWFrame\Core\HTML::outputString($dir['ordner_titel']) ?></a>
					</div>
					<div class="col-sm-2">---</div>
				</div>

			<?php endforeach ?>

		<?php endif ?>

		<?php if ($explorer['files']!=[]): ?>

			<?php foreach ($explorer['files'] as $file): ?>

				<div class="row">
					<div class="col-sm-8"><i class="fas fa-file fa-fw"></i>
						<a target="_blank" href="<?php echo $this->buildHrefLink('current', 'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage().'&datei='.$file['dokument_id']) ?>"><?php echo \osWFrame\Core\HTML::outputString($file['dokument_titel']) ?></a>
					</div>
					<div class="col-sm-2"><?php echo substr($file['dokument_datum'], 6, 2) ?>.<?php echo substr($file['dokument_datum'], 4, 2) ?>.<?php echo substr($file['dokument_datum'], 0, 4) ?></div>
					<div class="col-sm-2">---</div>
				</div>

			<?php endforeach ?>

		<?php endif ?>

	</div>
</div>