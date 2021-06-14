<?php
/**
 * Kunena Component
 *
 * @package         Kunena.Administrator
 * @subpackage      Views
 *
 * @copyright       Copyright (C) 2008 - 2021 Kunena Team. All rights reserved.
 * @license         https://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link            https://www.kunena.org
 **/

namespace Kunena\Forum\Administrator\View\Smiley;

\defined('_JEXEC') or die();

use Exception;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;

/**
 * About view for Kunena smiley backend
 *
 * @since   Kunena 1.X
 */
class HtmlView extends BaseHtmlView
{
	/**
	 * @var object
	 * @since version
	 */
	private $ktemplate;

	/**
	 * @param   null  $tpl  tpl
	 *
	 * @return  void
	 *
	 * @since   Kunena 6.0
	 *
	 * @throws  Exception
	 */
	public function display($tpl = null)
	{
		$this->setLayout('edit');

		$this->state          = $this->get('state');
		$this->smileySelected = $this->get('smiley');
		$this->smileyPath     = $this->ktemplate->getSmileyPath();
		$this->listSmileys    = $this->get('SmileysPaths');

		$this->addToolbar();

		return parent::display($tpl);
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
		ToolbarHelper::title(Text::_('COM_KUNENA') . ': ' . Text::_('COM_KUNENA_EMOTICON_MANAGER'), 'thumbs-up');
		ToolbarHelper::spacer();
		ToolbarHelper::save('save');
		ToolbarHelper::spacer();
		ToolbarHelper::cancel();
		$helpUrl = 'https://docs.kunena.org/en/manual/backend/emoticons/edit-emoticon';
		ToolbarHelper::help('COM_KUNENA', false, $helpUrl);
	}
}
