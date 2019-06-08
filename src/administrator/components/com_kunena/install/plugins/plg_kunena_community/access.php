<?php
/**
 * Kunena Plugin
 *
 * @package         Kunena.Plugins
 * @subpackage      Community
 *
 * @copyright       Copyright (C) 2008 - 2019 Kunena Team. All rights reserved.
 * @license         https://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link            https://www.kunena.org
 **/
defined('_JEXEC') or die();

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

/**
 * Class KunenaAccessCommunity
 * @since Kunena
 */
class KunenaAccessCommunity
{
	/**
	 * @var boolean
	 * @since Kunena
	 */
	protected $categories = false;

	/**
	 * @var boolean
	 * @since Kunena
	 */
	protected $groups = false;

	/**
	 * @var array
	 * @since Kunena
	 */
	protected $tree = array();

	/**
	 * @var null
	 * @since Kunena
	 */
	protected $params = null;

	/**
	 * KunenaAccessCommunity constructor.
	 *
	 * @param $params
	 *
	 * @since Kunena
	 */
	public function __construct($params)
	{
		$this->params = $params;
	}

	/**
	 * Get list of supported access types.
	 *
	 * List all access types you want to handle. All names must be less than 20 characters.
	 * Examples: joomla.level, mycomponent.groups, mycomponent.vipusers
	 *
	 * @return array    Supported access types.
	 * @since Kunena
	 */
	public function getAccessTypes()
	{
		static $accesstypes = array('jomsocial');

		return $accesstypes;
	}

	/**
	 * Get group name in selected access type.
	 *
	 * @param   string  $accesstype  Access type.
	 * @param   int     $id          Group id.
	 *
	 * @return boolean|void|string
	 * @since Kunena
	 * @throws Exception
	 */
	public function getGroupName($accesstype, $id = null)
	{
		if ($accesstype == 'jomsocial')
		{
			$this->loadGroups();

			if ($id !== null)
			{
				return isset($this->groups[$id]) ? $this->groups[$id]->name : '';
			}

			return $this->groups;
		}

		return;
	}

	/**
	 * @since Kunena
	 * @throws Exception
	 */
	protected function loadGroups()
	{
		if ($this->groups === false)
		{
			$db    = Factory::getDBO();
			$query = $db->getQuery(true);
			$query->select('id, CONCAT(\'c\', categoryid) AS parent_id, name')
				->update($db->quoteName('#__community_groups'))
				->order('categoryid, name');
			$db->setQuery($query);

			try
			{
				$this->groups = (array) $db->loadObjectList('id');
			}
			catch (RuntimeException $e)
			{
				KunenaError::displayDatabaseError($e);
			}

			if ($this->categories !== false)
			{
				$this->tree->add($this->groups);
			}
		}
	}

	/**
	 * Get HTML list of the available groups
	 *
	 * @param   string  $accesstype  Access type.
	 * @param   int     $category    Group id.
	 *
	 * @return array
	 * @since Kunena
	 * @throws Exception
	 */
	public function getAccessOptions($accesstype, $category)
	{
		$html = array();

		if (!$accesstype || $accesstype == 'jomsocial')
		{
			$this->loadCategories();
			$this->loadGroups();
			$options  = array();
			$selected = 'jomsocial' == $category->accesstype && isset($this->groups[$category->access]) ? $category->access : null;

			foreach ($this->tree as $item)
			{
				if (!$selected && is_numeric($item->id))
				{
					$selected = $item->id;
				}

				$options[] = HTMLHelper::_('select.option', $item->id, str_repeat('- ', $item->level) . $item->name, 'value', 'text', !is_numeric($item->id));
			}

			$html ['jomsocial']['access'] = array(
				'title' => Text::_('PLG_KUNENA_COMMUNITY_ACCESS_GROUP_TITLE'),
				'desc'  => Text::_('PLG_KUNENA_COMMUNITY_ACCESS_GROUP_DESC'),
				'input' => HTMLHelper::_('select.genericlist', $options, 'access-jomsocial', 'class="inputbox form-control" size="10"', 'value', 'text', $selected),
			);
		}

		return $html;
	}

