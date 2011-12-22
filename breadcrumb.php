<?php
/**
 * Created by dbpolito | contato@dbpolito.net
 *
 */

class Breadcrumb {

	protected static $breadcrumb = array();

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
		$config = \Config::load('breadcrumb');
		static::$home = $config['home'];
		static::$template = array_merge(static::$template, $config['template']);

		static::initialise();
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
		static::set_breadcrumb(static::$home['name'], static::$home['link']);

		$segments = \Uri::segments();
		$link     = '';

		foreach ($segments as $segment)
		{
			if (preg_match('/^([0-9])+$/', $segment) > 0)
			{
				continue;
			}

			$link .= '/'.$segment;
			static::set_breadcrumb(\Str::ucwords(str_replace('_', ' ', $segment)), $link);
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
	public static function set_breadcrumb($title, $link = "", $index = null)
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
			static::$breadcrumb[$index] = array('title' => $title, 'link' => $link);
		}
	}

	/**
	 * Unset an item on breadcrumb static property
	 *
	 * @param intenger $index
	 * @return void
	 */
	public static function unset_breadcrumb($index = null)
	{
		unset(static::$breadcrumb[$index]);
		$this->sort_array(static::$breadcrumb);
	}

	/**
	 * Create Html structure for Breadcrumb
	 *
	 * @return string The html
	 */
	public static function create_links()
	{
		if (empty(static::$breadcrumb))
		{
			return '';
		}

		$output = static::$template["wrapper_start"];

		for ($i=0,$total=count(static::$breadcrumb); $i < $total; $i++)
		{
			$is_last = ($i === $total - 1);

			$output .= ($is_last) ? static::$template["item_start_active"] : static::$template["item_start"];
			$output .= ($is_last) ? static::$breadcrumb[$i]["title"] : \Html::anchor(static::$breadcrumb[$i]["link"], static::$breadcrumb[$i]["title"]);
			$output .= ($is_last) ? "" : static::$template["divider"];
			$output .= static::$template["item_end"];
		}

		$output .= static::$template['wrapper_end'];

		return $output;
	}

	/**
	 * Sort the index of an array
	 *
	 * @return &$array Array to sort
	 */
	protected function sort_array(&$array)
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