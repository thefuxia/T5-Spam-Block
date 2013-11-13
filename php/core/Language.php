<?php # -*- coding: utf-8 -*-
namespace SpamBlock;

/**
 * Language loader.
 *
 * @package    SpamBlock
 * @subpackage Models
 */
class Language implements Loadable
{
	/**
	 * Path to plugin directory.
	 *
	 * @type string
	 */
	protected $dir;

	/**
	 * Text domain.
	 *
	 * @type string
	 */
	protected $domain;

	/**
	 * Constructor.
	 */
	public function __construct( $plugin_file, File_Data_Interface $file_meta )
	{
		$dir_name     = basename( dirname( $plugin_file ) );
		$this->dir    = "$dir_name/" . $file_meta->get( 'Text Domain' );
		$this->domain = $file_meta->get( 'Domain Path' );
	}

	/**
	 * Loads translation file.
	 *
	 * @return bool
	 */
	public function load()
	{
		return load_plugin_textdomain( $this->domain, FALSE, $this->dir );
	}

	/**
	 * Remove translations from memory.
	 *
	 * @return bool
	 */
	public function unload()
	{
		return unload_textdomain( $this->domain );
	}
}