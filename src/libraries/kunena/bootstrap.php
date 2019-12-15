<?php
/**
 * Kunena Component
 *
 * @package         Kunena.Framework
 * @subpackage      Integration
 *
 * @copyright       Copyright (C) 2008 - 2019 Kunena Team. All rights reserved.
 * @license         https://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link            https://www.kunena.org
 **/
defined('_JEXEC') or die;

if (!class_exists('JLoader'))
{
	return;
}

// Define Kunena framework path.
define('KPATH_FRAMEWORK', __DIR__);

// Register the Joomla compatibility layer.
JLoader::registerPrefix('KunenaCompat', KPATH_FRAMEWORK . '/Compat/joomla');

// Register the library base path for Kunena Framework.
JLoader::registerPrefix('Kunena', KPATH_FRAMEWORK);

// Give access to all Kunena tables.
Joomla\CMS\Table\Table::addIncludePath(KPATH_FRAMEWORK . '/Tables');

// Give access to all Kunena JHtml functions.
Joomla\CMS\HTML\HTMLHelper::addIncludePath(KPATH_FRAMEWORK . '/Html/html');

// Give access to all Kunena form fields.
Joomla\CMS\Form\Form::addFieldPath(KPATH_FRAMEWORK . '/Form/fields');

// Register classes where the names have been changed to fit the autoloader rules.
JLoader::register('KunenaAccess', KPATH_FRAMEWORK . '/access.php');
JLoader::register('KunenaConfig', KPATH_FRAMEWORK . '/config.php');
JLoader::register('KunenaController', KPATH_FRAMEWORK . '/controller.php');
JLoader::register('KunenaDate', KPATH_FRAMEWORK . '/date.php');
JLoader::register('KunenaError', KPATH_FRAMEWORK . '/error.php');
JLoader::register('KunenaException', KPATH_FRAMEWORK . '/exception.php');
JLoader::register('KunenaFactory', KPATH_FRAMEWORK . '/factory.php');
JLoader::register('KunenaInstaller', KPATH_FRAMEWORK . '/installer.php');
JLoader::register('KunenaLogin', KPATH_FRAMEWORK . '/login.php');
JLoader::register('KunenaModel', KPATH_FRAMEWORK . '/model.php');
JLoader::register('KunenaProfiler', KPATH_FRAMEWORK . '/profiler.php');
JLoader::register('KunenaSession', KPATH_FRAMEWORK . '/session.php');
JLoader::register('KunenaTree', KPATH_FRAMEWORK . '/tree.php');
JLoader::register('KunenaView', KPATH_FRAMEWORK . '/view.php');
JLoader::register('KunenaAvatar', KPATH_FRAMEWORK . '/Integration/avatar.php');
JLoader::register('KunenaPrivate', KPATH_FRAMEWORK . '/Integration/private.php');
JLoader::register('KunenaProfile', KPATH_FRAMEWORK . '/Integration/profile.php');
JLoader::register('KunenaPlugins', KPATH_FRAMEWORK . '/Integration/plugins.php');
JLoader::register('KunenaForumAnnouncement', KPATH_FRAMEWORK . '/Forum/announcement/announcement.php');
JLoader::register('KunenaForumCategory', KPATH_FRAMEWORK . '/Forum/category/category.php');
JLoader::register('KunenaForumCategoryUser', KPATH_FRAMEWORK . '/Forum/category/user/user.php');
JLoader::register('KunenaForumMessage', KPATH_FRAMEWORK . '/Forum/message/message.php');
JLoader::register('KunenaForumMessageThankyou', KPATH_FRAMEWORK . '/Forum/message/thankyou/thankyou.php');
JLoader::register('KunenaForumTopic', KPATH_FRAMEWORK . '/Forum/topic/topic.php');
JLoader::register('KunenaForumTopicPoll', KPATH_FRAMEWORK . '/Forum/topic/poll/poll.php');
JLoader::register('KunenaForumTopicUser', KPATH_FRAMEWORK . '/Forum/topic/user/user.php');
JLoader::register('KunenaForumTopicUserRead', KPATH_FRAMEWORK . '/Forum/topic/user/read/read.php');
JLoader::register('KunenaForumTopicRate', KPATH_FRAMEWORK . '/Forum/topic/rate/rate.php');
JLoader::register('Nbbc\BBCode', KPATH_FRAMEWORK . '/External/nbbc/src/BBCode.php');
JLoader::register('Nbbc\BBCodeLexer', KPATH_FRAMEWORK . '/External/nbbc/src/BBCodeLexer.php');
JLoader::register('Nbbc\BBCodeLibrary', KPATH_FRAMEWORK . '/External/nbbc/src/BBCodeLibrary.php');
JLoader::register('Nbbc\Debugger', KPATH_FRAMEWORK . '/External/nbbc/src/Debugger.php');
JLoader::register('Nbbc\EmailAddressValidator', KPATH_FRAMEWORK . '/External/nbbc/src/EmailAddressValidator.php');
JLoader::register('Nbbc\Profiler', KPATH_FRAMEWORK . '/External/nbbc/src/Profiler.php');
