<table width="100%">
	<tr>
		<td align="left" width="100%">
			<strong>Unpublish</strong><br />
			Adds a setting under <strong>Publish</strong> in the WordPress post editor screen to allow unpublishing a post at a given date.
		</td>
	</tr>
	<tr>
		<td>
			A <strong><a href="https://hmn.md/">Human Made</a></strong> project. 
		</td>
	</tr>
</table>

Have you ever wished you could set a post to be unpublished automatically at a preset date?
Well now you can!

Introducing **Unpublish**
==========

This plugin does one thing : it adds a setting to the post editor screen under the **Publish** section which allows you to enter a date by which the post will not be published anymore.

Installation
==========

1. Upload the `unpublish` folder to the `/wp-content/plugins/` directory  or just upload the ZIP package via `Plugins > Add New > Upload` in your WP Admin.
1. Activate the plugin through the`Plugins` menu in WordPress.
1. Place `add_post_type_support( 'post', 'unpublish' );` in your theme's functions.php for example.

Frequently Asked Questions
==========

## Does it work with any post type?
Yes, as long as you modify the code accordingly:

	$types = array( 'post', 'page' );
	foreach( $types as $type ) {
		add_post_type_support( $type, 'unpublish' );`
	}

Change log
=======

= 1.1 =
* Move unpublish field to appear immediatly below the publish date.
* Alter schedule as post meta updates.
* Match formatting of unpublish and publish time.
* Ensure schedule is removed when posts are trashed.

Credits
=======
Created by [Human Made](https://hmn.md/) for high volume and large-scale sites. Thanks to all our [contributors](https://github.com/humanmade/Unpublish/graphs/contributors).

Interested in joining in on the fun? [Join us, and become human!](https://hmn.md/is/hiring/)
