<?php
/**
 * Created by PhpStorm.

 * Date: 09.11.13
 * Time: 17:10
 */

namespace SpamBlock;

class Plugin_Row_Meta_Data implements Plugin_Row_Meta_Data_Interface
{
	public function get_url()
	{
		return \admin_url( 'options-discussion.php' );
	}

	public function get_hash()
	{
		return "#{$this->option}_id";
	}

	public function is_valid( $file )
	{
		return $this->plugin_base === $file;
	}
}