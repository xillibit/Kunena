<?php
/**
 * Kunena Component
 *
 * @package         Kunena.Administrator
 * @subpackage      Models
 *
 * @copyright       Copyright (C) 2008 - 2020 Kunena Team. All rights reserved.
 * @license         https://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link            https://www.kunena.org
 **/

namespace Kunena\Forum\Administrator\Model;

defined('_JEXEC') or die();

use Exception;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Pagination\Pagination;
use Joomla\CMS\Table\Table;
use Joomla\Registry\Registry;
use Kunena\Forum\Libraries\Access\Access;
use Kunena\Forum\Libraries\Factory\KunenaFactory;
use Kunena\Forum\Libraries\Forum\Category\Category;
use Kunena\Forum\Libraries\Forum\Category\CategoryHelper;
use Kunena\Forum\Libraries\Model\Model;
use Kunena\Forum\Libraries\Template\Template;
use RuntimeException;

/**
 * Categories Model for Kunena
 *
 * @since 2.0
 */
class CategoriesModel extends Model
{
	/**
	 * @var     string
	 * @since   Kunena 6.0
	 */
	public $context;

	/**
	 * @var     Category[]
	 * @since   Kunena 6.0
	 */
	protected $_admincategories = false;

	/**
	 * @var     Category
	 * @since   Kunena 6.0
	 */
	protected $_admincategory = false;

	/**
	 * @return  Pagination
	 * @since   Kunena 6.0
	 */
	public function getAdminNavigation()
	{
		$navigation = new Pagination($this->getState('list.total'), $this->getState('list.start'), $this->getState('list.limit'));

		return $navigation;
	}

