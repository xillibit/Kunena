<?php
/**
 * Kunena Component
 *
 * @package         Kunena.Site
 * @subpackage      Controller.Topic
 *
 * @copyright       Copyright (C) 2008 - 2020 Kunena Team. All rights reserved.
 * @license         https://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link            https://www.kunena.org
 **/

namespace Kunena\Forum\Site\Controller\Topic\Item\Actions;

defined('_JEXEC') or die();

use Exception;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Object\CMSObject;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Session\Session;
use Kunena\Forum\Libraries\Controller\KunenaControllerDisplay;
use Kunena\Forum\Libraries\Exception\KunenaAuthorise;
use Kunena\Forum\Libraries\Factory\KunenaFactory;
use Kunena\Forum\Libraries\Forum\Topic\KunenaTopic;
use Kunena\Forum\Libraries\Layout\KunenaLayout;
use Kunena\Forum\Libraries\Route\KunenaRoute;
use function defined;

/**
 * Class ComponentTopicControllerItemActionsDisplay
 *
 * @since   Kunena 4.0
 */
class ComponentTopicControllerItemActionsDisplay extends KunenaControllerDisplay
{
	/**
	 * @var     KunenaTopic
	 * @since   Kunena 6.0
	 */
	public $topic;

	/**
	 * @var     array
	 * @since   Kunena 6.0
	 */
	public $topicButtons;

	/**
	 * @var     string
	 * @since   Kunena 6.0
	 */
	protected $name = 'Topic/Item/Actions';
	/**
	 * @var \Kunena\Forum\Libraries\Template\KunenaTemplate
	 * @since version
	 */
	private $template;

