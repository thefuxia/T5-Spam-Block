<?php # -*- coding: utf-8 -*- php-version: 5.4 -*-
namespace SpamBlock;

/**
 * Autoloader for classes, interfaces and traits.
 *
 * @author     toscho
 * @version    2013.11.09
 * @package    SpamBlock
 * @subpackage Autoload
 */
class Autoload implements Autoload_Interface
{
	protected $rules = array ();
	/**
	 * Constructor
	 *
	 * @param string $pattern
	 */
	public function __construct()
	{
		spl_autoload_register( array ( $this, 'load' ) );
	}

	/**
	 * Add a rule as object instance.
	 *
	 * @param  \SpamBlock\Autoload_Rule $rule
	 * @return \SpamBlock\Autoload
	 */
	public function add_rule( Autoload_Rule $rule )
	{
		$this->rules[] = $rule;
		return $this;
	}

	/**
	 * Callback for spl_autoload_register()
	 *
	 * @param  string  $class_name
	 * @return boolean
	 */
	public function load( $name )
	{
		foreach ( $this->rules as $rule )
			if ( $rule->load( $name ) )
				return TRUE;

		return FALSE;
	}
}