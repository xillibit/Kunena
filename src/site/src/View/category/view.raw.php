<?php
/**
 * Kunena Component
 *
 * @package         Kunena.Site
 * @subpackage      Views
 *
 * @copyright       Copyright (C) 2008 - 2020 Kunena Team. All rights reserved.
 * @license         https://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link            https://www.kunena.org
 **/

namespace Kunena\Forum\Site\View\Category;

defined('_JEXEC') or die();

use Exception;
use Joomla\CMS\Factory;
use Kunena\Forum\Libraries\Error\KunenaError;
use Kunena\Forum\Libraries\View\KunenaView;
use StdClass;
use function defined;

/**
 * Category View
 *
 * @since   Kunena 6.0
 */
class raw extends KunenaView
{
	/**
	 * @var mixed
	 * @since version
	 */
	private $category;

	/**
	 * @param   null  $tpl  tpl
	 *
	 * @return  void
	 *
	 * @since   Kunena 6.0
	 *
	 * @throws  Exception
	 */
	public function displayDefault($tpl = null)
	{
		$response              = [];
		$response['topiclist'] = [];

		if ($this->me->exists())
		{
			$this->category = $this->get('Category');

			if (!$this->category->isAuthorised('read'))
			{
				$response['error'] = $this->category->getError();
			}
			else
			{
				$topics = $this->get('Topics');

				foreach ($topics as $topic)
				{
					$item                    = new StdClass;
					$item->id                = $topic->id;
					$item->subject           = $topic->subject;
					$response['topiclist'][] = $item;
				}
			}
		}

		// Set the MIME type and header for JSON output.
		$this->document->setMimeEncoding('application/json');
		Factory::getApplication()->setHeader('Content-Disposition',
			'attachment; filename="' . $this->getName() . '.' . $this->getLayout() . '.json"'
		);
		Factory::getApplication()->sendHeaders();

		echo json_encode($response);
	}
}