	/**
	 * Prepare topic actions display.
	 *
	 * @return  void
	 *
	 * @since   Kunena 6.0
	 *
	 * @throws  null
	 * @throws  Exception
	 */
	protected function before()
	{
		parent::before();

		$id = $this->input->getInt('id');

		$this->topic = KunenaTopic::getInstance($id);

		$catid = $this->topic->category_id;
		$token = Session::getFormToken();

		$task   = "index.php?option=com_kunena&view=topic&task=%s&catid={$catid}&id={$id}&{$token}=1";
		$layout = "index.php?option=com_kunena&view=topic&layout=%s&catid={$catid}&id={$id}";

		$userTopic          = $this->topic->getUserTopic();
		$this->template     = KunenaFactory::getTemplate();
		$this->topicButtons = new CMSObject;

		$fullactions   = $this->template->params->get('fullactions');
		$topicicontype = $this->template->params->get('topicicontype');

		$button = $fullactions ? true : false;

		if ($this->config->read_only)
		{
			throw new KunenaAuthorise(Text::_('COM_KUNENA_NO_ACCESS'), '401');
		}

		if ($this->topic->isAuthorised('reply'))
		{
			// Add Reply topic button.
			if ($topicicontype == 'B2' && !$fullactions)
			{
				$this->topicButtons->set('reply',
					$this->getButton(sprintf($layout, 'reply'), 'reply', 'topic', 'communication', false, $button, 'icon icon-undo')
				);
			}
			elseif ($topicicontype == 'B3' && !$fullactions)
			{
				$this->topicButtons->set('reply',
					$this->getButton(sprintf($layout, 'reply'), 'reply', 'topic', 'communication', false, $button, 'glyphicon glyphicon-share-alt')
				);
			}
			elseif ($topicicontype == 'fa' && !$fullactions)
			{
				$this->topicButtons->set('reply',
					$this->getButton(sprintf($layout, 'reply'), 'reply', 'topic', 'communication', false, $button, 'fa fa-reply')
				);
			}
			elseif ($topicicontype == 'image' && !$fullactions)
			{
				$this->topicButtons->set('reply',
					$this->getButton(sprintf($layout, 'reply'), 'reply', 'topic', 'communication', false, $button, 'kicon-reply')
				);
			}
			else
			{
				$this->topicButtons->set('reply',
					$this->getButton(sprintf($layout, 'reply'), 'reply', 'topic', 'communication', false, $button)
				);
			}
		}

		if ($userTopic->subscribed)
		{
			// User can always remove existing subscription.
			if ($topicicontype == 'B2' && !$fullactions)
			{
				$this->topicButtons->set('subscribe',
					$this->getButton(sprintf($task, 'unsubscribe'), 'unsubscribe', 'topic', 'user', false, $button, 'icon icon-envelope-opened')
				);
			}
			elseif ($topicicontype == 'B3' && !$fullactions)
			{
				$this->topicButtons->set('subscribe',
					$this->getButton(sprintf($task, 'unsubscribe'), 'unsubscribe', 'topic', 'user', false, $button, 'glyphicon glyphicon-envelope')
				);
			}
			elseif ($topicicontype == 'fa' && !$fullactions)
			{
				$this->topicButtons->set('subscribe',
					$this->getButton(sprintf($task, 'unsubscribe'), 'unsubscribe', 'topic', 'user', false, $button, 'fas fa-envelope-open')
				);
			}
			elseif ($topicicontype == 'image' && !$fullactions)
			{
				$this->topicButtons->set('subscribe',
					$this->getButton(sprintf($task, 'unsubscribe'), 'unsubscribe', 'topic', 'user', false, $button, 'kicon-unsubscribe')
				);
			}
			else
			{
				$this->topicButtons->set('subscribe',
					$this->getButton(sprintf($task, 'unsubscribe'), 'unsubscribe', 'topic', 'user', false, $button)
				);
			}
		}
		elseif ($this->topic->isAuthorised('subscribe'))
		{
			// Add subscribe topic button.
			if ($topicicontype == 'B2' && !$fullactions)
			{
				$this->topicButtons->set('subscribe',
					$this->getButton(sprintf($task, 'subscribe'), 'subscribe', 'topic', 'user', false, $button, 'icon icon-envelope')
				);
			}
			elseif ($topicicontype == 'B3' && !$fullactions)
			{
				$this->topicButtons->set('subscribe',
					$this->getButton(sprintf($task, 'subscribe'), 'subscribe', 'topic', 'user', false, $button, 'glyphicon glyphicon-envelope')
				);
			}
			elseif ($topicicontype == 'fa' && !$fullactions)
			{
				$this->topicButtons->set('subscribe',
					$this->getButton(sprintf($task, 'subscribe'), 'subscribe', 'topic', 'user', false, $button, 'fas fa-envelope')
				);
			}
			elseif ($topicicontype == 'image' && !$fullactions)
			{
				$this->topicButtons->set('subscribe',
					$this->getButton(sprintf($task, 'subscribe'), 'subscribe', 'topic', 'user', false, $button, 'kicon-subscribe')
				);
			}
			else
			{
				$this->topicButtons->set('subscribe',
					$this->getButton(sprintf($task, 'subscribe'), 'subscribe', 'topic', 'user', false, $button)
				);
			}
		}

		if ($userTopic->favorite)
		{
			// User can always remove existing favorite.
			if ($topicicontype == 'B2' && !$fullactions)
			{
				$this->topicButtons->set('favorite',
					$this->getButton(sprintf($task, 'unfavorite'), 'unfavorite', 'topic', 'user', false, $button, 'icon icon-star')
				);
			}
			elseif ($topicicontype == 'B3' && !$fullactions)
			{
				$this->topicButtons->set('favorite',
					$this->getButton(sprintf($task, 'unfavorite'), 'unfavorite', 'topic', 'user', false, $button, 'glyphicon glyphicon-star')
				);
			}
			elseif ($topicicontype == 'fa' && !$fullactions)
			{
				$this->topicButtons->set('favorite',
					$this->getButton(sprintf($task, 'unfavorite'), 'unfavorite', 'topic', 'user', false, $button, 'fa fa-star')
				);
			}
			elseif ($topicicontype == 'image' && !$fullactions)
			{
				$this->topicButtons->set('favorite',
					$this->getButton(sprintf($task, 'unfavorite'), 'unfavorite', 'topic', 'user', false, $button, 'kicon-unfavorite')
				);
			}
			else
			{
				$this->topicButtons->set('favorite',
					$this->getButton(sprintf($task, 'unfavorite'), 'unfavorite', 'topic', 'user', false, $button)
				);
			}
		}
		elseif ($this->topic->isAuthorised('favorite'))
		{
			// Add favorite topic button.
			if ($topicicontype == 'B2' && !$fullactions)
			{
				$this->topicButtons->set('favorite',
					$this->getButton(sprintf($task, 'favorite'), 'favorite', 'topic', 'user', false, $button, 'icon icon-star-empty')
				);
			}
			elseif ($topicicontype == 'B3' && !$fullactions)
			{
				$this->topicButtons->set('favorite',
					$this->getButton(sprintf($task, 'favorite'), 'favorite', 'topic', 'user', false, $button, 'glyphicon glyphicon-star-empty')
				);
			}
			elseif ($topicicontype == 'fa' && !$fullactions)
			{
				$this->topicButtons->set('favorite',
					$this->getButton(sprintf($task, 'favorite'), 'favorite', 'topic', 'user', false, $button, 'far fa-star')
				);
			}
			elseif ($topicicontype == 'image' && !$fullactions)
			{
				$this->topicButtons->set('favorite',
					$this->getButton(sprintf($task, 'favorite'), 'favorite', 'topic', 'user', false, $button, 'kicon-favorite')
				);
			}
			else
			{
				$this->topicButtons->set('favorite',
					$this->getButton(sprintf($task, 'favorite'), 'favorite', 'topic', 'user', false, $button)
				);
			}
		}

		if ($this->topic->getCategory()->isAuthorised('moderate'))
		{
			// Add moderator specific buttons.
			$sticky = $this->topic->ordering ? 'unsticky' : 'sticky';
			$lock   = $this->topic->locked ? 'unlock' : 'lock';

			$this->topicButtons->set('sticky',
				$this->getButton(sprintf($task, $sticky), $sticky, 'topic', 'moderation', false, $button)
			);

			$this->topicButtons->set('lock',
				$this->getButton(sprintf($task, $lock), $lock, 'topic', 'moderation', false, $button)
			);

			$this->topicButtons->set('moderate',
				$this->getButton(sprintf($layout, 'moderate'), 'moderate', 'topic', 'moderation', false, $button)
			);

			if ($this->topic->hold == 1)
			{
				$this->topicButtons->set('approve',
					$this->getButton(sprintf($task, 'approve'), 'moderate', 'topic', 'moderation', false, $button)
				);
			}

			if ($this->topic->hold == 1 || $this->topic->hold == 0)
			{
				$this->topicButtons->set('delete',
					$this->getButton(sprintf($task, 'delete'), 'delete', 'topic', 'moderation', false, $button)
				);
			}
			elseif ($this->topic->hold == 2 || $this->topic->hold == 3)
			{
				if ($this->topic->isAuthorised('permdelete'))
				{
					$this->topicButtons->set('permdelete',
						$this->getButton(sprintf($task, 'permdelete'), 'permdelete', 'topic', 'moderation', false, $button)
					);
				}

				if ($this->topic->isAuthorised('undelete'))
				{
					$this->topicButtons->set('undelete',
						$this->getButton(sprintf($task, 'undelete'), 'undelete', 'topic', 'moderation', false, $button)
					);
				}
			}
		}

		// Add buttons for changing between different layout modes.
		if ($this->config->enable_threaded_layouts)
		{
			$url = "index.php?option=com_kunena&view=user&task=change&topic_layout=%s&{$token}=1";

			if ($this->layout != 'default')
			{
				$this->topicButtons->set('flat',
					$this->getButton(sprintf($url, 'flat'), 'flat', 'layout', 'user', false, $button)
				);
			}

			if ($this->layout != 'threaded')
			{
				$this->topicButtons->set('threaded',
					$this->getButton(sprintf($url, 'threaded'), 'threaded', 'layout', 'user', false, $button)
				);
			}

			if ($this->layout != 'indented')
			{
				$this->topicButtons->set('indented',
					$this->getButton(sprintf($url, 'indented'), 'indented', 'layout', 'user', false, $button)
				);
			}
		}

		PluginHelper::importPlugin('kunena');

		$this->app->triggerEvent('onKunenaGetButtons', ['topic.action', $this->topicButtons, $this]);
	}

	/**
	 * Get button.
	 *
	 * @param   string  $url      Target link (do not route it).
	 * @param   string  $name     Name of the button.
	 * @param   string  $scope    Scope of the button.
	 * @param   string  $type     Type of the button.
	 * @param   bool    $primary  True if primary button.
	 * @param   bool    $normal   Define if the button will have the class btn or btn-small
	 * @param   string  $icon     icon
	 *
	 * @return  KunenaLayout
	 *
	 * @since   Kunena 6.0
	 *
	 * @throws  Exception
	 * @throws  null
	 */
	public function getButton($url, $name, $scope, $type, $primary = false, $normal = true, $icon = '')
	{
		return KunenaLayout::factory('Widget/Button')
			->setProperties(['url'   => KunenaRoute::_($url), 'name' => $name,
							 'scope' => $scope, 'type' => $type, 'primary' => $primary, 'normal' => $normal, 'icon' => $icon, ]
			);
	}
}
