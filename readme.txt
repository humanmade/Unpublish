=== Unpublish ===
Contributors: humanmade, danielbachhuber, pauldewouters
Tags: unpublish, post, posts, publish, schedule, scheduling
Requires at least: 3.0.1
Tested up to: 3.8-alpha
Stable tag: 0.1-alpha
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds a setting under **Publish** in the post editor screen to allow unpublishing a post at a given date.

== Description ==
Have you ever wished you could set a post to be unpublished automatically at a preset date?
Well now you can!

###Introducing **Unpublish**

This plugin does one thing : it adds a setting to the post editor screen under the **Publish** section which allows you to enter a date by which the post will not be published anymore.

== Installation ==
1. Upload the `unpublish` folder to the `/wp-content/plugins/` directory  or just upload the ZIP package via `Plugins > Add New > Upload` in your WP Admin.
1. Activate the plugin through the`Plugins` menu in WordPress.
1. Place `add_post_type_support( 'post', 'unpublish' );` in your theme's functions.php for example.

== Frequently Asked Questions ==

= Does it work with any post type? =
Yes, as long as you modify the code accordingly:

`$types = array( 'post', 'page' );
  foreach( $types as $type )
      add_post_type_support( $type, 'unpublish' );`

== Screenshots ==
1. The Post editor screen with the Unpublish setting

== Changelog ==
= 0.1-alpha =
* Initial release.

== Upgrade Notice ==
= 0.1-alpha =
* Initial release.