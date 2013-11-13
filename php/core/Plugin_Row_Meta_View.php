<?php
/**
 * Created by PhpStorm.
 * User: julian
 * Date: 09.11.13
 * Time: 17:09
 */

namespace SpamBlock;

class Plugin_Row_Meta_View
{
	protected $data, $watcher;

	public function __construct(
		Plugin_Row_Meta_Data_Interface $data,
		Mediator_Interface             $watcher
	)
	{
		$this->data     = $data;
		$this->watcher  = $watcher;
	}

	public function render( $links, $file )
	{
		if ( ! $this->data->is_valid( $file ) )
			return $links;

		$this->watcher->trigger( 'plugin_row_meta_render_start' );

		$url  = \esc_url( $this->data->get_url() );
		$hash = $this->data->get_hash();

		$text = \__( 'Edit comment block list', 'plugin_t5_spam_block' );

		$link = "<a href='$url#{$this->option}_id'>$text</a>";

		$this->watcher->trigger( 'plugin_row_meta_rendered' );

		return \array_merge( $links, array ( $link ) );

	}
}