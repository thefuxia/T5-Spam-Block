<?php
/**
 * Created by PhpStorm.
 * User: julian
 * Date: 11.11.13
 * Time: 16:23
 */

namespace SpamBlock;


class Option_Data implements Settings_Data_Interface
{
	protected $option_name, $values, $defaults;

	public function __construct( $option_name, $defaults = FALSE )
	{
		$this->option_name = $option_name;
		$this->defaults    = $defaults;
	}

	public function get( $name = NULL )
	{
		if ( empty ( $this->values ) )
			$this->fill_values();

		if ( NULL === $name )
			return $this->values;

		if ( isset ( $this->values[ $name ] ) )
			return $this->values[ $name ];

		return NULL;
	}

	protected function fill_values()
	{
		$this->values = \get_option( $this->option_name, $this->defaults );
	}
} 