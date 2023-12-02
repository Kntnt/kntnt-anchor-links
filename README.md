# Kntnt Anchor Links

WordPress plugin that adds anchor links headings.

## Description

This plugin automatically adds a link icon to the left of headings. The icon only appears when hovering headings. The icon is an anchor link to the current headline.

Visitors can copy the link, either by right-clicking on the icon and choosing "copy link" or by clicking on the link and then copying the URL in the browser's address bar. They can then use the copied URL to navigate directly to the location of the headline.

The technical explanation is that the plug-in adds an anchor element inside the header. The anchor element contains the icon and gets an `id` which is a machine readable version of the header. This makes it possible to use the `id` as a [fragment](https://en.wikipedia.org/wiki/URI_fragment) in a URL. The anchor element also gets a `href` attribute with a URL to the page using the `id` as a fragment.

By default, only H2 in posts get these anchors, but this can be changed by implementing filters.

## Filters

By default, the plugin ads anchor links only to H2 elements in posts.

You can change heading levels by implementing the filter `kntnt-anchor-links-heading_levels`:

```php
add_filter( 'kntnt-anchor-links-heading_levels', function( $heading_levels, $post_id, $post_type ) {
    $heading_levels = [ '1', '2', '3', '4', '5', '6' ]; // Add anchor links to H1–H6
    return $heading_levels;
}, 10, 3 );
```

You can change post types by implementing the filter `kntnt-anchor-links-post-types`:

```php
add_filter( 'kntnt-anchor-links-post-types', function( $post_types ) {
    $heading_levels = [ 'page', 'post' ]; // Add anchor links to both pages and posts
    return $post_types;
}, 10 );
```

You can enable or disable anchor links for any post by implementing the filter `kntnt-anchor-links-post-id`:

```php
add_filter( 'kntnt-anchor-links-post-id', function ( $do, $post_id ) {
	$do = 6190 == $post_id ? true : $do;  // Do show  anchor links on post 6190
	$do = 6303 == $post_id ? false : $do; // Don't show anchor links on post 6303
	return $do;
}, 10, 2 );
```

## Requirements

This plugin requires PHP 8.0 or later.

## Installation

Follow these instructions to install the plugin.

1. [Download the plugin from GitHub.](https://github.com/Kntnt/kntnt-anchor-links/releases/latest)
2. Unzip the zip file.
3. Rename the folder to `kntnt-anchor-links`.
4. Create a new zip file of the folder.
5. [Upload the newly created zip file through WordPress Admin](https://wordpress.org/documentation/article/manage-plugins/#upload-via-wordpress-admin).

## Changelog

### 1.0.0

* Initial release