	/**
	 * @since Kunena
	 * @throws Exception
	 */
	protected function loadCategories()
	{
		if ($this->categories === false)
		{
			$db    = Factory::getDBO();
			$query = $db->getQuery(true);
			$query->select('SELECT CONCAT(\'c\', id) AS id, CONCAT(\'c\', parent) AS parent_id, name')
				->update($db->quoteName('#__community_groups_category'))
				->order('parent, name');
			$db->setQuery($query);

			try
			{
				$this->categories = (array) $db->loadObjectList('id');
			}
			catch (RuntimeException $e)
			{
				KunenaError::displayDatabaseError($e);
			}

			$this->tree = new KunenaTree($this->categories);

			if ($this->groups !== false)
			{
				$this->tree->add($this->groups);
			}
		}
	}

	/**
	 * Load moderators and administrators for listed categories.
	 *
	 * This function is used to add category administrators and moderators to listed categories. In addition
	 * integration can also add global administrators (catid=0).
	 *
	 * Results may be cached.
	 *
	 * @param   array  $categories  List of categories, null = all.
	 *
	 * @return array(array => u, 'category_id'=>c, 'role'=>r))
	 * @since Kunena
	 * @throws Exception
	 */
	public function loadCategoryRoles(array $categories = null)
	{
		$db    = Factory::getDBO();
		$query = $db->getQuery(true);
		$query->select('g.memberid AS user_id, c.id AS category_id, ' . KunenaForum::ADMINISTRATOR . ' AS role')
			->from($db->quoteName('#__kunena_categories', 'c'))
			->innerJoin($db->quoteName('#__community_groups_members', 'g') . ' ON c.accesstype=\'jomsocial\' AND c.access = g.groupid')
			->where('c.published = 1')
			->andWhere('g.approved = 1')
			->andWhere('g.permissions = ' . $db->quote(COMMUNITY_GROUP_ADMIN));
		$db->setQuery($query);

		try
		{
			$list = (array) $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			KunenaError::displayDatabaseError($e);
		}

		return $list;
	}

	/**
	 * Authorise list of categories.
	 *
	 * Function accepts array of id indexed KunenaForumCategory objects and removes unauthorised
	 * categories from the list.
	 *
	 * Results for the current user are saved into session.
	 *
	 * @param   int    $userid      User who needs the authorisation (null=current user, 0=visitor).
	 * @param   array  $categories  List of categories in access type.
	 *
	 * @return array, where category ids are in the keys.
	 * @since Kunena
	 * @throws Exception
	 */
	public function authoriseCategories($userid, array &$categories)
	{
		$allowed = array();

		if (KunenaFactory::getUser($userid)->exists())
		{
			$db    = Factory::getDBO();
			$query = $db->getQuery(true);
			$query->select('c.id')
				->from($db->quoteName('#__kunena_categories', 'c'))
				->innerJoin($db->quoteName('#__community_groups_members', 'g') . ' ON c.accesstype = \'jomsocial\' AND c.access = g.groupid')
				->where('c.published = 1')
				->andWhere('g.approved = 1')
				->andWhere('g.memberid = ' . $db->quote((int) $userid));
			$db->setQuery($query);

			try
			{
				$list = (array) $db->loadColumn();
			}
			catch (RuntimeException $e)
			{
				KunenaError::displayDatabaseError($e);
			}

			foreach ($list as $catid)
			{
				$allowed [$catid] = $catid;
			}
		}

		return $allowed;
	}

	/**
	 * Authorise list of userids to topic or category.
	 *
	 * @param   mixed  $topic    Category or topic.
	 * @param   array  $userids  list(allow, deny).
	 *
	 * @return array
	 * @since Kunena
	 * @throws Exception
	 */
	public function authoriseUsers(KunenaDatabaseObject $topic, array &$userids)
	{
		if (empty($userids))
		{
			return array(array(), array());
		}

		$category = $topic->getCategory();
		$userlist = implode(',', $userids);

		$db    = Factory::getDBO();
		$query = $db->getQuery(true);
		$query->select('c.id')
			->from($db->quoteName('#__kunena_categories', 'c'))
			->innerJoin($db->quoteName('#__community_groups_members', 'g') . ' ON c.accesstype = \'jomsocial\' AND c.access = g.groupid')
			->where('c.id = ' . $db->quote((int) $category->id))
			->andWhere(' g.approved = 1')
			->andWhere('g.memberid IN (' . $userlist . ')');
		$db->setQuery($query);

		try
		{
			$allow = (array) $db->loadColumn();
			$deny  = array();
		}
		catch (RuntimeException $e)
		{
			KunenaError::displayDatabaseError($e);
		}

		return array($allow, $deny);
	}
}
