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

use Joomla\CMS\MVC\Model\AdminModel;
use function defined;

/**
 * Stats Model for Kunena
 *
 * @since   Kunena 2.0
 */
class StatsModel extends AdminModel
{
	/**
	 * @inheritDoc
	 *
	 * @param   array    $data     data
	 * @param   boolean  $loadData load data
	 *
	 * @return void
	 *
	 * @since  Kunena 6.0
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// TODO: Implement getForm() method.
	}
}
