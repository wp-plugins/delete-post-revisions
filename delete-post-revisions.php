<?php
/**
 * Plugin Name: Delete Post Revisions
 * Plugin URI: http://www.cranesandskyhooks.com/wordpress/plugins/delete-post-revisions/
 * Description: A simple plugin for deleting unwanted post revisions from your database.
 * Version: 1.1
 * Author: Donal MacArthur
 * Author URI: http://donalmacarthur.com/
 * Licence: GPL2
 * 
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License,
 * version 2, as published by the Free Software Foundation.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * @package Delete_Post_Revisions
 * @version 1.1
 * @author Donal MacArthur
 * @copyright Copyright (c) 2010, Cranes & Skyhooks
 * @link http://www.cranesandskyhooks.com/wordpress/plugins/delete-post-revisions/
 */

/* If the file has been loaded directly, halt execution. */
if ( !function_exists( 'add_action' ) )
	die( "This page should not be loaded directly." );

/* If we're on an admin page, load the plugin. */
if ( is_admin() )
	add_action( 'init', 'initialize_delete_post_revisions' );

/**
 * Initialization function. Runs on the 'init' hook.
 *
 * @since 1.0
 */
function initialize_delete_post_revisions() {

	/* Load the Delete_Post_Revisions class. */
	require_once( 'library/classes/delete-revisions.php' );
	
	/* If this isn't an Atlas theme, load the Atlas_Admin_Tools class. */
	if ( !class_exists( 'Atlas' ) )
		require_once( 'library/classes/atlas-admin-tools.php' );
		
	/* Set plugin constants. */
	define( 'DPR_URL', plugin_dir_url(__FILE__) );
	define( 'DPR_PATH', plugin_dir_path(__FILE__) );
	define( 'DPR_BASENAME', plugin_basename( __FILE__ ) );

	/* Instantiating the Delete_Post_Revisions class initializes the plugin. */
	$deleteRevisions = new Delete_Post_Revisions();
}