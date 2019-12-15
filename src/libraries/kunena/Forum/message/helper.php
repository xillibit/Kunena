<?php
/**
 * Kunena Component
 *
 * @package       Kunena.Framework
 * @subpackage    Forum.Message
 *
 * @copyright     Copyright (C) 2008 - 2019 Kunena Team. All rights reserved.
 * @license       https://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link          https://www.kunena.org
 **/
defined('_JEXEC') or die();

use Joomla\CMS\Factory;

/**
 * Kunena Forum Message Helper Class
 *
 * @since Kunena
 */
abstract class KunenaForumMessageHelper
{
	/**
	 * @var KunenaForumMessage[]
	 * @since Kunena
	 */
	protected static $_instances = array();

	/**
	 * @var array
	 * @since Kunena
	 */
	protected static $_location = array();

	/**
	 * @param   bool|array|int  $ids        ids
	 * @param   string          $authorise  authorise
	 *
	 * @return KunenaForumMessage[]
	 * @since Kunena
	 * @throws null
	 * @throws Exception
	 */
	public static function getMessages($ids = false, $authorise = 'read')
	{
		if ($ids === false)
		{
			return self::$_instances;
		}
		elseif (is_array($ids))
		{
			$ids = array_unique($ids);
		}
		else
		{
			$ids = array($ids);
		}

		self::loadMessages($ids);

		$list = array();

		foreach ($ids as $id)
		{
			// TODO: authorisation needs topics to be loaded, make sure that they are! (performance increase)
			if (!empty(self::$_instances [$id]) && self::$_instances [$id]->isAuthorised($authorise, null))
			{
				$list [$id] = self::$_instances [$id];
			}
		}

		return $list;
	}

	/**
	 * @param   array  $ids  ids
	 *
	 * @return void
	 * @since Kunena
	 * @throws Exception
	 */
	protected static function loadMessages(array $ids)
	{
		foreach ($ids as $i => $id)
		{
			$id = intval($id);

			if (!$id || isset(self::$_instances [$id]))
			{
				unset($ids[$i]);
			}
		}

		if (empty($ids))
		{
			return;
		}

		$idlist = implode(',', $ids);
		$db     = Factory::getDBO();
		$query  = $db->getQuery(true);
		$query->select('m.*, t.message')
			->from($db->quoteName('#__kunena_messages', 'm'))
			->innerJoin($db->quoteName('#__kunena_messages_text', 't') . ' ON m.id = t.mesid')
			->where('m.id IN (' . $idlist . ')');
		$db->setQuery($query);

		try
		{
			$results = (array) $db->loadAssocList('id');
		}
		catch (JDatabaseExceptionExecuting $e)
		{
			KunenaError::displayDatabaseError($e);
		}

		foreach ($ids as $id)
		{
			if (isset($results[$id]))
			{
				$instance = new KunenaForumMessage($results[$id]);
				$instance->exists(true);
				self::$_instances [$id] = $instance;
			}
			else
			{
				self::$_instances [$id] = null;
			}
		}

		unset($results);
	}

	/**
	 * @param   mixed   $topic      topic
	 * @param   int     $start      start
	 * @param   int     $limit      limit
	 * @param   string  $ordering   ordering
	 * @param   int     $hold       hold
	 * @param   bool    $orderbyid  orderbyid
	 *
	 * @return KunenaForumMessage[]
	 * @since Kunena
	 * @throws Exception
	 */
	public static function getMessagesByTopic($topic, $start = 0, $limit = 0, $ordering = 'ASC', $hold = 0, $orderbyid = false)
	{
		$topic = KunenaForumTopicHelper::get($topic);

		if (!$topic->exists())
		{
			return array();
		}

		$total = $topic->getTotal();

		if ($start < 0)
		{
			$start = 0;
		}

		if ($limit < 1)
		{
			$limit = KunenaFactory::getConfig()->messages_per_page;
		}

		// If out of range, use last page
		if ($total < $start)
		{
			$start = intval($total / $limit) * $limit;
		}

		$ordering = strtoupper($ordering);

		if ($ordering != 'DESC')
		{
			$ordering = 'ASC';
		}

		return self::loadMessagesByTopic($topic->id, $start, $limit, $ordering, $hold, $orderbyid);
	}

