<?php
/**
 * Created by PhpStorm.
 * User: julian
 * Date: 11.11.13
 * Time: 16:14
 */

namespace SpamBlock;


class Comment_Check {

	public function __construct( Mediator_Interface $mediator, Settings_Data_Interface $settings )
	{
		\add_action(
			'pre_comment_on_post',
			array ( $this, 'check_comment' )
		);
	}
}

class Comment_Inspector
{
	protected $mediator;

	public function __construct( Mediator_Interface $mediator )
	{
		$this->mediator = $mediator;
	}
}