<?php
namespace SpamBlock;
/**
 * Event and filter handler
 *
 * @version 2013.11.13
 * @package SpamBlock
 * @subpackage ObjectCommunication
 *
 * @todo Unregister
 */
class Mediator implements Mediator_Interface
{
	/**
	 * List of events
	 *
	 * @type array
	 */
	protected $events = [];

	/**
	 * List of filters
	 *
	 * @type array
	 */
	protected $filters = [];

	/**
	 * Register an event.
	 *
	 * @param  string   $name event name
	 * @param  callable $callback
	 * @return Mediator current instance
	 */
	public function register_event( $name, Callable $callback )
	{
		$this->events[ $name ][] = $callback;
		return $this;
	}
	/**
	 * Register a filter.
	 *
	 * @param  string   $name filter name
	 * @param  callable $callback
	 * @return Mediator current instance
	 */
	public function register_filter( $name, Callable $callback )
	{
		$this->filters[ $name ][] = $callback;
		return $this;
	}

	/**
	 * Runs all registered events and filters.
	 *
	 * @param string $name
	 * @param mixed  $data
	 * @return mixed $data might be filtered
	 */
	public function trigger( $name, $data = NULL )
	{
		$this->run_events( $name, $data );

		return $this->run_filters( $name, $data );
	}

	/**
	 * Run all registered event for $name.
	 *
	 * @param  string $name
	 * @param  mixed  $data
	 * @return void
	 */
	protected function run_events( $name, $data )
	{
		if ( empty ( $this->events[ $name ]  ) )
			return;

		foreach ( $this->events[ $name ] as $callback )
			\call_user_func( $callback, $data );
	}

	/**
	 * Run all registered filters
	 *
	 * @param  string $name
	 * @param  mixed  $data
	 * @return mixed  $data
	 */
	protected function run_filters( $name, $data )
	{
		if ( empty ( $this->filters[ $name ]  ) )
			return $data;

		foreach ( $this->filters[ $name ] as $callback )
			$data = call_user_func( $callback, $data );

		return $data;
	}
}