=== Delete Post Revisions ===
Contributors: donalmacarthur
Donate link: http://donalmacarthur.com/
Tags: post revisions, delete revisions
Requires at least: 3.0
Tested up to: 3.0.3
Stable tag: 1.0

A simple plugin for deleting unwanted post revisions from your database.

== Description ==

By default, whenever you update a post or page, WordPress saves a backup or 'revision' copy of the old page. There is no default limit to how many revisions are saved for each page so these revision copies can build up over time, bloating your database unnecessarily. This simple tool cleans up your database by deleting all existing revisions.

You should only need to run this plugin once, then you can deactivate and delete it. Deleting your existing revisions won't prevent them from building up again in future, but you can disable the revision feature or limit the number of revisions WordPress saves to a number of your choice by setting WordPress's `WP_POST_REVISIONS` constant. You can find instructions on how to do this on the plugin's homepage [here](http://www.cranesandskyhooks.com/wordpress/plugins/delete-post-revisions/).

== Installation ==

1. Upload the `delete-post-revisions` folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Navigate to `Tools > Delete Revisions` and click the delete button.
1. You're all done. You can deactivate and delete the plugin.

== Screenshots ==

1. You can find the Delete Revisions page under the WordPress Tools menu
2. Deleting unwanted revisions is as simple as clicking a button
3. Leaves your database lean, mean, and revision-free

== Changelog ==

= 1.0 =
* Initial Release






