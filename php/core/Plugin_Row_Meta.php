<?php
/**
 * Created by PhpStorm.

 * Date: 10.11.13
 * Time: 17:41
 */

namespace SpamBlock;


class Plugin_Row_Meta
{
	protected $callback;

	public function __construct( Loadable $language, Mediator_Interface $mediator )
	{
		$view           = new Plugin_Row_Meta_View( new Plugin_Row_Meta_Data, $mediator );
		$this->callback = [ $view, 'render' ];

		\add_filter( 'plugin_row_meta', $this->callback, 10, 2 );

		$mediator->register_event( 'plugin_row_meta_render_start', [ $language, 'load'   ] );
		$mediator->register_event( 'plugin_row_meta_rendered',     [ $this,     'stop'   ] );
		$mediator->register_event( 'plugin_row_meta_rendered',     [ $language, 'unload' ] );
	}

	public function stop()
	{
		\remove_filter( 'plugin_row_meta', $this->callback );
	}
}