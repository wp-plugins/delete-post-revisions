<?php
/**
 * Delete Post Revisions - a simple plugin for deleting unwanted post revisions from the WordPress database.
 * 
 * By default, WordPress stores an unlimited number of backup or 'revision' copies of each
 * post and page. These revisions can build up over time, bloating the database unnecessarily.
 * This plugin cleans up the database by deleting all existing revisions.
 *
 * @package DeleteRevisions
 * @author Donal MacArthur
 * @copyright Copyright (c) 2010, Donal MacArthur
 * @link http://www.cranesandskyhooks.com/wordpress/plugins/delete-post-revisions/
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Initialization function. Initializes the plugin by instantiating the Atlas_Delete_Revisions class.
 *
 * @since 1.0
 */
function atlas_delete_revisions() {
	$deleteRevisions = new Atlas_Delete_Revisions();
}

/**
 * The Atlas_Delete_Revisions class contains all the plugin's fuctionality.
 *
 * @package DeleteRevisions
 * @since 1.0
 */
class Atlas_Delete_Revisions {

	/**
	 * PHP4 constructor method.  This provides backwards compatibility for users with setups
	 * on older versions of PHP.  Once WordPress no longer supports PHP4, this method will be removed.
	 *
	 * @since 1.0
	 */
	function Atlas_Delete_Revisions() {
		$this->__construct();
	}

	/**
	 * Constructor method for the Atlas_Delete_Revisions class. Adds a new Delete Revisions page to the  
	 * WordPress Tools menu. No other function calls are necessary.
	 *
	 * @since 1.0
	 */
	function __construct() {
		add_submenu_page( 'tools.php', 'Delete Post Revisions', 'Delete Revisions', 'manage_options', 'delete-revisions', array( &$this, 'build_admin_page' ) );
	}

	/**
	 * This function builds the Delete Revisions admin page. It's called when the user 
	 * selects the page from the WordPress Tools menu.
	 *
	 * @since 1.0
	 */
	function build_admin_page() {
		
		/* Check the user has permission to be on this page. */
		if ( !current_user_can( 'manage_options' ) )
			wp_die( __('You do not have sufficient permissions to access this page.') );
		
		/* Check the form nonce is valid. */
		if ( !empty( $_POST ) )
			check_admin_referer( 'delete-revisions', 'nonce-delete-revisions' );
		
		/* Print CSS styles. */
		$this->print_styles();
		
		/* Prepare the plugin's instructions. */
		$instructions = array();
		$instructions[] = "By default, whenever you update a post or page, WordPress saves a backup or 'revision' copy of the old page. There is no default limit to how many revisions are saved for each page so these revision copies can build up over time, bloating your database unnecessarily. This simple tool cleans up your database by deleting all existing revisions.";
		$instructions[] = "Warning: The deletion process is irreversible. Once your revisions have been deleted, they're gone for good.";
		$instructions[] = "You should only need to run this plugin once, then you can deactivate and delete it. Deleting your existing revisions won't prevent them from building up again in future but you can disable the revision feature or limit the number of revisions WordPress saves to a number of your choice by setting WordPress's <code>WP_POST_REVISIONS</code> constant. You can find instructions on how to do this on the plugin's homepage <a href='http://www.cranesandskyhooks.com/wordpress/plugins/delete-post-revisions/'>here</a>.";
		$instructions[] = "To begin the deletion process, simply click the button below:";
		
		/* Print the page header. */
		echo "<div class='wrap delete-revisions'>\n";
		screen_icon();
		echo "<h2>Delete Post Revisions</h2>\n";
		
		/* Print the plugin's instructions. */
		foreach ( $instructions as $line ) {
			echo "<p>$line</p>\n";
		}
		
		/* Print a form with a 'delete' button. */ 
		echo "<form method='post' action=''>\n";
			echo "<p class='submit'><input type='submit' value='Delete All Revisions' /></p>\n";
			echo "<input type='hidden' name='delete' value='true' />\n";
			wp_nonce_field( 'delete-revisions', 'nonce-delete-revisions' );
		echo "</form>\n";

		/* If the delete button has been clicked, call the delete function. */
		if ( $_POST['delete'] == true )
			$this->delete_revisions();
		
		/* Print the page footer. */
		echo "</div>\n";
	}
		
	/**
	 * This function handles the actual deletion process.
	 *
	 * We get an array of revision posts from the database using get_posts().
	 * We loop through the array, print out some info for each post, then delete it
	 * using wp_delete_post().
	 * 
	 * @since 1.0
	 * @link http://codex.wordpress.org/Template_Tags/get_posts
	 * @link http://codex.wordpress.org/Function_Reference/wp_delete_post
	 */
	function delete_revisions() {
		
		$args = array( 'post_type' => 'revision', 'post_status' => 'any', 'numberposts' => -1 );
		$posts = get_posts( $args );
		$count = 0;
		
		echo "<h2 class='title'>Results</h2>\n";
		
		/* If no revisions were found... */
		if ( count( $posts ) == 0 ) {
			echo "<p><em>No revisions were found. Nothing has been deleted from the database.</em></p>\n";
		}
		
		/* Else, revisions were found, so delete them... */
		else {
			echo "<p>The following post revisions have been deleted from the database:</p>\n";
			
			/* Build a table to list the deleted revisions. */
			echo "<table>\n";
				echo "<tr>\n";
					echo "<th>No.</td>\n";
					echo "<th>ID</td>\n";
					echo "<th>Title</td>\n";
					echo "<th>Date Saved</td>\n";
				echo "</tr>\n";
			
				/* Loop through the array of revisions. For each post, print the post info, then delete it. */
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

	/**
	 * Print CSS styles for the admin page.
	 *
	 * @since 1.0
	 */
	function print_styles() {
	
		$styles = array();
		
		$styles[] = ".delete-revisions p.submit { margin: 0; padding: 15px 0; }";
		$styles[] = ".delete-revisions h2.title { padding: 0; margin 0 0 20px 0; font-size: 1.6em; font-style: normal; }";
		$styles[] = ".delete-revisions table { border-collapse: collapse; margin: 30px; }";
		$styles[] = ".delete-revisions tr { border: 1px solid #ccc; }";
		$styles[] = ".delete-revisions tr.even { background: white; }";
		$styles[] = ".delete-revisions th { padding: 8px 30px; font-weight: bold; text-align: left; background: #eee; }";
		$styles[] = ".delete-revisions td { padding: 8px 30px; }";
		
		echo "<style type='text/css'>\n";
		foreach ( $styles as $style ) {
			echo $style . "\n";
		}
		echo "</style>\n";
	}
}