	/**
	 * @return  array|boolean
	 *
	 * @since   Kunena 6.0
	 *
	 * @throws  Exception
	 */
	public function getAdminOptions()
	{
		$category = $this->getAdminCategory();

		if (!$category)
		{
			return false;
		}

		$category->params = new Registry($category->params);

		$catList    = [];
		$catList [] = HTMLHelper::_('select.option', 0, Text::_('COM_KUNENA_TOPLEVEL'));

		// Make a standard yes/no list
		$published    = [];
		$published [] = HTMLHelper::_('select.option', 1, Text::_('COM_KUNENA_PUBLISHED'));
		$published [] = HTMLHelper::_('select.option', 0, Text::_('COM_KUNENA_UNPUBLISHED'));

		// Make a standard yes/no list
		$yesno    = [];
		$yesno [] = HTMLHelper::_('select.option', 0, Text::_('COM_KUNENA_NO'));
		$yesno [] = HTMLHelper::_('select.option', 1, Text::_('COM_KUNENA_YES'));

		// Anonymous posts default
		$post_anonymous    = [];
		$post_anonymous [] = HTMLHelper::_('select.option', '0', Text::_('COM_KUNENA_CATEGORY_ANONYMOUS_X_REG'));
		$post_anonymous [] = HTMLHelper::_('select.option', '1', Text::_('COM_KUNENA_CATEGORY_ANONYMOUS_X_ANO'));

		$cat_params                = [];
		$cat_params['ordering']    = 'ordering';
		$cat_params['toplevel']    = Text::_('COM_KUNENA_TOPLEVEL');
		$cat_params['sections']    = 1;
		$cat_params['unpublished'] = 1;
		$cat_params['catid']       = $category->id;
		$cat_params['action']      = 'admin';

		$channels_params           = [];
		$channels_params['catid']  = $category->id;
		$channels_params['action'] = 'admin';
		$channels_options          = [];
		$channels_options []       = HTMLHelper::_('select.option', 'THIS', Text::_('COM_KUNENA_CATEGORY_CHANNELS_OPTION_THIS'));
		$channels_options []       = HTMLHelper::_('select.option', 'CHILDREN', Text::_('COM_KUNENA_CATEGORY_CHANNELS_OPTION_CHILDREN'));

		if (empty($category->channels))
		{
			$category->channels = 'THIS';
		}

		$topic_ordering_options   = [];
		$topic_ordering_options[] = HTMLHelper::_('select.option', 'lastpost', Text::_('COM_KUNENA_CATEGORY_TOPIC_ORDERING_OPTION_LASTPOST'));
		$topic_ordering_options[] = HTMLHelper::_('select.option', 'creation', Text::_('COM_KUNENA_CATEGORY_TOPIC_ORDERING_OPTION_CREATION'));
		$topic_ordering_options[] = HTMLHelper::_('select.option', 'alpha', Text::_('COM_KUNENA_CATEGORY_TOPIC_ORDERING_OPTION_ALPHA'));
		$topic_ordering_options[] = HTMLHelper::_('select.option', 'views', Text::_('COM_KUNENA_CATEGORY_TOPIC_ORDERING_OPTION_VIEWS'));
		$topic_ordering_options[] = HTMLHelper::_('select.option', 'posts', Text::_('COM_KUNENA_CATEGORY_TOPIC_ORDERING_OPTION_POSTS'));

		$aliases = array_keys($category->getAliases());

		$lists                     = [];
		$lists ['accesstypes']     = Access::getInstance()->getAccessTypesList($category);
		$lists ['accesslists']     = Access::getInstance()->getAccessOptions($category);
		$lists ['categories']      = HTMLHelper::_('select.genericlist', $cat_params, 'parent_id', 'class="inputbox form-control"', 'value', 'text', $category->parent_id);
		$lists ['channels']        = HTMLHelper::_('select.genericlist', $channels_options, 'channels', 'class="inputbox form-control" multiple="multiple"', 'value', 'text', explode(',', $category->channels));
		$lists ['aliases']         = $aliases ? HTMLHelper::_('kunenaforum.checklist', 'aliases', $aliases, true, 'category_aliases') : null;
		$lists ['published']       = HTMLHelper::_('select.genericlist', $published, 'published', 'class="inputbox form-control"', 'value', 'text', $category->published);
		$lists ['forumLocked']     = HTMLHelper::_('select.genericlist', $yesno, 'locked', 'class="inputbox form-control" size="1"', 'value', 'text', $category->locked);
		$lists ['forumReview']     = HTMLHelper::_('select.genericlist', $yesno, 'review', 'class="inputbox form-control" size="1"', 'value', 'text', $category->review);
		$lists ['allow_polls']     = HTMLHelper::_('select.genericlist', $yesno, 'allow_polls', 'class="inputbox form-control" size="1"', 'value', 'text', $category->allow_polls);
		$lists ['allow_anonymous'] = HTMLHelper::_('select.genericlist', $yesno, 'allow_anonymous', 'class="inputbox form-control" size="1"', 'value', 'text', $category->allow_anonymous);
		$lists ['post_anonymous']  = HTMLHelper::_('select.genericlist', $post_anonymous, 'post_anonymous', 'class="inputbox form-control" size="1"', 'value', 'text', $category->post_anonymous);
		$lists ['topic_ordering']  = HTMLHelper::_('select.genericlist', $topic_ordering_options, 'topic_ordering', 'class="inputbox form-control" size="1"', 'value', 'text', $category->topic_ordering);
		$lists ['allow_ratings']   = HTMLHelper::_('select.genericlist', $yesno, 'allow_ratings', 'class="inputbox form-control" size="1"', 'value', 'text', $category->allow_ratings);

		$options                 = [];
		$options[0]              = HTMLHelper::_('select.option', '0', Text::_('COM_KUNENA_A_CATEGORY_CFG_OPTION_NEVER'));
		$options[1]              = HTMLHelper::_('select.option', '1', Text::_('COM_KUNENA_A_CATEGORY_CFG_OPTION_SECTION'));
		$options[2]              = HTMLHelper::_('select.option', '2', Text::_('COM_KUNENA_A_CATEGORY_CFG_OPTION_CATEGORY'));
		$options[3]              = HTMLHelper::_('select.option', '3', Text::_('COM_KUNENA_A_CATEGORY_CFG_OPTION_SUBCATEGORY'));
		$lists['display_parent'] = HTMLHelper::_('select.genericlist', $options, 'params[display][index][parent]', 'class="inputbox form-control" size="1"', 'value', 'text', $category->params->get('display.index.parent', '3'));

		unset($options[1]);

		$lists['display_children'] = HTMLHelper::_('select.genericlist', $options, 'params[display][index][children]', 'class="inputbox form-control" size="1"', 'value', 'text', $category->params->get('display.index.children', '3'));

		$topicicons     = [];
		$topiciconslist = Folder::folders(JPATH_ROOT . '/media/kunena/topic_icons');

		foreach ($topiciconslist as $icon)
		{
			$topicicons[] = HTMLHelper::_('select.option', $icon, $icon);
		}

		if (empty($category->iconset))
		{
			$value = Template::getInstance()->params->get('DefaultIconset');
		}
		else
		{
			$value = $category->iconset;
		}

		$lists ['category_iconset'] = HTMLHelper::_('select.genericlist', $topicicons, 'iconset', 'class="inputbox form-control" size="1"', 'value', 'text', $value);

		return $lists;
	}

