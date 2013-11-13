<?php # -*- coding: utf-8 -*- php-version: 5.4 -*-
namespace SpamBlock;

/**
 * Basic interface to implement autoload rules.
 *
 * Based on an article by Tom Butler:
 * @link       {http://r.je/php-psr-0-pretty-shortsighted-really.html}
 * @author     toscho
 * @version    2013.05.25
 * @package    Cube
 * @subpackage Autoload
 */
interface Autoload_Rule_Interface
{
	/**
	 * Parse class/trait/interface name and load file.
	 *
	 * @param  string $name
	 * @return boolean
	 */
	public function load( $name );
}