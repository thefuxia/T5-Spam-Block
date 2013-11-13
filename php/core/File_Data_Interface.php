<?php
/**
 * Created by PhpStorm.

 * Date: 11.11.13
 * Time: 11:48
 */
namespace SpamBlock;

interface File_Data_Interface {

	public function __construct( $file );

	public function get( $regex );
}