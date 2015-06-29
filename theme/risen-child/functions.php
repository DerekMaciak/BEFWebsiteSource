<?php
/**
 * Risen Child Theme
 *
 * You can use this file to override and add functionality.
 * It is better to make modifications to a theme using a child theme so that your changes are not lost after a theme update.
 * See Risen's documentation for more information on using a child theme (changing styles, overriding templates, etc.).
 */

// Perform setup on after_setup_theme hook
// Default priority is 10, so 11 causes this to run after the parent theme's setup
// This way already added hooks can be removed / replaced as shown below
add_action( 'after_setup_theme', 'risen_child_setup', 11 );

// Setup theme features, actions, filters, etc.
function risen_child_setup() {

	// Inject parent stylesheets into header.php's <head>
	// Priority 9 causes styles to enqueue before child theme styles (so child styles can override parent styles)
	// We do this here instead of in child's style.css with @import because the JavaScript that enables
	// media queries / responsiveness does not work with @import; it requires regular <link> call to stylesheet
	add_action( 'wp_enqueue_scripts', 'risen_child_css_before', 9 );

	//  Inject new stylesheet into header.php's <head> (see risen_child_css() below)
	//add_action( 'wp_enqueue_scripts', 'risen_child_css' ); // front-end only

	//  Inject new JavaScript into header.php's <head> (see risen_child_js() below)
	//add_action( 'wp_enqueue_scripts', 'risen_child_js' ); // front-end only
	
	// Example of replacing a function that is hooked
	// Look at the parent theme's functions.php to see everything that can be replaced
	// remove_filter() works similarly
	/*
	remove_action( 'admin_menu', 'risen_admin_menu' ); // risen_admin_menu() will no longer manipulate admin menu
	add_action( 'admin_menu', 'risen_child_admin_menu' ); // now risen_child_admin_menu() will do it (see function below)
	*/
	
	// See bottom of file for example of how to replace pluggable function
	
}

// Inject parent stylesheets into header.php's <head>
// We do this here instead of in child's style.css with @import because the JavaScript that enables
// media queries / responsiveness does not work with @import; it requires regular <link> call to stylesheet
// See wp_enqueue_css_before action above
function risen_child_css_before() {

	// Main stylesheet from parent theme
	wp_enqueue_style( 'risen-parent-style', RISEN_THEME_URI . '/style.css', false, RISEN_VERSION );  // bust cache on theme update

	// Base style (light or dark) from parent theme
	if ( risen_child_base_style_exists() ) { // this is only necessary if child is overriding the base style; otherwise parent's base style is used already
		risen_enqueue_base_style( 'risen-parent-base-style', 'parent' );
	}

}

// Inject new stylesheet(s) calls into header.php's <head>
// See wp_enqueue_css action above
/*
function risen_child_css() {

	wp_enqueue_style( 'risen-child-stylesheet', RISEN_CHILD_URI . '/new-stylesheet.css', false, RISEN_VERSION ); // version busts cache on theme update

	// you can enqueue more here
	
}
*/

// Inject new JavaScript into header.php's <head>
// See wp_enqueue_scripts action above
/*
function risen_child_js() {

	wp_enqueue_script( 'risen-child-script', RISEN_CHILD_URI . '/js/new-script.js', false, RISEN_VERSION ); // version busts cache on theme update

	// you can enqueue more here

}
*/

// Example of a new hook replacing an old one
// See the remove_action/add_action example above. Without that, this does nothing.
/*
function risen_child_admin_menu() {

	// a new way to manipulate admin menus here

}
*/

// Example of replacing a pluggable function
// Pluggable functions are those enclosed with an if ( function_exists( 'function_name' ) statement
// Functions not checked for existence are hooked and can be replaced by removing old hook and adding new (see example above)
/*
function risen_google_map( $options = false ) {

	$content = 'new HTML for displaying a map';

	return $content;

}
*/
