<?php
/**
 * Kunena Component
 * @package     Kunena.Framework
 * @subpackage  Form
 *
 * @copyright   (C) 2008 - 2013 Kunena Team. All rights reserved.
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link        http://www.kunena.org
 **/
defined('_JEXEC') or die ();

jimport('joomla.form.formfield');

/**
 * Class JFormFieldKunenaVerifychecksum
 *
 * @since  3.1
 */
class JFormFieldKunenaVerifychecksum extends JFormField
{
	protected $type = 'KunenaVerifychecksum';

	/**
	 * Method to show list of modified files by comparing the checksum calculated during kunena installation
	 *
	 * @return HTML list
	 *
	 * @since  3.1
	 */
	protected  function getInput()
	{
		jimport('joomla.filesystem.file');

		if ( !JFile::exists(JPATH_SITE . '/components/com_kunena/template/checksum.txt') )
		{
			return;
		}

		$rows = file(JPATH_SITE . '/components/com_kunena/template/checksum.txt');
		$list_modifed = '<ul>';

		foreach ($rows as $row)
		{
			$line = explode(',', trim($row));
			$checksum = sha1_file($line[0]);

			if ( $checksum !== $line[1] )
			{
				$list_modifed .= '<li>' . $line[0] . '</li>';
			}
		}

		$list_modifed .= '</ul>';

		echo $list_modifed;
	}
}