	/**
	 * @return  boolean|Category|void
	 *
	 * @since   Kunena 6.0
	 *
	 * @throws  Exception
	 */
	public function getAdminCategory()
	{
		$category = CategoryHelper::get($this->getState('item.id'));

		if (!$this->me->isAdmin($category))
		{
			return false;
		}

		if ($this->_admincategory === false)
		{
			if ($category->exists())
			{
				if (!$category->isCheckedOut($this->me->userid))
				{
					$category->checkout($this->me->userid);
				}
			}
			else
			{
				// New category is by default child of the first section -- this will help new users to do it right
				$db = Factory::getDBO();

				$query = $db->getQuery(true)
					->select('a.id, a.name')
					->from("{$db->quoteName('#__kunena_categories')} AS a")
					->where("parent_id={$db->quote('0')}")
					->where("id!={$db->quote($category->id)}")
					->order('ordering');

				$db->setQuery($query);

				try
				{
					$sections = $db->loadObjectList();
				}
				catch (RuntimeException $e)
				{
					Factory::getApplication()->enqueueMessage($e->getMessage());

					return;
				}

				$category->parent_id     = $this->getState('item.parent_id');
				$category->published     = 0;
				$category->ordering      = 9999;
				$category->pub_recurse   = 1;
				$category->admin_recurse = 1;
				$category->accesstype    = 'joomla.level';
				$category->access        = 1;
				$category->pub_access    = 1;
				$category->admin_access  = 8;
			}

			$this->_admincategory = $category;
		}

		return $this->_admincategory;
	}

	/**
	 * @return  array|boolean
	 *
	 * @since   Kunena 6.0
	 *
	 * @throws  Exception
	 */
	public function getAdminModerators()
	{
		$category = $this->getAdminCategory();

		if (!$category)
		{
			return false;
		}

		$moderators = $category->getModerators(false);

		return $moderators;
	}

	/**
	 * @param   null  $pks    pks
	 * @param   null  $order  order
	 *
	 * @return  boolean
	 *
	 * @since   Kunena 6.0
	 *
	 * @throws  Exception
	 */
	public function saveorder($pks = null, $order = null)
	{
		$table      = Table::getInstance('KunenaCategories', 'Table');
		$conditions = [];

		if (empty($pks))
		{
			return false;
		}

		// Update ordering values
		foreach ($pks as $i => $pk)
		{
			$table->load((int) $pk);

			if ($table->ordering != $order[$i])
			{
				$table->ordering = $order[$i];

				if (!$table->store())
				{
					Factory::getApplication()->enqueueMessage($table->getError());

					return false;
				}

				// Remember to reorder within position and client_id
				$condition = $this->getReorderConditions($table);
				$found     = false;

				foreach ($conditions as $cond)
				{
					if ($cond[1] == $condition)
					{
						$found = true;
						break;
					}
				}

				if (!$found)
				{
					$key          = $table->getKeyName();
					$conditions[] = [$table->$key, $condition];
				}
			}
		}

		// Execute reorder for each category.
		foreach ($conditions as $cond)
		{
			$table->load($cond[0]);
			$table->reorder($cond[1]);
		}

		// Clear the component's cache
		$this->cleanCache();

		return true;
	}

	/**
	 * @param   array  $table  table
	 *
	 * @return  array
	 *
	 * @since   Kunena 6.0
	 */
	protected function getReorderConditions($table)
	{
		$condition   = [];
		$condition[] = 'parent_id = ' . (int) $table->parent_id;

		return $condition;
	}

	/**
	 * Get list of categories to be displayed in drop-down select in batch
	 *
	 * @return  array
	 *
	 * @since   Kunena 5.1
	 *
	 * @throws  Exception
	 * @throws  null
	 */
	public function getBatchCategories()
	{
		$categories         = $this->getAdminCategories();
		$batch_categories   = [];
		$batch_categories[] = HTMLHelper::_('select.option', 'select', Text::_('JSELECT'));

		foreach ($categories as $category)
		{
			$batch_categories [] = HTMLHelper::_('select.option', $category->id, str_repeat('...', count($category->indent) - 1) . ' ' . $category->name);
		}

		$list = HTMLHelper::_('select.genericlist', $batch_categories, 'batch_catid_target', 'class="inputbox form-control" size="1"', 'value', 'text', 'select');

		return $list;
	}

