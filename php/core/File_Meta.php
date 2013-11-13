<?php
/**
 * Created by PhpStorm.

 * Date: 11.11.13
 * Time: 12:13
 */

namespace SpamBlock;

class File_Meta implements File_Data_Interface
{
	protected $file;

	protected $values = [];

	public function __construct( $file )
	{
		$this->file = $file;
	}

	public function get( $regex )
	{
		if ( isset ( $this->values[ $regex ] ) )
			return $this->values[ $regex ];

		$this->values[ $regex ] = \get_file_data( $this->file, [ $regex ] );

		return $this->values[ $regex ];
	}
}