	/**
	 * @param   int     $topic_id   topic id
	 * @param   int     $start      start
	 * @param   int     $limit      limit
	 * @param   string  $ordering   ordering
	 * @param   int     $hold       hold
	 * @param   bool    $orderbyid  orderbyid
	 *
	 * @return array
	 * @since Kunena
	 * @throws Exception
	 */
	protected static function loadMessagesByTopic($topic_id, $start = 0, $limit = 0, $ordering = 'ASC', $hold = 0, $orderbyid = false)
	{
		$db    = Factory::getDBO();
		$query = $db->getQuery(true);
		$query->select('m.*, t.message')
			->from($db->quoteName('#__kunena_messages', 'm'))
			->innerJoin($db->quoteName('#__kunena_messages_text', 't') . ' ON m.id = t.mesid')
			->where('m.thread = ' . $db->quote($topic_id))
			->andWhere('m.hold IN (' . $hold . ')')
			->order('m.time ' . $ordering);
		$query->setLimit($limit, $start);
		$db->setQuery($query);

		try
		{
			$results = (array) $db->loadAssocList('id');
		}
		catch (JDatabaseExceptionExecuting $e)
		{
			KunenaError::displayDatabaseError($e);
		}

		$location = ($orderbyid || $ordering == 'ASC') ? $start : KunenaForumTopicHelper::get($topic_id)->getTotal($hold) - $start - 1;
		$order    = ($ordering == 'ASC') ? 1 : -1;
		$list     = array();

		if (!empty($results))
		{
			foreach ($results as $id => $result)
			{
				$instance = new KunenaForumMessage($result);
				$instance->exists(true);
				self::$_instances [$id]             = $instance;
				$list[$orderbyid ? $id : $location] = $instance;
				$location                           += $order;
			}
		}

		unset($results);

		return $list;
	}

	/**
	 * @param   bool|array|int  $categories  categories
	 * @param   int             $limitstart  limitstart
	 * @param   int             $limit       limit
	 * @param   array           $params      params
	 *
	 * @return array
	 * @since Kunena
	 * @throws null
	 * @throws Exception
	 */
	public static function getLatestMessages($categories = false, $limitstart = 0, $limit = 0, $params = array())
	{
		$reverse     = isset($params['reverse']) ? (int) $params['reverse'] : 0;
		$orderby     = isset($params['orderby']) ? (string) $params['orderby'] : 'm.time DESC';
		$starttime   = isset($params['starttime']) ? (int) $params['starttime'] : 0;
		$mode        = isset($params['mode']) ? $params['mode'] : 'recent';
		$user        = isset($params['user']) ? $params['user'] : false;
		$where       = isset($params['where']) ? (string) $params['where'] : '';
		$childforums = isset($params['childforums']) ? (bool) $params['childforums'] : false;

		if ($limit < 1 && empty($params['nolimit']))
		{
			$view = Factory::getApplication()->input->getCmd('view');

			if ($view == 'search')
			{
				$limit = KunenaFactory::getConfig()->messages_per_page_search;
			}
			elseif ($view == 'topics')
			{
				$limit = KunenaFactory::getConfig()->threads_per_page;
			}
			else
			{
				$limit = KunenaFactory::getConfig()->messages_per_page;
			}
		}

		$db    = Factory::getDBO();
		$query = $db->getQuery(true);
		$query->select('m.*, t.message')
			->from('#__kunena_messages AS m')
			->innerJoin('#__kunena_messages_text AS t ON m.id = t.mesid')
			->order($orderby);

		$authorise = 'read';
		$hold      = 'm.hold = 0';
		$userfield = 'm.userid';

		switch ($mode)
		{
			case 'unapproved':
				$authorise = 'approve';
				$hold      = "m.hold = 1";
				break;
			case 'deleted':
				$authorise = 'undelete';
				$hold      = "m.hold >= 2";
				break;
			case 'mythanks':
				$userfield = 'th.userid';
				$query->innerJoin($db->quoteName('#__kunena_thankyou', 'th') . ' ON m.id = th.postid');
				break;
			case 'thankyou':
				$userfield = 'th.targetuserid';
				$query->innerJoin($db->quoteName('#__kunena_thankyou', 'th') . ' ON m.id = th.postid');
				break;
			case 'recent':
			default:
		}

		if (is_array($categories) && in_array(0, $categories))
		{
			$categories = false;
		}

		$categories = KunenaForumCategoryHelper::getCategories($categories, $reverse, 'topic.' . $authorise);

		if ($childforums)
		{
			$categories += KunenaForumCategoryHelper::getChildren($categories, -1, array('action' => 'topic.' . $authorise));
		}

		$catlist = array();

		foreach ($categories as $category)
		{
			$catlist += $category->getChannels();
		}

		if (empty($catlist))
		{
			return array(0, array());
		}

		$allowed = implode(',', array_keys($catlist));
		$query->where('m.catid IN (' . $allowed . ')');

		$query->where($hold);

		if ($user)
		{
			$query->where($db->quoteName($userfield) . ' = ' . $db->quote($user));
		}

		// Negative time means no time
		if ($starttime == 0)
		{
			$starttime = KunenaFactory::getSession()->lasttime;
		}
		elseif ($starttime > 0)
		{
			$starttime = Factory::getDate()->toUnix() - ($starttime * 3600);
		}

		if ($starttime > 0)
		{
			$query->where('m.time > ' . $db->quote($starttime));
		}

		if ($where)
		{
			$query->where($where);
		}

		$cquery = clone $query;
		$cquery->clear('select')
			->clear('order')
			->select('COUNT(*)');
		$db->setQuery($cquery);

		try
		{
			$total = (int) $db->loadResult();
		}
		catch (JDatabaseExceptionExecuting $e)
		{
			KunenaError::displayDatabaseError($e);

			return array(0, array());
		}

		if (!$total)
		{
			return array(0, array());
		}

		// If out of range, use last page
		if ($limit && $total < $limitstart)
		{
			$limitstart = intval($total / $limit) * $limit;
		}

		$query->setLimit($limit, $limitstart);
		$db->setQuery($query);

		try
		{
			$results = $db->loadAssocList();
		}
		catch (JDatabaseExceptionExecuting $e)
		{
			KunenaError::displayDatabaseError($e);

			return array(0, array());
		}

		$messages = array();

		if (!empty($results))
		{
			foreach ($results as $result)
			{
				$instance = new KunenaForumMessage($result);
				$instance->exists(true);
				self::$_instances [$instance->id] = $instance;
				$messages[$instance->id]          = $instance;
			}
		}

		unset($results);

		return array($total, $messages);
	}

