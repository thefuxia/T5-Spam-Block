<?php  # -*- coding: utf-8 -*-
/**
 * Plugin Name: T5 Spam Block
 * Description: Block spam before it reaches the database.
 * Plugin URI:  https://github.com/toscho/T5-Spam-Block
 * Version:     2013.03.30
 * Author:      Thomas Scholz
 * Author URI:  http://toscho.de
 * Licence:     MIT
 * License URI: http://opensource.org/licenses/MIT
 * Text Domain: plugin_t5_spam_block
 * Domain Path: /languages
 */
// See also https://gist.github.com/splorp/1385930 for inspiration.


if ( T5_Spam_Block::start_me() )
	add_action(
		'wp_loaded',
		array ( T5_Spam_Block::get_instance(), 'plugin_setup' )
	);

/**
 * Simple spam block based on stop words.
 *
 * Go to "wp-admin/options-discussion.php" to change the stop words.
 * It is a list of regular expressions, so you should know how they work.
 *
 * @author toscho
 */
class T5_Spam_Block
{
	/**
	 * Plugin main instance.
	 *
	 * @type object
	 */
	protected static $instance = NULL;

	/**
	 * basename() of global $pagenow
	 *
	 * @type string
	 */
	protected static $page_base;

	/**
	 * Plugin base name.
	 *
	 * @type string
	 */
	protected $plugin_base;

	/**
	 * Plugin option name.
	 *
	 * @type string
	 */
	protected $option = 't5_spam_block';

	/**
	 * Default spam block list. Change it in "wp-admin/options-discussion.php".
	 *
	 * @type array
	 */
	protected $default_list = array (
		'[a-z]\d+\.fr',
		'bestonline',
		'bag\.sh',
		'bit\.ly',
		'cheap\.[^ ]',
		'/cheap',
		'cialis',
		'demo2\.com',
		'facebook\.com/profile',
		'goo\.gl',
		'gucci',
		'handbag',
		'interesy',
		'louboutin',
		'outlet',
		'replica',
		'ringtones',
		'submit\-link4u\.net',
		'tiny\.cc',
		'vuitton',
		'wiki/index\.php',
	);

	/**
	 * Access plugin instance. You can create further instances by calling
	 * the constructor directly.
	 *
	 * @wp-hook wp_loaded
	 * @return  object T5_Spam_Block
	 */
	public static function get_instance()
	{
		if ( NULL === self::$instance )
			self::$instance = new self;

		return self::$instance;
	}

	/**
	 * Check if we need an instance of our class.
	 *
	 * @return boolean
	 */
	public static function start_me()
	{
		global $pagenow;

		if ( empty ( $pagenow ) )
			return FALSE;

		self::$page_base = basename( $pagenow, '.php' );
		$active_pages    = array (
			'options',
			'options-discussion',
			'plugins',
			'wp-comments-post'
		);

		if ( ! in_array( self::$page_base, $active_pages ) )
			return FALSE;

		return TRUE;
	}

	/**
	 * Constructor. Intentionally left blank.
	 *
	 * @wp-hook wp_loaded
	 */
	public function __construct() {}

	/**
	 * Register actions.
	 *
	 * @wp-hook wp_loaded
	 * @return  boolean
	 */
	public function plugin_setup()
	{
		// Register callbacks only when needed.
		if ( 'wp-comments-post' === self::$page_base )
			return add_action(
				'pre_comment_on_post',
				array ( $this, 'check_comment' )
			);

		if ( 'options' === self::$page_base )
			return add_action(
				'admin_init',
				array ( $this, 'register_setting' )
			);

		if ( 'options-discussion' === self::$page_base )
			return add_action(
				'admin_init',
				array ( $this, 'add_settings_field' )
			);

		// Now 'plugins' === self::$page_base
		// Used by add_settings_link() later.
		$this->plugin_base  = plugin_basename( __FILE__ );

		return add_filter(
			'plugin_row_meta',
			array ( $this, 'add_settings_link' ),
			10,
			2
		);
	}

