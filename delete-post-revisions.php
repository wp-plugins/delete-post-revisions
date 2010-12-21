<?php
/*
Plugin Name: Delete Post Revisions
Plugin URI: http://www.cranesandskyhooks.com/wordpress/plugins/delete-post-revisions/
Description: A simple plugin for deleting unwanted post revisions from your database.
Version: 1.0
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


// If the file has been loaded directly, stop now
if ( !function_exists( 'add_action' ) ) {
	die( "This page should not be loaded directly." );
}


// Initialize the plugin
function atlas_delete_revisions() {
	$deleteRevisions = new Atlas_Delete_Revisions();
}
add_action( 'admin_menu', 'atlas_delete_revisions' );


// Define the plugin class
if ( !class_exists( 'Atlas_Delete_Revisions' ) ) {
	class Atlas_Delete_Revisions {

		// Constructor
		function Atlas_Delete_Revisions() {
			add_submenu_page( 'tools.php', 'Delete Post Revisions', 'Delete Revisions', 'manage_options', 'delete-revisions', array( &$this, 'build_admin_page' ) );
		}

		// Build the admin page
		function build_admin_page() {
					
			// Check the user has permission to be on this page
			if ( !current_user_can( 'manage_options' ) )
				wp_die( __('You do not have sufficient permissions to access this page.') );
			
			// Check the form nonce is valid
			if ( !empty( $_POST ) )
				check_admin_referer( 'delete-revisions', 'nonce-delete-revisions' );
			
			// Print CSS styles
			$this->print_styles();
			
			// Print page content
?>
<div class='wrap delete-revisions'>

	<?php screen_icon(); ?>
	<h2>Delete Post Revisions</h2>
	
	<p>By default, whenever you update a post or page, WordPress saves a backup or 'revision' copy of the old page. There is no default limit to how many revisions are saved for each page so these revision copies can build up over time, bloating your database unnecessarily. This simple tool cleans up your database by deleting all existing revisions.</p>
	<p>Warning: The deletion process is irreversible. Once your revisions have been deleted, they're gone for good.</p>
	<p>You should only need to run this plugin once, then you can deactivate and delete it. Deleting your existing revisions won't prevent them from building up again in future but you can disable the revision feature or limit the number of revisions WordPress saves to a number of your choice by setting WordPress's <code>WP_POST_REVISIONS</code> constant. You can find instructions on how to do this on the plugin's homepage <a href='http://www.cranesandskyhooks.com/wordpress/plugins/delete-post-revisions/'>here</a>.</p>
	<p>To begin the deletion process, simply click the button below:</p>
	
	<form method="post" action="">
		<p class="submit"><input type="submit" value="Delete All Revisions" /></p>
		<input type='hidden' name='delete' value='true' />
		<?php wp_nonce_field( 'delete-revisions', 'nonce-delete-revisions' ); ?>
	</form>
<?php	
			// If the delete button has been clicked, call the delete function
			if ( $_POST['delete'] == true )
				$this->delete_revisions();
			
			// End page
			echo "</div>\n";
		}
			
		// Delete revisions
		function delete_revisions() {
				
			$args = array( 'post_type' => 'revision', 'post_status' => 'any', 'numberposts' => -1 );
			$posts = get_posts( $args );
			$count = 0;
			
			echo "<h2 class='title'>Results</h2>\n";
			
			// If no revisions were found...
			if ( count( $posts ) == 0 ) {
				echo "<p><em>No revisions were found. Nothing has been deleted from the database.</em></p>\n";
			}
			
			// Else, revisions were found, so delete them...
			else {
				echo "<p>The following post revisions have been deleted from the database:</p>\n";
				echo "<table>\n";
				echo "<tr>\n";
					echo "<th>No.</td>\n";
					echo "<th>ID</td>\n";
					echo "<th>Title</td>\n";
					echo "<th>Date Saved</td>\n";
				echo "</tr>\n";
				
				// Loop through the array of revisions; print post info, then delete
				foreach ( $posts as $post ) {
					$count++;
					$alt = $count % 2 == 0 ? 'even' : 'odd'; 
					echo "<tr class='$alt'>\n";
						echo '<td>' . $count . ".</td>\n";
						echo '<td>' . $post->ID . "</td>\n";
						echo '<td>' . $post->post_title . "</td>\n";
						echo '<td>' . $post->post_modified . "</td>\n";
					echo "</tr>\n";
					wp_delete_post( $post->ID, true );
				}
				echo "</table>\n";
			}
		}
		
		// CSS styles for admin page
		function print_styles() {
		
			echo "<style type='text/css'>\n";
				echo ".delete-revisions p.submit { margin: 0; padding: 15px 0; }\n";
				echo ".delete-revisions h2.title { padding: 0; margin 0 0 20px 0; font-size: 1.6em; font-style: normal; }\n";
				echo ".delete-revisions table { border-collapse: collapse; margin: 30px; }\n";
				echo ".delete-revisions tr { border: 1px solid #ccc; }\n";
				echo ".delete-revisions tr.even { background: white; }\n";
				echo ".delete-revisions th { padding: 8px 30px; font-weight: bold; text-align: left; background: #eee; }\n";		
				echo ".delete-revisions td { padding: 8px 30px; }\n";
			echo "</style>\n";
		}
		
	} // end class
} // end if