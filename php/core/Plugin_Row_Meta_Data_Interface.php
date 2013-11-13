<?php
/**
 * Created by PhpStorm.

 * Date: 11.11.13
 * Time: 12:09
 */
namespace SpamBlock;

interface Plugin_Row_Meta_Data_Interface {

	public function is_valid( $file );

	public function get_url();

	public function get_hash();
}