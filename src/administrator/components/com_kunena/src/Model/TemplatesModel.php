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
use Joomla\CMS\Client\ClientHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Pagination\Pagination;
use Kunena\Forum\Libraries\Factory\KunenaFactory;
use Kunena\Forum\Libraries\Template\Template;
use Kunena\Forum\Libraries\Template\TemplateHelper;
use Kunena\Forum\Libraries\User\KunenaUserHelper;
use stdClass;

/**
 * Templates Model for Kunena
 *
 * @since   Kunena 2.0
 */
class TemplatesModel extends AdminModel
{
	/**
	 * @param   array  $config  config
	 *
	 * @since   Kunena 6.0
	 *
	 * @throws  Exception
	 */
	public function __construct($config = [])
	{
		parent::__construct($config);
		$this->app    = Factory::getApplication();
		$this->me     = KunenaUserHelper::getMyself();
		$this->config = KunenaFactory::getConfig();
	}

	/**
	 * @see     \Joomla\CMS\MVC\Model\FormModel::getForm()
	 *
	 * @param   array  $data      data
	 * @param   bool   $loadData  loadData
	 *
	 * @return  boolean|mixed
	 *
	 * @since   Kunena 6.0
	 *
	 * @throws  Exception
	 */
	public function getForm($data = [], $loadData = true)
	{
		// Load the configuration definition file.
		$template = $this->getState('template');
		$xml      = Template::getInstance($template)->getConfigXml();

		// Get the form.
		$form = $this->loadForm('com_kunena_template', $xml, ['control' => 'jform', 'load_data' => $loadData, 'file' => false], true, '//config');

		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	/**
	 * @return  array
	 *
	 * @since   Kunena 6.0
	 *
	 * @throws  Exception
	 */
	public function getTemplates()
	{
		// Get template xml file info
		$rows = TemplateHelper::parseXmlFiles();

		// Set dynamic template information
		foreach ($rows as $row)
		{
			$row->published = TemplateHelper::isDefault($row->directory);
		}

		$this->setState('list.total', count($rows));

		if ($this->getState('list.limit'))
		{
			$rows = array_slice($rows, $this->getState('list.start'), $this->getState('list.limit'));
		}

		return $rows;
	}

	/**
	 * @return  boolean|stdClass
	 *
	 * @since   Kunena 6.0
	 */
	public function getTemplatedetails()
	{
		$template = $this->app->getUserState('kunena.edit.template');
		$details  = TemplateHelper::parseXmlFile($template);

		if (empty($template))
		{
			$template = $this->getState('template');
			$details  = TemplateHelper::parseXmlFile($template);
		}

		return $details;
	}

	/**
	 * @return  boolean|void|string
	 *
	 * @since   Kunena 6.0
	 */
	public function getFileLessParsed()
	{
		$template = $this->app->getUserState('kunena.templatename');
		$filename = $this->app->getUserState('kunena.editless.filename');

		$content = file_get_contents(KPATH_SITE . '/template/' . $template . '/assets/less/' . $filename);
		$content = htmlspecialchars($content, ENT_COMPAT, 'UTF-8');

		if ($content === false)
		{
			return;
		}

		return $content;
	}

	/**
	 * @return  boolean|void|string
	 *
	 * @since   Kunena 6.0
	 */
	public function getFileContentParsed()
	{
		$template = $this->app->getUserState('kunena.templatename');
		$filename = $this->app->getUserState('kunena.editcss.filename');
		$content  = file_get_contents(KPATH_SITE . '/template/' . $template . '/assets/css/' . $filename);

		if ($content === false)
		{
			return;
		}

		$content = htmlspecialchars($content, ENT_COMPAT, 'UTF-8');

		return $content;
	}

	/**
	 * @return  mixed
	 *
	 * @since   Kunena 6.0
	 */
	public function getFTPcredentials()
	{
		// Set FTP credentials, if given
		$ftp = ClientHelper::setCredentialsFromRequest('ftp');

		return $ftp;
	}

	/**
	 * @return  mixed
	 *
	 * @since   Kunena 6.0
	 */
	public function getPagination()
	{
		// Get a storage key.
		$store = $this->getStoreId('getPagination');

		// Try to load the data from internal storage.
		if (isset($this->cache[$store]))
		{
			return $this->cache[$store];
		}

		// Create the pagination object.
		$limit = (int) $this->getState('list.limit') - (int) $this->getState('list.links');
		$page  = new Pagination($this->getTotal(), $this->getStart(), $limit);

		// Add the object to the internal cache.
		$this->cache[$store] = $page;

		return $this->cache[$store];
	}

	/**
	 * @param   string  $id  id
	 *
	 * @return  string
	 *
	 * @since   Kunena 6.0
	 */
	protected function getStoreId($id = '')
	{
		// Add the list state to the store id.
		$id .= ':' . $this->getState('list.start');
		$id .= ':' . $this->getState('list.limit');
		$id .= ':' . $this->getState('list.ordering');
		$id .= ':' . $this->getState('list.direction');

		return md5($this->context . ':' . $id);
	}

	/**
	 * @return  integer
	 *
	 * @since   Kunena 6.0
	 */
	public function getTotal()
	{
		return $this->getState('list.total');
	}

	/**
	 * @return  integer
	 *
	 * @since   Kunena 6.0
	 */
	public function getStart()
	{
		return $this->getState('list.start');
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * @return  void
	 *
	 * @since   Kunena 6.0
	 *
	 * @throws  Exception
	 */
	protected function populateState()
	{
		$this->context = 'com_kunena.admin.templates';

		$app = Factory::getApplication();

		// Adjust the context to support modal layouts.
		$layout = $app->input->get('layout');

		if ($layout)
		{
			$this->context .= '.' . $layout;
		}

		// Edit state information
		$value = $this->getUserStateFromRequest($this->context . '.edit', 'name', '', 'cmd');
		$this->setState('template', $value);

		if (empty($this->app->getUserState('kunena.edit.templatename')))
		{
			$this->app->setUserState('kunena.edit.templatename', $value);
		}

		// List state information
		$value = $this->getUserStateFromRequest($this->context . '.list.limit', 'limit', $this->app->get('list_limit'), 'int');
		$this->setState('list.limit', $value);

		$value = $this->getUserStateFromRequest($this->context . '.list.ordering', 'filter_order', 'ordering', 'cmd');
		$this->setState('list.ordering', $value);

		$value = $this->getUserStateFromRequest($this->context . '.list.start', 'limitstart', 0, 'int');
		$this->setState('list.start', $value);
	}

	/**
	 * @param   string  $key        key
	 * @param   string  $request    request
	 * @param   null    $default    default
	 * @param   string  $type       type
	 * @param   bool    $resetPage  resetPage
	 *
	 * @return  mixed|null
	 *
	 * @since   Kunena 6.0
	 *
	 * @throws  Exception
	 */
	public function getUserStateFromRequest($key, $request, $default = null, $type = 'none', $resetPage = true)
	{
		$app       = Factory::getApplication();
		$input     = $app->input;
		$old_state = $app->getUserState($key);
		$cur_state = ($old_state !== null) ? $old_state : $default;
		$new_state = $input->get($request, null, $type);

		if (($cur_state != $new_state) && ($resetPage))
		{
			$input->set('limitstart', 0);
		}

		// Save the new value only if it is set in this request.
		if ($new_state !== null)
		{
			$app->setUserState($key, $new_state);
		}
		else
		{
			$new_state = $cur_state;
		}

		return $new_state;
	}

	/**
	 * @see     \Joomla\CMS\MVC\Model\FormModel::loadFormData()
	 *
	 * @return  array|mixed
	 *
	 * @since   Kunena 6.0
	 *
	 * @throws  Exception
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = Factory::getApplication()->getUserState('com_kunena.edit.template.data', []);

		if (empty($data))
		{
			$template = $this->getState('template');
			$data     = Template::getInstance($template)->params->toArray();
		}

		return $data;
	}
}
