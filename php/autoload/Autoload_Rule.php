<?php # -*- coding: utf-8 -*- php-version: 5.4 -*-
namespace SpamBlock;

/**
 * Load classes, traits and interfaces from Cube.
 *
 * @author     toscho
 * @version    2013.05.25
 * @package    Cube
 * @subpackage Autoload
 */
class Autoload_Rule implements Autoload_Rule_Interface
{
	/**
	 * Path pattern in sprintf() format.
	 *
	 * @type string
	 */
	protected $pattern;

	/**
	 * The namespace to check for.
	 *
	 * @type string
	 */
	protected $namespace = __NAMESPACE__;

	/**
	 * Constructor
	 *
	 * @param string $pattern
	 */
	public function __construct( $pattern )
	{
		$this->pattern = $pattern;
	}

	/**
	 * Parse class/trait/interface name and load file.
	 *
	 * @param  string $name
	 * @return boolean
	 */
	public function load( $name )
	{
		$name = trim( $name, '\\' );

		if ( FALSE === strpos( $name, '\\' ) )
			return FALSE;

		if ( $this->namespace !== strtok( $name, '\\' ) )
			return FALSE;

		$base = strtok( '\\' );
		$base = mb_strtolower( $base, 'utf-8' );
		$path = sprintf( $this->pattern, $base );
		// Requires PHP 5.3.2
		$file = stream_resolve_include_path( $path );

		if ( ! $file )
			return FALSE;

		include $file;
		return TRUE;
	}
}