<?php
namespace SpamBlock;

/**
 * Created by PhpStorm.
 * User: julian
 * Date: 08.11.13
 * Time: 23:34
 */
class Front_Controller {

	public function __construct( $file, Autoload_Interface $autoload )
	{
		$page = $this->get_current_page();

		if ( '' === $page )
			return;

		$file_meta = new File_Meta( $file );
		$language  = new Language( $file, $file_meta );
		$mediator  = new Mediator();

		if ( 'plugins' === $page )
			return new Plugin_Row_Meta( $language, $mediator );

		if ( 'wp-comments-post' === $page )
			return new Comment_Check( $mediator, new Option_Data( 't5_spam_block' ) );
	}

	/**
	 * Get current page base name.
	 *
	 * @return string
	 */
	protected function get_current_page()
	{
		global $pagenow;

		if ( empty ( $pagenow ) )
			return '';

		$search  = basename( $pagenow, '.php' );
		$allowed = [ 'options', 'options-discussion', 'plugins', 'wp-comments-post' ];

		if ( in_array( $search, $allowed ) )
			return $search;

		return '';
	}
}