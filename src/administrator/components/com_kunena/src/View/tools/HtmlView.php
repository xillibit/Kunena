<?php
/**
 * Kunena Component
 *
 * @package         Kunena.Administrator
 * @subpackage      Views
 *
 * @copyright       Copyright (C) 2008 - 2020 Kunena Team. All rights reserved.
 * @license         https://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link            https://www.kunena.org
 **/

namespace Kunena\Forum\Administrator\View\Tools;

defined('_JEXEC') or die();

use Exception;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Kunena\Forum\Libraries\Access\Access;
use Kunena\Forum\Libraries\Forum\Topic\TopicHelper;
use Kunena\Forum\Libraries\Login\Login;
use Kunena\Forum\Libraries\Menu\MenuFix;
use Kunena\Forum\Libraries\User\KunenaUserHelper;
use function defined;

/**
 * About view for Kunena cpanel
 *
 * @since   Kunena 6.0
 */
class HtmlView extends BaseHtmlView
{
	/**
	 * @var     array
	 * @since   Kunena 6.0
	 */
	protected $systemreport = [];

	/**
	 * @var     array
	 * @since   Kunena 6.0
	 */
	protected $systemreport_anonymous = [];

	/**
	 * @var     array
	 * @since   Kunena 6.0
	 */
	protected $listtrashdelete = [];

	/**
	 * @var     array
	 * @since   Kunena 6.0
	 */
	protected $forumList = [];

	/**
	 * @var     array
	 * @since   Kunena 6.0
	 */
	protected $controloptions = [];

	/**
	 * @var     array
	 * @since   Kunena 6.0
	 */
	protected $keepSticky = [];

	/**
	 * @var     array
	 * @since   Kunena 6.0
	 */
	protected $legacy = [];

	/**
	 * @var     array
	 * @since   Kunena 6.0
	 */
	protected $conflicts = [];

	/**
	 * @var     array
	 * @since   Kunena 6.0
	 */
	protected $invalid = [];

	/**
	 * @var     array
	 * @since   Kunena 6.0
	 */
	protected $cat_subscribers_users = [];

	/**
	 * @var     array
	 * @since   Kunena 6.0
	 */
	protected $topic_subscribers_users = [];

	/**
	 * @var     array
	 * @since   Kunena 6.0
	 */
	protected $cat_topic_subscribers = [];

