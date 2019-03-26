<?php

namespace HM\Platform\CMS\Branding;

const COLOR_BLUE = '#4667de';
const COLOR_DARKBLUE = '#152a4e';
const COLOR_GREEN = '#3FCF8E';
const COLOR_OFFWHITE = '#f3f5f9';

/**
 * Bootstrap the branding.
 */
function bootstrap() {
	add_action( 'add_admin_bar_menus', __NAMESPACE__ . '\\remove_wordpress_admin_bar_item' );
	add_filter( 'admin_footer_text', '__return_empty_string' );
	add_action( 'wp_network_dashboard_setup', __NAMESPACE__ . '\\remove_dashboard_widgets' );
	add_action( 'wp_user_dashboard_setup', __NAMESPACE__ . '\\remove_dashboard_widgets' );
	add_action( 'wp_dashboard_setup', __NAMESPACE__ . '\\remove_dashboard_widgets' );
	add_action( 'admin_init', __NAMESPACE__ . '\\add_color_scheme' );
	add_filter( 'get_user_option_admin_color', __NAMESPACE__ . '\\override_default_color_scheme' );
}

/**
 * Remove the WordPress logo admin menu bar item.
 */
function remove_wordpress_admin_bar_item() {
	remove_action( 'admin_bar_menu', 'wp_admin_bar_wp_menu' );
}

/**
 * Remove dashboard widgets that are not useful.
 */
function remove_dashboard_widgets() {
	remove_meta_box( 'dashboard_primary', [ 'dashboard', 'dashboard-network', 'dashboard-user' ], 'side' );
}

/**
 * Add the Platform color scheme to the user options.
 */
function add_color_scheme() {
	wp_admin_css_color(
		'platform',
		__( 'Platform', 'hm-platform' ),
		plugin_dir_url( dirname( __FILE__, 2 ) ) . '/assets/admin-color-scheme.css',
		[
			COLOR_BLUE,
			COLOR_DARKBLUE,
			COLOR_GREEN,
			COLOR_OFFWHITE,
		],
		[
			'base' => '#e5f8ff',
			'focus' => 'white',
			'current' => 'white',
		]
	);
}

/**
 * Override the default color scheme
 *
 * This is hooked into "get_user_option_admin_color" so we have to
 * make sure to return the value if it's already set.
 *
 * @param string|false $value
 * @return string
 */
function override_default_color_scheme( $value ) : string {
	if ( $value ) {
		return $value;
	}

	return 'platform';
}
