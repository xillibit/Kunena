<?php
/**
 * Kunena Component
 * @package Kunena.Administrator.Template.Joomla30
 * @subpackage Layouts.Attachment
 *
 * @copyright (C) 2008 - 2016 Kunena Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.kunena.org
 **/
defined ( '_JEXEC' ) or die ();

/** @var KunenaAttachment $attachment */
$attachment = $this->attachment;

if ($attachment->protected && !KunenaFactory::getConfig()->access_component)
{
	$url_href = 'forum/attachment/' . $attachment->id;
	$url_href2 = JUri::root() . $url_href ;
}
elseif ($attachment->protected)
{
	$url_href = $attachment->getUrl();
}
else
{
	$url_href = JUri::root() . $attachment->getUrl();
}

if ($attachment->protected&& !KunenaFactory::getConfig()->access_component) {
	$icon = 'icon-image';
}
else
{
	$icon = 'icon-flag-2';
}
?>

<?php if ($attachment->protected && !KunenaFactory::getConfig()->access_component) : ?>
	<a href="<?php echo $url_href2; ?>" title="<?php echo $attachment->getFilename(); ?>">
<?php else : ?>
	<a href="<?php echo $url_href; ?>" title="<?php echo $attachment->getFilename(); ?>">
		<?php endif; ?>

		<?php

		if ($attachment->isImage() && !$attachment->protected)
		{
			echo '<img src="' . JUri::root() . $attachment->getUrl(true) . ' " height="40" width="40" />';
		}
		elseif ($attachment->isImage() && $attachment->protected && KunenaFactory::getConfig()->access_component)
		{
			echo '<img src="' . JUri::root() . $url_href . ' " height="40" width="40" />';
		}
		elseif ($attachment->isImage())
		{
			echo '<i class="'. $icon .' icon-big"></i>';
		}
		else
		{
			echo '<i class="icon-flag-2 icon-big"></i>';
		}
		?>
	</a>