	/**
	 * @return  void
	 *
	 * @since   Kunena 6.0
	 *
	 * @throws  Exception
	 */
	public function display($tpl = null)
	{
		$layout = $this->getLayout();

		if ($layout == 'default')
		{
			$this->setToolBar();
		}
		elseif ($layout == 'cleanupip')
		{
			$this->setToolBarCleanupIP();
		}
		elseif ($layout == 'diagnostics')
		{
			$this->setToolBarDiagnostics();
		}
		elseif ($layout == 'menu')
		{
			$this->legacy    = MenuFix::getLegacy();
			$this->invalid   = MenuFix::getInvalid();
			$this->conflicts = MenuFix::getConflicts();

			$this->setToolBarMenu();
		}
		elseif ($layout == 'prune')
		{
			$this->forumList       = $this->get('PruneCategories');
			$this->listtrashdelete = $this->get('PruneListtrashdelete');
			$this->controloptions  = $this->get('PruneControlOptions');
			$this->keepSticky      = $this->get('PruneKeepSticky');

			$this->setToolBarPrune();
		}
		elseif ($layout == 'purgerestatements')
		{
			$this->setToolBarPurgeReStatements();
		}
		elseif ($layout == 'recount')
		{
			$this->setToolBarRecount();
		}
		elseif ($layout == 'report')
		{
			$this->systemreport           = $this->get('SystemReport');
			$this->systemreport_anonymous = $this->get('SystemReportAnonymous');

			$this->setToolBarReport();
		}
		elseif ($layout == 'subscriptions')
		{
			$this->app = Factory::getApplication();
			$id = $this->app->input->get('id', 0, 'int');

			if ($id)
			{
				$topic           = TopicHelper::get($id);
				$acl             = Access::getInstance();
				$cat_subscribers = $acl->loadSubscribers($topic, Access::CATEGORY_SUBSCRIPTION);

				$this->cat_subscribers_users   = KunenaUserHelper::loadUsers($cat_subscribers);
				$topic_subscribers             = $acl->loadSubscribers($topic, Access::TOPIC_SUBSCRIPTION);
				$this->topic_subscribers_users = KunenaUserHelper::loadUsers($topic_subscribers);
				$this->cat_topic_subscribers   = $acl->getSubscribers($topic->getCategory()->id, $id, Access::CATEGORY_SUBSCRIPTION | Access::TOPIC_SUBSCRIPTION, 1, 1);
			}

			$this->setToolBarSubscriptions();
		}
		elseif ($layout == 'syncusers')
		{
			$this->setToolBarSyncUsers();
		}
		elseif ($layout == 'uninstall')
		{
			$login              = Login::getInstance();
			$this->isTFAEnabled = $login->isTFAEnabled();

			$this->setToolBarUninstall();
		}

		return parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   Kunena 6.0
	 */
	protected function setToolBar()
	{
		ToolbarHelper::title(Text::_('COM_KUNENA') . ': ' . Text::_('COM_KUNENA_FORUM_TOOLS'), 'tools');
		$help_url = 'https://docs.kunena.org/en/manual/backend/tools';
		ToolbarHelper::help('COM_KUNENA', false, $help_url);
	}

	/**
	 * @return  void
	 *
	 * @since   Kunena 6.0
	 */
	protected function setToolBarPrune()
	{
		ToolbarHelper::title(Text::_('COM_KUNENA'), 'tools');
		ToolbarHelper::spacer();
		ToolbarHelper::custom('tools.prune', 'delete.png', 'delete_f2.png', 'COM_KUNENA_PRUNE', false);
		ToolbarHelper::spacer();
		ToolbarHelper::cancel();
		ToolbarHelper::spacer();
		$help_url = 'https://docs.kunena.org/en/manual/backend/tools/prune-categories';
		ToolbarHelper::help('COM_KUNENA', false, $help_url);
	}

	/**
	 * @return  void
	 *
	 * @since   Kunena 6.0
	 */
	protected function setToolBarSyncUsers()
	{
		ToolbarHelper::title(Text::_('COM_KUNENA'), 'tools');
		ToolbarHelper::spacer();
		ToolbarHelper::custom('tools.syncusers', 'apply.png', 'apply_f2.png', 'COM_KUNENA_SYNC', false);
		ToolbarHelper::spacer();
		ToolbarHelper::cancel();
		ToolbarHelper::spacer();
		$help_url = 'https://docs.kunena.org/en/manual/backend/tools/synchronize-users';
		ToolbarHelper::help('COM_KUNENA', false, $help_url);
	}

	/**
	 * @return  void
	 *
	 * @since   Kunena 6.0
	 */
	protected function setToolBarRecount()
	{
		ToolbarHelper::title(Text::_('COM_KUNENA'), 'tools');
		ToolbarHelper::spacer();
		ToolbarHelper::custom('tools.recount', 'apply.png', 'apply_f2.png', 'COM_KUNENA_A_RECOUNT', false);
		ToolbarHelper::spacer();
		ToolbarHelper::cancel();
		ToolbarHelper::spacer();
		$help_url = 'https://docs.kunena.org/en/manual/backend/tools/recount-statistics';
		ToolbarHelper::help('COM_KUNENA', false, $help_url);
	}

	/**
	 * @return  void
	 *
	 * @since   Kunena 6.0
	 */
	protected function setToolBarMenu()
	{
		ToolbarHelper::title(Text::_('COM_KUNENA'), 'tools');
		ToolbarHelper::spacer();

		if (!empty($this->legacy))
		{
			ToolbarHelper::custom('tools.fixlegacy', 'edit.png', 'edit_f2.png', 'COM_KUNENA_A_MENU_TOOLBAR_FIXLEGACY', false);
		}

		ToolbarHelper::custom('tools.trashmenu', 'apply.png', 'apply_f2.png', 'COM_KUNENA_A_TRASH_MENU', false);
		ToolbarHelper::spacer();
		ToolbarHelper::cancel();
		ToolbarHelper::spacer();
		$help_url = 'https://docs.kunena.org/en/manual/backend/tools/menu-manager';
		ToolbarHelper::help('COM_KUNENA', false, $help_url);
	}

	/**
	 * @return  void
	 *
	 * @since   Kunena 6.0
	 */
	protected function setToolBarPurgeReStatements()
	{
		ToolbarHelper::title(Text::_('COM_KUNENA'), 'tools');
		ToolbarHelper::spacer();
		ToolbarHelper::trash('tools.purgerestatements', 'COM_KUNENA_A_PURGE_RE_MENU_VALIDATE', false);
		ToolbarHelper::spacer();
		ToolbarHelper::cancel();
		ToolbarHelper::spacer();
		$help_url = 'https://docs.kunena.org/en/manual/backend/tools/purge-re-prefixes';
		ToolbarHelper::help('COM_KUNENA', false, $help_url);
	}

	/**
	 * @return  void
	 *
	 * @since   Kunena 6.0
	 */
	protected function setToolBarCleanupIP()
	{
		ToolbarHelper::title(Text::_('COM_KUNENA'), 'tools');
		ToolbarHelper::spacer();
		ToolbarHelper::custom('tools.cleanupip', 'apply.png', 'apply_f2.png', 'COM_KUNENA_TOOLS_LABEL_CLEANUP_IP', false);
		ToolbarHelper::spacer();
		ToolbarHelper::cancel();
		ToolbarHelper::spacer();
		$help_url = 'https://docs.kunena.org/en/manual/backend/tools/remove-stored-ip-addresses';
		ToolbarHelper::help('COM_KUNENA', false, $help_url);
	}

	/**
	 * @return  void
	 *
	 * @since   Kunena 6.0
	 */
	protected function setToolBarDiagnostics()
	{
		ToolbarHelper::title(Text::_('COM_KUNENA'), 'tools');
		ToolbarHelper::spacer();
		ToolbarHelper::cancel();
		ToolbarHelper::spacer();
		$help_url = 'https://docs.kunena.org/en/manual/backend/tools/diagnostics';
		ToolbarHelper::help('COM_KUNENA', false, $help_url);
	}

	/**
	 * @return  void
	 *
	 * @since   Kunena 6.0
	 */
	protected function setToolBarUninstall()
	{
		ToolbarHelper::title(Text::_('COM_KUNENA'), 'tools');
		ToolbarHelper::spacer();
		ToolbarHelper::cancel();
		ToolbarHelper::spacer();
		$help_url = 'https://docs.kunena.org/en/manual/backend/tools/uninstall-kunena';
		ToolbarHelper::help('COM_KUNENA', false, $help_url);
	}

	/**
	 * @return  void
	 *
	 * @since   Kunena 6.0
	 */
	protected function setToolBarReport()
	{
		ToolbarHelper::title(Text::_('COM_KUNENA'), 'help');
		ToolbarHelper::spacer();
		ToolbarHelper::cancel();
		ToolbarHelper::spacer();
		$help_url = 'https://docs.kunena.org/en/faq/configuration-report';
		ToolbarHelper::help('COM_KUNENA', false, $help_url);
	}

	/**
	 * @return  void
	 *
	 * @since   Kunena 6.0
	 */
	protected function setToolBarSubscriptions()
	{
		ToolbarHelper::title(Text::_('COM_KUNENA'), 'help');
		ToolbarHelper::spacer();
		ToolbarHelper::cancel();
		ToolbarHelper::spacer();
		$help_url = 'https://docs.kunena.org/en/faq/configuration-report';
		ToolbarHelper::help('COM_KUNENA', false, $help_url);
	}
}