	/**
	 * @param   int          $mesid      mesid
	 * @param   null|string  $direction  direction
	 * @param   null|array   $hold       hold
	 *
	 * @return integer
	 * @since Kunena
	 * @throws Exception
	 */
	public static function getLocation($mesid, $direction = null, $hold = null)
	{
		if (is_null($direction))
		{
			$direction = KunenaUserHelper::getMyself()->getMessageOrdering();
		}

		if (!$hold)
		{
			$me           = KunenaUserHelper::getMyself();
			$mes_instance = self::get($mesid);
			$hold         = KunenaAccess::getInstance()->getAllowedHold($me->userid, $mes_instance->catid, false);
		}

		if (!isset(self::$_location [$mesid]))
		{
			self::loadLocation(array($mesid));
		}

		$location = self::$_location [$mesid];
		$count    = 0;

		foreach ($location->hold as $meshold => $values)
		{
			if (isset($hold[$meshold]))
			{
				$count += $values[$direction == 'asc' ? 'before' : 'after'];

				if ($direction == 'both')
				{
					$count += $values['before'];
				}
			}
		}

		return $count;
	}

	/**
	 * Returns KunenaForumMessage object.
	 *
	 * @param   null  $identifier  The message to load - Can be only an integer.
	 * @param   bool  $reload      reload
	 *
	 * @return KunenaForumMessage    The message object.
	 * @since Kunena
	 * @throws Exception
	 */
	public static function get($identifier = null, $reload = false)
	{
		if ($identifier instanceof KunenaForumMessage)
		{
			return $identifier;
		}

		$id = (int) $identifier;

		if ($id < 1)
		{
			return new KunenaForumMessage;
		}

		if (empty(self::$_instances[$id]))
		{
			$instance = new KunenaForumMessage;

			// Only load messages which haven't been preloaded before (including missing ones).
			$instance->load(!array_key_exists($id, self::$_instances) ? $id : null);
			$instance->id          = $id;
			self::$_instances[$id] = $instance;
		}
		elseif ($reload)
		{
			self::$_instances[$id]->load();
		}

		return self::$_instances[$id];
	}

