<?php # -*- coding: utf-8 -*-
namespace SpamBlock;
/**
 * Interface for translation loader.
 *
 * @package    SpamBlock
 * @subpackage Models
 */
interface Loadable
{
	/**
	 * Load language.
	 *
	 * @return bool TRUE when a mo file was found, FALSE otherwise.
	 */
	public function load();

	/**
	 * Remove translation object from memory.
	 *
	 * @return void
	 */
	public function unload();
}