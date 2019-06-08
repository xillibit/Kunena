<?php
/**
 * Kunena Component
 * @package         Kunena.Template.Aurelia
 * @subpackage      Layout.Widget
 *
 * @copyright       Copyright (C) 2008 - 2019 Kunena Team. All rights reserved.
 * @license         https://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link            https://www.kunena.org
 **/
defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

$label = Text::_("COM_KUNENA_BUTTON_{$this->scope}_{$this->name}");
$title = Text::_("COM_KUNENA_BUTTON_{$this->scope}_{$this->name}_LONG");

if ($title == "COM_KUNENA_BUTTON_{$this->scope}_{$this->name}_LONG")
{
	$title = '';
}

$modal   = isset($this->modal) ? 'data-toggle="modal" data-backdrop="false"' : '';
$right   = isset($this->pullright) ? ' float-right' : '';
$id      = isset($this->id) ? 'id="' . $this->id . '"' : '';
$success = !empty($this->success) ? ' btn-outline-success' : '';
$primary = !empty($this->primary) ? ' btn-outline-primary' : '';
$normal  = !empty($this->normal) ? 'btn-small dropdown-item' : 'btn btn-outline-primary border';
$icon    = $this->icon;
?>

<a <?php echo $id; ?> class="<?php echo $normal . $primary . $success . $right . ' kbutton-' . $this->name; ?>"
                      href="<?php echo $this->url; ?>" rel="nofollow"
                      title="<?php echo $title; ?>" name="<?php echo $this->name; ?>" <?php echo $modal; ?>>
	<?php
	if (!empty($icon))
		:
		?>
		<i class="<?php echo $icon; ?>"></i>
	<?php endif; ?>
	<?php echo $label; ?>
</a>
