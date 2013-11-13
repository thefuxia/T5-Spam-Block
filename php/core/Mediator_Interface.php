<?php
namespace SpamBlock;

/**
 * Created by PhpStorm.

 * Date: 09.11.13
 * Time: 15:35
 */

interface Mediator_Interface
{
	public function register_event( $name, Callable $callback );

	public function register_filter( $name, Callable $callback );

	public function trigger( $name, $data = NULL );
}