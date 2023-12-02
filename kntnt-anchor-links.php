<?php

/**
 * @wordpress-plugin
 * Plugin Name:     Kntnt Anchor Links
 * Plugin URI:      https://github.com/Kntnt/kntnt-anchor-links
 * Description:     Adds anchor links to headings.
 * Version:         1.0.1
 * Author:          Thomas Barregren
 * Author URI:      https://www.kntnt.com/
 * License:         GPL-3.0+
 * License URI:     http://www.gnu.org/licenses/gpl-3.0.txt
 * Requires PHP:    8.0
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

		if ( apply_filters( 'kntnt-anchor-links-post-id', null, $post_id ) ?? in_array( $post_type, apply_filters( 'kntnt-anchor-links-post-types', $this->default_post_types ) ) ) {
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

			if ( in_array( $level, $heading_levels ) ) {
				$anchor = sanitize_title( $title );
				$anchor = '<a href="#' . $anchor . '" aria-hidden="true" class="kntnt-anchor-link" id="' . $anchor . '"></a>';

				return "<h$level$attributes>$anchor$title</h$level>";
			}
			else {
				return $original;
			}

		}, $html );

	}

}
