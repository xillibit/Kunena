<?php
/**
 * Kunena Component
 * @package Kunena.Administrator.Template
 * @subpackage Categories
 *
 * @copyright (C) 2008 - 2014 Kunena Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.kunena.org
 **/
defined ( '_JEXEC' ) or die ();

/** @var KunenaAdminViewCategories $this */

JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
//JHtml::_('formbehavior.chosen', 'select');

$iconPath = JUri::root();
$this->document->addScriptDeclaration('
jQuery( document ).ready(function() {
  jQuery( "#rank_image" ).change(function() {
    var selected = jQuery( "#rank_image option:selected" ).text();
    jQuery("#rank_image_preview").attr("src","'.$iconPath.'/media/kunena/ranks/"+selected); 
  });
});');

?>

<div id="kunena" class="admin override">
	<div id="j-sidebar-container" class="span2">
		<div id="sidebar">
			<div class="sidebar-nav"><?php include KPATH_ADMIN.'/template/joomla30/common/menu.php'; ?></div>
		</div>
	</div>
	<div id="j-main-container" class="span10">
		<form action="<?php echo KunenaRoute::_('administrator/index.php?option=com_kunena&view=usertypes') ?>" method="post" id="adminForm" name="adminForm">
			<input type="hidden" name="task" value="save" />
      <input type="hidden" name="id" value="<?php echo !empty($this->types_object->id) ? $this->types_object->id : ''; ?>" />
			<?php echo JHtml::_( 'form.token' ); ?>

      <label>Name</label>
			<input type="text" name="usertype_name" value="<?php echo !empty($this->types_object->name) ? $this->types_object->name : ''; ?>"> <br />
      <label>Language string</label>
      <input type="text" name="usertype_languagestring" value="<?php echo !empty($this->types_object->title) ? $this->types_object->title : ''; ?>"><br />
      <label>Joomla! group id</label>
      <input type="text" name="usertype_groupid" value="<?php echo !empty($this->types_object) && $this->types_object->joomla_group_id>=0 ? $this->types_object->joomla_group_id : '' ?>"><br />
      <label>Affichage dans qui est en ligne</label>
      <input type="checkbox" name="display_whoisonline" value="1"><br />
      <label>Choix de l'image de rang associée</label>
      <?php echo $this->rankspath ?> <img id="rank_image_preview" src="" border="0" alt="" />
		</form>
	</div>
	<div class="pull-right small">
		<?php echo KunenaVersion::getLongVersionHTML(); ?>
	</div>
</div>
