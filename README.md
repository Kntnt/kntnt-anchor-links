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
add_filter( 'kntnt-anchor-links-post-id', function( $add_anchor_links, $post_id ) {
    
    $posts_to_not_have_anchor_links = [ 123, 456, 789 ];
    $pages_to_have_anchor_links = [ 321, 654, 987 ];
    
    if ( in_array( $post_id, $posts_to_not_have_anchor_links ) ) {
        $add_anchor_links = false;
    }
    elseif ( in_array( $post_id, $pages_to_have_anchor_links ) ) {
        $add_anchor_links = true;
    }
    
    return $add_anchor_links;

}, 10, 2 );
```