	/**
	 * @param   array|string  $mesids  mesid
	 *
	 * @return void
	 * @since Kunena
	 * @throws Exception
	 */
	public static function loadLocation($mesids)
	{
		// NOTE: if you already know the location using this code just takes resources
		if (!is_array($mesids))
		{
			$mesids = explode(',', $mesids);
		}

		$ids = array();

		foreach ($mesids as $id)
		{
			if ($id instanceof KunenaForumMessage)
			{
				$id = $id->id;
			}
			else
			{
				$id = (int) $id;
			}

			if (!isset(self::$_location [$id]))
			{
				$ids[$id]                    = $id;
				self::$_location [$id]       = new stdClass;
				self::$_location [$id]->hold = array('before' => 0, 'after' => 0);
			}
		}

		if (empty($ids))
		{
			return;
		}

		$idlist = implode(',', $ids);
		$db     = Factory::getDBO();
		$query  = $db->getQuery(true);
		$query->select('m.id, mm.hold, m.catid AS category_id, m.thread AS topic_id,
				SUM(mm.time<m.time) AS before_count,
				SUM(mm.time>m.time) AS after_count'
		)
			->from($db->quoteName('#__kunena_messages', 'm'))
			->innerJoin($db->quoteName('#__kunena_messages', 'mm') . ' ON m.thread = mm.thread')
			->where('m.id IN (' . $idlist . ')')
			->group('m.id, mm.hold');
		$db->setQuery($query);

		try
		{
			$results = (array) $db->loadObjectList();
		}
		catch (JDatabaseExceptionExecuting $e)
		{
			KunenaError::displayDatabaseError($e);
		}

		if (!empty($results))
		{
			foreach ($results as $result)
			{
				$instance = self::$_location [$result->id];

				if (!isset($instance->id))
				{
					$instance->id                    = $result->id;
					$instance->category_id           = $result->category_id;
					$instance->topic_id              = $result->topic_id;
					self::$_location [$instance->id] = $instance;
				}

				$instance->hold[$result->hold] = array('before' => $result->before_count, 'after' => $result->after_count);
			}
		}
	}

	/**
	 * Free up memory by cleaning up all cached items.
	 *
	 * @return void
	 * @since Kunena
	 */
	public static function cleanup()
	{
		self::$_instances = array();
		self::$_location  = array();
	}

	/**
	 * @param   bool|array|int  $topicids  topicids
	 *
	 * @return boolean|integer
	 * @since Kunena
	 * @throws Exception
	 */
	public static function recount($topicids = false)
	{
		$db = Factory::getDBO();

		if (is_array($topicids))
		{
			$where = 'WHERE m.thread IN (' . implode(',', $topicids) . ')';
		}
		elseif ((int) $topicids)
		{
			$where = 'WHERE m.thread = ' . $db->quote((int) $topicids);
		}
		else
		{
			$where = '';
		}

		// Update catid in all messages
		$query = $db->getQuery(true);
		$query->update($db->quoteName('#__kunena_messages', 'm'))
			->innerJoin($db->quoteName('#__kunena_attachments', 'tt') . ' ON tt.id = m.thread')
			->set('m.catid = tt.category_id ' . $where);
		$db->setQuery($query);

		try
		{
			$db->execute();
		}
		catch (JDatabaseExceptionExecuting $e)
		{
			KunenaError::displayDatabaseError($e);

			return false;
		}

		return $db->getAffectedRows();
	}

	/**
	 * @param   array  $ids  ids
	 *
	 * @return array|boolean
	 * @since 5.0.3
	 * @throws Exception
	 */
	public static function getMessagesByTopics(array $ids)
	{
		if (empty($ids))
		{
			return false;
		}

		$db = Factory::getDBO();

		$idlist = implode(',', $ids);
		$query  = $db->getQuery(true);
		$query->select('m.*, t.message')
			->from($db->quoteName('#__kunena_messages', 'm'))
			->innerJoin($db->quoteName('#__kunena_messages_text', 't') . ' ON m.id = t.mesid')
			->where('m.thread IN (' . $idlist . ')')
			->andWhere('m.hold = 0');
		$db->setQuery($query);

		try
		{
			$results = (array) $db->loadAssocList('id');
		}
		catch (JDatabaseExceptionExecuting $e)
		{
			KunenaError::displayDatabaseError($e);
		}

		return $results;
	}

	/**
	 * Get last IP address used by the user
	 *
	 * @param   int  $userid
	 *
	 * @return bool|mixed
	 * @since 6.0.0
	 * @throws Exception
	 */
	public static function getLastUserIP(int $userid)
	{
		if (!$userid)
		{
			return false;
		}

		$db = Factory::getDBO();

		$query = $db->getQuery(true);
		$query->select('ip')
			->from($db->quoteName('#__kunena_messages'))
			->where('userid=' . $userid)
			->group('ip')
			->order('time DESC');
		$query->setLimit(1);
		$db->setQuery($query);

		try
		{
			$ip = $db->loadResult();
		}
		catch (JDatabaseExceptionExecuting $e)
		{
			KunenaError::displayDatabaseError($e);
		}

		return $ip;
	}
}
