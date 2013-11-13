<?php
namespace SpamBlock;

interface Autoload_Interface
{
	public function add_rule( Autoload_Rule $rule );

	public function load( $name );
}