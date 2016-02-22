<?php
/**
 * Kunena Component
 * @package Kunena.Administrator.Template
 * @subpackage Users
 *
 * @copyright (C) 2008 - 2014 Kunena Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.kunena.org
 **/
defined ( '_JEXEC' ) or die ();

/** @var KunenaAdminViewUsers $this */

JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');

?>

<script type="text/javascript">
	Joomla.orderTable = function() {
		var table = document.getElementById("sortTable");
		var direction = document.getElementById("directionTable");
		var order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $this->listOrdering; ?>') {
			var dirn = 'asc';
		} else {
			var dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
</script>

<div id="kunena" class="admin override">
	<div id="j-sidebar-container" class="span2">
		<div id="sidebar">
			<div class="sidebar-nav"><?php include KPATH_ADMIN.'/template/joomla30/common/menu.php'; ?></div>
		</div>
	</div>
	<div id="j-main-container" class="span10">
		<form action="<?php echo KunenaRoute::_('administrator/index.php?option=com_kunena&view=usertypes') ?>" method="post" id="adminForm" name="adminForm">
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="type" value="list" />
			<input type="hidden" name="filter_order" value="<?php echo $this->escape ( $this->state->get('list.ordering') ) ?>" />
			<input type="hidden" name="filter_order_Dir" value="<?php echo $this->escape ($this->state->get('list.direction')) ?>" />
			<input type="hidden" name="boxchecked" value="0" />
			<?php echo JHtml::_( 'form.token' ); ?>

			<div id="filter-bar" class="btn-toolbar">
				<div class="filter-search btn-group pull-left">
					<label for="filter_search" class="element-invisible"><?php echo JText::_('COM_KUNENA_FIELD_LABEL_SEARCHIN') ?></label>
					<input type="text" name="filter_search" id="filter_search" class="filter" placeholder="<?php echo JText::_('COM_KUNENA_USERS_FIELD_INPUT_SEARCHUSERS'); ?>" value="<?php echo $this->filterSearch; ?>" title="<?php echo JText::_('COM_KUNENA_USERS_FIELD_INPUT_SEARCHUSERS'); ?>" />
				</div>
				<div class="btn-group pull-left">
					<button class="btn tip" type="submit" title="<?php echo JText::_('COM_KUNENA_SYS_BUTTON_FILTERSUBMIT'); ?>"><i class="icon-search"></i> <?php echo JText::_('COM_KUNENA_SYS_BUTTON_FILTERSUBMIT') ?></button>
					<button class="btn tip" type="button" title="<?php echo JText::_('COM_KUNENA_SYS_BUTTON_FILTERRESET'); ?>" onclick="jQuery('.filter').val('');jQuery('#adminForm').submit();"><i class="icon-remove"></i> <?php echo JText::_('COM_KUNENA_SYS_BUTTON_FILTERRESET'); ?></button>
				</div>
				<div class="btn-group pull-right hidden-phone">
					<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
					<?php echo KunenaLayout::factory('pagination/limitbox')->set('pagination', $this->pagination); ?>
				</div>
				<div class="btn-group pull-right hidden-phone">
					<label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC');?></label>
					<select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
						<option value=""><?php echo JText::_('JFIELD_ORDERING_DESC');?></option>
						<?php echo JHtml::_('select.options', $this->sortDirectionFields, 'value', 'text', $this->listDirection);?>
						</select>
				</div>
				<div class="btn-group pull-right">
					<label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY');?></label>
					<select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
						<option value=""><?php echo JText::_('JGLOBAL_SORT_BY');?></option>
						<?php echo JHtml::_('select.options', $this->sortFields, 'value', 'text', $this->listOrdering);?>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>

			<table class="table table-striped" id="userList">
				<thead>
					<tr>
						<th width="1%" class="nowrap center"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this)" /></th>
						<th><?php echo JHtml::_('grid.sort', 'COM_KUNENA_USERTYPES_NAME', 'username', $this->state->get('list.direction'), $this->state->get('list.ordering') ); ?></th>
						<th class="hidden-phone"><?php echo JHtml::_('grid.sort', 'COM_KUNENA_USERTYPES_LANGUAGE_STRING', 'email', $this->state->get('list.direction'), $this->state->get('list.ordering') ); ?></th>
            <th class="hidden-phone"><?php echo JHtml::_('grid.sort', 'COM_KUNENA_USERTYPES_JOOMLA_GROUP_ID', 'email', $this->state->get('list.direction'), $this->state->get('list.ordering') ); ?></th>
            <th class="hidden-phone"><?php echo JHtml::_('grid.sort', 'COM_KUNENA_USERTYPES_RANK_ASSOCIATED', 'email', $this->state->get('list.direction'), $this->state->get('list.ordering') ); ?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="8">
							<?php echo KunenaLayout::factory('pagination/footer')->set('pagination', $this->pagination); ?>
						</td>
					</tr>
				</tfoot>
				<tbody>
				<?php
				$i = 0;
				$img_no = '<i class="icon-cancel"></i>';
				$img_yes = '<i class="icon-checkmark"></i>';

				if ( !empty($this->usertypes)  ):
        foreach($this->usertypes as $type) :
				?>
					<tr>
            <td>
							<?php echo JHtml::_('grid.id', $i, $type->action['id']) ?>
						</td>
						<td>
							<?php echo $type->action['name'] ?>
						</td>
						<td>
							<?php echo JText::_($type->action['title']) ?>
						</td>
            <td>
							<?php if ( $type->action['joomla_group_id'] != 0 ) echo $type->action['joomla_group_id'];
              else echo JText::_('COM_KUNENA_USERTYPES_NONE_JOOMLA_GROUP_RELATED'); ?>
						</td>
            <td>
							<?php if ($type->action['rankid_associated']==0 ) echo JText::_('COM_KUNENA_USERTYPES_NONE_JOOMLA_RANK_RELATED');
              else echo $type->action['rankid_associated'] ?>
						</td>
					</tr>
				<?php $i++;
				endforeach;
				else : ?>
					<tr>
						<td colspan="10">
							<div class="well center filter-state">
								<span><?php echo JText::_('COM_KUNENA_FILTERACTIVE'); ?>
									<?php /*<a href="#" onclick="document.getElements('.filter').set('value', '');this.form.submit();return false;"><?php echo JText::_('COM_KUNENA_FIELD_LABEL_FILTERCLEAR'); ?></a> */?>
									<button class="btn" type="button"  onclick="document.getElements('.filter').set('value', '');this.form.submit();"><?php echo JText::_('COM_KUNENA_FIELD_LABEL_FILTERCLEAR'); ?></button>
								</span>
							</div>
						</td>
					</tr>
        <?php endif; ?>
				</tbody>
			</table>
		</form>
	</div>
	<div class="pull-right small">
		<?php echo KunenaVersion::getLongVersionHTML(); ?>
	</div>
</div>
