<?php
/**
 * Kunena Component
 *
 * @package         Kunena.Administrator.Template
 * @subpackage      Templates
 *
 * @copyright       Copyright (C) 2008 - 2020 Kunena Team. All rights reserved.
 * @license         https://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link            https://www.kunena.org
 **/
defined('_JEXEC') or die();

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

HTMLHelper::_('behavior.tooltip');
HTMLHelper::_('behavior.multiselect');
HTMLHelper::_('dropdown.init');
?>

<div id="kunena" class="container-fluid">
	<div class="row">
		<div id="j-main-container" class="col-md-12" role="main">
			<div class="card card-block bg-faded p-2">
				<div class="module-title nav-header">
					<i class="icon-color-palette"></i>
					<?php echo Text::_('COM_KUNENA_A_TEMPLATE_MANAGER') ?>
				</div>
				<hr class="hr-condensed">
				<form action="<?php echo KunenaRoute::_('administrator/index.php?option=com_kunena&view=templates') ?>"
				      method="post" id="adminForm" name="adminForm">
					<input type="hidden" name="task" value=""/>
					<input type="hidden" name="boxchecked" value="0"/>
					<?php echo HTMLHelper::_('form.token'); ?>

					<div class="btn-group pull-right hidden-phone">
						<label for="limit"
						       class="element-invisible"><?php echo Text::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
						<?php echo KunenaLayout::factory('pagination/limitbox')->set('pagination', $this->pagination); ?>
					</div>

					<table class="table table-striped">
						<thead>
						<tr>
							<th width="1%"></th>
							<th><?php echo Text::_('COM_KUNENA_A_TEMPLATE_MANAGER_TEMPLATE_NAME'); ?></th>
							<th class="center"><?php echo Text::_('COM_KUNENA_A_TEMPLATE_MANAGER_DEFAULT'); ?></th>
							<th><?php echo Text::_('COM_KUNENA_A_TEMPLATE_MANAGER_AUTHOR'); ?></th>
							<th><?php echo Text::_('COM_KUNENA_A_TEMPLATE_MANAGER_VERSION'); ?></th>
							<th><?php echo Text::_('COM_KUNENA_A_TEMPLATE_MANAGER_DATE'); ?></th>
							<th><?php echo Text::_('COM_KUNENA_A_TEMPLATE_MANAGER_AUTHOR_URL'); ?></th>
						</tr>
						</thead>
						<tfoot>
						<tr>
							<td colspan="7">
								<?php echo KunenaLayout::factory('pagination/footer')->set('pagination', $this->pagination); ?>
							</td>
						</tr>
						</tfoot>
						<tbody>
						<?php foreach ($this->templates as $id => $row) : ?>
							<tr>
								<td>
									<input type="radio" id="cb<?php echo $this->escape($row->directory); ?>"
									       name="cid[]"
									       value="<?php echo $this->escape($row->directory); ?>"
									       onclick="Joomla.isChecked(this.checked);"/>
								</td>
								<td>
									<?php $img_path = Uri::root(true) . '/components/com_kunena/template/' . $row->directory . '/assets/images/template_thumbnail.png'; ?>
									<span class="editlinktip hasTip"
									      title="<?php echo $this->escape($row->name . '::<img border="1" src="' . $this->escape($img_path) . '" name="imagelib" alt="' . Text::_('COM_KUNENA_A_TEMPLATE_MANAGER_NO_PREVIEW') . '" width="200" height="145" />'); ?>">
										<a href="<?php echo Route::_('index.php?option=com_kunena&view=templates&layout=edit&name=' . $this->escape($row->directory)); ?>"
										   title="<?php echo $this->escape($row->name); ?>">
													<?php echo $this->escape($row->name); ?></a>
							</span>
								</td>
								<td class="center">
									<?php if ($row->published == 1) : ?>
										<a class="tbody-icon disabled jgrid hasTooltip" title="Default"><span
													class="icon-featured"></span></a>
									<?php else : ?>
										<a href="javascript: void(0);"
										   onclick="return Joomla.listItemTask('cb<?php echo urlencode($row->directory); ?>','publish')">
											<span class="icon-featured pl-2"
											      title="<?php echo Text::_('COM_KUNENA_A_TEMPLATE_MANAGER_NO_DEFAULT'); ?>"></span>
										</a>
									<?php endif; ?>
								</td>
								<td>
									<?php echo $row->authorEmail ? '<a href="mailto:' . $this->escape($row->authorEmail) . '">' . $this->escape($row->author) . '</a>' : $this->escape($row->author); ?>
								</td>
								<td>
									<?php echo $this->escape($row->version); ?>
								</td>
								<td>
									<?php echo $this->escape($row->creationdate); ?>
								</td>
								<td>
									<a href="<?php echo $this->escape($row->authorUrl); ?>" target="_blank"
									   rel="noopener noreferrer"><?php echo $this->escape($row->authorUrl); ?></a>
								</td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
					<table class="table table-striped" style="padding-top: 200px;">
						<thead>
						<tr>
							<td colspan="7">
								<strong><?php echo Text::_('COM_KUNENA_A_TEMPLATE_MANAGER_PREMIUM'); ?></strong></td>
						</tr>
						</thead>
						<tbody>
						<tr>
							<th width="10%"><?php echo Text::_('COM_KUNENA_A_TEMPLATE_MANAGER_TEMPLATE_PRICE'); ?></th>
							<th width="10%"><?php echo Text::_('COM_KUNENA_A_TEMPLATE_MANAGER_TEMPLATE_NAME'); ?></th>
							<th width="5%"><?php echo Text::_('COM_KUNENA_A_TEMPLATE_MANAGER_AUTHOR'); ?></th>
							<th width="5%"><?php echo Text::_('COM_KUNENA_A_TEMPLATE_MANAGER_VERSION'); ?></th>
							<th width="5%"><?php echo Text::_('COM_KUNENA_A_TEMPLATE_MANAGER_DOWNLOAD'); ?></th>
							<th width="25%"><?php echo Text::_('COM_KUNENA_A_TEMPLATE_MANAGER_AUTHOR_URL'); ?></th>
							<th width="30%"></th>
						</tr>

						<tr>
							<td style="width: 5%;">€10,00
							</td>
							<td style="width: 7%;">
								<?php $img_path = Uri::root(true) . '/media/kunena/images/template_thumbnail.png'; ?>
								<span class="editlinktip hasTip"
								      title="<?php echo $this->escape('Blue Eagle 5' . '::<img border="1" src="' . $this->escape($img_path) . '" name="imagelib" alt="' . Text::_('COM_KUNENA_A_TEMPLATE_MANAGER_NO_PREVIEW') . '" width="200" height="145" />'); ?>">
									<a href="https://www.kunena.org/download/templates/product/blue-eagle-5"
									   target="_blank"
									   rel="noopener noreferrer">Blue Eagle 5</a>
								</span>
							</td>
							<td style="width: 7%;">
								<a href="mailto:team@kunena.org">Kunena Team</a>
							</td>
							<td style="width: 5%;">
								K5.0.X
							</td>
							<td style="width: 5%;">
								<a href="https://www.kunena.org/download/templates" target="_blank"
								   rel="noopener noreferrer"><?php echo Text::_('COM_KUNENA_A_TEMPLATE_MANAGER_DOWNLOAD'); ?></a>
							</td>
							<td style="width: 25%;">
								<a href="https://www.kunena.org" target="_blank" rel="noopener noreferrer">https://www.kunena.org</a>
							</td>
							<td style="width: 30%;">
							</td>
						</tr>
						<tr>
							<td style="width: 5%;">$8.99 - $20.00
							</td>
							<td style="width: 7%;">
								<span class="editlinktip hasTip"
								      title="<?php echo $this->escape('9themestore.com' . '::<img border="1" src="https://www.9themestore.com/images/dms/documents/nts_kmax.jpg" name="imagelib" alt="' . Text::_('COM_KUNENA_A_TEMPLATE_MANAGER_NO_PREVIEW') . '" width="200" height="145" />'); ?>">
									<a href="https://www.9themestore.com/index.php/our-themes/kunena-templates"
									   target="_blank" rel="noopener noreferrer">9themestore.com</a>
								</span>
							</td>
							<td style="width: 7%;">
								<a href="mailto:info@9themestore.com">9themestore.com</a>
							</td>
							<td style="width: 5%;">
								K5.0.X
							</td>
							<td style="width: 5%;">
								<a href="https://www.9themestore.com/index.php/our-themes/kunena-templates"
								   target="_blank"
								   rel="noopener noreferrer"><?php echo Text::_('COM_KUNENA_A_TEMPLATE_MANAGER_DOWNLOAD'); ?></a>
							</td>
							<td style="width: 25%;">
								<a href="https://www.9themestore.com/index.php/our-themes/kunena-templates"
								   target="_blank"
								   rel="noopener noreferrer">https://www.9themestore.com</a>
							</td>
							<td style="width: 30%;">
							</td>
						</tr>
						</tbody>
					</table>
				</form>
			</div>
		</div>
	</div>
	<div class="pull-right small">
		<?php echo KunenaVersion::getLongVersionHTML(); ?>
	</div>
</div>
