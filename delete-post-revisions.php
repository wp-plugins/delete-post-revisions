<?php
/*
Plugin Name: Delete Post Revisions
Version: 1.0
Plugin URI: http://www.cranesandskyhooks.com/wordpress/plugins/delete-post-revisions/
Description: A simple plugin for deleting unwanted post revisions from your database.
Author: Donal MacArthur
Author URI: http://donalmacarthur.com/
Licence: GPL2
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

/* If the file has been loaded directly, halt execution. */
if ( !function_exists( 'add_action' ) )
	die( "This page should not be loaded directly." );

/* If we're on an admin page, load the plugin. */
if ( is_admin() ) {
	require_once( 'includes/delete-revisions.php' );
	add_action( 'admin_menu', 'atlas_delete_revisions' );
}