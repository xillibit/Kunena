<?php
/**
 * Kunena Component
 *
 * @package       Kunena.Administrator
 * @subpackage    Views
 *
 * @copyright     Copyright (C) 2008 - 2020 Kunena Team. All rights reserved.
 * @license       https://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link          https://www.kunena.org
 **/

namespace Kunena\Forum\Administrator\View\Statistics;

defined('_JEXEC') or die();

use Exception;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use ReflectionClass;
use ReflectionException;
use function defined;

/**
 * Statistics view for Kunena backend
 *
 * @since   Kunena 6.0
 */
class HtmlView extends BaseHtmlView
{
	private $group;
	/**
	 * @var mixed
	 * @since version
	 */
	private $listDirection;
	/**
	 * @var mixed
	 * @since version
	 */
	private $listOrdering;
	/**
	 * @var mixed
	 * @since version
	 */
	private $filterUsertypes;
	/**
	 * @var mixed
	 * @since version
	 */
	private $filterActive;
	/**
	 * @var mixed
	 * @since version
	 */
	private $filterOperation;
	/**
	 * @var mixed
	 * @since version
	 */
	private $filterTimeStop;
	/**
	 * @var mixed
	 * @since version
	 */
	private $filterTimeStart;
	/**
	 * @var mixed
	 * @since version
	 */
	private $filterIp;
	/**
	 * @var mixed
	 * @since version
	 */
	private $filterTargetUser;
	/**
	 * @var mixed
	 * @since version
	 */
	private $filterTopic;
	/**
	 * @var mixed
	 * @since version
	 */
	private $filterCategory;
	/**
	 * @var mixed
	 * @since version
	 */
	private $filterUser;
	/**
	 * @var mixed
	 * @since version
	 */
	private $filterType;
	/**
	 * @var mixed
	 * @since version
	 */
	private $filterSearch;
	/**
	 * @var array
	 * @since version
	 */
	private $filterOperationFields;
	/**
	 * @var array
	 * @since version
	 */
	private $filterTypeFields;
	/**
	 * @var array
	 * @since version
	 */
	private $sortDirectionFields;
	/**
	 * @var array
	 * @since version
	 */
	private $sortFields;
	/**
	 * @var array
	 * @since version
	 */
	private $filterUserFields;
	/**
	 * @var mixed
	 * @since version
	 */
	private $pagination;
	/**
	 * @var mixed
	 * @since version
	 */
	private $items;
	private $group;
	/**
	 * @var mixed
	 * @since version
	 */
	private $state;

	/**
	 * @param   null  $tpl  tpl
	 *
	 * @return  void
	 *
	 * @since   Kunena 6.0
	 *
	 * @throws Exception
	 */
	public function display($tpl = null)
	{
		$this->state      = $this->get('state');
		$this->group      = $this->state->get('group');
		$this->items      = $this->get('items');
		$this->pagination = $this->get('Pagination');

		$this->filterUserFields    = $this->getFilterUserFields();
		$this->sortFields          = $this->getSortFields();
		$this->sortDirectionFields = $this->getSortDirectionFields();

		$this->filterTypeFields      = $this->getFilterTypeFields();
		$this->filterOperationFields = $this->getFilterOperationFields();

		$this->filterSearch     = $this->escape($this->state->get('filter.search'));
		$this->filterType       = $this->escape($this->state->get('filter.type'));
		$this->filterUser       = $this->escape($this->state->get('filter.user'));
		$this->filterCategory   = $this->escape($this->state->get('filter.category'));
		$this->filterTopic      = $this->escape($this->state->get('filter.topic'));
		$this->filterTargetUser = $this->escape($this->state->get('filter.target_user'));
		$this->filterIp         = $this->escape($this->state->get('filter.ip'));
		$this->filterTimeStart  = $this->escape($this->state->get('filter.time_start'));
		$this->filterTimeStop   = $this->escape($this->state->get('filter.time_stop'));
		$this->filterOperation  = $this->escape($this->state->get('filter.operation'));
		$this->filterActive     = $this->escape($this->state->get('filter.active'));

		$this->filterUsertypes = $this->escape($this->state->get('filter.usertypes'));
		$this->listOrdering    = $this->escape($this->state->get('list.ordering'));
		$this->listDirection   = $this->escape($this->state->get('list.direction'));

		$document = Factory::getApplication()->getDocument();
		$document->setTitle(Text::_('Forum Logs'));

		$this->addToolbar();

		return parent::display($tpl);
	}

