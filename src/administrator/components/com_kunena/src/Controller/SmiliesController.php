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
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
use Joomla\Utilities\ArrayHelper;
use Kunena\Forum\Libraries\Factory\KunenaFactory;
use Kunena\Forum\Libraries\Route\KunenaRoute;
use Kunena\Forum\Libraries\Upload\UploadHelper;
use RuntimeException;
use function defined;

/**
 * Kunena Smileys Controller
 *
 * @since   Kunena 2.0
 */
class SmiliesController extends FormController
{
	/**
	 * @var     null|string
	 * @since   Kunena 6.0
	 */
	protected $baseurl = null;

	/**
	 * Construct
	 *
	 * @param   array  $config  config
	 *
	 * @since   Kunena 2.0
	 *
	 * @throws  Exception
	 */
	public function __construct($config = [])
	{
		parent::__construct($config);
		$this->baseurl = 'administrator/index.php?option=com_kunena&view=smilies';
	}

	/**
	 * Add
	 *
	 * @return  void
	 *
	 * @since   Kunena 2.0
	 *
	 * @throws  Exception
	 * @throws  null
	 */
	public function add()
	{
		if (!Session::checkToken('post'))
		{
			$this->app->enqueueMessage(Text::_('COM_KUNENA_ERROR_TOKEN'), 'error');
			$this->setRedirect(KunenaRoute::_($this->baseurl, false));

			return;
		}

		$this->setRedirect(Route::_('index.php?option=com_kunena&view=smiley&layout=add', false));
	}

	/**
	 * Edit
	 *
	 * @param   null  $key    key
	 * @param   null  $urlVar urlvar
	 *
	 * @return  void
	 *
	 * @since   Kunena 6.0
	 *
	 * @throws  Exception
	 */
	public function edit($key = null, $urlVar = null)
	{
		if (!Session::checkToken('post'))
		{
			$this->app->enqueueMessage(Text::_('COM_KUNENA_ERROR_TOKEN'), 'error');
			$this->setRedirect(KunenaRoute::_($this->baseurl, false));

			return;
		}

		$cid = $this->input->get('cid', [], 'array');
		$cid = ArrayHelper::toInteger($cid, []);

		$id = array_shift($cid);

		if (!$id)
		{
			$this->app->enqueueMessage(Text::_('COM_KUNENA_A_NO_SMILEYS_SELECTED'), 'notice');
			$this->setRedirect(KunenaRoute::_($this->baseurl, false));

			return;
		}

		$this->setRedirect(Route::_("index.php?option=com_kunena&view=smiley&layout=edit&id={$id}", false));
	}

	/**
	 * Save
	 *
	 * @param   null  $key    key
	 * @param   null  $urlVar urlvar
	 *
	 * @return  void
	 *
	 * @since   Kunena 2.0
	 *
	 * @throws  Exception
	 */
	public function save($key = null, $urlVar = null)
	{
		$db = Factory::getDbo();

		if (!Session::checkToken('post'))
		{
			$this->app->enqueueMessage(Text::_('COM_KUNENA_ERROR_TOKEN'), 'error');
			$this->setRedirect(KunenaRoute::_($this->baseurl, false));

			return;
		}

		$smiley_code        = $this->app->input->getString('smiley_code');
		$smiley_location    = basename($this->app->input->getString('smiley_url'));
		$smiley_emoticonbar = $this->app->input->getInt('smiley_emoticonbar', 0);
		$smileyid           = $this->app->input->getInt('smileyid', 0);

		if (!$smileyid)
		{
			$query = $db->getQuery(true)
				->insert("{$db->quoteName('#__kunena_smileys')}")->set("code={$db->quote($smiley_code)}, location={$db->quote($smiley_location)}, emoticonbar={$db->quote($smiley_emoticonbar)}");

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
				->update("{$db->quoteName('#__kunena_smileys')}")->set("code={$db->quote($smiley_code)}, location={$db->quote($smiley_location)}, emoticonbar={$db->quote($smiley_emoticonbar)}")
				->where("id = {$db->quote($smileyid)}");

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

		$this->app->enqueueMessage(Text::_('COM_KUNENA_SMILEY_SAVED'));
		$this->setRedirect(KunenaRoute::_($this->baseurl, false));
	}

	/**
	 * Smiley upload
	 *
	 * @return  void
	 *
	 * @since   Kunena 2.0
	 *
	 * @throws  Exception
	 * @throws  null
	 */
	public function smileyupload()
	{
		if (!Session::checkToken('post'))
		{
			$this->app->enqueueMessage(Text::_('COM_KUNENA_ERROR_TOKEN'), 'error');
			$this->setRedirect(KunenaRoute::_($this->baseurl, false));

			return;
		}

		$file = $this->app->input->files->get('Filedata');

		// TODO : change this part to use other method than \Kunena\Forum\Libraries\Upload\UploadHelper::upload()
		$upload = UploadHelper::upload($file, JPATH_ROOT . '/' . KunenaFactory::getTemplate()->getSmileyPath(), 'html');

		if ($upload)
		{
			$this->app->enqueueMessage(Text::_('COM_KUNENA_A_EMOTICONS_UPLOAD_SUCCESS'));
		}
		else
		{
			$this->app->enqueueMessage(Text::_('COM_KUNENA_A_EMOTICONS_UPLOAD_ERROR_UNABLE'), 'error');
		}

		$this->setRedirect(KunenaRoute::_($this->baseurl, false));
	}

	/**
	 * Remove
	 *
	 * @return  void
	 *
	 * @since   Kunena 2.0
	 *
	 * @throws  Exception
	 * @throws  null
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

		$cid = $this->input->get('cid', [], 'array');
		$cid = ArrayHelper::toInteger($cid, []);

		$cids = implode(',', $cid);

		if ($cids)
		{
			$query = $db->getQuery(true)
				->delete()->from("{$db->quoteName('#__kunena_smileys')}")->where("id IN ($cids)");

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

		$this->app->enqueueMessage(Text::_('COM_KUNENA_SMILEY_DELETED'));
		$this->setRedirect(KunenaRoute::_($this->baseurl, false));
	}

	/**
	 * Method to just redirect to main manager in case of use of cancel button
	 *
	 * @param   null  $key key
	 *
	 * @return  void
	 *
	 * @since   Kunena 4.0
	 *
	 * @throws  Exception
	 */
	public function cancel($key = null)
	{
		$this->app->redirect(KunenaRoute::_($this->baseurl, false));
	}
}
