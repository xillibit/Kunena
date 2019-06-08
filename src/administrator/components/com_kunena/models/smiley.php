<?php
/**
 * Kunena Component
 *
 * @package         Kunena.Administrator
 * @subpackage      Models
 *
 * @copyright       Copyright (C) 2008 - 2019 Kunena Team. All rights reserved.
 * @license         https://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link            https://www.kunena.org
 **/
defined('_JEXEC') or die();

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\Folder;

jimport('joomla.application.component.modellist');

/**
 * Smiley Model for Kunena
 *
 * @since 3.0
 */
class KunenaAdminModelSmiley extends KunenaModel
{
	/**
	 * @return  mixed
	 * @since Kunena
	 * @throws Exception
	 */
	public function getSmileyspaths()
	{
		$template = KunenaFactory::getTemplate();

		$selected = $this->getSmiley();

		$smileypath = $template->getSmileyPath();
		$files1     = (array) Folder::Files(JPATH_SITE . '/' . $smileypath, false, false, false, array('index.php', 'index.html'));
		$files1     = (array) array_flip($files1);

		foreach ($files1 as $key => &$path)
		{
			$path = $smileypath . $key;
		}

		$smileypath = 'media/kunena/emoticons/';
		$files2     = (array) Folder::Files(JPATH_SITE . '/' . $smileypath, false, false, false, array('index.php', 'index.html'));
		$files2     = (array) array_flip($files2);

		foreach ($files2 as $key => &$path)
		{
			$path = $smileypath . $key;
		}

		$smiley_images = $files1 + $files2;
		ksort($smiley_images);

		$smiley_list = array();

		foreach ($smiley_images as $file => $path)
		{
			$smiley_list[] = HTMLHelper::_('select.option', $path, $file);
		}

		$list = HTMLHelper::_('select.genericlist', $smiley_list, 'smiley_url', 'class="inputbox form-control" onchange="update_smiley(this.options[selectedIndex].value);" onmousemove="update_smiley(this.options[selectedIndex].value);"', 'value', 'text', !empty($selected->location) ? $smiley_images[$selected->location] : '');

		return $list;
	}

	/**
	 * @return  mixed|void
	 *
	 * @since Kunena
	 * @throws Exception
	 */
	public function getSmiley()
	{
		$db = Factory::getDBO();

		$id = $this->getState($this->getName() . '.id');

		if ($id)
		{
			$query = $db->getQuery(true);
			$query->select('*')
				->from($db->quoteName('#__kunena_smileys'))
				->where('id=' . $db->quote($id));
			$db->setQuery($query);

			try
			{
				$selected = $db->loadObject();
			}
			catch (RuntimeException $e)
			{
				Factory::getApplication()->enqueueMessage($e->getMessage());

				return;
			}

			return $selected;
		}

		return;
	}

	/**
	 * Method to auto-populate the model state.
	 * @since Kunena
	 * @throws Exception
	 */
	protected function populateState()
	{
		$this->context = 'com_kunena.admin.smiley';

		$app = Factory::getApplication();

		// Adjust the context to support modal layouts.
		$layout = $app->input->get('layout');

		if ($layout)
		{
			$this->context .= '.' . $layout;
		}

		$value = Factory::getApplication()->input->getInt('id');
		$this->setState($this->getName() . '.id', $value);
		$this->setState('item.id', $value);
	}
}