	/**
	 * Adds a link to the settings to plugin list.
	 *
	 * @wp-hook plugin_row_meta
	 * @param   array  $links Already existing links.
	 * @return  string
	 */
	public function add_settings_link( $links, $file )
	{
		if ( $this->plugin_base !== $file )
			return $links;

		$url  = admin_url( 'options-discussion.php' );

		$this->load_language();
		$text = __( 'Edit comment block list', 'plugin_t5_spam_block' );
		$this->unload_language();

		$link = "<a href='$url#{$this->option}_id'>$text</a>";

		// No need for further work.
		remove_filter( 'plugin_row_meta', array ( $this, __FUNCTION__ ) );

		return array_merge( $links, array ( $link ) );
	}

	/**
	 * Check comment text and URL for block words. Die on match.
	 *
	 * @wp-hook pre_comment_on_post
	 * @return  void
	 */
	public function check_comment()
	{
		if ( wp_get_current_user()->exists() )
			return;

		if ( ! isset ( $_POST['comment'] ) )
			exit;

		if ( '' === trim( $_POST['comment'] ) )
			exit;

		if ( $this->is_spam( $_POST['comment'] ) )
			exit;

		if ( isset ( $_POST['url'] ) and $this->is_spam( $_POST['url'] ) )
			exit;
	}

	/**
	 * Check $text against block list.
	 *
	 * You can use thise function from the outside with
	 * T5_Spam_Block::get_instance()->is_spam( $text );
	 *
	 * @param  string $text
	 * @return bool
	 */
	public function is_spam( $text )
	{
		$find  = join( '|', $this->get_block_list() );

		return (bool) preg_match( "~$find~mi", $text );
	}

	/**
	 * Register extra settings for discussion options page.
	 *
	 * @wp-hook admin_init
	 * @return  void
	 */
	public function register_setting()
	{
		register_setting(
			'discussion',
			$this->option,
			array ( $this, 'save_setting' )
		);
	}

	/**
	 * Prepare option values before they are saved.
	 *
	 * @wp-hook sanitize_option_t5_spam_block
	 * @param   string $data
	 * @return  array
	 */
	public function save_setting( $data )
	{
		$lines = explode( "\n", $data );
		$lines = array_map( 'trim', $lines );
		$lines = array_unique( $lines );
		$lines = array_filter( $lines );
		sort( $lines );

		return $lines;
	}

	/**
	 * Register textarea for block list.
	 *
	 * @wp-hook admin_init
	 */
	public function add_settings_field()
	{
		$this->load_language();

		add_settings_field(
			$this->option,
			__( 'Block comments completely', 'plugin_t5_spam_block' ),
			array ( $this, 'show_settings' ),
			'discussion',
			'default',
			array ( 'label_for' => "{$this->option}_id" )
		);
	}

	/**
	 * Print textarea into discussion option page.
	 *
	 * @see    register_settings()
	 * @param  array $args Passed as last argument in add_settings_field()
	 * @return void
	 */
	public function show_settings( Array $args )
	{
		$data = $this->get_block_list();
		$data = join( "\n", $data );
		$label_trans = _x(
			'These comments will be blocked before they reach the database. You will never see them. Each line one regular expression to match against the comment text and URL. The pattern delimiter is %s.',
			'%s = <code>~</code>',
			'plugin_t5_spam_block'
		);
		$label = sprintf( $label_trans, '<code>~</code>' );

		printf(
			'<p><label for="%2$s">%4$s</label></p>
			<textarea name="%1$s" id="%2$s" rows="10" cols="30" class="large-text code">%3$s</textarea>',
			$this->option,
			$args['label_for'],
			esc_textarea( $data ),
			$label
		);

		$this->unload_language();
	}

	/**
	 * Loads translation file.
	 *
	 * @return bool
	 */
	protected function load_language()
	{
		$path = plugin_basename( dirname( __FILE__ ) ) . '/languages';
		return load_plugin_textdomain( 'plugin_t5_spam_block', FALSE, $path );
	}

	/**
	 * Remove translations from memory.
	 *
	 * @return void
	 */
	protected function unload_language()
	{
		unset ( $GLOBALS['l10n']['plugin_t5_spam_block'] );
	}

	/**
	 * List of stop words.
	 *
	 * @return array
	 */
	protected function get_block_list()
	{
		// Internal cache. Prevents us from doing the following twice.
		static $list = NULL;

		if ( NULL !== $list )
			return $list;

		$data = get_option( $this->option, array () );

		// there might be an array like 'array( 0 => "" )' when someone updates
		// this per "wp-admin/options.php".
		$data = array_filter( $data );
		$list = empty ( $data ) ? $this->default_list : $data;

		return $list;
	}
}