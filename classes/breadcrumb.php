<?php

namespace Breadcrumb;

/**
 * Breadcrumb solution
 *
 * @version    0.2
 * @author     Daniel Polito - @dbpolito
 */

class Breadcrumb {

	protected static $breadcrumb = array();

	protected static $auto_render = true;

	protected static $use_lang = false;

	protected static $home = array('name' => 'Home', 'link' => '/');

	protected static $template = array(
		'wrapper_start' => '<ul class="breadcrumb">',
		'wrapper_end' => ' </ul>',
		'item_start' => '<li>',
		'item_start_active' => '<li class="active">',
		'item_end' => '</li>',
		'divider' => '<span class="divider">/</span>'
	);

	/**
	 * Init
	 *
	 * Loads in the config and sets the variables
	 *
	 * @access	public
	 * @return	void
	 */
	public static function _init()
	{
		\Config::load('breadcrumb', 'breadcrumb');

		if (\Config::get('breadcrumb.auto_render', static::$auto_render) === true)
		{
			static::initialise();
		}
	}

	/**
	 * Init
	 *
	 * Initialise breadcrumb based on URI segments
	 *
	 * @access	protected
	 * @return	void
	 */
	protected static function initialise()
	{
		$home = \Config::get('breadcrumb.home', static::$home);
		$use_lang = \Config::get('breadcrumb.use_lang', static::$use_lang);

		static::set($home['name'], $home['link']);

		$segments = \Uri::segments();
		$link     = '';

		foreach ($segments as $segment)
		{
			if (preg_match('/^([0-9])+$/', $segment) > 0 or $segment === 'index')
			{
				continue;
			}

			$link .= '/'.$segment;

			if ($use_lang === true)
			{
				static::set(\Lang::get($segment), $link);
			}
			else
			{
				static::set(\Str::ucwords(str_replace('_', ' ', $segment)), $link);
			}
		}
	}

	/**
	 * Set an item on breadcrumb static property
	 *
	 * @param string $title Display Link
	 * @param string $link Relative Link
	 * @param intenger $index Index to replace itens
	 * @return void
	 */
	public static function set($title, $link = "", $index = null, $overwrite = true)
	{
		// trim the bastard
		$title = trim($title);

		// if link is empty user the current
		$link = ($link === '') ? \Uri::current() : $link;

		if (is_null($index))
		{
			static::$breadcrumb[] = array('title' => $title, 'link' => $link);
		}
		else
		{
			if ($overwrite === true)
			{
				static::$breadcrumb[$index] = array('title' => $title, 'link' => $link);
			}
			else
			{
				static::append($title, $link, $index);
			}
		}
	}

	/**
	 * Unset an item on breadcrumb static property
	 *
	 * @param intenger $index
	 * @return void
	 */
	public static function remove($index = null)
	{
		unset(static::$breadcrumb[$index]);
		static::sort_array(static::$breadcrumb);
	}

	/**
	 * Count number of items on breadcrumb
	 *
	 * @return int
	 */
	public static function count()
	{
		return count(static::$breadcrumb);
	}

	/**
	 * Create Html structure for Breadcrumb
	 *
	 * @return string The html
	 */
	public static function create_links()
	{
		if (empty(static::$breadcrumb) or count(static::$breadcrumb) < 2)
		{
			return '';
		}

		$template = \Config::get('breadcrumb.template', static::$template);

		$output = $template["wrapper_start"];

		for ($i=0,$total=static::count(); $i < $total; $i++)
		{
			$is_last = ($i === $total - 1);

			$output .= ($is_last) ? $template["item_start_active"] : $template["item_start"];
			$output .= ($is_last) ? static::$breadcrumb[$i]["title"] : \Html::anchor(static::$breadcrumb[$i]["link"], static::$breadcrumb[$i]["title"]);
			$output .= ($is_last) ? "" : $template["divider"];
			$output .= $template["item_end"];
		}

		$output .= $template['wrapper_end'];

		return $output;
	}

	/**
	 * Set an item on breadcrumb without overwrite the index
	 *
	 * @param string $title Display Link
	 * @param string $link Relative Link
	 * @param intenger $index Index to replace itens
	 * @return void
	 */
	protected static function append($title, $link = "", $index = null)
	{
		$breadcrumb = array();

		if (is_null($index) or $index > count(static::$breadcrumb) - 1)
		{
			static::set($title, $link);
		}

		for ($i=0,$total=count(static::$breadcrumb); $i < $total; $i++)
		{
			if ($i === $index)
			{
				$breadcrumb[] = array('title' => $title, 'link' => $link);
				$i--;
				$index = null;
			}
			else
			{
				$breadcrumb[] = static::$breadcrumb[$i];
			}
		}

		static::$breadcrumb = $breadcrumb;
	}

	/**
	 * Sort the index of an array
	 *
	 * @return &$array Array to sort
	 */
	protected static function sort_array(&$array)
	{
		if(is_array($array))
		{
			$aux = array();
			foreach ($array as $value)
			{
				$aux[] = $value;
			}

			$array = $aux;
		}
	}
}