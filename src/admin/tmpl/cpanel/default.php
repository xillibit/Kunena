<?php
/**
 * Kunena Component
 *
 * @package         Kunena.Administrator.Template
 * @subpackage      CPanel
 *
 * @copyright       Copyright (C) 2008 - 2021 Kunena Team. All rights reserved.
 * @license         https://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link            https://www.kunena.org
 **/
defined('_JEXEC') or die();

use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Kunena\Forum\Libraries\Attachment\KunenaAttachmentHelper;
use Kunena\Forum\Libraries\Date\KunenaDate;
use Kunena\Forum\Libraries\Forum\KunenaForum;
use Kunena\Forum\Libraries\Forum\KunenaStatistics;
use Kunena\Forum\Libraries\Integration\KunenaPlugins;
use Kunena\Forum\Libraries\Template\KunenaTemplate;
use Kunena\Forum\Libraries\User\KunenaUser;
use Kunena\Forum\Libraries\User\KunenaUserHelper;
use Kunena\Forum\Libraries\Version\KunenaVersion;

$count = KunenaStatistics::getInstance()->loadCategoryCount();
?>

<div id="kunena" class="container-fluid">
	<div class="row">
		<div id="j-main-container" class="col-md-12" role="main">
			<?php if (!KunenaForum::versionSampleData()): ?>
				<div class="row clearfix">
					<div class="col-xl-3 col-md-3">
						<div class="card proj-t-card bg-warning">
							<div class="card-body">
								<div class="row align-items-center mb-30">
									<div class="col-auto">
										<i class="fas fa-database text-white f-30"></i>
									</div>
									<div class="col pl-0">
										<h6 class="mb-0 text-white">Install</h6>
										<h6 class="mb-0 text-white">Sample Data</h6>
									</div>
								</div>
								<div>
									<ul id="sample-data-wrapper" class="list-group list-group-flush">
										<li class="list-group-item sampleData-kunena">
											<div class="d-flex justify-content-between align-items-center">
												<div class="mr-2">
													<span class="fas fa-comments" aria-hidden="true"></span>
													Kunena Forum Sample Data
												</div>
												<button type="button" class="btn btn-secondary btn-sm apply-sample-data"
														data-type="kunena" data-steps="1">
													<span class="fas fa-upload" aria-hidden="true"></span> Install
													<span class="sr-only">Kunena Forum Sample Data</span>
												</button>
											</div>
											<p class="small mt-1">Install Sample Data - Kunena Forum</p>
										</li>
										<li class="list-group-item sampleData-progress-kunena d-none">
											<div class="progress">
												<div class="progress-bar progress-bar-striped progress-bar-animated"
													 role="progressbar"></div>
											</div>
										</li>
										<li class="list-group-item sampleData-progress-kunena d-none">
											<ul class="list-unstyled"></ul>
										</li>
									</ul>
								</div>
								<h6 class="pt-badge bg-cyan"><i class="fas fa-exclamation text-white f-18"></i></h6>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>
			<div class="row clearfix">
					<div class="col-xl-3 col-md-3">
						<div class="card proj-t-card bg-warning">
							<div class="card-body">
								<div class="row align-items-center mb-30">
									<div class="col-auto">
										<i class="fas fa-database text-white f-30"></i>
									</div>
									<div class="col pl-0">
										<h6 class="mb-0 text-white">Install</h6>
										<h6 class="mb-0 text-white">Kunena Menu</h6>
									</div>
								</div>
								<div>
									<ul id="sample-data-wrapper" class="list-group list-group-flush">
										<li class="list-group-item sampleData-kunena">
											<div class="d-flex justify-content-between align-items-center">
												<div class="mr-2">
													<span class="fas fa-comments" aria-hidden="true"></span>
													Kunena Menus
												</div>
												<button type="button" class="btn btn-secondary btn-sm apply-sample-data"
														data-type="kunena" data-steps="1">
													<span class="fas fa-upload" aria-hidden="true"></span> Install
													<span class="sr-only">Kunena Menus</span>
												</button>
											</div>
											<p class="small mt-1">Install Kunena Menus</p>
										</li>
										<li class="list-group-item sampleData-progress-kunena d-none">
											<div class="progress">
												<div class="progress-bar progress-bar-striped progress-bar-animated"
													 role="progressbar"></div>
											</div>
										</li>
										<li class="list-group-item sampleData-progress-kunena d-none">
											<ul class="list-unstyled"></ul>
										</li>
									</ul>
								</div>
								<h6 class="pt-badge bg-cyan"><i class="fas fa-exclamation text-white f-18"></i></h6>
							</div>
						</div>
					</div>
				</div>
			<div class="row clearfix">
				<div class="col-xl-4 col-md-12">
					<div class="card proj-t-card">
						<div class="card-body">
							<div class="row align-items-center mb-30">
								<div class="col-auto">
									<i class="fas fa-eye text-cyan f-30"></i>
								</div>
								<div class="col pl-0">
									<h6 class="mb-0">Ticket Answered</h6>
									<h6 class="mb-0 text-cyan">Live Update</h6>
								</div>
							</div>
							<div class="row align-items-center text-center">
								<div class="col">
									<h6 class="mb-0">327</h6></div>
								<div class="col"><i class="fas fa-exchange-alt text-cyan f-18"></i></div>
								<div class="col">
									<h6 class="mb-0">10 Days</h6></div>
							</div>
							<h6 class="pt-badge bg-cyan"><i class="fas fa-exclamation text-white f-18"></i></h6>
						</div>
					</div>
				</div>
				<div class="col-xl-4 col-md-6">
					<div class="card proj-t-card">
						<div class="card-body">
							<div class="row align-items-center mb-30">
								<div class="col-auto">
									<i class="fas fa-cloud-download-alt text-cyan f-30"></i>
								</div>
								<div class="col pl-0">
									<h6 class="mb-0">Kunena Version Check</h6>
									<h6 class="mb-0 text-cyan">Last Check: Today</h6>
								</div>
							</div>
							<div class="row align-items-center">
								<div class="col pl-5">
									<img loading=lazy src="components/com_kunena/media/icons/kunena_logo.png"
										 style="width: 70%"/>
								</div>
								<div class="col">
									<h6 class="mb-0"><?php echo strtoupper(KunenaForum::version()); ?></h6>
									<h6 class="mb-0 text-cyan"><?php echo strtoupper(KunenaForum::versionDate()); ?></h6>
								</div>
							</div>
							<h6 class="pt-badge bg-cyan"><i class="fas fa-check text-white f-18"></i></h6>
						</div>
					</div>
				</div>
				<div class="col-xl-4 col-md-6">
					<div class="card proj-t-card">
						<div class="card-body">
							<div class="row align-items-center mb-30">
								<div class="col-auto">
									<i class="fas fa-lightbulb text-cyan f-30"></i>
								</div>
								<div class="col pl-0">
									<h6 class="mb-0">Unique Innovation</h6>
									<h6 class="mb-0 text-cyan">Last Check</h6>
								</div>
							</div>
							<div class="row align-items-center text-center">
								<div class="col">
									<h6 class="mb-0">Today</h6></div>
								<div class="col"><i class="fas fa-exchange-alt text-cyan f-18"></i></div>
								<div class="col">
									<h6 class="mb-0">248</h6></div>
							</div>
							<h6 class="pt-badge bg-cyan">73%</h6>
						</div>
					</div>
				</div>

				<div class="col-xl-3 col-md-12">
					<div class="card proj-t-card comp-card">
						<div class="card-body">
							<div class="row align-items-center">
								<div class="col">
									<h6 class="mb-25">
										<a href="<?php echo Route::_('index.php?option=com_kunena&view=categories'); ?>">
											<?php echo Text::_('COM_KUNENA_CPANEL_LABEL_CATEGORIES') ?>
										</a>
									</h6>
									<h3 class="fw-700 text-cyan">
										<?php echo $count['sections'] . ' / ' . $count['categories']; ?>
									</h3>
									<p class="mb-0">Last Edit: Welcome</p>
								</div>
								<div class="col-auto">
									<i class="fas fa-list-alt bg-cyan"></i>
								</div>
								<span class="pt-badge bg-cyan">
									<a href="<?php echo Route::_('index.php?option=com_kunena&view=categories&layout=create'); ?>">
										<i class="fas fa-plus"
										   style="width: 12px;height: 12px;margin-top: -40px;font-size: 12px"></i>
									</a>
								</span>
							</div>
						</div>
					</div>
				</div>

				<div class="col-xl-3 col-md-6">
					<div class="card proj-t-card comp-card">
						<div class="card-body">
							<div class="row align-items-center">
								<div class="col">
									<h6 class="mb-25">
										<a href="<?php echo Route::_('index.php?option=com_kunena&view=users'); ?>">
											<?php echo Text::_('COM_KUNENA_CPANEL_LABEL_USERS') ?>
										</a>
									</h6>
									<h3 class="fw-700 text-cyan"><?php echo KunenaUserHelper::getTotalCount(); ?></h3>
									<p class="mb-0"><?php $lastid = KunenaUserHelper::getLastId();
										$user                     = KunenaUser::getInstance($lastid)->registerDate;
										echo KunenaDate::getInstance($user)->toKunena('ago'); ?></p>
								</div>
								<div class="col-auto">
									<i class="fas fa-users bg-cyan"></i>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-xl-3 col-md-6">
					<div class="card proj-t-card comp-card">
						<div class="card-body">
							<div class="row align-items-center">
								<div class="col">
									<h6 class="mb-25">
										<a href="<?php echo Route::_('index.php?option=com_kunena&view=attachments'); ?>">
											<?php echo Text::_('COM_KUNENA_CPANEL_LABEL_FILES') ?>
										</a>
									</h6>
									<h3 class="fw-700 text-cyan"><?php echo KunenaAttachmentHelper::getTotalAttachments(); ?></h3>
									<p class="mb-0">photo.png (topic id: 44343)</p>
								</div>
								<div class="col-auto">
									<i class="fas fa-photo-video bg-cyan"></i>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-xl-3 col-md-6">
					<div class="card proj-t-card comp-card">
						<div class="card-body">
							<div class="row align-items-center">
								<div class="col">
									<h6 class="mb-25">
										<a href="<?php echo Route::_('index.php?option=com_kunena&view=smilies'); ?>">
											<?php echo Text::_('COM_KUNENA_CPANEL_LABEL_EMOTICONS') ?>
										</a>
									</h6>
									<h3 class="fw-700 text-cyan"><?php echo KunenaStatistics::getTotalEmoticons() ?></h3>
									<p class="mb-0"><?php echo Text::_('COM_KUNENA_EDITOR_SMILIES') ?></p>
								</div>
								<div class="col-auto">
									<i class="fas fa-grin-squint-tears bg-cyan"></i>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-xl-3 col-md-12">
					<div class="card proj-t-card comp-card">
						<div class="card-body">
							<div class="row align-items-center">
								<div class="col">
									<h6 class="mb-25">
										<a href="<?php echo Route::_('index.php?option=com_kunena&view=config'); ?>">
											<?php echo Text::_('COM_KUNENA_CPANEL_LABEL_CONFIG') ?>
										</a>
									</h6>
									<h3 class="fw-700 text-cyan">&nbsp;</h3>
									<p class="mb-0">&nbsp;</p>
								</div>
								<div class="col-auto">
									<i class="fas fa-edit bg-cyan"></i>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-xl-3 col-md-6">
					<div class="card proj-t-card comp-card">
						<div class="card-body">
							<div class="row align-items-center">
								<div class="col">
									<h6 class="mb-25">
										<a href="<?php echo Route::_('index.php?option=com_kunena&view=statistics'); ?>">
											<?php echo Text::_('COM_KUNENA_MENU_STATISTICS') ?>
										</a>
									</h6>
									<h3 class="fw-700 text-cyan">&nbsp;</h3>
									<p class="mb-0">&nbsp;</p>
								</div>
								<div class="col-auto">
									<i class="fas fa-chart-bar bg-cyan"></i>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-xl-3 col-md-6">
					<div class="card proj-t-card comp-card">
						<div class="card-body">
							<div class="row align-items-center">
								<div class="col">
									<h6 class="mb-25">
										<a href="<?php echo Route::_('index.php?option=com_kunena&view=templates'); ?>">
											<?php echo Text::_('COM_KUNENA_CPANEL_LABEL_TEMPLATES') ?>
										</a>
									</h6>
									<h3 class="fw-700 text-cyan"><?php echo count(KunenaTemplate::getInstance()->getTemplatePaths()); ?></h3>
									<p class="mb-0">Installed</p>
								</div>
								<div class="col-auto">
									<i class="fas fa-palette bg-cyan"></i>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-xl-3 col-md-6">
					<div class="card proj-t-card comp-card">
						<div class="card-body">
							<div class="row align-items-center">
								<div class="col">
									<h6 class="mb-25">
										<a href="<?php echo Route::_('index.php?option=com_kunena&view=ranks'); ?>">
											<?php echo Text::_('COM_KUNENA_CPANEL_LABEL_RANKS') ?>
										</a>
									</h6>
									<h3 class="fw-700 text-cyan"><?php echo KunenaUserHelper::getTotalRanks(); ?></h3>
									<p class="mb-0">Groups</p>
								</div>
								<div class="col-auto">
									<i class="fas fa-bars bg-cyan"></i>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-xl-3 col-md-12">
					<div class="card proj-t-card comp-card">
						<div class="card-body">
							<div class="row align-items-center">
								<div class="col">
									<h6 class="mb-25">
										<a href="<?php echo Route::_('index.php?option=com_kunena&view=plugins'); ?>">
											<?php echo Text::_('COM_KUNENA_CPANEL_LABEL_PLUGINS') ?>
										</a>
									</h6>
									<h3 class="fw-700 text-cyan"><?php echo KunenaPlugins::getTotalPlugins(); ?></h3>
									<p class="mb-0"><?php echo Text::_('COM_KUNENA_CPANEL_LABEL_PLUGINS') ?></p>
								</div>
								<div class="col-auto">
									<i class="fas fa-plug bg-cyan"></i>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-xl-3 col-md-6">
					<div class="card proj-t-card comp-card">
						<div class="card-body">
							<div class="row align-items-center">
								<div class="col">
									<h6 class="mb-25">
										<a href="<?php echo Route::_('index.php?option=com_kunena&view=trash'); ?>">
											<?php echo Text::_('COM_KUNENA_CPANEL_LABEL_TRASH') ?>
										</a>
									</h6>
									<h3 class="fw-700 text-cyan">12</h3>
									<p class="mb-0">Items</p>
								</div>
								<div class="col-auto">
									<i class="fas fa-trash-alt bg-cyan"></i>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-3 col-md-6">
					<div class="card proj-t-card comp-card">
						<div class="card-body">
							<div class="row align-items-center">
								<div class="col">
									<h6 class="mb-25">
										<a href="<?php echo Route::_('index.php?option=com_kunena&view=logs'); ?>">
											<?php echo Text::_('COM_KUNENA_LOG_MANAGER') ?>
										</a>
									</h6>
									<h3 class="fw-700 text-cyan">12</h3>
									<p class="mb-0">Items</p>
								</div>
								<div class="col-auto">
									<i class="fas fa-clipboard-list bg-cyan"></i>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-3 col-md-6">
					<div class="card proj-t-card comp-card">
						<div class="card-body">
							<div class="row align-items-center">
								<div class="col">
									<h6 class="mb-25">
										<a href="<?php echo Route::_('index.php?option=com_kunena&view=tools'); ?>">
											<?php echo Text::_('COM_KUNENA_CPANEL_LABEL_TOOLS') ?>
										</a>
									</h6>
									<h3 class="fw-700 text-cyan">12</h3>
									<p class="mb-0">Items</p>
								</div>
								<div class="col-auto">
									<i class="fas fa-tools bg-cyan"></i>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="copyright"><?php echo KunenaVersion::getLongVersionHTML(); ?></div>
	</div>
</div>
