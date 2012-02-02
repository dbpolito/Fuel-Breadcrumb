<?php
/**
 * Breadcrumb solution
 *
 * @version    0.2
 * @author     Daniel Polito - @dbpolito
 */

return array(

	/**
	 * Auto Populate Breadcrumb based on routes
	 */
	'auto_populate' => true,

	/**
	 * If true the class will call ONLY ON AUTO POPULATING Lang::get() to each item
	 * of breadcrumb and WILL NOT ucwords and replace underscores to spaces
	 */
	'use_lang' => false,

	/**
	 * Home Link
	 */
	'home' => array('name' => 'Início', 'link' => '/'),

	/**
	 * Template Structure
	 */
	'template' => array(
		'wrapper_start' => '<ul class="breadcrumb">',
		'wrapper_end' => ' </ul>',
		'item_start' => '<li>',
		'item_start_active' => '<li class="active">',
		'item_end' => '</li>',
		'divider' => '<span class="divider">/</span>'
	),

);

/* End of file breadcrumb.php */