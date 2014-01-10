<?php
/**
 * Part of Windwalker project. 
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Windwalker\Model\Filter;

use JDatabaseQueryElement;

/**
 * Class FilterHelper
 *
 * @since 1.0
 */
class SearchHelper extends AbstractFilterHelper
{
	/**
	 * execute
	 *
	 * @param \JDatabaseQuery $query
	 * @param array           $searches
	 *
	 * @return  \JDatabaseQuery
	 */
	public function execute(\JDatabaseQuery $query, $searches = array())
	{
		$searchValue = array();
		\AK::show($searches);
		foreach ($searches as $field => $value)
		{
			if (!empty($this->handler[$field]) && is_callable($this->handler[$field]))
			{
				$condition = call_user_func_array($this->handler[$field], array($query, $field, $value));
			}
			else
			{
				$handler = $this->defaultHandler;

				/** @see SearchHelper::registerDefaultHandler() */
				$condition = $handler($query, $field, $value);
			}

			if ($condition)
			{
				$searchValue[] = $condition;
			}
		}

		if (count($searchValue))
		{
			$query->where(new JDatabaseQueryElement('()', $searchValue, " \nOR "));
		}

		return $query;
	}

	/**
	 * getDefaultHandler
	 *
	 * @return  callable
	 */
	protected function registerDefaultHandler()
	{
		/**
		 * Default handler closure.
		 *
		 * @param \JDatabaseQuery $query
		 * @param string          $field
		 * @param string          $value
		 *
		 * @return  \JDatabaseQuery
		 */
		return function(\JDatabaseQuery $query, $field, $value)
		{
			if ($value && $field != '*')
			{
				return $query->quoteName($field) . ' LIKE ' . $query->quote('%' . $value . '%');
			}

			return null;
		};
	}
}