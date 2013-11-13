<?php
/**
 * Created by PhpStorm.
 * User: julian
 * Date: 11.11.13
 * Time: 16:37
 */
namespace SpamBlock;

interface Settings_Data_Interface {

	public function __construct( $option_name, $defaults = FALSE );

	public function get( $name = NULL );
}