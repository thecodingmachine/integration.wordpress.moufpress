Troubleshooting the title
-------------------------

If the title is not displayed correctly in the &lt;title&gt; tag (especially, if you see the "WP Router Placeholder Page" text instead of your title,
it is likely that you are using the "All-in-one SEO pack" plugin. This module rewrites the title in a way that is not compatible with Moufpress. A simple
workaround to disable this feature of the "All-in-one SEO pack" plugin in Moufpress is to edit your `header.php` file in your theme and add a space in the title tag.

For instance, write:

```php
<title ><?php wp_title( '|', true, 'right' ); ?></title>
```

instead of 

```php
<title><?php wp_title( '|', true, 'right' ); ?></title>
```