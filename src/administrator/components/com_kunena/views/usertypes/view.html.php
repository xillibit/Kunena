<?php
/**
 * Kunena Component
 *
 * @package     Kunena.Administrator
 * @subpackage  Views
 *
 * @copyright   (C) 2008 - 2016 Kunena Team. All rights reserved.
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link        http://www.kunena.org
 **/
defined( '_JEXEC' ) or die();

/**
 * Usertypes view for Kunena backend
 *
 * @since  K5.0
 */
class KunenaAdminViewUsertypes extends KunenaView
{
	function displayEdit() {
		$this->types_object = JFactory::getApplication()->getUserState ('kunena.usertypes.object');
		$this->rankspath = $this->get('Rankspaths');

		$this->setToolBarEdit();
		$this->display();
	}

 protected function setToolBarEdit() {
		// Set the titlebar text
		JToolBarHelper::title ( JText::_('COM_KUNENA'), 'users' );
		JToolbarHelper::spacer();
		JToolBarHelper::apply('apply');
		JToolBarHelper::save('save');
		JToolBarHelper::save2new('save2new');


		JToolBarHelper::cancel();
		JToolbarHelper::spacer();
	}

  public function displayDefault() {
		$this->setToolbar();

    $this->pagination = $this->get('Pagination');

    $this->usertypes = $this->get('ItemsFromXML');

    $this->sortFields = $this->getSortFields();
    $this->sortDirectionFields = $this->getSortDirectionFields();

    $this->filterSearch = $this->escape($this->state->get('filter.search'));
    $this->filterUsername	= $this->escape($this->state->get('filter.username'));
    $this->filterEmail = $this->escape($this->state->get('filter.email'));
    $this->filterSignature = $this->escape($this->state->get('filter.signature'));
    $this->filterBlock = $this->escape($this->state->get('filter.block'));
    $this->filterBanned = $this->escape($this->state->get('filter.banned'));
    $this->filterModerator = $this->escape($this->state->get('filter.moderator'));
    $this->filterActive = $this->escape($this->state->get('filter.active'));
    $this->listOrdering = $this->escape($this->state->get('list.ordering'));
    $this->listDirection = $this->escape($this->state->get('list.direction'));

		$this->display();
	}

	protected function setToolbar() {
		// Get the toolbar object instance
		$bar = JToolBar::getInstance('toolbar');
		// Set the titlebar text
		JToolBarHelper::title ( JText::_('COM_KUNENA').': '.JText::_('COM_KUNENA_USERTYPES_MANAGER'), 'users' );
		JToolBarHelper::addNew ('add', 'COM_KUNENA_NEW_USERTYPE');
    JToolBarHelper::spacer();
		JToolBarHelper::editList();
    JToolBarHelper::divider();
		JToolBarHelper::deleteList();

	}

	/**
	 * Returns an array of locked filter options.
	 *
	 * @return	string	The HTML code for the select tag
	 */
	public function signatureOptions() {
		// Build the active state filter options.
		$options	= array();
		$options[]	= JHtml::_('select.option', '1', JText::_('COM_KUNENA_FIELD_LABEL_YES'));
		$options[]	= JHtml::_('select.option', '0', JText::_('COM_KUNENA_FIELD_LABEL_NO'));

		return $options;
	}

	/**
	 * Returns an array of standard published state filter options.
	 *
	 * @return	string	The HTML code for the select tag
	 */
	public function blockOptions() {
		// Build the active state filter options.
		$options	= array();
		$options[]	= JHtml::_('select.option', '0', JText::_('COM_KUNENA_FIELD_LABEL_ON'));
		$options[]	= JHtml::_('select.option', '1', JText::_('COM_KUNENA_FIELD_LABEL_OFF'));

		return $options;
	}

	/**
	 * Returns an array of type filter options.
	 *
	 * @return	string	The HTML code for the select tag
	 */
	public function bannedOptions() {
		// Build the active state filter options.
		$options	= array();
		$options[]	= JHtml::_('select.option', '1', JText::_('COM_KUNENA_FIELD_LABEL_ON'));
		$options[]	= JHtml::_('select.option', '0', JText::_('COM_KUNENA_FIELD_LABEL_OFF'));

		return $options;
	}

	/**
	 * Returns an array of standard published state filter options.
	 *
	 * @return	string	The HTML code for the select tag
	 */
	public function moderatorOptions() {
		// Build the active state filter options.
		$options	= array();
		$options[]	= JHtml::_('select.option', '1', JText::_('COM_KUNENA_FIELD_LABEL_YES'));
		$options[]	= JHtml::_('select.option', '0', JText::_('COM_KUNENA_FIELD_LABEL_NO'));

		return $options;
	}

	protected function getSortFields() {
		$sortFields = array();
		$sortFields[] = JHtml::_('select.option', 'username', JText::_('COM_KUNENA_USRL_USERNAME'));
		//$sortFields[] = JHtml::_('select.option', 'name', JText::_('COM_KUNENA_USRL_REALNAME'));
		$sortFields[] = JHtml::_('select.option', 'email', JText::_('COM_KUNENA_USRL_EMAIL'));
		$sortFields[] = JHtml::_('select.option', 'signature', JText::_('COM_KUNENA_GEN_SIGNATURE'));
		$sortFields[] = JHtml::_('select.option', 'enabled', JText::_('COM_KUNENA_USRL_ENABLED'));
		$sortFields[] = JHtml::_('select.option', 'banned', JText::_('COM_KUNENA_USRL_BANNED'));
		$sortFields[] = JHtml::_('select.option', 'moderator', JText::_('COM_KUNENA_VIEW_MODERATOR'));
		$sortFields[] = JHtml::_('select.option', 'id', JText::_('JGRID_HEADING_ID'));

		return $sortFields;
	}

    protected function getSortDirectionFields() {
        $sortDirection = array();
//		$sortDirection[] = JHtml::_('select.option', 'asc', JText::_('JGLOBAL_ORDER_ASCENDING'));
//		$sortDirection[] = JHtml::_('select.option', 'desc', JText::_('JGLOBAL_ORDER_DESCENDING'));
        // TODO: remove it when J2.5 support is dropped
        $sortDirection[] = JHtml::_('select.option', 'asc', JText::_('COM_KUNENA_FIELD_LABEL_ASCENDING'));
        $sortDirection[] = JHtml::_('select.option', 'desc', JText::_('COM_KUNENA_FIELD_LABEL_DESCENDING'));

        return $sortDirection;
    }
}
