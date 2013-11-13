<?php
/**
 * Created by PhpStorm.
 * User: julian
 * Date: 09.11.13
 * Time: 17:11
 */

interface Plugin_Row_Meta_Data_Interface
{
	public function __construct();

	public function get_url();

	public function get_hash();

	public function is_valid( $file );
}