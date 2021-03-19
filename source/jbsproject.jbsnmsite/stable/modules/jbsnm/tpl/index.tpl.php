<?php

/**
 * This file is part of the VIS2 package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package VIS2
 * @link https://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 */

?><!DOCTYPE html>
<html lang="<?php echo \osWFrame\Core\Language::getCurrentLanguage('short') ?>">
<head>
	<?php echo $this->getHead(); ?>
</head>
<body id="page-top">
<?php echo $this->getBody(); ?>

<body class="bg-light vh-100" style="background-attachment: fixed !important;">
	<nav class="navbar navbar-expand-md navbar-light bg-white mb-4 fixed-top shadow border-bottom border-primary" style="border-width:10px !important;">
		<div class="container">
			<a class="navbar-brand d-flex align-items-center" href="<?php echo $this->buildhrefLink('default')?>">
				<div class="navbar-brand-icon">
					<img src="<?php echo JBSNewMedia\Site\Core::getResourcePath();?>img/jbs-logo.svg" style="height: 36px;" title="JBS New Media GmbH" alt="jbs new media gmbh"/>
				</div>
			</a>

			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#PageNavigation" aria-controls="PageNavigation" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="PageNavigation">
				<ul class="navbar-nav ml-auto">
<?php foreach ($Site->getNavigation('header', \osWFrame\Core\Settings::getStringVar('frame_current_module')) as $sub=>$page):?>
<?php if(isset($page['links'])):?>
					<li class="nav-item<?php if($page['details']['active']===true):?> active<?php endif?> dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navi_<?php echo $sub?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $page['details']['title']?></a>
						<div class="dropdown-menu" aria-labelledby="navi_<?php echo $sub?>">
<?php foreach ($page['links'] as $link):?>
							<a class="dropdown-item<?php if($link['details']['active']===true):?> active<?php endif?>" href="<?php echo $this->buildhrefLink($link['details']['module'])?>"><?php echo $link['details']['title']?></a>
<?php endforeach?>
						</div>
					</li>
<?php else:?>
					<li class="nav-item">
						<a class="nav-link<?php if($page['details']['active']===true):?> active<?php endif?>" href="<?php echo $this->buildhrefLink($page['details']['module'])?>"><?php echo $page['details']['title']?></a>
					</li>
<?php endif?>
<?php endforeach?>
				</ul>
			</div>
		</div>
	</nav>

	<nav aria-label="breadcrumb" class="pb-0 mb-0" style="padding-top:5rem;">
		<ol class="container breadcrumb bg-transparent pb-3 mb-0">
<?php if(\osWFrame\Core\Settings::getStringVar('project_default_module')==\osWFrame\Core\Settings::getStringVar('frame_default_module')):?>
			<li class="breadcrumb-item active" aria-current="page">Start</li>
<?php else:?>
			<li class="breadcrumb-item"><a href="<?php echo $this->buildhrefLink('default')?>">Start</a></li>
			<li class="breadcrumb-item active" aria-current="page"><?php echo \osWFrame\Core\Language::getModuleName(\osWFrame\Core\Settings::getStringVar('frame_current_module'))?></li>
<?php endif?>
		</ol>
	</nav>

	<main role="main" class="container-fluid" style="padding-bottom:8rem;">

		<div class="container card shadow">
			<div class="card-body mb-4">
				<?php echo $content?>
			</div>
		</div>

	</main>

	<footer class="navbar navbar-expand navbar-light bg-white mt-4 fixed-bottom shadow border-top border-primary" style="border-width:3px !important"">
		<div class="container p-2">
			<ul class="navbar-nav mr-auto">
				<?php foreach ($Site->getNavigation('footer', \osWFrame\Core\Settings::getStringVar('frame_current_module')) as $page):?>
				<li class="nav-item">
					<a class="nav-link<?php if($page['details']['active']===true):?> active<?php endif?>" href="<?php echo $this->buildhrefLink($page['details']['module'])?>"><?php echo $page['details']['title']?></a>
				</li>
				<?php endforeach?>
			</ul>
			<div class="ml-auto small">
				© JBS New Media GmbH
			</div>
		</div>
	</footer>

	<a class="scroll-to-top rounded" href="<?php echo \osWFrame\Core\Navigation::getCanonicalUrl()?>#page-top"> <i class="fas fa-angle-up"></i> </a>

</body>
</html>