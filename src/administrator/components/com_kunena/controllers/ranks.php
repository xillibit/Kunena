<?php
/**
 * Kunena Component
 *
 * @package         Kunena.Administrator
 * @subpackage      Controllers
 *
 * @copyright       Copyright (C) 2008 - 2019 Kunena Team. All rights reserved.
 * @license         https://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link            https://www.kunena.org
 **/
defined('_JEXEC') or die();

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
use Joomla\Utilities\ArrayHelper;

/**
 * Kunena Ranks Controller
 *
 * @since  2.0
 */
class KunenaAdminControllerRanks extends KunenaController
{
	/**
	 * @var null|string
	 * @since Kunena
	 */
	protected $baseurl = null;

	/**
	 * Construct
	 *
	 * @param   array  $config  config
	 *
	 * @since    2.0
	 * @throws Exception
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
		$this->baseurl = 'administrator/index.php?option=com_kunena&view=ranks';
	}

	/**
	 * Add
	 *
	 * @return void
	 *
	 * @since    2.0
	 * @throws Exception
	 * @throws null
	 */
	public function add()
	{
		if (!Session::checkToken('post'))
		{
			$this->app->enqueueMessage(Text::_('COM_KUNENA_ERROR_TOKEN'), 'error');
			$this->setRedirect(KunenaRoute::_($this->baseurl, false));

			return;
		}

		$this->setRedirect(Route::_('index.php?option=com_kunena&view=rank&layout=add', false));
	}

	/**
	 * Edit
	 *
	 * @return void
	 *
	 * @since    2.0
	 * @throws Exception
	 *
	 * @throws null
	 */
	public function edit()
	{
		if (!Session::checkToken('post'))
		{
			$this->app->enqueueMessage(Text::_('COM_KUNENA_ERROR_TOKEN'), 'error');
			$this->setRedirect(KunenaRoute::_($this->baseurl, false));

			return;
		}

		$cid = $this->input->get('cid', array(), 'array');
		$cid = ArrayHelper::toInteger($cid, array());

		$id = array_shift($cid);

		if (!$id)
		{
			$this->app->enqueueMessage(Text::_('COM_KUNENA_A_NO_RANKS_SELECTED'), 'notice');
			$this->setRedirect(KunenaRoute::_($this->baseurl, false));

			return;
		}
		else
		{
			$this->setRedirect(Route::_("index.php?option=com_kunena&view=rank&layout=edit&id={$id}", false));
		}
	}

	/**
	 * Save
	 *
	 * @return void
	 *
	 * @since    2.0
	 *
	 * @since    Kunena
	 * @throws Exception
	 * @throws null
	 */
	public function save()
	{
		$db = Factory::getDbo();

		if (!Session::checkToken('post'))
		{
			$this->app->enqueueMessage(Text::_('COM_KUNENA_ERROR_TOKEN'), 'error');
			$this->setRedirect(KunenaRoute::_($this->baseurl, false));

			return;
		}

		$rank_title   = $this->app->input->getString('rank_title');
		$rank_image   = basename($this->app->input->getString('rank_image'));
		$rank_special = $this->app->input->getInt('rank_special');
		$rank_min     = $this->app->input->getInt('rank_min');
		$rankid       = $this->app->input->getInt('rankid', 0);

		if (!$rankid)
		{
			$query = $db->getQuery(true)
				->insert("{$db->quoteName('#__kunena_ranks')}")
				->set("rank_title={$db->quote($rank_title)}, rank_image={$db->quote($rank_image)}, rank_special={$db->quote($rank_special)}, rank_min={$db->quote($rank_min)}");

			$db->setQuery($query);

			try
			{
				$db->execute();
			}
			catch (RuntimeException $e)
			{
				$this->app->enqueueMessage($e->getMessage());

				return;
			}
		}
		else
		{
			$query = $db->getQuery(true)
				->update("{$db->quoteName('#__kunena_ranks')}")
				->set("rank_title={$db->quote($rank_title)}, rank_image={$db->quote($rank_image)}, rank_special={$db->quote($rank_special)}, rank_min={$db->quote($rank_min)}")
				->where("rank_id={$db->quote($rankid)}");

			$db->setQuery($query);

			try
			{
				$db->execute();
			}
			catch (RuntimeException $e)
			{
				$this->app->enqueueMessage($e->getMessage());

				return;
			}
		}

		$this->app->enqueueMessage(Text::_('COM_KUNENA_RANK_SAVED'));
		$this->setRedirect(KunenaRoute::_($this->baseurl, false));
	}

	/**
	 * Rank upload
	 *
	 * @return void
	 *
	 * @since    2.0
	 *
	 * @since    Kunena
	 * @throws Exception
	 * @throws null
	 */
	public function rankupload()
	{
		if (!Session::checkToken('post'))
		{
			$this->app->enqueueMessage(Text::_('COM_KUNENA_ERROR_TOKEN'), 'error');
			$this->setRedirect(KunenaRoute::_($this->baseurl, false));

			return;
		}

		$file = $this->app->input->files->get('Filedata');

		// TODO : change this part to use other method than KunenaUploadHelper::upload()
		$upload = KunenaUploadHelper::upload($file, JPATH_ROOT . '/' . KunenaFactory::getTemplate()->getRankPath(), 'html');

		if ($upload)
		{
			$this->app->enqueueMessage(Text::_('COM_KUNENA_A_RANKS_UPLOAD_SUCCESS'));
		}
		else
		{
			$this->app->enqueueMessage(Text::_('COM_KUNENA_A_RANKS_UPLOAD_ERROR_UNABLE'), 'error');
		}

		$this->setRedirect(KunenaRoute::_($this->baseurl, false));
	}

	/**
	 * Remove
	 *
	 * @return void
	 *
	 * @since    2.0
	 *
	 * @since    Kunena
	 * @throws Exception
	 * @throws null
	 */
	public function remove()
	{
		$db = Factory::getDbo();

		if (!Session::checkToken('post'))
		{
			$this->app->enqueueMessage(Text::_('COM_KUNENA_ERROR_TOKEN'), 'error');
			$this->setRedirect(KunenaRoute::_($this->baseurl, false));

			return;
		}

		$cid = $this->input->get('cid', array(), 'array');
		$cid = ArrayHelper::toInteger($cid, array());

		$cids = implode(',', $cid);

		if ($cids)
		{
			$query = $db->getQuery(true)
				->delete()
				->from("{$db->quoteName('#__kunena_ranks')}")
				->where("rank_id IN ($cids)");

			$db->setQuery($query);

			try
			{
				$db->execute();
			}
			catch (RuntimeException $e)
			{
				$this->app->enqueueMessage($e->getMessage());

				return;
			}
		}

		$this->app->enqueueMessage(Text::_('COM_KUNENA_RANK_DELETED'));
		$this->setRedirect(KunenaRoute::_($this->baseurl, false));
	}

	/**
	 * Method to just redirect to main manager in case of use of cancel button
	 *
	 * @return void
	 *
	 * @since K4.0
	 * @throws Exception
	 * @throws null
	 */
	public function cancel()
	{
		$this->app->redirect(KunenaRoute::_($this->baseurl, false));
	}
}
