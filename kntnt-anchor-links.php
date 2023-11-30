<?php

/**
 * @wordpress-plugin
 * Plugin Name:       Kntnt Anchor Links
 * Plugin URI:        https://github.com/Kntnt/kntnt-anchor-links
 * Description:       Adds anchor links to headings.
 * Version:           1.0.0
 * Author:            Thomas Barregren
 * Author URI:        https://www.kntnt.com/
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Kntnt\Anchor_Links;

defined( 'ABSPATH' ) && new Plugin;

class Plugin {

	private $ver;

	private $default_post_types = [ 'post' ];

	private $default_heading_levels = [ '2' ];

	public function __construct() {

		$this->ver = get_file_data( __FILE__, [ 'Version' => 'Version' ] )['Version'];

		add_action( 'wp_enqueue_scripts', [ $this, 'register_resources' ] );
		add_filter( 'the_content', [ $this, 'filter_the_content' ] );

	}

	public function register_resources() {
		wp_register_style( 'kntnt-anchor-links.css', plugins_url( '/css/kntnt-anchor-links.css', __FILE__ ), [], $this->ver );
	}

	public function filter_the_content( $html ) {

		$post      = get_post();
		$post_id   = $post?->ID;
		$post_type = $post?->post_type;

		$do_id        = apply_filters( 'kntnt-anchor-links-post-id', null, $post_id );
		$do_post_type = in_array( $post_type, apply_filters( 'kntnt-anchor-links-post-types', $this->default_post_types ) );

		if ( ( isset( $do_id ) && $do_id ) || $do_post_type ) {
			wp_enqueue_style( 'kntnt-anchor-links.css' );
			$html = $this->add_anchor_links( $html, $post_id, $post_type );
		}

		return $html;

	}

	private function add_anchor_links( $html, $post_id, $post_type ) {

		$heading_levels = apply_filters( 'kntnt-anchor-links-heading_levels', $this->default_heading_levels, $post_id, $post_type );

		$pattern = '@<h([' . implode( '', $heading_levels ) . '])([^>]*)>(.+?)</h\1>@is';

		return preg_replace_callback( $pattern, function ( $match ) use ( $heading_levels ) {

			$original   = $match[0];
			$level      = $match[1];
			$attributes = $match[2];
			$title      = $match[3];

			if ( in_array( $match[1], $heading_levels ) ) {
				$anchor = sanitize_title( $title );
				$anchor = '<a href="#' . $anchor . '" aria-hidden="true" class="kntnt-anchor-link" id="' . $anchor . '"><svg height="16" width="16" viewBox="0 0 16 16" aria-hidden="true" ><path fill-rule="evenodd" d="M4 9h1v1H4c-1.5 0-3-1.69-3-3.5S2.55 3 4 3h4c1.45 0 3 1.69 3 3.5 0 1.41-.91 2.72-2 3.25V8.59c.58-.45 1-1.27 1-2.09C10 5.22 8.98 4 8 4H4c-.98 0-2 1.22-2 2.5S3 9 4 9zm9-3h-1v1h1c1 0 2 1.22 2 2.5S13.98 12 13 12H9c-.98 0-2-1.22-2-2.5 0-.83.42-1.64 1-2.09V6.25c-1.09.53-2 1.84-2 3.25C6 11.31 7.55 13 9 13h4c1.45 0 3-1.69 3-3.5S14.5 6 13 6z"></path></svg></a>';

				return "<h$level$attributes>$anchor$title</h$level>";
			} else {
				return $original;
			}

		}, $html );

	}

}
