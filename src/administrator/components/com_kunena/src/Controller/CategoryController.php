<?php
/**
 * Kunena Component
 *
 * @package         Kunena.Administrator
 * @subpackage      Controllers
 *
 * @copyright       Copyright (C) 2008 - 2020 Kunena Team. All rights reserved.
 * @license         https://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link            https://www.kunena.org
 **/

namespace Kunena\Forum\Administrator\Controller;

defined('_JEXEC') or die();

use Exception;
use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Input\Input;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Session\Session;
use Joomla\String\StringHelper;
use Kunena\Forum\Libraries\Factory\KunenaFactory;
use Kunena\Forum\Libraries\Forum\Category\Category;
use Kunena\Forum\Libraries\Forum\Category\CategoryHelper;
use Kunena\Forum\Libraries\Route\KunenaRoute;
use Kunena\Forum\Libraries\User\KunenaUserHelper;

/**
 * Kunena Category Controller
 *
 * @since   Kunena 6.0
 */
class CategoryController extends FormController
{
	/**
	 * @var     string
	 * @since   Kunena 6.0
	 */
	protected $baseurl = null;

	/**
	 * @var     string
	 * @since   Kunena 6.0
	 */
	protected $basecategoryurl = null;

	/**
	 * Constructor.
	 *
	 * @see     BaseController
	 *
	 * @param   MVCFactoryInterface  $factory  The factory.
	 * @param   CMSApplication       $app      The CMSApplication for the dispatcher
	 * @param   Input                $input    Input
	 *
	 * @param   array                $config   An optional associative array of configuration settings.
	 *
	 * @since   Kunena 2.0
	 *
	 * @throws  Exception
	 */
	public function __construct($config = array(), MVCFactoryInterface $factory = null, $app = null, $input = null)
	{
		parent::__construct($config);

		$this->baseurl         = 'administrator/index.php?option=com_kunena&view=categories';
		$this->basecategoryurl = 'administrator/index.php?option=com_kunena&view=category';
	}

	/**
	 * Save changes on the category
	 *
	 * @param   null  $key     key
	 * @param   null  $urlVar  urlvar
	 *
	 * @return  void
	 *
	 * @since   Kunena 2.0.0-BETA2
	 *
	 * @throws  Exception
	 */
	public function save($key = null, $urlVar = null)
	{
		$this->_save();
		$post_catid = $this->app->input->post->get('catid', '', 'raw');

		if ($this->app->isClient('administrator'))
		{
			if ($this->task == 'apply')
			{
				$this->setRedirect(KunenaRoute::_($this->basecategoryurl . "&layout=edit&catid={$post_catid}", false));
			}
			else
			{
				$this->setRedirect(KunenaRoute::_($this->baseurl, false));
			}
		}
		else
		{
			$this->setRedirect(KunenaRoute::_($this->basecategoryurl . '&catid=' . $post_catid));
		}
	}

	/**
	 * Internal method to save category
	 *
	 * @return Category|void
	 *
	 * @since   Kunena 2.0.0-BETA2
	 *
	 * @throws  null
	 * @throws  Exception
	 */
	protected function _save()
	{
		KunenaFactory::loadLanguage('com_kunena', 'admin');
		$me = KunenaUserHelper::getMyself();

		if ($this->app->isClient('site'))
		{
			KunenaFactory::loadLanguage('com_kunena.controllers', 'admin');
		}

		if (!Session::checkToken('post'))
		{
			$this->app->enqueueMessage(Text::_('COM_KUNENA_ERROR_TOKEN'), 'error');
			$this->setRedirect(KunenaRoute::_($this->baseurl, false));

			return;
		}

		$input      = $this->app->input;
		$post       = $input->post->getArray();
		$accesstype = strtr($input->getCmd('accesstype', 'joomla.level'), '.', '-');

		if ($post['task'] == 'save2copy')
		{
			$post['title'] = $this->app->getUserState('com_kunena.category_title');
			$post['alias'] = $this->app->getUserState('com_kunena.category_alias');
			$post['catid'] = $this->app->getUserState('com_kunena.category_catid');
		}

		$post['access'] = $input->getInt("access-{$accesstype}", $input->getInt('access', null));
		$post['params'] = $input->get("params-{$accesstype}", [], 'array');
		$post['params'] += $input->get("params", [], 'array');
		$success        = false;

		$category = CategoryHelper::get(intval($post ['catid']));
		$parent   = CategoryHelper::get(intval($post ['parent_id']));

		if ($category->exists() && !$category->isAuthorised('admin'))
		{
			// Category exists and user is not admin in category
			$this->app->enqueueMessage(Text::sprintf('COM_KUNENA_A_CATEGORY_NO_ADMIN', $this->escape($category->name)), 'notice');
		}
		elseif (!$category->exists() && !$me->isAdmin($parent))
		{
			// Category doesn't exist and user is not admin in parent, parent_id=0 needs global admin rights
			$this->app->enqueueMessage(Text::sprintf('COM_KUNENA_A_CATEGORY_NO_ADMIN', $this->escape($parent->name)), 'notice');
		}
		elseif (!$category->isCheckedOut($me->userid))
		{
			// Nobody can change id or statistics
			$ignore = ['option', 'view', 'task', 'catid', 'id', 'id_last_msg', 'numTopics', 'numPosts', 'time_last_msg', 'aliases', 'aliases_all'];

			// User needs to be admin in parent (both new and old) in order to move category, parent_id=0 needs global admin rights
			if (!$me->isAdmin($parent) || ($category->exists() && !$me->isAdmin($category->getParent())))
			{
				$ignore             = array_merge($ignore, ['parent_id', 'ordering']);
				$post ['parent_id'] = $category->parent_id;
			}

			// Only global admin can change access control and class_sfx (others are inherited from parent)
			if (!$me->isAdmin())
			{
				$access = ['accesstype', 'access', 'pub_access', 'pub_recurse', 'admin_access', 'admin_recurse', 'channels', 'class_sfx', 'params'];

				if (!$category->exists() || $parent->id != $category->parent_id)
				{
					// If category didn't exist or is moved, copy access and class_sfx from parent
					$category->bind($parent->getProperties(), $access, true);
				}

				$ignore = array_merge($ignore, $access);
			}

			$category->bind($post, $ignore);

			if (!$category->exists())
			{
				$category->ordering = 99999;
			}

			$success     = $category->save();
			$aliases_all = explode(',', $input->getString('aliases_all'));

			$aliases = $input->post->getArray(['aliases' => '']);

			if ($aliases_all)
			{
				$aliases = array_diff($aliases_all, $aliases['aliases']);

				foreach ($aliases_all as $alias)
				{
					$category->deleteAlias($alias);
				}
			}

			// Update read access
			$read                = $this->app->getUserState("com_kunena.user{$me->userid}_read");
			$read[$category->id] = $category->id;
			$this->app->setUserState("com_kunena.user{$me->userid}_read", null);

			if (!$success)
			{
				$this->app->enqueueMessage(Text::sprintf('COM_KUNENA_A_CATEGORY_SAVE_FAILED', $category->id, $this->escape($category->getError())), 'notice');
			}

			$category->checkin();
		}
		else
		{
			// Category was checked out by someone else.
			$this->app->enqueueMessage(Text::sprintf('COM_KUNENA_A_CATEGORY_X_CHECKED_OUT', $this->escape($category->name)), 'notice');
		}

		if ($success)
		{
			$this->app->enqueueMessage(Text::sprintf('COM_KUNENA_A_CATEGORY_SAVED', $this->escape($category->name)));
		}

		if (!empty($post['rmmod']))
		{
			foreach ((array) $post['rmmod'] as $userid => $value)
			{
				$user = KunenaFactory::getUser($userid);

				if ($category->tryAuthorise('admin', null, false) && $category->removeModerator($user))
				{
					$this->app->enqueueMessage(Text::sprintf('COM_KUNENA_VIEW_CATEGORY_EDIT_MODERATOR_REMOVED', $this->escape($user->getName()), $this->escape($category->name)));
				}
			}
		}

		return $category;
	}

