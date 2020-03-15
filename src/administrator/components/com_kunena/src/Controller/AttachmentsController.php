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
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Session\Session;
use Joomla\Utilities\ArrayHelper;
use Kunena\Forum\Libraries\Attachment\AttachmentHelper;
use Kunena\Forum\Libraries\Route\KunenaRoute;
use function defined;

/**
 * Kunena Attachments Controller
 *
 * @since   Kunena 2.0
 */
class AttachmentsController extends FormController
{
	/**
	 * @var     null|string
	 * @since   Kunena 2.0.0-BETA2
	 */
	protected $baseurl = null;

	/**
	 * Constructor
	 *
	 * @param   array  $config  Construct
	 *
	 * @since   Kunena 2.0
	 *
	 * @throws  Exception
	 */
	public function __construct($config = [])
	{
		parent::__construct($config);
		$this->baseurl = 'administrator/index.php?option=com_kunena&view=attachments';
	}

	/**
	 * Delete
	 *
	 * @return  void
	 *
	 * @since   Kunena 2.0
	 *
	 * @throws  Exception
	 * @throws  null
	 */
	public function delete()
	{
		if (!Session::checkToken('post'))
		{
			$this->app->enqueueMessage(Text::_('COM_KUNENA_ERROR_TOKEN'), 'error');
			$this->setRedirect(KunenaRoute::_($this->baseurl, false));

			return;
		}

		$cid = $this->input->get('cid', [], 'array');
		$cid = ArrayHelper::toInteger($cid, []);

		if (!$cid)
		{
			$this->app->enqueueMessage(Text::_('COM_KUNENA_NO_ATTACHMENTS_SELECTED'), 'error');
			$this->setRedirect(KunenaRoute::_($this->baseurl, false));

			return;
		}

		foreach ($cid as $id)
		{
			$attachment = AttachmentHelper::get($id);

			$message     = $attachment->getMessage();
			$attachments = [$attachment->id, 1];
			$attach      = [];
			$removeList  = array_keys(array_diff_key($attachments, $attach));
			$removeList  = ArrayHelper::toInteger($removeList);
			$message->removeAttachments($removeList);

			$message_text = $attachment->removeBBCodeInMessage($message->message);

			if ($message_text !== false)
			{
				$message->message = $message_text;
			}

			$message->save();

			$topic = $message->getTopic();
			$attachment->delete();

			if ($topic->attachments > 0)
			{
				$topic->attachments = $topic->attachments - 1;
				$topic->save(false);
			}
		}

		$this->app->enqueueMessage(Text::_('COM_KUNENA_ATTACHMENTS_DELETED_SUCCESSFULLY'));
		$this->setRedirect(KunenaRoute::_($this->baseurl, false));
	}
}
