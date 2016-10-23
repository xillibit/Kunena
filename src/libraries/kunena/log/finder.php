<?php
/**
 * Kunena Component
 * @package       Kunena.Framework
 * @subpackage    Forum.Message
 *
 * @copyright     Copyright (C) 2008 - 2016 Kunena Team. All rights reserved.
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link          https://www.kunena.org
 **/
defined('_JEXEC') or die();

/**
 * Class KunenaLogFinder
 *
 * @since 5.0
 */
class KunenaLogFinder extends KunenaDatabaseObjectFinder
{
	/**
	 * @var string
	 * @since Kunena
	 */
	protected $table = '#__kunena_logs';

	/**
	 * Constructor.
	 * @since Kunena
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Filter by time.
	 *
	 * @param   JDate $starting Starting date or null if older than ending date.
	 * @param   JDate $ending   Ending date or null if newer than starting date.
	 *
	 * @return $this
	 * @since Kunena
	 */
	public function filterByTime(JDate $starting = null, JDate $ending = null)
	{
		if ($starting && $ending)
		{
			$this->query->where("a.time BETWEEN {$this->db->quote($starting->toUnix())} AND {$this->db->quote($ending->toUnix())}");
		}
		elseif ($starting)
		{
			$this->query->where("a.time > {$this->db->quote($starting->toUnix())}");
		}
		elseif ($ending)
		{
			$this->query->where("a.time <= {$this->db->quote($ending->toUnix())}");
		}

		return $this;
	}

	/**
	 * @param $condition
	 *
	 * @return $this
	 * @since Kunena
	 */
	public function innerJoin($condition)
	{
		$this->query->innerJoin($condition);

		return $this;
	}

	/**
	 * @param $columns
	 *
	 * @return $this
	 * @since Kunena
	 */
	public function select($columns)
	{
		$this->query->select($columns);

		return $this;
	}

	/**
	 * @param $columns
	 *
	 * @return $this
	 * @since Kunena
	 */
	public function group($columns)
	{
		$this->query->group($columns);

		return $this;
	}

	/**
	 * Get log entries.
	 *
	 * @return array|KunenaCollection
	 * @since Kunena
	 */
	public function find()
	{
		if ($this->skip)
		{
			return array();
		}

		$query = clone $this->query;
		$this->build($query);
		$query->select('a.*');
		$this->db->setQuery($query, $this->start, $this->limit);

		try
		{
			$results = new KunenaCollection((array) $this->db->loadObjectList('id'));
		}
		catch (JDatabaseExceptionExecuting $e)
		{
			KunenaError::displayDatabaseError($e);
		}

		return $results;
	}

	/**
	 * @param   JDatabaseQuery $query
	 *
	 * @since Kunena
	 */
	protected function build(JDatabaseQuery $query)
	{
	}
}