	/**
	 * @return  array|Category[]
	 *
	 * @since   Kunena 6.0
	 *
	 * @throws  null
	 * @throws  Exception
	 */
	public function getAdminCategories()
	{
		if ($this->_admincategories === false)
		{
			$params = [
				'ordering'           => $this->getState('list.ordering'),
				'direction'          => $this->getState('list.direction') == 'asc' ? 1 : -1,
				'search'             => $this->getState('filter.search'),
				'unpublished'        => 1,
				'published'          => $this->getState('filter.published'),
				'filter_title'       => $this->getState('filter.title'),
				'filter_type'        => $this->getState('filter.type'),
				'filter_access'      => $this->getState('filter.access'),
				'filter_locked'      => $this->getState('filter.locked'),
				'filter_allow_polls' => $this->getState('filter.allow_polls'),
				'filter_review'      => $this->getState('filter.review'),
				'filter_anonymous'   => $this->getState('filter.anonymous'),
				'action'             => 'none'
			];

			$catid      = $this->getState('item.id', 0);
			$categories = [];
			$orphans    = [];

			if ($catid)
			{
				$categories   = CategoryHelper::getParents($catid, $this->getState('filter.levels') - 1, ['unpublished' => 1, 'action' => 'none']);
				$categories[] = CategoryHelper::get($catid);
			}
			else
			{
				$orphans = CategoryHelper::getOrphaned($this->getState('filter.levels') - 1, $params);
			}

			$categories = array_merge($categories, CategoryHelper::getChildren($catid, $this->getState('filter.levels') - 1, $params));
			$categories = array_merge($orphans, $categories);

			$categories = CategoryHelper::getIndentation($categories);
			$this->setState('list.total', count($categories));

			if ($this->getState('list.limit'))
			{
				$this->_admincategories = array_slice($categories, $this->getState('list.start'), $this->getState('list.limit'));
			}
			else
			{
				$this->_admincategories = $categories;
			}

			$admin = 0;
			$acl   = Access::getInstance();

			foreach ($this->_admincategories as $category)
			{
				// TODO: Following is needed for J!2.5 only:
				$parent   = $category->getParent();
				$siblings = array_keys(CategoryHelper::getCategoryTree($category->parent_id));

				if ($parent)
				{
					$category->up      = $this->me->isAdmin($parent) && reset($siblings) != $category->id;
					$category->down    = $this->me->isAdmin($parent) && end($siblings) != $category->id;
					$category->reorder = $this->me->isAdmin($parent);
				}
				else
				{
					$category->up      = $this->me->isAdmin($category) && reset($siblings) != $category->id;
					$category->down    = $this->me->isAdmin($category) && end($siblings) != $category->id;
					$category->reorder = $this->me->isAdmin($category);
				}

				// Get ACL groups for the category.
				$access               = $acl->getCategoryAccess($category);
				$category->accessname = [];

				foreach ($access as $item)
				{
					if (!empty($item['admin.link']))
					{
						$category->accessname[] = '<a href="' . htmlentities($item['admin.link'], ENT_COMPAT, 'utf-8') . '">' . htmlentities($item['title'], ENT_COMPAT, 'utf-8') . '</a>';
					}
					else
					{
						$category->accessname[] = htmlentities($item['title'], ENT_COMPAT, 'utf-8');
					}
				}

				$category->accessname = implode(' / ', $category->accessname);

				// Checkout?
				if ($this->me->isAdmin($category) && $category->isCheckedOut(0))
				{
					$category->editor = KunenaFactory::getUser($category->checked_out)->getName();
				}
				else
				{
					$category->checked_out = 0;
					$category->editor      = '';
				}

				$admin += $this->me->isAdmin($category);
			}

			$this->setState('list.count.admin', $admin);
		}

		if (!empty($orphans))
		{
			$this->app->enqueueMessage(Text::_('COM_KUNENA_CATEGORY_ORPHAN_DESC'), 'notice');
		}

		return $this->_admincategories;
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * @param   string  $ordering   ordering
	 * @param   string  $direction  direction
	 *
	 * @return  void
	 *
	 * @since   Kunena 6.0
	 */
	protected function populateState($ordering = 'a.lft', $direction = 'asc')
	{
		// Load the parameters.
		$params = ComponentHelper::getParams('com_kunena');
		$this->setState('params', $params);

		// List state information.
		parent::populateState($ordering, $direction);
	}
}