	/**
	 * Escapes a value for output in a view script.
	 *
	 * @param   string  $var  The output to escape.
	 *
	 * @return  string The escaped value.
	 *
	 * @since   Kunena 6.0
	 */
	protected function escape($var)
	{
		return htmlspecialchars($var, ENT_COMPAT, 'UTF-8');
	}

	/**
	 * Apply
	 *
	 * @return  void
	 *
	 * @since   Kunena 2.0.0-BETA2
	 *
	 * @throws  null
	 * @throws  Exception
	 */
	public function apply()
	{
		$category = $this->_save();

		if ($category->exists())
		{
			$this->setRedirect(KunenaRoute::_($this->basecategoryurl . "&layout=edit&catid={$category->id}", false));
		}
		else
		{
			$this->setRedirect(KunenaRoute::_($this->basecategoryurl . "&layout=create", false));
		}
	}

	/**
	 * Cancel
	 *
	 * @param   null  $key     key
	 * @param   null  $urlVar  urlvar
	 *
	 * @return  void
	 *
	 * @since   Kunena 2.0.0-BETA2
	 *
	 * @throws  Exception
	 */
	public function cancel($key = null, $urlVar = null)
	{
		$post_catid = $this->app->input->post->get('catid', '', 'raw');
		$category   = CategoryHelper::get($post_catid);
		$category->checkin();

		$this->setRedirect(KunenaRoute::_($this->baseurl, false));
	}

	/**
	 * Method to save a category like a copy of existing one.
	 *
	 * @return  void
	 *
	 * @since   Kunena 2.0.0-BETA2
	 *
	 * @throws  null
	 * @throws  Exception
	 */
	public function save2copy()
	{
		$post_catid = $this->app->input->post->get('catid', '', 'raw');
		$post_alias = $this->app->input->post->get('alias', '', 'raw');
		$post_name  = $this->app->input->post->get('name', '', 'raw');

		list($title, $alias) = $this->_generateNewTitle($post_catid, $post_alias, $post_name);

		$this->app->setUserState('com_kunena.category_title', $title);
		$this->app->setUserState('com_kunena.category_alias', $alias);
		$this->app->setUserState('com_kunena.category_catid', 0);

		$this->_save();
		$this->setRedirect(KunenaRoute::_($this->basecategoryurl, false));
	}

	/**
	 * Method to change the title & alias.
	 *
	 * @param   integer  $category_id  The id of the category.
	 * @param   string   $alias        The alias.
	 * @param   string   $name         The name.
	 *
	 * @return  array  Contains the modified title and alias.
	 *
	 * @since   Kunena 2.0.0-BETA2
	 *
	 * @throws  Exception
	 */
	protected function _generateNewTitle($category_id, $alias, $name)
	{
		while (CategoryHelper::getAlias($category_id, $alias))
		{
			$name  = StringHelper::increment($name);
			$alias = StringHelper::increment($alias, 'dash');
		}

		return [$name, $alias];
	}

	/**
	 * Save2new
	 *
	 * @return  void
	 *
	 * @since   Kunena 2.0.0-BETA2
	 *
	 * @throws  null
	 * @throws  Exception
	 */
	public function save2new()
	{
		$this->_save();
		$this->setRedirect(KunenaRoute::_($this->basecategoryurl . "&layout=create", false));
	}
}