	/**
	 * @return  array
	 *
	 * @since   Kunena 6.0
	 */
	protected function getFilterUserFields(): array
	{
		$filterFields   = [];
		$filterFields[] = HTMLHelper::_('select.option', 0, 'Guests');
		$filterFields[] = HTMLHelper::_('select.option', 1, 'Registered users');
		$filterFields[] = HTMLHelper::_('select.option', 2, 'Regular members');
		$filterFields[] = HTMLHelper::_('select.option', 3, 'Moderators');
		$filterFields[] = HTMLHelper::_('select.option', 4, 'Administrators');
		$filterFields[] = HTMLHelper::_('select.option', 5, 'Mods and Admins');

		return $filterFields;
	}

	/**
	 * @return  array
	 *
	 * @since   Kunena
	 */
	protected function getSortFields(): array
	{
		$sortFields = [];

		// TODO: translate
		$sortFields[] = HTMLHelper::_('select.option', 'id', $this->group ? 'Count' : 'Id');
		$sortFields[] = HTMLHelper::_('select.option', 'type', 'Type (by id)');
		$sortFields[] = HTMLHelper::_('select.option', 'user', 'User (by id)');
		$sortFields[] = HTMLHelper::_('select.option', 'category', 'Category (by id)');
		$sortFields[] = HTMLHelper::_('select.option', 'topic', 'Topic (by id)');
		$sortFields[] = HTMLHelper::_('select.option', 'target_user', 'Target User (by id)');
		$sortFields[] = HTMLHelper::_('select.option', 'time', 'Time (by id)');

		return $sortFields;
	}

	/**
	 * @return  array
	 *
	 * @since   Kunena
	 */
	protected function getSortDirectionFields(): array
	{
		$sortDirection   = [];
		$sortDirection[] = HTMLHelper::_('select.option', 'asc', Text::_('COM_KUNENA_FIELD_LABEL_ASCENDING'));
		$sortDirection[] = HTMLHelper::_('select.option', 'desc', Text::_('COM_KUNENA_FIELD_LABEL_DESCENDING'));

		return $sortDirection;
	}

	/**
	 * @return  array
	 *
	 * @since   Kunena 6.0
	 */
	protected function getFilterTypeFields(): array
	{
		$filterFields   = [];
		$filterFields[] = HTMLHelper::_('select.option', 1, 'MOD');
		$filterFields[] = HTMLHelper::_('select.option', 2, 'ACT');
		$filterFields[] = HTMLHelper::_('select.option', 3, 'ERR');
		$filterFields[] = HTMLHelper::_('select.option', 4, 'REP');

		return $filterFields;
	}

	/**
	 * @return  array
	 *
	 * @since   Kunena 6.0
	 *
	 */
	protected function getFilterOperationFields(): array
	{
		$filterFields = [];

		$reflection = new ReflectionClass('Kunena\Forum\Libraries\Log\KunenaLog');
		$constants  = $reflection->getConstants();
		ksort($constants);

		foreach ($constants as $key => $value)
		{
			if (strpos($key, 'LOG_') === 0)
			{
				$filterFields[] = HTMLHelper::_('select.option', $key, Text::_("COM_KUNENA_{$value}"));
			}
		}

		return $filterFields;
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   Kunena 6.0
	 */
	protected function addToolbar(): void
	{
		// Set the titlebar text
		ToolbarHelper::title(Text::_('COM_KUNENA') . ': ' . Text::_('COM_KUNENA_MENU_STATISTICS'), 'chart');
	}

	/**
	 * @param   integer  $id  id
	 *
	 * @return  mixed|string
	 *
	 * @since   Kunena 6.0
	 */
	public function getType(int $id): string
	{
		static $types = [1 => 'MOD', 2 => 'ACT', 3 => 'ERR', 4 => 'REP'];

		return isset($types[$id]) ? $types[$id] : '???';
	}

	/**
	 * @param   string  $name  name
	 *
	 * @return  string
	 *
	 * @since   Kunena 6.0
	 */
	public function getGroupCheckbox(string $name): string
	{
		$checked = isset($this->group[$name]) ? ' checked="checked"' : '';

		return '<input type="checkbox" name="group_' . $name . '" value="1" title="Group By" ' . $checked . ' class="filter form-control" />';
	}
}
